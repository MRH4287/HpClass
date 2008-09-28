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
var $präfix;
var $user;
var $password;
var $db;
var $connection;
var $sitepath;
var $redirectlock;
var $config;


function setlang($langclass2)
{

$this->langclass = $langclass2;
$this->lang = $this->langclass->getlang();
}

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


function get()
{
// Checkversion
// 4.1
$this->config = $this->getconfig();
if (($this->config['checkversion'] == true) and ($this->outputg['login'] == "j"))
{
$this->checkversion();
}

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



function setdata($host, $user,$password, $präfix, $db)
{

$this->host=$host;
$this->password=$password;
$this->user=$user;
$this->präfix=$präfix;
$this->db=$db;
}

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

function getpräfix()
{
return $this->präfix;
}

function escapestring($string)
{
return mysql_real_escape_string($string);
}

function getlangclass()
{
return $this->langclass;
}

function setinfo($info)
{
$this->info = $info;
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



function seterror($error)
{
$this->error = $error;
}

function geterror()
{
return $this->error;
}

function getinfo()
{
return $this->info;
}

// Module
function addredirect($site, $path)
{
$this->sitepath[$site]=$path;
}

function addredirectlock($site)
{
$this->redirectlock[]=$site;
}


// Versions überprüfung
// 4.1

function getversion($path = "version/version.php")
{
include($path);
return array( 'version' => "$version", 'changelog' => "$changelog");
}


function checkversion($url = "http://mrh.mr.ohost.de/version/version.php?name=HPClass")
{
$version= file_get_contents($url);


$array = $this->getversion();
$version2 = $array['version'];


if (($version != $version2) and (($_SESSION['username'] == "mrh") or ($_SESSION['username'] == $superadmin)))
{
$this->info->info("Ihre Version ist nicht mehr auf dem neusten Stand!");

}

}

} // Class Ende!
?>
