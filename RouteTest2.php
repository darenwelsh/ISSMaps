<?php /* From http://en.giswiki.net/wiki/Dijkstra%27s_algorithm#PHP */

include "Dijkstra2.php";
 
// I is the infinite distance.
define('I',1000);
 
// Size of the matrix
$matrixWidth = 20;
 
// $points is an array in the following format: (router1,router2,distance-between-them)
$points = array(
	array('HR1'=>'AL HR 500','HR2'=>'AL HR 501','distance'=>4),
	array('HR1'=>'AL HR 500','HR2'=>'AL HR 502','distance'=>I),
	array('HR1'=>'AL HR 501','HR2'=>'AL HR 502','distance'=>5),
 	array('HR1'=>'AL HR 501','HR2'=>'AL HR 503','distance'=>5),
	array('HR1'=>'AL HR 502','HR2'=>'AL HR 503','distance'=>5),
	array('HR1'=>'AL HR 503','HR2'=>'AL HR 504','distance'=>5),
	array('HR1'=>'AL HR 504','HR2'=>'AL HR 505','distance'=>5),
	array('HR1'=>'AL HR 504','HR2'=>'AL HR 505','distance'=>5),
	array('HR1'=>'AL HR 502','HR2'=>'AL HR 510','distance'=>30),
	array('HR1'=>'AL HR 502','HR2'=>'AL HR 511','distance'=>40),
	array('HR1'=>'AL HR 505','HR2'=>'AL HR 519','distance'=>20),
	array('HR1'=>'AL HR 510','HR2'=>'AL HR 511','distance'=>20),
	array('HR1'=>'AL HR 512','HR2'=>'AL HR 513','distance'=>20),
);
 
$ourMap = array();
 
 
// Read in the points and push them into the map
 
foreach ($points as $row) {
	$HR1 = $row['HR1'];
	$HR2 = $row['HR2'];
	$c = $row['distance'];
	$ourMap[$HR1][$HR2] = $c;
	$ourMap[$HR2][$HR1] = $c;
}
var_dump($points);
echo "<br /><br />---<br /><br />";
var_dump($ourMap);
echo "<br /><br />---<br /><br />";
echo $ourMap['AL HR 510']['AL HR 502'];

/*
for ($i=0,$m=count($points); $i<$m; $i++) {
	$x = $points[$i][0];
	$y = $points[$i][1];
	$c = $points[$i][2];
	$ourMap[$x][$y] = $c;
	$ourMap[$y][$x] = $c;
}*/
 
// ensure that the distance from a node to itself is always zero
// Purists may want to edit this bit out.
 
/*
for ($i=0; $i < $matrixWidth; $i++) {
    for ($k=0; $k < $matrixWidth; $k++) {
        if ($i == $k) $ourMap[$i][$k] = 0;
    }
}
 */
 
// initialize the algorithm class
$dijkstra = new Dijkstra($ourMap, I,$matrixWidth);
 
// $dijkstra->findShortestPath(0,13); to find only path from field 0 to field 13...
//$dijkstra->findShortestPath(0); 
 
// Display the results
 
echo '<pre>';
echo "the map looks like:\n\n";
echo $dijkstra -> printMap($ourMap);
echo "\n\nthe shortest paths from point 0:\n";
//echo $dijkstra -> getResults();
echo '</pre>';
 
?>
