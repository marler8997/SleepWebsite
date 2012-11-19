<?php
//
// This file will handle
//   1. setting cookies for logouts
//   2. login attempts
//   3. retrieving cookie sessions
//   4. retriving user information
//
// When another script includes this file they will have access 
// to the globals $loginError and $uid.
// 
// If $loginError !== FALSE, then the client tried to login but failed.
//
// if $uid !== FALSE, then the client is logged in and the other globals
// will be set:
//   $email
//   $firstName
//   $lastName
//
//
require_once('config.php');

require_once('../common/password.php');
require_once('../common/sessions.php');


// call: $result = login():
// if(is_numeric($result)) // success
function login() {
  $email = $_REQUEST['Email'];
  if(empty($email)) return 'Email is empty';
  if(!ValidEmail($email)) return "Email '$email' is invalid";

  if(!isset($_REQUEST['Password'])) return 'Missing password';
  $password = $_REQUEST['Password'];
    
  // Get their IP and Port session
  $loginCount = MysqlIPAndPortLoginAttempt();
  
  if($loginCount > MAX_LOGIN_ATTEMPTS) return 'Reached maximum login attempts ('.MAX_LOGIN_ATTEMPTS.')';

  define('EMAIL_OR_PASS_MSG', 'Either email is not registered or password is invalid');

  // Check Login
  $result = MysqlRow("SELECT Uid,Salt,PasswordHash FROM Users WHERE Email='$email';");
  if($result === FALSE) return EMAIL_OR_PASS_MSG;

  list($uid,$salt,$storedHash) = $result;
  
  $computedHash = passwd($password, $salt);
  if($computedHash != $storedHash) return EMAIL_OR_PASS_MSG;
  
  return $uid;  
}

$uid        = FALSE;
$loginError = FALSE;

try {

  MysqlInit();

  //
  // Check for logout
  //
  if(isset($_REQUEST['Logout'])) {

    EndCookieSession();

  //    
  // Check for login attempt
  //
  } else if(isset($_REQUEST['Email'])) {

    $result = login();
    if(is_numeric($result)) {

      $uid = $result;
      MysqlNewCookieSession($result);

    } else {            

      $loginError = $result;
      EndCookieSession();

    }

  //
  // Check for session cookie
  //
  } else {

    $result = MysqlCookieSession();
    if($result !== FALSE) {
      
      $uid = $result;

    }

  }


  //
  // Get User Info
  //
  if($uid !== FALSE) {
    list($email,$firstName,$lastName) =
      MysqlExactlyOneRow("SELECT Email,FirstName,LastName FROM Users WHERE Uid=$uid;");
  }


} catch(MysqlException $me) {
  $loginError = 'Server Error: your reference number is '.$me->logRefNum;
}

?>