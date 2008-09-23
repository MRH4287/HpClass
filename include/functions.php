<?php
// Funktions


function PM($zu, $von, $Betreff, $text, $datum, $dbpräfix)
{ // BEGIN function PM
$time = time();
	$eintragintodb = "INSERT INTO `".$dbpräfix."pm`
(von, datum, zu, text, Betreff, gelesen, timestamp)
VALUES
('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$time')";
$eintragen123 = mysql_query($eintragintodb);
echo mysql_error();
} // END function PM

function Error($errorcode, $wo)
{ // BEGIN function Error
echo "FEHLER... Zu neuem errorsystem wechseln!!";
} // END function Error

function SQLexec($string, $wo)
{ // BEGIN function Error
echo "zu neuem system wechseln";


return $proofpost;
} // END function Error

//LoginFunktion
function login ($level = "3", $dbpräfix, $bereich = "Privat", $fehler = "Dieser Bereich ist nicht öffentlich zugänglich!")
{
//MYSQL Zugriff ...
// Nur möglich bei einer vorhandenen Mysql verbindung!!
if ($level == "1") {
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '1'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '2'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '3'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
} elseif ($level == "2")
{
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '2'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '3'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
} else
{

$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '3'";
$ergebnis = mysql_query($abfrage);
echo mysql_error();

while($row = mysql_fetch_object($ergebnis))
{
$user= "$row->user";
$pass = "$row->pass";
$nutzer[$user]=$pass;
}
}

$user = '';
$pass = '';

if (!array_key_exists($_SERVER['PHP_AUTH_USER'], $nutzer) ||
 $_SERVER['PHP_AUTH_PW'] != $nutzer[$_SERVER['PHP_AUTH_USER']]) {
 header("HTTP/1.1 401 Unauthorized");
 header("WWW-Authenticate: Basic realm=".$bereich);
 echo $fmeldung;
 exit;
 }
 }


function Autorun()
{ // BEGIN function Autorun
	


$x=-2;
$handle = @opendir("./include/autorun/$file"); 
while (false !== ($file = @readdir($handle))) {
	$attrib=@fileperms("./include/autorun/$file");
	$filesize=@filesize("./include/autorun/$file");
	$file_size_now = @round($filesize / 1024 * 100) / 100 . "Kb";
	$n= @explode(".",$file);
$art = @strtolower($n[1]);


if (($file != ".") and ($file != "..") and ($file != "...") and ($file != ".svn"))
{
if (file_exists("./include/autorun/$file"))
include ("./include/autorun/$file");

//echo "<a href=./include/autorun/$file>$file</a>";
}
}  
	
} // END function Autorun

function br()
{
echo "<br>";
}

?>
