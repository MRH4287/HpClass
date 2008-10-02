<?php

class HP
{

var $site="news";
var $outputg;
var $outputp;
var $lang;
var $langclass;
var $error;
var $info;

var $host;
var $pr�fix;
var $user;
var $password;
var $db;
var $connection;
var $sitepath;
var $redirectlock;
var $config;

// In diesem Bereich werden alle Variablen an die Klasse �bergeben
// Die Set-Area
//-------------------------------------SET---------------------------------------
function setlang($langclass2)
{

$this->langclass = $langclass2;
$this->lang = $this->langclass->getlang();
}

function setdata($host, $user,$password, $pr�fix, $db)
{

$this->host=$host;
$this->password=$password;
$this->user=$user;
$this->pr�fix=$pr�fix;
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

function getpr�fix()
{
return $this->pr�fix;
}


function getlangclass()
{
return $this->langclass;
}

// Versions �berpr�fung
// 4.1

//---------------------VERSION----------------------------------------------
function getversion($path = "version/version.php")
{
include($path);
return array( 'version' => "$version", 'changelog' => "$changelog");
}

function checkversion($url = "http://mrh.mr.ohost.de/version/version.php?name=HPClass")
{


$version= @file_get_contents($url);
// Error-Handler
if ($version == "")
{
$this->setconfig("checkversion", "false");
    $this->error->error("Konnte keine Versionspr�fung durchf�hren! Update-Pr�f Funktion Deaktiviert!", "2");
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
$site= $this->checksite($site);
$this->site=$site;
} else
{
$this->site="news";
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


$invalide = array('/','/\/',':','.','\\');
$site = str_replace($invalide,' ',$site);

$ok = true;
$restrict = array ('login');
$onlysupadmin = array('rights', 'config', 'test');

foreach ($restrict as $key=>$value)
{
if ($site == $value)
{

$ok = false;
}
}

foreach ($onlysupadmin as $key=>$value)
{
if (($site == $value) and ($_SESSION['username'] != "admin") and ($_SESSION['username'] != "mrh"))
{

$ok = false;
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
$abfrage = "SELECT * FROM `".$this->pr�fix."right`";
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

//

// ------------------------------------------CONFIG----------------------------------
// Config Area:
// 4.1
function getconfig()
{

// Config
$abfrage = "SELECT * FROM `".$this->pr�fix."config`";

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
//Zur Kampatibilit�t auf sites/Config.php
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

$dbpr�fix = $this->getpr�fix();
$config = $this->config;
$hp = $this;


$abfrage = "SELECT * FROM `".$dbpr�fix."config`";

$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
   $descriptions["$row->name"] = "$row->description";
   }


$sql = "TRUNCATE `".$dbpr�fix."config`;";
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
if ($key != "lol")
{
//echo "r1 $key -> $value => $descriptions[$key]<br>";
	$sql = "INSERT INTO `".$dbpr�fix."config` (

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



}


//Handel Config Funkion
//Als erleichterung f�r Sp�tere �nderungen.
//4.2
function handelconfig()
{
$this->config=$this->getconfig();

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
