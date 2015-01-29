<?php
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

include "DBinfo.php";
try {
    $con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
}
catch(PDOException $e)    {
    echo $e->getMessage();
}

$sqlHRInfo = 'SELECT * FROM Handrails WHERE Name = :hr';
$smt = $con->prepare($sqlHRInfo);
$smt->execute(array(':hr' => $hr1));
$hr1Info = $smt->fetchAll();
foreach ($hr1Info as $rw) {
    $xo = $rw['x'];
    $yo = $rw['y'];
    $zo = $rw['z'];
}
$smt->execute(array(':hr' => $hr2));
$hr2Info = $smt->fetchAll();

foreach ($hr2Info as $rw) {
    $x2 = $rw['x'];
    $y2 = $rw['y'];
    $z2 = $rw['z'];
}

$distance = round(SQRT((POW(($xo - $x2), 2) + POW(($yo - $y2), 2) + POW(($zo - $z2), 2))),2);

/* SQL query for HRs within reach */
/*
$sqlHRsInReach = 'SELECT *
FROM
 (SELECT 
   Name,
   SQRT((POW((:xo - :x), 2) + POW((:yo - :y), 2) + POW((:zo - :z), 2))) AS distance,
   x,
   y,
   z
  FROM Handrails) as tmp
WHERE distance < $r AND distance != 0
ORDER BY distance ASC
LIMIT 50';
$smt = $con->prepare($sqlHRsInReach);
$smt->execute(array(':hr' => $hr1));
$hr1Info = $smt->fetchAll();*/
$nearbyHRs="SELECT *
FROM
 (SELECT 
   Name,
   SQRT((POW(($xo - x), 2) + POW(($yo - y), 2) + POW(($zo - z), 2))) AS distance,
   x,
   y,
   z
  FROM Handrails) as tmp
WHERE distance < $r AND distance != 0
ORDER BY distance ASC
LIMIT 50";

/* Origin Handrail Info */
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

/* Destination Handrail Info */
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

/* Handrails Within Reach of Origin */
echo "<p>Nearby handrails:</p><table>
<tr>
<th>Name</th>
<th>Distance</th>
<th>x</th>
<th>y</th>
<th>z</th>
</tr>";

foreach ($con->query($nearbyHRs) as $row) {
    echo "<tr>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . round($row['distance'],2) . "</td>";
    echo "<td>" . $row['x'] . "</td>";
    echo "<td>" . $row['y'] . "</td>";
    echo "<td>" . $row['z'] . "</td>";
    echo "</tr>";
}
echo "</table>";




// $points is an array in the following format: (HR1,HR2,distance-between-them)
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



/* Close db connection */
$db = null;

