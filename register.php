<?php

require_once 'config.php';
require_once '../common/util.php';
require_once '../common/password.php';
require_once '../common/mysql.php';

// call: $result = Register();
//       if(is_numeric($result)) // success
function Register() {

  try {
    MysqlInit();

    if(!isset($_REQUEST['Email']))     return 'Missing Email';
    if(!isset($_REQUEST['FirstName'])) return 'Missing First Name';
    if(!isset($_REQUEST['LastName']))  return 'Missing Last Name';
    if(!isset($_REQUEST['Password']))  return 'Missing Password';

    $email     = trim($_REQUEST['Email']);
    $firstName = trim($_REQUEST['FirstName']);
    $lastName  = trim($_REQUEST['LastName']);
    $password  = trim($_REQUEST['Password']);

    if(empty($email))     return 'Email is empty';
    if(empty($firstName)) return 'First Name is empty';
    if(empty($lastName))  return 'LastName is empty';
    if(empty($password))  return 'Password is empty';

    if(!ValidEmail($email))          return "Email '$email' is invalid";
    if(!ValidPersonName($firstName)) return "First Name '$firstName' is invalid";
    if(!ValidPersonName($lastName))  return "Last Name '$lastName' is invalid";
    if(strlen($password) < 6)        return 'Password not long enough';

    $salt = rand() & 0xFFFF;
    $hash = passwd($password, $salt);
  
    return MysqlInsertID("INSERT INTO Users VALUES (0,NULL,'$email',$salt,x'$hash','$firstName','$lastName',NULL,NULL,NULL,NULL,NULL);");

  } catch(MysqlException $me) {
    return 'Server Error: your reference number is '.$me->logRefNum;
  }

}

$uid = FALSE;
$loginError = FALSE;

$result = Register();
if(!is_numeric($result)) {
  $pageTitle = 'Registration Failed';

  ob_start();   

    echo '<h1 class="ErrorMessage" style="text-align:center;">'.$result.'</h1>';
    include('register.html');

  $bodyContent = ob_get_contents();
  ob_end_clean();

} else {

  $uid = $result;
  $pageTitle = 'Registration Success';
  $bodyContent = '<h1>Registration Success</h1>';

}



include ('master.php');
?>


