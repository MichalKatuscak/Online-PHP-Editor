<<?php echo "";?>?xml version="1.0" encoding="UTF-8"?>
<?php
include_once ("./config.php");
mysql_connect ( $juw_mysql_server, $juw_mysql_user, $juw_mysql_password );
mysql_select_db ( $juw_mysql_db );
mysql_query ("SET NAMES 'utf8';");
if ($juw_komentare == 1){

$web_rss = "http://".$_SERVER["HTTP_HOST"].str_replace("/rss_kom.php","",$_SERVER["SCRIPT_NAME"]);

$sel = mysql_query("SELECT rss FROM juw_config LIMIT 1");
$num = mysql_fetch_array($sel);
if($num["rss"] == 1){

$ren =  gmdate('D, d M Y H:i:s').' GMT'; 
$sel = mysql_query("SELECT * FROM juw_config LIMIT 1");
$num = mysql_num_rows($sel);
  for($r=0;$r<$num;$r++)
  {
   $rs = mysql_fetch_array($sel);
    echo <<<END

<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel rdf:about="$web_rss">
    <title>$rs[name] - komentáře</title> 
    <link>$web_rss</link>
    <description>$rs[subname]</description>
  </channel>
END;
  }

$sell = mysql_query("SELECT * FROM juw_komentare ORDER BY id desc");

$numm = mysql_num_rows($sell);
  for($r=0;$r<$numm;$r++)
  {
   $rs = mysql_fetch_array($sell);


$text = $rs["text"];
$text = htmlspecialchars($text);
$au = $rs["podpis"];
$au = strip_tags($au);
$ssql = mysql_query("SELECT id,nadpis FROM juw_clanky WHERE id = $rs[clan_id] LIMIT 1");
$zzz = mysql_fetch_array($ssql);
echo $zzz["jmeno"];
echo <<<END
  <item rdf:about="$web_rss/?clanek=$zzz[id]#kom">
    <title>$zzz[nadpis] - $au</title>
    <link>$web_rss/?clanek=$zzz[id]#kom</link>
    <description>$text</description>
    <dc:creator>$au</dc:creator>
  </item>

END;
   
  }
echo "</rdf:RDF>";
}else{
echo "RSS není na tomto webu povolené.";
}
}
?>
