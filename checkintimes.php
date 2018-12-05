<?php

require_once "jsoncalls.php";

header("Content-Type: application/json");
$json = new JsonObject();

try {

  MysqlInit();

  CheckinTimesToJson($json);

} catch(MysqlException $me) {
  $json->ex('Server Error: your reference number is '.$me->logRefNum);
}

?>