<?php

$nutzer['admin']="install";

$fmeldung="Dieser Bereich ist nur für die Installation des Systems gedacht!<br>
Das Passwort und der Benutzername befinden sich in der Readme Datei!";

if (!array_key_exists($_SERVER['PHP_AUTH_USER'], $nutzer) ||
 $_SERVER['PHP_AUTH_PW'] != $nutzer[$_SERVER['PHP_AUTH_USER']]) {
 header("HTTP/1.1 401 Unauthorized");
 header("WWW-Authenticate: Basic realm=".$bereich);
 echo $fmeldung;
 exit;
 }


extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);
//require 'ProgressClass.php';

if (file_exists("../include/config.php")){
echo "Ihre Webseite wurde bereits Installiert!<br>Für eine Neuinstallation,
löschen Sie die config.php und alle Tabellen die zu ihrer Webseite gehören von ihrem Server!<br>
(Da es sonst zu Fehlern kommen kann!)";
echo "<br>Für ein Update des Systems klicken sie <a href=update.php>Hier</a>.";
exit;
}


Echo "<p align=\"center\"><font size=\"6\">Installation:</font></p>";
if (!isset($s1) and !isset($s2))
{
?>
<p><form method="POST" action="index.php">

  <p>MYSQL:</p>
  <p>Server:<br>
  <input type="text" name="dbserver" size="20" value="localhost"></p>
  <p>User:<br>
  <input type="text" name="dbuser" size="20"></p>
  <p>Passwort<br>
  <input type="text" name="dbpass" size="20"></p>
  <p>Datenbank:<br>
  <input type="text" name="dbdatenbank" size="20"><input type="checkbox" name="dbcreate" value="ON">Erstellen</p>
  <p>Präfix<br>
  <input type="text" name="dbpraefix" size="20" value="test_">
  <input type="checkbox" name="dbonlyconfig" value="ON">Nur Config erzeugen
  <input type="submit" value="Abschicken" name="s1"></p>
</form> </p>
<?
} else
{
if (!isset($s2) and !isset($dbonlyconfig)) {
?>
<p align="left"><font size="4">Verlauf:</font></p>
<?  


$db = mysql_connect($dbserver,
$dbuser,$dbpass)
or print "keine Verbindung möglich.
 Benutzername oder Passwort sind falsch<br>";
if (!isset($dbcreate)){
echo "select ok<br>"; 
mysql_select_db($dbdatenbank, $db)

or print "Die Datenbank existiert nicht.<br>";
}

if ($s1 == "Abschicken" and !isset($dbonlyconfig))
{

if (isset($dbcreate)){
$eintrag = "CREATE DATABASE `".$dbdatenbank."` ;";
$eintragen = mysql_query($eintrag)  or print "1: Error: ".mysql_error()."";
if ($eintragen == true)
{ $ok1=true;
echo "1: Datenbank erstellt<br>";

mysql_select_db($dbdatenbank, $db);
 }
} else { $ok[1]=true;  echo "Datenbank Erstellung übersprungen<br>";  }



$handle = @opendir("./sql"); 
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if ($exp[1] == "sql")
{

$content = file_get_contents("sql/".$file);

$content = str_replace("#!-PRÄFIX-!#", $dbpraefix, $content);

$eintragen = mysql_query($content)  or print "$file: Error: ".mysql_error()."";
if ($eintragen == true)
{ $ok1=true;
echo "Tabelle ".$exp[0]." erstellt<br>";
}
}

}

$handle = @opendir("./sql/insert"); 
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if ($exp[1] == "sql")
{

$content = file_get_contents("sql/insert/".$file);

$content = str_replace("#!-PRÄFIX-!#", $dbpraefix, $content);

$eintragen = mysql_query($content)  or print "$file: Error: ".mysql_error()."";
if ($eintragen == true)
{ $ok1=true;
echo "Daten in die Tabelle ".$exp[0]." eingefügt<br>";
}
}

}







echo "<p align=\"center\"><font size=\"4\">Die Datenbank wurde konfiguriert!</font></p>";
?>
<br>
<p>Config mit standardwerten erstellen?</p>
<?
echo" <form method=\"POST\" action=\"index.php?dbserver=$dbserver&dbuser=$dbuser&dbpass=$dbpass&dbdatenbank=$dbdatenbank&dbpraefix=$dbpraefix\">";
?>
  <p><input type="checkbox" name="config" value="ON">JA&nbsp;&nbsp;&nbsp; <input type="submit" value="OK" name="s2"></p>
</form>
<?

}
}

if ($s2 == "OK" or isset($dbonlyconfig) or isset($config))
{

$userdatei = fopen ("../include/config.php","w");


     // $userdatei = fopen ($datakata,"a");

      fwrite($userdatei, "<?php\n");
      fwrite($userdatei, "// Config Datei\n");
      fwrite($userdatei, "\n");
      fwrite($userdatei, "//Hier weden alle Variablen gespeichert, wie MySQL Daten.\n");
      fwrite($userdatei, "//Diese Datei wurde so ausgelegt, so dass sie von einem belibigen,\n");
      fwrite($userdatei, "//Editor editiert werden kann!\n");
      fwrite($userdatei, "\n");
      fwrite($userdatei, "//MySQL\n");
      fwrite($userdatei, "\$dbserver=\"$dbserver\";\n");
      fwrite($userdatei, "\$dbuser=\"$dbuser\";\n");
      fwrite($userdatei, "\$dbpass=\"$dbpass\";\n");
      fwrite($userdatei, "\$dbdatenbank=\"$dbdatenbank\";\n");
      fwrite($userdatei, "\$dbpräfix=\"$dbpraefix\";\n");
      fwrite($userdatei, "?"); 
      fwrite($userdatei, ">\n");
      fclose($userdatei);
      echo "<br>Erfolgreich eingetragen!<br>";
echo "<br>Installation Erfolgreich!<br>";
echo "<p align=\"center\"><font size=\"4\">Herzlichen Glückwunsch!<br>Die Website ist nun voll Funktionsfähig!!</font></p>";
echo "<p align=\"center\"><font size=\"4\">Die Benutzerdaten sind: admin Passwort: admin (dieser Benutzer kann ohne gefahren wieder gelöscht werden)</font></p>";
echo "<br><b>Bitte diese Datei Löschen!!</b><br><a href=\"../index.php\">Zurück zur Hauptseite</a>";
      
} else
{ 
if (file_exists("../include/config.php")) 
{
echo "<br>Installation Erfolgreich!<br>";
echo "<p align=\"center\"><font size=\"4\">Herzlichen Glückwunsch!<br>Die Website ist nun voll Funktionsfähig!!</font></p>";
echo "<p align=\"center\"><font size=\"4\">Die Benutzerdaten sind: admin Passwort: admin (dieser Benutzer kann ohne gefahren wieder gelöscht werden)</font></p>";
echo "<br><b>Bitte diese Datei Löschen!!</b><br><a href=\"./update.php\">PATCHEN</a>";
} else
{
echo "Es wurde keine Config Datei gefunden!<br>Wir raten ihnen, eine Config Datei zu erstellen!";
}
}



}

?>
