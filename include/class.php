<?php

class HP
{

var $site;
var $outputg;
var $outputp;
var $lang;
var $langclass;
var $error;
var $info;

var $host;
var $präfix;
var $user;
var $password;
var $db;
var $connection;
var $sitepath;
var $redirectlock;
var $config;

// Siteconfig
var $restrict;
var $superadminonly;
var $checkversionurl;
var $pathtoversion;
var $superadmin;
var $standardsite;


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

// Versions überprüfung
// 4.1

//---------------------VERSION----------------------------------------------
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
$this->setconfig("checkversion", "false");
    $this->error->error("Konnte keine Versionsprüfung durchführen! Update-Prüf Funktion Deaktiviert!", "2");
} else
{


$array = $this->getversion();
$version2 = $array['version'];


if (($version != $version2) and (($_SESSION['username'] == "mrh") or ($_SESSION['username'] == $superadmin)))
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
or print $this->lang['userorpasswordwrong'];
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


if (!in_array($_SESSION['username'], $this->superadmin)){

foreach ($onlysupadmin as $key=>$value)
{

if (($site == $value) and (!$superadmin));
{

$ok = false;
}
}
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
return $this->mysqlquery($eintragintodb);


}


//

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
`typ`
)
VALUES (
'$key', '$value', '$descriptions[$key]', '$typ'
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
$this->superadmin = array_merge($this->superadmin, $admins2);



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
