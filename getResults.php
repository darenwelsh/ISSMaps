<?php
/* VARIABLE DEFINITION */
$hr1      = $_GET['hr1'];
$hr2      = $_GET['hr2'];
$r        = $_GET['r'];
$xo       = 0;
$yo       = 0;
$zo       = 0;
$x2       = 0;
$y2       = 0;
$z2       = 0;
$distance = 0;

/* DB CONNECTION */
include "DBinfo.php";
try {
    $con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
}
catch(PDOException $e)    {
    echo $e->getMessage();
}

/* RETRIEVE INFO FOR HR 1 */
$sqlHRInfo = 'SELECT * FROM Handrails WHERE Name = :hr';
$smt = $con->prepare($sqlHRInfo);
$smt->execute(array(':hr' => $hr1));
$hr1Info = $smt->fetchAll();
foreach ($hr1Info as $rw) {
    $xo = $rw['x'];
    $yo = $rw['y'];
    $zo = $rw['z'];
}

/* RETRIEVE INFO FOR HR 2 */
$smt->execute(array(':hr' => $hr2));
$hr2Info = $smt->fetchAll();

foreach ($hr2Info as $rw) {
    $x2 = $rw['x'];
    $y2 = $rw['y'];
    $z2 = $rw['z'];
}

/* CALCULATE DISTANCE BETWEEN HR1 and HR2 */
$distance = round(SQRT((POW(($xo - $x2), 2) + POW(($yo - $y2), 2) + POW(($zo - $z2), 2))),2);

/* SQL QUERY FOR HANDRAILS WITHIN REACH OF HR1 */
$sqlHRsInReach = '
SELECT 
 *
FROM
 (SELECT 
   Name,
   SQRT(
    (POW((:xo - x), 2) + POW((:yo - y), 2) + POW((:zo - z), 2))
   ) AS distance,
   x,
   y,
   z
  FROM Handrails
 ) as tmp
WHERE distance < :r AND distance != 0
ORDER BY distance ASC
LIMIT 50';
$smt = $con->prepare($sqlHRsInReach);
$smt->execute(array(
 ':xo' => $xo,
 ':yo' => $yo,
 ':zo' => $zo,
 ':r'  => $r
));
$nearbyHRs = $smt->fetchAll();

/* DISPLAY HR1 INFO (ORIGIN) */
echo "
<div class=\"halfCol\">
<p>Origin handrail info:</p><table>
<tr>
<th>Name</th>
<th>x</th>
<th>y</th>
<th>z</th>
</tr>";

foreach ($hr1Info as $row) {
    echo "<tr>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['x'] . "</td>";
    echo "<td>" . $row['y'] . "</td>";
    echo "<td>" . $row['z'] . "</td>";
    echo "</tr>";
}
echo "</table></div><div class=\"halfCol\">"; /* Start of Destination HR Info to keep 50/50 on one line */

/* DISPLAY HR2 INFO (DESTINATION) */
echo "
<p>Destination handrail info:</p><table>
<tr>
<th>Name</th>
<th>x</th>
<th>y</th>
<th>z</th>
<th>Distance</th>
</tr>";

foreach ($hr2Info as $row) {
    echo "<tr>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['x'] . "</td>";
    echo "<td>" . $row['y'] . "</td>";
    echo "<td>" . $row['z'] . "</td>";
    echo "<td>" . $distance . "</td>";
    echo "</tr>";
}
echo "</table></div>";

/* HANDRAILS WITHIN REACH OF ORIGIN */
echo "<p>Nearby handrails:</p><table>
<tr>
<th>Name</th>
<th>Distance</th>
<th>x</th>
<th>y</th>
<th>z</th>
</tr>";

foreach ($nearbyHRs as $row) {
    echo "<tr>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . round($row['distance'],2) . "</td>";
    echo "<td>" . $row['x'] . "</td>";
    echo "<td>" . $row['y'] . "</td>";
    echo "<td>" . $row['z'] . "</td>";
    echo "</tr>";
}
echo "</table>";


/* GENERATE ARRAY OF EDGES (PATHS BETWEEN HANDRAILS) */

$sqlHRArray = '
SELECT
 Name,
 x,
 y,
 z
FROM Handrails
ORDER BY Name
';
$smt = $con->prepare($sqlHRArray);
$smt->execute();
$HRArray = $smt->fetchAll();

foreach ($HRArray as $row1) {
 $tmpName1 = $row1['Name'];
 $tmpx1 = $row1['x'];
 $tmpy1 = $row1['y'];
 $tmpz1 = $row1['z'];
 foreach ($HRArray as $row2) {
  $tmpName2 = $row2['Name'];
  $tmpx2 = $row2['x'];
  $tmpy2 = $row2['y'];
  $tmpz2 = $row2['z'];
  $tmpDistance = SQRT((POW(($tmpx1 - $tmpx2), 2) + POW(($tmpy1 - $tmpy2), 2) + POW(($tmpz1 - $tmpz2), 2)));
  $edges[] = array('HR1' => $tmpName1, 'HR2' => $tmpName2, 'Distance' => $tmpDistance);
 }
}
var_dump($edges);



// $points is an array in the following format: (HR1,HR2,distance-between-them)

/*
$points = array(
	array(0,1,4),
	array(0,2,I),
	array(1,2,5),
 	array(1,3,5),
	array(2,3,5),
	array(3,4,5),
	array(4,5,5),
	array(4,5,5),
	array(2,10,30),
	array(2,11,40),
	array(5,19,20),
	array(10,11,20),
	array(12,13,20),
);
 
$ourMap = array();
*/



/* Close db connection */
$db = null;

