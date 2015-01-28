<html>
 <head>
  <title>ISS Maps</title>

  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
  <link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
  <link rel="stylesheet" type="text/css" media="all" href="http://fonts.googleapis.com/css?family=Acme">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

  <script>
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","gethandrail.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
 </head>
 <body>

<!-- Slider to input crewmember reach -->
  <div id="w">
    <div id="content">
      <div id="defaultval">
        Crewmember reach (inches): <span id="currentval">40</span>
      </div>
      
      <div id="defaultslide"></div>
</div></div>

 <?php  

/*** mysql info ***/
$hostname = '139.169.37.115';
$username = 'host';
$password = 'password';
$dbname   = 'Handrails';

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    /*** echo a message saying we have connected ***/
    //echo 'Connected to database';

$smt = $db->prepare('select Name From Handrails');
$smt->execute();
$data = $smt->fetchAll();





/* Close db connection */
$db = null;
}

catch(PDOException $e)    {
    echo $e->getMessage();
}

?> 

<form>
<select name="lst_exam" id="lst_exam" onchange="showUser(this.value)">
<?php foreach ($data as $row): ?>
    <option><?=$row["Name"]?></option>
<?php endforeach ?>
</select>
</form>

<!--
<form>
<select name="users" onchange="showUser(this.value)">
  <option value="">Select a handrail:</option>
  <option value="AL Handrail 0500">AL Handrail 0500</option>
  <option value="AL Handrail 0501">AL Handrail 0501</option>
  <option value="AL Handrail 0502">AL Handrail 0502</option>
  <option value="AL Handrail 0503">AL Handrail 0503</option>
  </select>
</form>
-->
<br />
<div id="txtHint" style="margin: 10px;"><b>Handrail info will be listed here...</b></div>


<script type="text/javascript">
$(function(){
  $('#defaultslide').slider({ 
    max: 70,
    min: 30,
    value: 50,
    slide: function(e,ui) {
      $('#currentval').html(ui.value);
    }
  });
  
});
</script>

 </body>
</html>
