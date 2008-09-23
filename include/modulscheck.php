<?php
// Ausschluss bestimmter Seitn für das Weiterleiten!
$lock = array ("admin", "register", "login", "test", "profil", "pm");
foreach ($lock as $key=>$value) {
$hp->addredirectlock($value);	
}



if ($_SESSION['level'] == 3)
{
$filearray = array();
$modulsvor = false;
$handle = @opendir("./moduls"); 
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if (count($exp) == 1)
{
$filearray[]=$file;
}

}

foreach ($filearray as $key=>$value) {

$handle = @opendir("./moduls/$value"); 
while (false !== ($file = readdir($handle))) {

if (($file == "install.php") and ($value != "Muster"))
{
$modulsvor = true;
}

}


	
}

if ($modulsvor) 
{
$info->info("<a href=index.php?site=modulinstall>Es sind ein oder mehrere Module zum Installieren.</a>");
}

}

$abfrage = "SELECT * FROM ".$dbpräfix."modul  WHERE `active` = 1";
$daswasrauskommt = $hp->mysqlquery($abfrage);
echo mysql_error();
    
while($reihe = mysql_fetch_object($daswasrauskommt))
   {
  
$path = $reihe->path;
$run = $reihe->run;
$name = $reihe->Name;

 include "moduls/".$path."/".$run; 
   
   }


?>
