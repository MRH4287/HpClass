<?


	require_once("config.php");


	$connection=mysql_connect($dbserver,
$dbuser,$dbpass);
	if(!$connection) 
	{
		print "Fehler bei Datenbankverbindungsaufbau.<br/>\n";
		print mysql_error($connection)."<br/>\n";			
		exit;
	}
	
	if(!mysql_select_db($dbdatenbank,$connection))
	{
		print "Fehler bei Auswahl der Datenbank '$dbdatenbank'.<br/>\n";
		print mysql_error($connection)."<br/>\n";
		exit;			
	}



 header("Content-type: text/xml"); 
 echo "<". "?xml version =\"1.0\" encoding=\"ISO-8859-1\"?".">\n";
 
 function Textcutter($text, $anzahl)
 { // BEGIN function Textcutter
 	preg_match("/(.{0,$anzahl}\b)/s", $text, $kurtz);
 	return $kurtz[0].' ...';
 } // END function Textcutter
 
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
 <channel>
 <title>HPClass News RSS</title>
 <description>Test Script, für eventuelle verwendung</description>
 <link>http://localhost/</link>
 <generator>HPClass CMS</generator>
 <language>de</language>
 	<image>
		<link>http://localhost/</link>
		<title>MRH</title>
		<url>http://localhost/hpclass/nopic.jpg</url>
	</image>
 <?php
// RSS Feed

$limit=10;
$abfrage = "SELECT * FROM ".$dbpräfix."news ORDER BY `ID` DESC LIMIT ".$limit;
$ergebnis = mysql_query($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());
    
while($row = mysql_fetch_object($ergebnis))
{
$newstext= "$row->text";
//$newstext = str_replace('<br>',"\n" ,$newstext);
//$newstext = str_replace('<',"&lt;" ,$newstext);
//$newstext = str_replace('&',"&amp;" ,$newstext);
//$newstext = Textcutter($newstext, 180);

if ("$row->level" == 0)
{
echo " <item>\n";
echo "  <title>$row->titel</title>\n";
echo "  <link>http://localhost/hpclass/index.php</link>\n";
echo "  <description><![CDATA[
$newstext
]]></description>\n";
//echo "  <comments>http://mrh.mr.ohost.de/Tobi/feed/comments.php?id=$row->ID</comments>\n"; //http://mrh.mr.ohost.de/Tobi/index.php?site=comments&id=$row->ID
echo "  <pubDate>$row->datum</pubDate>\n";
echo "  <author>$row->user</author>\n";

echo " </item>\n";
} 

}

?>
 </channel>
</rss>
