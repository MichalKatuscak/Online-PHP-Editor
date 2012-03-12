<?php

session_start();
include_once("./config.php");

$db.=@mysql_connect($juw_mysql_server, $juw_mysql_user, $juw_mysql_password );
$db .= @mysql_select_db ( $juw_mysql_db );
$db .= @mysql_query ("SET NAMES 'utf8';");

header('content-type:text/html;charset=utf-8');
echo "ýýžýžřýřýž";