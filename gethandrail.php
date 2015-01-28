<!DOCTYPE html>
<html>
<head>
<style>/*
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
    margin: 5px;
}

th {text-align: left;}*/
</style>
</head>
<body>

<?php
$hr1 = $_GET['hr1'];
$r   = $_GET['r'];
$xo  = 0;
$yo  = 0;
$zo  = 0;

/*** mysql info ***/
$hostname = '139.169.37.115';
$username = 'host';
$password = 'password';
$dbname   = 'Handrails';

try {
    $con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$hrInfo="SELECT * FROM Handrails WHERE Name = '".$hr1."'";
foreach ($con->query($hrInfo) as $rw) {
    $xo = $rw['x'];
    $yo = $rw['y'];
    $zo = $rw['z'];
}

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

echo "<p>Handrail info:</p><table>
<tr>
<th>Name</th>
<th>x</th>
<th>y</th>
<th>z</th>
</tr>";

foreach ($con->query($hrInfo) as $row) {
    echo "<tr>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['x'] . "</td>";
    echo "<td>" . $row['y'] . "</td>";
    echo "<td>" . $row['z'] . "</td>";
    echo "</tr>";
}
echo "</table>";

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
    echo "<td>" . $row['distance'] . "</td>";
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
