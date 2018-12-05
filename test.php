<?php

require_once('common.php');
require_once('mysqlCommon.php');


MysqlInit();


$_SERVER['REMOTE_ADDR'] = '1.2.3.4';
$_SERVER['REMOTE_PORT'] = '46';

MysqlIPAndPortSession();



?>