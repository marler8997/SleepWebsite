<?php

require_once "../common/util.php";
require_once "../common/mysql.php";
require_once "../common/json.php";

function CheckinTimesToJson($json) {
  // Get Current Time
  $mysqlNowString = MysqlValue('SELECT NOW();');
  $json->addString('Now',$mysqlNowString);

  list($result,$count) = MysqlRows("SELECT DateTime FROM Checkin ORDER BY DateTime DESC;");
  if($count > 0) {
    $json = $json->startArray('Checkins');
    while(TRUE) {
      $row = mysql_fetch_row($result);
      if(!$row) break;
      $json->addString($row[0]);      
    }
    $json = $json->end();
  }

  $json->end();
}


?>