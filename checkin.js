var lastUpdateTime = 0;

<?php
require_once('jsoncalls.php');

MysqlInit();

//
// Get Checkin Times as JSON
//
   

?>    

    function updateClockSeconds(displayDom) {
      var nowDate = new Date();
      var nowTime = nowDate.getTime();

      lastUpdateTime = nowTime;
      displayDom.innerHTML = nowDate.format('h:MM:ss TT');

      setTimeout(updateClockSeconds, 100, displayDom);
    }
    function updateClockMinutes(displayDom) {
      var nowDate = new Date();
      var nowTime = nowDate.getTime();

      lastUpdateTime = nowTime;
      displayDom.innerHTML = nowDate.format('h:MM TT');

      setTimeout(updateClockMinutes, 5000, displayDom);
    }

   function updateCheckinTimes(checkinTimesAsJson) {
     var nowDate = new Date();
     var nowTime = nowDate.getTime();

     var serverNowString = checkinTimesAsJson.Now;
     var checkins = ('Checkins' in checkinTimesAsJson) ?
        checkinTimesAsJson.Checkins : null;


     var serverNowDate = new Date(serverNowString);
     var serverNowTime = serverNowDate.getTime();
     var serverTimeOffset = nowTime - serverNowTime;

     if(checkins != null && checkins.length > 0) {
       var lastCheckinString = checkins[0];
       var lastCheckinDate = new Date(lastCheckinString);
       var lastCheckinTime = lastCheckinDate.getTime();

       var lastCheckinTimeCorrected = lastCheckinTime + serverTimeOffset;
       var lastCheckinDateCorrected = new Date(lastCheckinTimeCorrected);


       var lastCheckinDiff = nowTime - lastCheckinTimeCorrected;

       get('LastCheckin').innerHTML = 'Last checkin was <span class="LastCheckinTime">' +
         prettyTimeLong(lastCheckinDiff / 1000) + '</span> ago at ' + 
          lastCheckinDateCorrected.format('h:MM:ss TT');


     }
     
   }

   $(document).ready(function() {
     updateClockMinutes(get('DisplayTime'));

     updateCheckinTimes(initialCheckinTimesJson);
   });

  </script>
</head>
<body>
<div style="margin:20px auto 0 auto;text-align:center;">
  <h1 id="DisplayTime">12:00 AM</h1><br/>



<?php


try {

  // Get Current Time
  $mysqlNowRow = MysqlOneRow('SELECT NOW(),DATE_FORMAT(NOW(),"%l:%i %p");');
  $mysqlNowPretty = $mysqlNowRow[1];



  $result = MysqlQuery("SELECT DATE_FORMAT(DateTime,'%l:%i %p'),TIMEDIFF('$mysqlNow',DateTime) FROM Checkin ORDER BY DateTime DESC;");
  $checkinCount = MysqlCount($result);




  echo '<form action="/checkin.php"><input class="Button" type="submit" value="Sleep Checkin" /></form><br/>';
  echo '<form action="/wake.php"><input class="Button" type="submit" value="Wake Up" /></form>';

/*
  if($checkinCount > 0) {
    $lastCheckinRow = mysql_fetch_row($result);
    $lastCheckin = $lastCheckinRow[0]; 
    $lastCheckinDiffPretty = TimeDiffPretty($lastCheckinRow[1]);

    echo "<br/><h1> Last checkin was <span class=\"LastCheckin\">$lastCheckinDiffPretty</span> ago at $lastCheckin</h1>";
		      
    echo "<br/><h2> You've checked in $checkinCount times </h2>";


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
*/
} catch(MysqlException $me) {
  echo 'Server Error: your reference number is '.$me->logRefNum;
}
?>

<br/><div id="LastCheckin"></div>

</div>
</body>
</html>
