<?php
require_once('common.php');
require_once('mysql.php');

$error = FALSE;

try {

  MysqlInit();

  // Get Current Time
  $mysqlNowRow = MysqlOneRow('SELECT NOW(),DATE_FORMAT(NOW(),"%l:%i %p");');
  $mysqlNow = $mysqlNowRow[0];
  $mysqlNowPretty = $mysqlNowRow[1];


  if(!isset($_REQUEST['SleepTime'])) {goto SEND;}
  if(!isset($_REQUEST['WakeTime'])) {$error = 'Missing Wake Time'; goto SEND;}

  $sleepTime = trim($_REQUEST['SleepTime']);
  $wakeTime = trim($_REQUEST['WakeTime']);

  if(empty($sleepTime)) {$error = 'Empty Sleep Time';goto SEND;}
  if(empty($wakeTime)) {$error = 'Empty Wake Time';goto SEND;}


  



} catch(MysqlException $me) {
  $error = 'Server Error: your reference number is '.$me->logRefNum;
}


SEND:
?>

<html>
<head>
  <title>Sleep Entry - Wake Up</title>
  <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div style="margin:20px auto 0 auto;text-align:center;">

<?php

try {


  $displayTime = ($mysqlNowPretty[0] == '0') ? substr($mysqlNowPretty, 1) : $mysqlNowPretty;
  echo "<h1> $displayTime </h1>";




  $result = MysqlQuery("SELECT DATE_FORMAT(DateTime,'%l:%i %p'),TIMEDIFF('$mysqlNow',DateTime) FROM Checkin ORDER BY DateTime DESC;");
  $checkinCount = MysqlCount($result);
  $sleepTime = '';
  if($checkinCount > 0) {
    $lastCheckinRow = mysql_fetch_row($result);
    $lastCheckin = $lastCheckinRow[0]; 
    $lastCheckinDiffPretty = TimeDiffPretty($lastCheckinRow[1]);

    $sleepTime = $lastCheckin;
  }		     
  $wakeTime = $mysqlNowPretty;



  echo '<form method="post" action="wake.php">';

  echo '<table>';

  echo   '<tr><td><span class="InputLabel">Sleep Time:</span></td>';
  echo   '<td><input class="TimeInput" type="text" name="SleepTime" value="'.$sleepTime.'" /></td>';
  echo   '<td>'.$lastCheckinDiffPretty.' ago</td></tr>';
		      

  echo   '<tr><td><span class="InputLabel">Wake Time:</span></td>';
  echo   '<td><input class="TimeInput" type="text" name="WakeTime" value="'.$wakeTime.'" /></td></tr>';

  echo '</table><br/>';

  echo '<input class="Button" type="submit" value="Save"/>';
  echo '</form>';

  if($error !== FALSE) {
    echo '<h1 class="ErrorMessage">'.$error.'</h1>';
  }


  if($checkinCount > 0) {
    echo "<br/><h2> You checked in $checkinCount times </h2>";

    if($checkinCount > 1) {    
      echo '<table id="CheckinTable">';
      while(TRUE) {
        $row = mysql_fetch_row($result);
        if(!$row) break;
        $datetime = $row[0];
	$datetimeDiffPretty = TimeDiffPretty($row[1]);
        echo "<tr><td class=\"Time\">$datetime</td><td>$datetimeDiffPretty ago</td></tr>";
      }
      echo '</table>';
    }
  }

} catch(MysqlException $me) {
  echo 'Server Error: your reference number is '.$me->logRefNum;
}
?>

</div>
</body>
</html>
