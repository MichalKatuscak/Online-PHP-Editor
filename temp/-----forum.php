<?php

session_start();
include_once ("./config.php");

mysql_connect ( $juw_mysql_server, $juw_mysql_user, $juw_mysql_password );
mysql_select_db ( $juw_mysql_db );
mysql_query ("SET NAMES 'utf8';");

	if (!$_SESSION["juw-admin"]) {
	$sql = mysql_query ("SELECT juw_hodnosti.* FROM juw_config LEFT JOIN juw_hodnosti ON juw_hodnosti.id = juw_config.defchmod LIMIT 1;");
				if (mysql_num_rows($sql) == 1 ){
				$z = mysql_fetch_array($sql);			
				$_SESSION["prihlaseni"] = $z["prihlaseni"];
				$_SESSION["clanky"] = $z["clanky"];
				$_SESSION["clanky_publ"] = $z["clanky_publ"];
				$_SESSION["stranky"] = $z["stranky"];
				$_SESSION["kategorie"] = $z["kategorie"];
				$_SESSION["uzivatele"] = $z["uzivatele"];
				$_SESSION["doplnky"] = $z["doplnky"];
				$_SESSION["soubory"] = $z["soubory"];
				$_SESSION["nastaveni"] = $z["nastaveni"];
				$_SESSION["statistika"] = $z["statistika"];
				$_SESSION["hodnoceni"] = $z["hodnoceni"];
				$_SESSION["komentovani"] = $z["komentovani"];
				$_SESSION["zah_clanky"] = $z["zah_clanky"];
				$_SESSION["zah_kategorie"] = $z["zah_kategorie"];
				}
	}

$e = mysql_fetch_array(mysql_query("SELECT spravce FROM juw_doplnek_forum LIMIT 1;"));
$s = $e["spravce"];
$_SESSION["spravce"] = $s;
if ($s == 0){
	$_SESSION["forum"] = $_SESSION["doplnky"];
} elseif ($_SESSION["juw-admin-id"] == $s) {
	$_SESSION["forum"] = 1;
}

include_once("./juw-includes/function.php");
if(file_exists("./juw-doplnky/forum/index.php")){
	include_once ("./juw-doplnky/forum/index.php");
} else {
	function juw_page_content() { echo "<h1>Fórum</h1><p>Fórum není nainstalované</p>"; }
}

juw_page();

?>
