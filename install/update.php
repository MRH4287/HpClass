<?php
$nutzer['admin']="install";

$fmeldung="Dieser Bereich ist nur für das Updaten des Systems gedacht!<br>
Das Passwort und der Benutzername befinden sich in der Readme Datei!";


if (!array_key_exists($_SERVER['PHP_AUTH_USER'], $nutzer) ||
 $_SERVER['PHP_AUTH_PW'] != $nutzer[$_SERVER['PHP_AUTH_USER']]) {
 header("HTTP/1.1 401 Unauthorized");
 header("WWW-Authenticate: Basic realm=".$bereich);
 echo $fmeldung;
 exit;
 }


class update
{
var $path = "../version/mysql.php";
var $host;
var $user;
var $connection;
var $password;
var $db;
var $präfix;
var $error = array();

function __construct()
{
include "../include/config.php";

$this->host =  $dbserver;
$this->user = $dbuser;
$this->password = $dbpass;
$this->db = $dbdatenbank;
$this->präfix = $dbpräfix;

$this->connect();
}

function getversion()
{
$path = $this->path;
if (file_exists($path))
{
$version = file_get_contents($path);

if ($version == "")
{
return false;
} else
{
return $version;
}
} else
{
echo "Versionsdatei nicht gefunden, erstellen.";
$date = fopen($path, "a");

return false;
}
}

function versions()
{

$handle = @opendir("./update"); 
$filearray = array();
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if ($exp[1] == "php")
{
$filearray[]=$exp[0];

}
}

return $filearray;

}

function getnewversion()
{
$filearray = $this->versions();

$last = 1;
foreach ($filearray as $key=>$value) {
	if ($value > $last )
	{
  $last = $value;
  }
}

return $last;
}

function listupdates()
{
$akt = $this->getversion();
$files = $this->versions();
$updates = array();

foreach ($files as $key=>$value) {
	if ($value > $akt)
	{
  $updates[] = $value;
  }
}
return $updates;
}

function connect()
{

if (!isset($this->host) or !isset($this->user) or !isset($this->password)) 
{

$this->error[] = "No Database Data set!";
echo "No Database Data Set! ($this->host, $this->user, $this->password)";
exit;
}

$this->connection = mysql_connect($this->host,$this->user,$this->password);

$myerror = mysql_error();
if ($myerror <> "")
{
$this->error[] = "$myerror";
}
 
mysql_select_db($this->db, $this->connection)
or print "Konnte die Datenbank nicht finden!";
$myerror = mysql_error();
if ($myerror <> "")
{
$this->error[] = "$myerror";
}
// Löschen alller DB Variablen
// Berhindert späteres auslesen!
$this->host = "";
$this->user = "";
$this->password = "";
$this->db = ""; 


}

function query($query)
{
$sql = $query;
$query = mysql_query($query);

$myerror = mysql_error();
if ($myerror <> "")
{

$this->error[] = "$myerror ($sql)";

}

return $query;
}

function getpräfix()
{
return $this->präfix;
}

function st_t()
{
$sql = "START TRANSACTION;";
$this->query($sql);
}
function ok()
{
$sql = "COMMIT;";
$this->query($sql);
}

function rollback()
{
$sql = "ROLLBACK;";
$this->query($sql);
}

function writeversion($version)
{
unlink($this->path);
$data = fopen($this->path, "a");
fwrite($data, $version);
fclose($data);

}
//Class Ende
}

$update = new update;
if (count($update->error) != 0)
{
print_r($update->error);
$update->error = array();
} else
{

$updates = $update->listupdates();
if (count($updates) >= 1)
{

foreach ($updates as $key=>$value) {
	$update->st_t();
	$file = file("./update/$value.php");
	
	foreach ($file as $a=>$v) {
 	
 $sql = $v;
	
	$sql = str_replace("#!-PRÄFIX-!#", $update->getpräfix(), $sql);
  	if (($sql != "") and ($sql != "<br>"))
 	 {
	 $update->query($sql);
	 }
  }
if (count($update->error) != 0)
{
echo "Ein Fehler ist Aufgetreten!<br>Fehlerlog:<br><pre>";
print_r($update->error);
echo "</pre><br>Ein Rollback wird ausgeführt!";
$update->rollback();
exit;
}	else
{
$update->ok();
$update->writeversion($value);
echo "<br> Update zur Mysql-Version $value Komplett!<br>";
}
	
}


} else
{
echo "Keine neuen Patch Dateien gefunden!";
}
}


?>