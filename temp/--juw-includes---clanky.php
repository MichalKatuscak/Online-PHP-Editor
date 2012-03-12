<?php

if (isset($_GET["stranka"]) AND !$_GET["clanek"] AND !$_GET["kategorie"]) {
 function juw_page_content () { napis_clanek ("stranky"); to_statistika("s".$_GET["stranka"]); }
} else {


if (!isset($_GET["clanek"]) AND !isset($_GET["stranka"])){


function juw_page_content ()
{
global $juw_strankovani;
$l = mysql_fetch_array(mysql_query("SELECT uvod FROM juw_config LIMIT 1;"));
$uvod = $l["uvod"];
	if ( $juw_strankovani == 1 ){
		$sql_stranka = mysql_query("SELECT stranky FROM juw_config LIMIT 1;");
		$z_stranka = mysql_fetch_array($sql_stranka);
		$juw_strankovani_limit = $z_stranka["stranky"];
	}
	if(!isset($_GET["kategorie"])){
		if ($uvod == "seznam"){
					if($juw_strankovani == 1){
						$sql = mysql_query("SELECT juw_clanky.* FROM juw_clanky LEFT JOIN juw_kategorie ON juw_kategorie.id = juw_clanky.kategorie WHERE juw_clanky.publikovat = 'ano' ORDER BY juw_clanky.id DESC LIMIT ".$juw_strankovani_limit.";");
					}
					else {
						$sql = mysql_query("SELECT juw_clanky.* FROM juw_clanky LEFT JOIN juw_kategorie ON juw_kategorie.id = juw_clanky.kategorie WHERE juw_clanky.publikovat = 'ano' ORDER BY juw_clanky.id DESC ;");
					}
				echo "<h1>".w_uvod."</h1>";
				to_statistika("home");
		} elseif ($uvod > 0 AND $uvod < 10000000000) {
				napis_clanek ("stranky",$uvod);
				to_statistika("home");
		}
	}else{

	  echo "<h1>".juw_page_kategorie($_GET["kategorie"])."</h1>";

		$sql = razeni_clanku($juw_strankovani, $juw_strankovani_limit);
	}
		

if ($uvod == "seznam" OR IsSet($_GET["kategorie"])) {
while($z = mysql_fetch_array($sql)){
$href = nice_url($z["nadpis"]);
echo <<<END
		<div class="post">
<div class="post_title">
			<h2 class="title"><a href="?clanek=$z[id]-$href">$z[nadpis]</a></h2></div>
END;

$ssqlpop = mysql_fetch_array(mysql_query("SELECT popisky FROM juw_config LIMIT 1"));
$pop = $ssqlpop["popisky"];
if ($pop == 1){ // Začátek popisku
$pub = w_clanek;
if (!$_GET["clanek"] AND !$_GET["stranka"] AND !$_GET["kategorie"]){
$co = "clanky";
}

$nice_url = $href;
popisek ($z["autor"],$z["cas"],"%help%",$pub,$z["id"], $co, $z["kategorie"]);
if(!isset($_GET["kategorie"])){
	$sqlt = mysql_query("SELECT * FROM juw_kategorie WHERE id = ".$z["kategorie"].";");
	while($zzz = mysql_fetch_array($sqlt)){
		$obrazek = $zzz["obrazek"];
	}
}

} // Konec popisku

echo "<div class=\"entry\">";
if (file_exists("./files/images_kategory/$obrazek") AND $obrazek != "none") {
echo "<img src=\"./files/images_kategory/$obrazek\" class=\"kategory\">";
}
echo "$z[text1]</div></div>";
}
	if($juw_strankovani == 1 AND isset($_GET["kategorie"])) strankovani($juw_strankovani_limit); // Stránkování ( 1 | 2 | 3 )L
}
}





}else{


	function juw_page_content ()
	{
		global $juw_komentare, $juw_znamkovani;
		to_statistika ($_GET["clanek"]);
		napis_clanek ("clanky");
	}


}




}

?>
