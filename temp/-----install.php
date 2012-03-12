<?php
session_start();

	if ($_POST["lang"]) $_SESSION["in_lang"] = $_POST["lang"];

	if (!$_SESSION["in_lang"]){
		include_once ("./juw-languages/cs_CZ.php");
	} else {
		include_once ("./juw-languages/".$_SESSION["in_lang"].".php");
	}

	include_once ("./juw-admin/juw-admin-includes/function.php");

	if ($_POST["new-conn"]){
		$conn = @mysql_connect($_POST["m_server"], $_POST["m_user"], $_POST["m_password"]);
		$cond = @mysql_select_db($_POST["m_database"]);
		if (!$conn OR !$cond) $m_err = "error";
		if ($conn AND $cond) {
			$_SESSION["in_m_server"] = $_POST["m_server"];
			$_SESSION["in_m_user"] = $_POST["m_user"];
			$_SESSION["in_m_password"] = $_POST["m_password"];
			$_SESSION["in_m_database"] = $_POST["m_database"];
			Header("Location: ./install.php?krok=3");
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head> 
  <title><?php echo in_nadpis ;?></title> 
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />  
  <link rel="stylesheet" href="./juw-admin/theme/style.css" type="text/css" /> 
  <link href="./files/favicon.png" rel="icon" type="image/png" />
  <script language="javascript" src="./juw-admin/juw-admin-includes/ajax.js"></script>
</head> 
<body>
<div class="page">
 <div class="header">
               <img src="./juw-admin/theme/images/logo.png" border="0">

 </div>
    
    <div class="nav">
      <ul class="menu">
<li><a<?php if (!$_GET["krok"] OR $_GET["krok"] >= 1) echo " class=' active'" ;?>><span><span><?php echo in_k_1 ;?></span></span></a></li>
<li><a<?php if ($_GET["krok"] >= 2) echo " class=' active'" ;?>><span><span><?php echo in_k_2 ;?></span></span></a></li>
<li><a<?php if ($_GET["krok"] >= 3) echo " class=' active'" ;?>><span><span><?php echo in_k_3 ;?></span></span></a></li>


      </ul>
	</div>

 

                
                    <div class="box">

                      <h1><?php echo in_box_nad ;?></h1>
<h2><?php echo in_box_pod ;?></h2>					</div>
 <div class="content">





<?php

switch($_GET["krok"])
{
case "2":
echo "<h1>".in_k_2."</h1>";
echo "<form action=\"./install.php?krok=2\" method=\"post\">";
if ($m_err){
	echo "<br /><span style=\"border:2px red solid;padding:5px 30px 5px 30px;\">".in_m_err."</span>";
}
echo "<table border=0 id=vypis>";
echo "<tr><td>MySQL Server <td><input name=\"m_server\" value=\"".$_POST["m_server"]."\">  ".in_m_server;
echo "<tr><td>MySQL User <td><input name=\"m_user\" value=\"".$_POST["m_user"]."\">";
echo "<tr><td>MySQL Password <td><input type=\"password\" name=\"m_password\" value=\"".$_POST["m_password"]."\">";
echo "<tr><td>MySQL Database <td><input name=\"m_database\" value=\"".$_POST["m_database"]."\">";
echo "</table>";
echo "<input type=\"submit\" name=\"new-conn\" value=\"".in_k_dal."\"></form>";




break;

case "3":
	echo "<h1>".in_k_3."</h1>";

	mysql_connect($_SESSION["in_m_server"], $_SESSION["in_m_user"], $_SESSION["in_m_password"]);
	mysql_select_db($_SESSION["in_m_database"]);
	mysql_query ("SET NAMES 'utf8';");

	$sql = mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_clanky` (
  `id` int(11) NOT NULL auto_increment,
  `nadpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `text1` varchar(1000) collate utf8_czech_ci NOT NULL,
  `text` varchar(4000) collate utf8_czech_ci NOT NULL,
  `texy1` varchar(1000) collate utf8_czech_ci NOT NULL,
  `texy` varchar(4000) collate utf8_czech_ci NOT NULL,
  `autor` varchar(1000) collate utf8_czech_ci NOT NULL,
  `cas` varchar(1000) collate utf8_czech_ci NOT NULL,
  `kategorie` varchar(1000) collate utf8_czech_ci NOT NULL,
  `publikovat` varchar(3) collate utf8_czech_ci NOT NULL,
  `hod` int(1) NOT NULL default '1',
  `kom` int(1) NOT NULL default '1',
  `tagy` varchar(1000) collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_clanky` (`id`, `nadpis`, `text1`, `text`,`texy1`, `texy`, `autor`, `cas`, `kategorie`, `publikovat`, `hod`, `kom`, `tagy`) VALUES
(1, '".in_d_prv_cl."', '".in_d_cl_v."','', '".in_d_cl_v."','', '1', '1249460381', '1', 'ano', 0, 1, '".in_cl_tag."');
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_config` (
  `name` varchar(1000) collate utf8_czech_ci NOT NULL,
  `subname` varchar(1000) collate utf8_czech_ci NOT NULL,
  `title` varchar(200) collate utf8_czech_ci NOT NULL,
  `footer` varchar(1000) collate utf8_czech_ci NOT NULL,
  `theme` varchar(100) collate utf8_czech_ci NOT NULL,
  `doplnky` int(1) NOT NULL default '0',
  `stranky` varchar(10) collate utf8_czech_ci NOT NULL,
  `html` int(1) NOT NULL default '0',
  `rss` int(1) NOT NULL default '0',
  `defchmod` int(11) NOT NULL,
  `regchmod` int(11) NOT NULL,
  `reg` int(1) NOT NULL,
  `popisky` int(1) NOT NULL,
  `popisky2` int(1) NOT NULL,
  `uvod` varchar(100) collate utf8_czech_ci NOT NULL,
  `tvar_popisku` varchar(1000) collate utf8_czech_ci NOT NULL,
  `date` varchar(100) collate utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
");
	$sql .= mysql_query ("
INSERT INTO `juw_config` (`name`, `subname`, `title`, `footer`, `theme`, `doplnky`, `stranky`, `html`, `rss`, `defchmod`, `regchmod`, `reg`, `popisky`, `popisky2`, `uvod`, `tvar_popisku`, `date`) VALUES
('".in_d_co_jm."', '".in_d_co_po."', '".in_d_co_ti."', '".in_d_co_pa."', '".in_d_co_vz."', 1, '5', 1, 1, 5, 4, 1, 1, 0, 'seznam','%pub% %autor% %date%, %kat%, %kom%','j/m/Y H:i:s');

");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_doplnky` (
  `id` int(9) NOT NULL auto_increment,
  `jmeno` varchar(100) collate utf8_czech_ci NOT NULL,
  `funkce` varchar(100) collate utf8_czech_ci NOT NULL,
  `slozka` varchar(100) collate utf8_czech_ci NOT NULL,
  `verze` varchar(100) collate utf8_czech_ci NOT NULL,
  `aktivni` varchar(110) collate utf8_czech_ci NOT NULL,
  `poradi` int(3) NOT NULL default '999',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=3 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_doplnky` (`id`, `jmeno`, `funkce`, `slozka`, `verze`, `aktivni`, `poradi`) VALUES
(1, '".in_d_co_d_m."', 'menu', 'menu', '0.1', 'ano', 1),
(2, '".in_d_co_d_v."', 'vyhledavani', 'vyhledavani', '0.1', 'ano', 3),
(3, 'Meta', 'meta', 'meta', '0.1', 'ano', 2),  
(4, 'Fórum', 'forum', 'forum', '0.1', 'ne', 4);
");
	$sql = mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_hodnosti` (
  `id` int(9) NOT NULL auto_increment,
  `smazano` int(1) NOT NULL,
  `jmeno` varchar(100) character set utf8 collate utf8_czech_ci NOT NULL,
  `clanky` int(1) NOT NULL,
  `clanky_publ` int(1) NOT NULL,
  `komentovani` int(1) NOT NULL,
  `hodnoceni` int(1) NOT NULL,
  `kategorie` int(1) NOT NULL,
  `stranky` int(1) NOT NULL,
  `doplnky` int(1) NOT NULL,
  `soubory` int(1) NOT NULL,
  `statistika` int(1) NOT NULL,
  `uzivatele` int(1) NOT NULL,
  `prihlaseni` int(1) NOT NULL,
  `nastaveni` int(1) NOT NULL,
  `zah_kategorie` int(1) NOT NULL,
  `zah_clanky` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_hodnosti` (`id`, `smazano`, `jmeno`, `clanky`, `clanky_publ`, `komentovani`, `hodnoceni`, `kategorie`, `stranky`, `doplnky`, `soubory`, `statistika`, `uzivatele`, `prihlaseni`, `nastaveni`, `zah_kategorie`, `zah_clanky`) VALUES
(1, 0, '".in_ho_a."', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(2, 0, '".in_ho_s."', 0, 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0),
(3, 0, '".in_ho_r."', 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(4, 0, '".in_ho_e."', 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(5, 0, '".in_ho_n."', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
");
	$sql .= mysql_query ("CREATE TABLE `juw_komentare` (
  `id` int(11) NOT NULL auto_increment,
  `podpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `clan_id` varchar(1000) collate utf8_czech_ci NOT NULL,
  `clan_nadpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `nadpis` varchar(100) collate utf8_czech_ci NOT NULL,
  `text` varchar(600) collate utf8_czech_ci NOT NULL,
  `cas` varchar(1000) collate utf8_czech_ci NOT NULL,
  `pokus` varchar(1000) collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=12 ;
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_kategorie` (
  `id` int(9) NOT NULL auto_increment,
  `jmeno` varchar(100) collate utf8_czech_ci NOT NULL,
  `desk` int(1) NOT NULL,
   `obrazek` VARCHAR( 100 ) NOT NULL DEFAULT 'none',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_kategorie` (`id`, `jmeno`, `desk`) VALUES
(1, '".in_k_os."', 0);
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_komentare` (
  `id` int(11) NOT NULL auto_increment,
  `podpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `clan_id` varchar(1000) collate utf8_czech_ci NOT NULL,
  `clan_nadpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `nadpis` varchar(100) collate utf8_czech_ci NOT NULL,
  `text` varchar(600) collate utf8_czech_ci NOT NULL,
  `cas` varchar(1000) collate utf8_czech_ci NOT NULL,
  `pokus` varchar(1000) collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_statistika` (
  `id` int(11) NOT NULL auto_increment,
  `clanek` varchar(100) character set utf8 collate utf8_czech_ci NOT NULL,
  `ip` varchar(100) character set utf8 collate utf8_czech_ci NOT NULL,
  `time` int(9) NOT NULL,
  `den` int(2) NOT NULL,
  `mesic` int(2) NOT NULL,
  `rok` int(4) NOT NULL,
  `referer` varchar(1000) character set utf8 collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_stranky` (
  `id` int(11) NOT NULL auto_increment,
  `nadpis` varchar(1000) collate utf8_czech_ci NOT NULL,
  `text1` varchar(4000) collate utf8_czech_ci NOT NULL,
  `text` varchar(4000) collate utf8_czech_ci NOT NULL,
  `autor` varchar(1000) collate utf8_czech_ci NOT NULL,
  `cas` varchar(1000) collate utf8_czech_ci NOT NULL,
  `kategorie` int(9) NOT NULL,
  `poradi` INT( 11 ) NOT NULL DEFAULT '999',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_stranky` (`id`, `nadpis`, `text1`, `text`, `autor`, `cas`, `kategorie`) VALUES
(1, '".in_s_pr."', '".in_s_t."', '".in_s_t."', '1', '1249460381', 1);
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_user` (
  `id` int(100) NOT NULL auto_increment,
  `login` varchar(100) collate utf8_czech_ci NOT NULL,
  `password` varchar(100) collate utf8_czech_ci NOT NULL,
  `chmod` int(11) NOT NULL,
  `jmeno` varchar(100) collate utf8_czech_ci NOT NULL,
  `adresa` varchar(1000) collate utf8_czech_ci NOT NULL,
  `tel` varchar(100) collate utf8_czech_ci NOT NULL,
  `www` varchar(100) collate utf8_czech_ci NOT NULL,
  `email` varchar(100) collate utf8_czech_ci NOT NULL,
  `popis` varchar(1000) collate utf8_czech_ci NOT NULL, 
  `avatar` varchar(100) collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=3 ;
");
	$sql .= mysql_query ("
INSERT INTO `juw_user` (`id`, `login`, `password`, `chmod`, `jmeno`, `adresa`, `tel`, `www`, `email`, `popis`) VALUES
(1, '".in_u_j."', '".md5(in_u_h)."', 1, '".in_u_jm."', '', '', '', '', '');
");
	$sql .= mysql_query ("
CREATE TABLE IF NOT EXISTS `juw_znamky` (
  `id` int(11) NOT NULL auto_increment,
  `autor` varchar(100) collate utf8_czech_ci NOT NULL,
  `id_clanku` int(11) NOT NULL,
  `nadpis_clanku` varchar(1000) collate utf8_czech_ci NOT NULL,
  `znamka` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;
");
 	$sql .= mysql_query ("ALTER TABLE `juw_clanky` ADD FULLTEXT ( `nadpis` )");  
 	$sql .= mysql_query ("ALTER TABLE `juw_clanky` ADD FULLTEXT ( `text` )");
 	$sql .= mysql_query ("ALTER TABLE `juw_clanky` ADD FULLTEXT ( `text1` )");
 	
$sql .= mysql_query ("
CREATE TABLE `juw_doplnek_forum_tema` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`jmeno` VARCHAR( 100 ) NOT NULL ,
`zamknuto` INT( 1 ) NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_czech_ci;
");
$sql .= mysql_query ("
CREATE TABLE `juw_doplnek_forum_prispevky` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`predmet` VARCHAR( 100 ) NOT NULL ,
`text` VARCHAR( 4000 ) NOT NULL ,
`autor` INT( 11 ) NOT NULL ,
`id_pris` VARCHAR( 100 ) NOT NULL,
`tema` INT( 11 ) NOT NULL ,
`time` INT( 11 ) NOT NULL 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_czech_ci;
");
$sql .= mysql_query ("
CREATE TABLE `juw_doplnek_forum` (
`spravce` INT( 11 ) NOT NULL PRIMARY KEY 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_czech_ci;
");
$sql .= mysql_query ("
INSERT INTO `juw_doplnek_forum` VALUES ('0');
");
if ($sql) echo in_d_ok;


$system = cms;
$verze = cms_verze;
$server = $_SESSION["in_m_server"];
$text = <<<END
<?php

/**********************************************************
*                          $system                            *
*                                                         *
*    Copyright (c) 2009, Enyeus Neiklot, $verze          *
*              Vydáno pod licencí GNU/GPL 3               *
**********************************************************/


/* Nastavení MySQL databáze */

\$juw_mysql_server = "{$server}";
\$juw_mysql_user = "{$_SESSION[in_m_user]}";
\$juw_mysql_password = "{$_SESSION[in_m_password]}";
\$juw_mysql_db = "{$_SESSION[in_m_database]}";


/* Nastavení */

\$juw_strankovani = 1;             // 1 - stránkování zapnuto; 0 - stránkování vypnuto
\$juw_znamkovani = 1;              // 1 - známkování zapnuto; 0 - známkování vypnuto
\$juw_komentare = 1;               // 1 - komentáře zapnuty; 0 - komentáře vypnuty

#error_reporting("E_NOTICE");


include_once (dirname(__FILE__)."/juw-languages/{$_SESSION[in_lang]}.php");

?>
END;
$soubor = @fopen("./config.php", "w");
@fwrite($soubor, $text);
@fclose($soubor);
if (!$soubor) {
echo in_s_err."<textarea cols=\"90\" rows=\"35\">$text</textarea>";
}
break;
default:

	echo "<h1>".in_k_1."</h1>";
	echo "<center><form action=\"./install.php?krok=2\" method=\"post\"><select name=\"lang\">";
	juw_obsah_slozky("./juw-languages/", "none.php");
	echo "</select> <input type=\"submit\" name=\"new-lang\" value=\"".in_k_dal."\"></form></center>";

break;
}
?>
 </div>


        <div class="footer">

	<p><?php echo cms_verze;?>, Copyright 2009, All right reserved, Created by Enyeus Neiklot</p>

    </div>
</div>

</body>
</html>
