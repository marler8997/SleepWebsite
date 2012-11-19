<!DOCTYPE html>
<html>
<head>
  <title>Sleep Entry - <?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="layout.css" type="text/css" />
  <link rel="stylesheet" href="button.css" type="text/css" />
  <script type="text/javascript" src="jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="common.js"></script>
  <script type="text/javascript" src="date.format.js"></script>
<?php
  if(isset($extraJavascriptFiles)) {
    foreach($extraJavascriptFiles as $scriptName) {
      echo '<script type="text/javascript" src="'.$scriptName.'"></script>';
    }		    
  }
?>  
  <script type="text/javascript">
<?php
    if(isset($extraJavascript)) {
      echo $extraJavascript;
    }
?>

    function logout() {
      document.cookie = 'Sid=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
      document.location = '/index.php?Logout';
    }

  </script>
</head>
<body>
  <div id="BodyDiv">
    <div id="HeaderDiv">
      <h1 id="Title">Sleep Tracker</h1>
<?php
if($uid !== FALSE) {

  echo '<div id="UserNameDiv">';
  echo   '<span style="font-weight:bold;">'.$userName.'</span> ';
  echo   '<span class="WhiteButton" style="background:#aaa;color:#000;" onclick="logout()">Logout</span>';
  echo '</div>';

} else {

  ?>
  <div id="UserNameDiv">
    <form style="display:inline-block;" method="POST">
      <table>
        <tr><td>Email</td><td>Password</td><td></td></tr>
         <tr><td><input type="text" name="Email" /></td>
         <td><input type="password" name="Password" /></td>
         <td><input class="SmallButton" type="submit" value="Login"/></td></tr>
      </table>
    </form>
  a</div>
  <?php

}

?>
    </div>
    <?php echo $bodyContent; ?>
  </div>
</body>
</html>
