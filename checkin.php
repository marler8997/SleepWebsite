<?php

require_once('common.php');
require_once('mysql.php');

try {
  MysqlInit();
  MysqlQuery('INSERT INTO Checkin VALUES (NOW());');
  header('Location: /');
} catch(MysqlException $me) {
  echo 'Server Error: your reference number is '.$me->logRefNum;
}
?>
<html>
<head>
  <title>Sleep Entry - Checkin</title>
  <meta http-equiv="CACHE-CONTROL" content="NO_CACHE" />
  <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
</body>
</html>

