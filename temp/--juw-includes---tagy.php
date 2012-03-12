<?php

function juw_page_content()
{
global $juw_strankovani;
echo "<h1>".cl_s_t.$_GET["tag"]."</h1>";
	if ( $juw_strankovani == 1 ){
		$sql_stranka = mysql_query("SELECT stranky FROM juw_config LIMIT 1;");
		$z_stranka = mysql_fetch_array($sql_stranka);
		$juw_strankovani_limit = $z_stranka["stranky"];
	}

if (IsSet($_GET["tag"])){

	if($juw_strankovani == 1){
		if(isset($_GET["start"])){
			$sql = mysql_query("SELECT * FROM juw_clanky WHERE tagy LIKE '%;".addslashes($_GET["tag"]).";%' AND publikovat = 'ano' ORDER BY id DESC LIMIT ".$_GET["start"].",".$juw_strankovani_limit.";");}
		else{
			$sql = mysql_query("SELECT * FROM juw_clanky WHERE tagy LIKE '%;".addslashes($_GET["tag"]).";%' AND publikovat = 'ano' ORDER BY id DESC LIMIT ".$juw_strankovani_limit.";");
		}
	} else {
		$sql = mysql_query("SELECT * FROM juw_clanky WHERE tagy LIKE '%;".addslashes($_GET["tag"]).";%' AND publikovat = 'ano' ORDER BY id DESC");
	}

while($z = mysql_fetch_array($sql)){
echo <<<END
		<div class="post">
<div class="post_title">
			<h2 class="title"><a href="?kategorie=$z[kategorie]&clanek=$z[id]">$z[nadpis]</a></h2></div>
END;

$ssqlpop = mysql_fetch_array(mysql_query("SELECT popisky FROM juw_config LIMIT 1"));
$pop = $ssqlpop["popisky"];
if ($pop == 1){ // Začátek popisku
echo "<p class=\"meta\">".cl_publikoval."<a href=\"?user=$z[autor]\">";
$ssql = mysql_query("SELECT jmeno FROM juw_user WHERE id = $z[autor] LIMIT 1");
$zzz = mysql_fetch_array($ssql);
echo $zzz["jmeno"]."</a> ".$z["cas"];
if(!isset($_GET["kategorie"])){
	$sqlt = mysql_query("SELECT * FROM juw_kategorie WHERE id = ".$z["kategorie"].";");
	while($zzz = mysql_fetch_array($sqlt)){
		echo ", <a href=\"./?kategorie=".$zzz["id"]."\">". $zzz["jmeno"]."</a>";
	}
}


	$ssqql = mysql_query("SELECT id FROM juw_komentare WHERE clan_id = $z[id]");
	$zzz = mysql_num_rows($ssqql);
	if($zzz == 0){
		echo k_none;
	}else{
		echo ", <a href=\"?clanek=$z[id]#kom\">".k_nenone."</a>: $zzz ";
	}
	echo "</p>";
} // Konec popisku

echo "<div class=\"entry\">$z[text1]</div></div>";
}

strankovani($juw_strankovani_limit);

}

}

?>
