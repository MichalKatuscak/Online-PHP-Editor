<?php

session_start();

include_once ("./config.php");
mysql_connect ( $juw_mysql_server, $juw_mysql_user, $juw_mysql_password );
mysql_select_db ( $juw_mysql_db );
mysql_query ("SET NAMES 'utf8';");


if ($_SESSION["prihlaseni"] == 1)
{

	include_once("./juw-admin/juw-admin-includes/function.php");

	switch ($_GET["kategorie"]){ 

	    case "1": 
			include_once ("./juw-admin/juw-admin-includes/clanky.php");	     
		break;
	
	    case "2":
			include_once ("./juw-admin/juw-admin-includes/kategorie.php");
		break;

	    case "3":
			include_once ("./juw-admin/juw-admin-includes/obecne_nastaveni.php");
		break;
	
	    case "4":
			include_once ("./juw-admin/juw-admin-includes/uzivatele.php");
		break;
	
	    case "5":
			include_once ("./juw-admin/juw-admin-includes/doplnky.php");
		break;

	    case "6":
			include_once ("./juw-admin/juw-admin-includes/soubory.php");
		break;

	    case "7": 
			include_once ("./juw-admin/juw-admin-includes/nastenka.php");	     
		break;

	    case "8": 
			include_once ("./juw-admin/juw-admin-includes/stranky.php");	     
		break;

	    case "9": 
			include_once ("./juw-admin/juw-admin-includes/hodnosti.php");	     
		break;

	    default:
		 	include_once ("./juw-admin/juw-admin-includes/home.php");	     
		break;
	}

	juw_page();


}



else
{
	include_once ("./juw-admin/juw-admin-includes/login_error.php");
	include_once ("./juw-admin/juw-admin-includes/login_table.php");
}


?>
