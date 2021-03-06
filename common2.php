<?php

define('MAX_LOGINS',20);
define('REGCODE_LENGTH','8');

define('MAX_ACTIVATED_OFFLINE_KEYS'  ,5);
define('MAX_DEACTIVATED_OFFLINE_KEYS',8);



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



function hex2bin($hex) {
  return pack('H*', $hex);
}

function error_log_with_ref($message) {
  $ref = rand();
  error_log("[log_reference_number=$ref] $message");
  return $ref;
}

// Error Log with code location
function code_error($file, $line, $message) {
  error_log("$file line $line: $message");
}

function code_error_with_ref($file, $line, $message) {
  $ref = rand();
  error_log("[log_reference_number=$ref] $file line $line: $message");
  return $ref;
}

function ServerErrorPage($logRefNum) {
  echo '<!DOCTYPE><html><head><link rel="stylesheet" href="style.css" type="text/css" /><title>Server Error</title>';
  echo '<body><h1 class="errormsg">Server Error: you can reference this error with the number ';
  echo $logRefNum;
  echo '</h1></body></html>';
}

function PrintFullErrorPage($title, $message) {
  echo '<!DOCTYPE><html><head><link rel="stylesheet" href="style.css" type="text/css" /><title>';
  echo $title;
  echo '</title><body><h1 class="errormsg">';
  echo $message;
  echo '</h1></body></html>';
}

function GetPreviousPage() {
  return isset($_REQUEST["frompage"])? $_REQUEST["frompage"] : '/';
}

function AddUrlVar($getvars,$var,$value) {
  if($getvars == null || !isset($getvars[0])) {
    return '?'.$var.'='.$value;
  }
  return $getvars.'&'.$var.'='.$value;
}

// fiv= Form Input Value, the HTML Value attribute
function fiv($val) {
  return 'value="'.$val.'"';
}

function ContainsBadChars($field)
{
  // 32 = ' ', 126 = '~', '\'' = 39, "\"" = 34
  $fieldLength = strlen($field);
  for($i = 0; $i < $fieldLength; $i++) {
    $c = ord($field[$i]);
    if($c < 32 || $c > 126 || $c == 39 || $c == 34) {
      return TRUE;
    }
  }
  return FALSE;
}

function GenerateRegcode()
{
  $chars = '123456789abcdefghijkmnpqrstuvwxyz'; /*omit 0,o and l, they look kinda ambiguous*/
  $charsLength = strlen($chars);
  $string = '';

  for($i = 0; $i < REGCODE_LENGTH; $i++) {
    $string .= $chars[mt_rand(0,$charsLength-1)];
  }
  return $string;
}

function Get($assocArray, $key)
{
  return isset($assocArray[$key]) ? $assocArray[$key] : NULL;
}

// just get all the digits
function PhoneDigits($phone)
{
  return ereg_replace('[^0-9]','',$phone);
}

// returns FALSE on error, and array of 2 strings on success
function TryParsePoint($point)
{
  if(!preg_match('/POINT\\(([^ ]+) ([^\\)]+)\\)/', $point, $matches)) {
    return FALSE;
  }
  return array($matches[1],$matches[2]);
}

// throws InvalidArgumentException
function ParsePoint($point) {
  if(!preg_match('/POINT\\(([^ ]+) ([^\\)]+)\\)/', $point, $matches)) {
    throw new InvalidArgumentException('Expected "POINT(<num> <num>)" but got "'.$point.'"');
  }
  return array($matches[1],$matches[2]);
}

// Used to get valid person's name
function GetPersonName($assocArray, $nameKey)
{
  $personName = Get($assocArray, $nameKey);
  if(!$personName) return NULL;
  $personName = trim($personName);
  return IsValidPersonName($personName) ? $personName : NULL;
}

// pass in the associative array where "email" contains the email.
// this function returns NULL if the email is not set or invalid
function GetEmail($assocArray) {
  $email = Get($assocArray, "email");
  if(!$email) return NULL;
  $email = trim($email);
  return IsValidEmail($email) ? $email : NULL;
}
function IsValidRegcode($regcode) {
  return (strlen($regcode) == REGCODE_LENGTH && eregi('^[0-9a-z]+$', $regcode));
}


function IsValidUserName($userName)
{
  return eregi("^[a-zA-Z0-9]+$", $userName);
}
function IsValidMacAddress($mac)
{
  return strlen($mac) == 12 && eregi("^[a-zA-Z0-9]+$", $mac);
}
function IsValidDeviceName($deviceName)
{
  return eregi("^[\-_ a-zA-Z0-9]+$", $deviceName);
}
function IsValidPersonName($personName)
{
  return eregi("^[A-Z][-a-zA-Z]+$", $personName);
}
function IsValidGroupName($groupName)
{
  return (strlen($groupName) < 64) && eregi("^[- _a-zA-Z][- _a-zA-Z0-9]+$", $groupName);
}
function IsValidEmail($email)
{
  return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$", $email);
}
function IsValidPassword($password)
{
  return strlen($password) >= 8;
}
function IsValidSqlColumn($column)
{
  return eregi("^[a-zA-Z][a-zA-Z0-9_]*$", $column);
}
function IsValidFloat($str)
{
  return eregi("^-?[0-9]*(\.[0-9]*)?$", $str);
}
function IsValidScriptName($scriptName)
{
  return eregi('^[a-zA-Z][-a-zA-Z0-9_ ]*$', $scriptName);
}
function SplitEmails($emails) {
  return preg_split('/[\\s,;:]+/', $emails, -1, PREG_SPLIT_NO_EMPTY);
}
function passwd($password, $salt)
{
  $password .= sprintf('%u',$salt);
  for($i = 0; $i < 100; $i++) {
    $password = sha1($password);
  }
  return $password;
}

function uploadErrorString($err) {
  $upload_errors = array(
    "No errors",
    "File size larger than upload_max_filesize",
    "File size larger than MAX_FILE_SIZE directive",
    "Partial upload",
    "No file",
    "No temporary directory",
    "Can't write to disk",
    "File uploaded stopped by extension",
    "File is empty"  
  );

  if(!isset($err)) {
    return "No file";
  }
  error_log('err='.$err);
  if($err >= count($upload_errors)) {
    return "Unknown File error";
  }
  return $upload_errors[$err];
}
?>