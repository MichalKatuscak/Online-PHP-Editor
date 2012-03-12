<?php

/**********************************************************
*                          Uno                            *
*                                                         *
*    Copyright (c) 2009, Enyeus Neiklot, Uno 0.4          *
*              Vydáno pod licencí GNU/GPL 3               *
**********************************************************/


/* Nastavení MySQL databáze */

$juw_mysql_server = "mysql.ic.cz";
$juw_mysql_user = "ic_enyeus";
$juw_mysql_password = "";
$juw_mysql_db = "ic_enyeus";


/* Nastavení */

$juw_strankovani = 1;             // 1 - stránkování zapnuto; 0 - stránkování vypnuto
$juw_znamkovani = 1;              // 1 - známkování zapnuto; 0 - známkování vypnuto
$juw_komentare = 1;               // 1 - komentáře zapnuty; 0 - komentáře vypnuty

#error_reporting("E_NOTICE");


include_once (dirname(__FILE__)."/juw-languages/cs_CZ.php");

?>