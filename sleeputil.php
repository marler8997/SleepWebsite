<?php
function TimeDiffPretty($timeDiff)
{
  $hours   = intval(substr($timeDiff, 0, 2));
  $minutes = intval(substr($timeDiff, 3, 2));
  $seconds = intval(substr($timeDiff, 6, 2));

  $hourNoun = 'hour';
  if($hours > 1) $hourNoun .= 's';

  if($minutes > 0) {
    $minuteNoun = 'minute';
    if($minutes > 1) $minuteNoun .= 's';

    if($hours > 0) {
      return "$hours $hourNoun and $minutes $minuteNoun";
    }

    return "$minutes $minuteNoun";
  }

  if($hours > 0) {
    return "$hours $hourNoun";
  }

  $secondNoun = 'second';
  if($seconds != 1) $secondNoun .= 's';
  return "$seconds $secondNoun";
}


function UserTimeInPastToMysqlDateTime($now, $userTime)
{
  error_log("UserTime '$userTime'");

  // Parse User Time
  $userTime = trim($userTime);

  $split = preg_split('/[\s:]+/', $userTime);
  if(count($split) != 3) return FALSE;

  $hour   = intval($split[0]);
  $minute = intval($split[1]);
  $amOrPm = $split[2];
  $isPM   = $amOrPm[0] == 'p' || $amOrPm[0] == 'P';
  error_log("amOrPM='$amOrPm', isPM='$isPM'");

  if($hour < 1 || $hour > 12) return FALSE;
  if($minute < 0 || $minute > 59) return FALSE;

  // fix hour
  if($hour == 12) {
    if(!$isPM) $hour = 0;
  } else {
    if($isPM) $hour += 12;
  }

  //
  // check if the time is yesterday
  //
  


  // create new
/*
  $hourString = ($hour < 10) ? "0$hour" : strval($hour);
  $minuteString = strval($minute);

  error_log("Old Time: $nowMysqlTime");
  $nowMysqlTime[11] = $hourString[0];
  $nowMysqlTime[12] = $hourString[1];

  $nowMysqlTime[14] = $minuteString[0];
  $nowMysqlTime[15] = $minuteString[1];
  error_log("New Time: $nowMysqlTime");
*/

  echo "\n";

}
?>