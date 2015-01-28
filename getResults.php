<!DOCTYPE html>
<html>
<head>
</head>
<body>

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

/*** mysql info ***/
$hostname = '139.169.37.115';
$username = 'host';
$password = 'password';
$dbname   = 'Handrails';

try {
    $con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$hr1Info="SELECT * FROM Handrails WHERE Name = '".$hr1."'";
foreach ($con->query($hr1Info) as $rw) {
    $xo = $rw['x'];
    $yo = $rw['y'];
    $zo = $rw['z'];
}
$hr2Info="SELECT * FROM Handrails WHERE Name = '".$hr2."'";
foreach ($con->query($hr2Info) as $rw) {
    $x2 = $rw['x'];
    $y2 = $rw['y'];
    $z2 = $rw['z'];
}
$distance = round(SQRT((POW(($xo - $x2), 2) + POW(($yo - $y2), 2) + POW(($zo - $z2), 2))),2);

/* SQL query for HRs within reach */
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

foreach ($con->query($hr1Info) as $row) {
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

foreach ($con->query($hr2Info) as $row) {
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


/* function Dijkstra(Graph, source):

      dist[source] ← 0                       // Distance from source to source
      prev[source] ← undefined               // Previous node in optimal path initialization

      for each vertex v in Graph:  // Initialization
          if v ≠ source            // Where v has not yet been removed from Q (unvisited nodes)
              dist[v] ← infinity             // Unknown distance function from source to v
              prev[v] ← undefined            // Previous node in optimal path from source
          end if 
          add v to Q                     // All nodes initially in Q (unvisited nodes)
      end for
      
      while Q is not empty:
          u ← vertex in Q with min dist[u]  // Source node in first case
          remove u from Q 
          
          for each neighbor v of u:           // where v has not yet been removed from Q.
              alt ← dist[u] + length(u, v)
              if alt < dist[v]:               // A shorter path to v has been found
                  dist[v] ← alt 
                  prev[v] ← u 
              end if
          end for
      end while

      return dist[], prev[]

  end function

*/



/* Close db connection */
$db = null;
}

catch(PDOException $e)    {
    echo $e->getMessage();
}

?>
</body>
</html>
