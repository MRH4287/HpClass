<?php

class HP
{
// Öffentliche Variablen:
public    $site;
public    $lang;
public    $hp;
public    $langclass;
public    $error;
public    $info;
public    $firephp;
public    $fp;
public    $lbsites;
public    $template;
public    $xajaxF;
public    $forum;


// Geschützte Variablen
protected $outputg;
protected $outputp;
protected $host;
protected $präfix;
protected $user;
protected $password;
protected $db;
protected $connection;
protected $sitepath;
protected $redirectlock;
protected $config;
protected $restrict;
protected $superadminonly;
protected $checkversionurl;
protected $pathtoversion;
protected $pathtomysqlversion;
protected $superadmin;
protected $standardsite;


// Config Area;
// ----------------------------------------------------------------
function HP()
{
//Main
$this->standardsite = "news"; // Wenn nicht durch Config verändert!

// Superadmins:
$this->superadmin = array("admin", "mrh");

// Siteckeckup
$this->restrict       = array("login");
$this->superadminonly = array("rights", "config", "test");

// Version
$this->checkversionurl = "http://mrh.mr.ohost.de/version/version.php?name=HPClass";
$this->pathtoversion   = "version/version.php";

//Mysql
$this->pathtomysqlversion = "version/mysql.php";

$this->hp = this;
}
// ------------------------------------------------------------------
// In diesem Bereich werden alle Variablen an die Klasse übergeben
// Die Set-Area
//-------------------------------------SET---------------------------------------
function setlang($langclass2)
{
$this->langclass = $langclass2;
$this->lang = $this->langclass->getlang();
}

function setdata($host, $user,$password, $präfix, $db)
{
$this->host=$host;
$this->password=$password;
$this->user=$user;
$this->präfix=$präfix;
$this->db=$db;
}

function setinfo($info)
{
$this->info = $info;
}

function seterror($error)
{
$this->error = $error;
}

function settemplate($template)
{
$this->template = $template;
}

function setfirephp($firephp)
{
$this->firephp = $firephp;
$this->fp = $firephp;
}

function setlbsites($lbsites)
{
$this->lbsites=$lbsites;
}

function setxajaxF($xajax)
{
$this->xajaxF = $xajax;
}

function setforum($forum)
{
$this->forum = $forum;
}

//  Ende set-Area

// Die GET Area
// In diesem Bereich werden Funktionen bestimmt, die Variablen an andere Funktionen liefern.
//--------------------------------------GET------------------------------------------
function get()
{
return $this->outputg;
}

function post()
{

return $this->outputp;
}

function site()
{
return $this->site;
}

function getpräfix()
{
return $this->präfix;
}


function getlangclass()
{
return $this->langclass;
}

function getfirephp()
{
return $this->firephp;
}
// Versions überprüfung
// 4.1

//---------------------VERSION----------------------------------------------
function getmysqlversion()
{
$path = $this->pathtomysqlversion;
$version = @file_get_contents($path);

if ($version == "")
{
return false;
} else
{
return $version;
}
}

function getversion()
{
$path = $this->pathtoversion;
include($path);
return array( 'version' => "$version", 'changelog' => "$changelog");
}

function checkversion()
{
$url = $this->checkversionurl;

$version= @file_get_contents($url);
// Error-Handler
if ($version == "")
{
$this->setconfig("checkversion", false);
    $this->error->error("Konnte keine Versionsprüfung durchführen! Update-Prüf Funktion Deaktiviert!", "2");
} else
{

$array = $this->getversion();
$version2 = $array['version'];

if (($version != $version2) and (in_array($_SESSION['username'], $this->superadmin)))
{
$this->info->info("Ihre Version ist nicht mehr auf dem neusten Stand! ($version2 - $version)");

}
} // Error -Handler
}
//-----------------------------/VERSION----------------------------------------------- 

function geterror()
{
return $this->error;
}

function getinfo()
{
return $this->info;
}
// Ende Get Area
//-----------------------------------------MYSQL-----------------------------------------
// Mysql Area:
function connect()
{

if (!isset($this->host) or !isset($this->user) or !isset($this->password)) 
{
$this->error->error("No Database Data set!", "3");
}

$this->connection = mysql_connect($this->host,
$this->user,$this->password)
or $this->error->error($this->lang['userorpasswordwrong'], "3");
$myerror = mysql_error();
if ($myerror <> "")
{
$this->error->error("$myerror", "2");
}
 
mysql_select_db($this->db, $this->connection)
or print $this->lang['nodb'];
$myerror = mysql_error();
if ($myerror <> "")
{
$this->error->error("$myerror", "2");
}
// Löschen aller DB Variablen
// Verhindert späteres auslesen!
$this->host = "";
$this->user = "";
$this->password = "";
$this->db = ""; 


}

function mysqlquery($query)
{
$query = mysql_query($query);

$myerror = mysql_error();
if ($myerror <> "")
{

$this->error->error("$myerror", "2");

}

return $query;
}

function escapestring($string)
{
return mysql_real_escape_string($string);
}

//-------------------------------------------ALLGEMEIN--------------------------------
// Allgemeine Funktionen:

function handelinput ($get, $post)
{
if (isset($get))
{
foreach ($get as $key=>$value) {
$this->outputg[$key]=$value;
}
} 


if (isset($post))
{
foreach ($post as $key=>$value) {
$this->outputp[$key]=$value;
	
}
} 

if (isset($get['lbsite']))
{
$vars = $get['vars'];

$this->lbsites->load($get['lbsite'], $vars);
exit;
}

if (isset($get['site']))
{

$site = $get['site'];
$this->site=$site;
} 

if (isset($get['lang']))
{
$this->langclass->setlang($get['lang']);
$this->setlang($this->langclass);
}

if ($get['login'] == "n")
{
$loginfail = $this->langclass->word("loginfail");
$this->error->error($loginfail, "2");
}

if(isset($get['lchange']) and ($_SESSION['username'] == "mrh"))
{
$_SESSION['level'] = $get['lchange']; 
}

$hp = $this;
$dbpräfix = $hp->getpräfix();
$right = $hp->getright();
$level = $_SESSION['level'];
$info = $hp->getinfo();

if (isset($post['forum_editpost']))
{

$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);
$time = time();

if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbpräfix"."posts` SET `text` = '".$post['text']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Post geändert");
}


}

if (isset($post['forum_editthread']))
{

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$titel = $post['titel'];
$level = $post['level'];
$type = $post['type'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];
$time = time();

$pw = "";
if (($passwort != "*") and ($passwort != ""))
{
$pw = "`passwort` = '".md5($passwort)."' ,";
} elseif ($passwort == "")
{
$pw = "`passwort` = '' ,";
}
$visible2 = "0";
if ($visible == "on")
{
$visible2 = "1";
}


if (($row->userid == $_SESSION['ID']) or ($right[$level]['forum_edit_post']))
{
$sql = "UPDATE `$dbpräfix"."threads` SET `text` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread geändert");
}


}

if (isset($post['forum_editforum']))
{

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$titel = $post['titel'];
$level = $post['level'];
$type = $post['type'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];


$pw = "";
if (($passwort != "*") and ($passwort != ""))
{
$pw = "`passwort` = '".md5($passwort)."' ,";
} elseif ($passwort == "")
{
$pw = "`passwort` = '' ,";
}
$visible2 = "0";
if ($visible == "on")
{
$visible2 = "1";
}


if (($row->userid == $_SESSION['ID']) or ($right[$level]['forum_edit_forum']))
{
$sql = "UPDATE `$dbpräfix"."forums` SET `description` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Forum geändert");
}


}

if (isset($post['forum_movethread']))
{

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbpräfix"."threads` SET `forumid` = '".$post['moveto']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread verschoben");
}


}

}


function checksite($site)
{
// Verschoben wegen Config
// 4.2b
if ($site == "")
{
$site = $this->standardsite;
}

$invalide = array('/','/\/',':','.','\\');
$site = str_replace($invalide,' ',$site);

$ok = true;

$restrict = $this->restrict;
$onlysupadmin = $this->superadminonly;

foreach ($restrict as $key=>$value)
{
if ($site == $value)
{

$ok = false;
}
}

if (!in_array($_SESSION['username'], $this->superadmin)) {
if(in_array($site, $this->superadminonly)) { $ok = false; }
}

if ($ok)
{
return $site;
} else
{
return "404";
}

}

function inc()
{
$site = $this->site;

// Verlegung der Funktion wegen Config
//4.2b
$site= $this->checksite($site);


if (($this->sitepath[$site] != "") and (!in_array($site, $this->redirectlock)))
{
$sitesp = $this->sitepath[$site];
} else
{
$sitesp = "sites";
}

if (file_exists("$sitesp/$site.php") and (is_file("$sitesp/$site.php")))
 {
 include "$sitesp/$site.php";
 } else
  {
  if ($this->sitepath['404'] != "")
   {
   $sitesp = $this->sitepath['404'];
   include "$sitesp/404.php";
   } else
    {  
    if (file_exists("sites/404.php"))
     {
      include "sites/404.php";
     } else
      {
        echo $this->langclass->word('notfound');
      }
    }// Moduls
  } 
}


function getright()
{

// Rechte
$abfrage = "SELECT * FROM `".$this->präfix."right`";
//$ergebnis = SQLexec($abfrage, "index");
$ergebnisss = $this->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
   $rlevel="$row->level";
   if ("$row->ok" == "true")
   {
   $value = true;
   } else
   {
   $value = false;
   }
   $rright = "$row->right";
   
   $right[$rlevel][$rright] = $value;
   }

return $right;
}

// Von Functions.php Übernommen
// 4.2b
function PM($zu, $von, $Betreff, $text)
{ 
$time = time();
$datum = date('j.n.y');


	$eintragintodb = "INSERT INTO `".$this->präfix."pm`
(
von, 
datum, 
zu, 
text, 
Betreff, 
gelesen, 
timestamp
)
VALUES
('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$time')";
@$this->mysqlquery($eintragintodb);
}

// ------------------------------------------CONFIG----------------------------------
// Config Area:
// 4.1
function getconfig()
{

// Config
$abfrage = "SELECT * FROM `".$this->präfix."config`";

$ergebnisss = $this->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
  
   if ("$row->ok" == "true")
   {
   $value = true;
   } elseif ("$row->ok" == "false")
   {
   $value = false;
   } else
   {
   $value = $row->ok;
   }
   $name = "$row->name";
   
   $config[$name] = $value;
   }

return $config;

}

//Setconfig_array
// 4.2b
//Zur Kampatibilität auf sites/Config.php
function setconfig_array($array)
{
$this->config = $array;
}

//Setconfig
// 4.2b
function setconfig($name, $value)
{
$this->config[$name] = $value;
$this->applyconfig();
}

// Applyconfig
// 4.2b
function applyconfig()
{

$dbpräfix = $this->getpräfix();
$config = $this->config;
$hp = $this;


$abfrage = "SELECT * FROM `".$dbpräfix."config`";

$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
   $descriptions["$row->name"] = "$row->description";
   $kat["$row->name"] = $row->kat;
   }


$sql = "TRUNCATE `".$dbpräfix."config`;";
$hp->mysqlquery($sql);
echo mysql_error();



foreach ($config as $key=>$value) {

//echo "$key - $value <br>";

$typ = "bool";
if ($value == "true")
{
$value = "true";

} elseif ($value == "false")
{
$value = "false";
} else
{
$typ = "string";

$value = str_replace("\"", "'", $value);
$value = str_replace("<", "&lt;", $value);

}

//echo "r1 $key -> $value => $descriptions[$key]<br>";
	$sql = "INSERT INTO `".$dbpräfix."config` (

`name`,
`ok`,
`description`,
`typ`,
`kat`
)
VALUES (
'$key', '$value', '$descriptions[$key]', '$typ', '$kat[$key]'
);";

$hp->mysqlquery($sql);
echo mysql_error();

}

}


//Handel Config Funkion
//Als erleichterung für Spätere änderungen.
//4.2
function handelconfig()
{
$this->config=$this->getconfig();

// Superadmins
// 4.2b


$admins2 = explode(", ", $this->config['superadmin']);
if ($admins2[0] != "")
{
$this->superadmin = array_merge($this->superadmin, $admins2);
}


// StandardSeite
//4.2b
if ($this->config['standardsite'] != "")
{
$this->standardsite=$this->config['standardsite'];
}


// Checkversion
// 4.1

if (($this->config['checkversion'] == true) and ($this->outputg['login'] == "j"))
{
$this->checkversion();
}


// Redirectlock Config
//4.2
$redirectlock = explode(", ", $this->config['redirectlock']);

if (!is_array($this->redirectlock))
{
$this->redirectlock = array();
}
$this->redirectlock = array_merge($this->redirectlock, $redirectlock);

}

//---------------------------------------------MODULE----------------------------

// Module
function addredirect($site, $path)
{
$this->sitepath[$site]=$path;
}

function addredirectlock($site)
{
$this->redirectlock[]=$site;
}


} // Class Ende!
?>
