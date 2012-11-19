<?php 
include('login.php');

if($uid !== FALSE) {
  $extraJavascriptFiles = array('checkin.js');  

  // Get initial checkin times
  ob_start();   

    $json = new JsonObject();
    CheckinTimesToJson($json);

  $checkinTimesAsJson = ob_get_contents();
  ob_end_clean();
  $extraJavscript = "var initialCheckinTimesJson = $checkinTimesAsJson;";

  ob_start();   

    include('checkinbody.php');

  $bodyContent = ob_get_contents();
  ob_end_clean();

} else {

  ob_start();   


    if($loginError !== FALSE) {
       echo '<h1 class="ErrorMessage" style="text-align:center;">'.$loginError.'</h1>';
    }
    include('register.html');


  $bodyContent = ob_get_contents();
  ob_end_clean();

}
  
if($loginError !== FALSE) {
  $pageTitle = 'Login Failed';
} else if($uid === FALSE) {
  $pageTitle = 'Login';
} else {
  $pageTitle = "Checkin $firstName";
}

include('master.php');
?>
