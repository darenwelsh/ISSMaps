<html>
 <head>
  <title>ISS Maps</title>

  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
  <link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
  <link rel="stylesheet" type="text/css" media="all" href="http://fonts.googleapis.com/css?family=Acme">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script>
function updateResults(){
  var handrail = $("#hr_start").val();
  var reach    = $("#reachSlider").slider("value");
  console.log( "HR:" + handrail + " Reach:" + reach );
  $.get(
    "gethandrail.php",
    { hr1:handrail, r:reach},
    function( response ){
      $("#responseWrapper").html( response );
    }
  )
}

$(document).ready(function(){
  $(".updater").change(updateResults);
});

</script>
 </head>
 <body>

<!-- Slider to input crewmember reach -->
  <div id="w">
    <div id="content">
      <div id="defaultval" class="updater">
        Crewmember reach (inches): <span id="reachVal"></span>
      </div>
      
      <div id="reachSlider"></div>
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
<!--<select class="updater" name="hr_start" id="hr_start" onchange="showUser(this.value)">-->
<select class="updater" name="hr_start" id="hr_start">
<?php foreach ($data as $row): ?>
    <option><?=$row["Name"]?></option>
<?php endforeach ?>
</select>
</form>


<br />
<!--
<div id="results" style="margin: 10px;"><b>Handrail info will be listed here...</b></div>
-->

<script type="text/javascript">
$(function(){
  var startValue = 55;
  $('#reachSlider').slider({ 
    max: 80,
    min: 30,
    value: startValue,
    create: function(e,ui) {
      $('#reachVal').html(startValue);
    },
    slide: function(e,ui) {
      $('#reachVal').html(ui.value);
    },
    change: function(e,ui) {
      updateResults();
    }
  });
  
});
</script>

<div id="responseWrapper">Response:<br /></div>

 </body>
</html>
