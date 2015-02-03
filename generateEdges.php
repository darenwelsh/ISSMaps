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
  //$edges[] = array($tmpName1, $tmpName2, $tmpDistance);


 }
}




$num = 0;
$sql = "SELECT id, name, description FROM products";
if($result = $mysqli->query($sql)) {
     while($p = $result->fetch_array()) {
         $prod[$num]['id']          = $p['id'];
         $prod[$num]['name']        = $p['name'];
         $prod[$num]['description'] = $p['description'];
         $num++;        
    }
 }
$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=pressurecsv.csv"); 
fputcsv($output, array('id','name','description'));
foreach($prod as $product) {
    fputcsv($output, $product);
}
fclose($output) or die("Can't close php://output");


/* Close db connection */
$db = null;

