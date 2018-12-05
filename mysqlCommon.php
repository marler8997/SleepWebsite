<?php

require_once('mysql.php');

// call: list($logins) = MysqlIPAndPortSession();
//       throws RuntimeException, MysqlException (or MysqlQueryOneException) on error
function MysqlIPAndPortSession()
{
  if(!isset($_SERVER['REMOTE_ADDR']))
    throw new RuntimeException("Missing \$_SERVER['REMOTE_ADDR']");
  if(!isset($_SERVER["REMOTE_PORT"]))
    throw new RuntimeException("Missing \$_SERVER['REMOTE_PORT']");

  $ip = ip2long($_SERVER['REMOTE_ADDR']);

  if($ip === FALSE)
    throw new RuntimeException("The \$_SERVER['REMOTE_ADDR'] variable should be an ip address but it is '".$_SERVER["REMOTE_ADDR"]."'");

  $port = intval($_SERVER['REMOTE_PORT']);

  $ipAndPort = pack('Nn', $ip, $port);

  $result = MysqlQueryOne("SELECT * FROM IPAndPortSessions WHERE ip=$ip");

  if($result === 0) {
    MysqlQuery("INSERT INTO IPSessions VALUES ('$ip',NOW(),NOW(),0);");
    $result = MysqlQueryOne("SELECT * FROM IPSessions WHERE ip=$ip");
    if($result === 0) {
      $logRefNum = code_error_with_ref(__FILE__,__LINE__,"created a new ipsession entry, and then failed to retrieve it");
      throw new MysqlException($logRefNum);
    }
    return $result;
  }

  // update last request time
  MysqlQuery("UPDATE IPSessions SET lastRequest=NOW() WHERE ip=$ip;");
  return $result;
}

?>