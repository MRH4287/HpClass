<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();

if (!$right[$_SESSION['level']]['moduladmin'])
{
$error->error("Sie haben kein Recht Module zu installieren!", "2");
echo "Sie haben kein Recht Module zu Installieren!<br><a href=index.php>Zurück</a>";
} else
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

if (!$modulsvor)
{
echo "Es sind keine Module zum Installieren!";
}
 else

{

if (!$get['ok'])
{
echo "Hier werden nun alle Module Installiert, bei denen eine install.php vorhanden ist!<br>
Sollten sie ein Modul nicht installieren wolln, löschen Sie bitte dessen install.php oder nennen Sie diese um.<br>
Wie zum Beispiel in install.php.txt!<br>Fortfahren?<br><a href=index.php?site=modulinstall&ok=j>Ja</a> <a href=index.php>Nein</a>";

} else
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
echo "Das Modul im Ordner $value wird nun installiert:<br><hr><br>";
include "moduls/$value/install.php";
rename("moduls/$value/install.php", "moduls/$value/install.php.txt");
echo "<hr><br>";
}
}
}
echo "<br>";
echo "<br>";
echo "<br>";
echo "Alle Module erfolgreich Installiert!<br><a href=index.php>zurück</a>";

}
}

} // Recht
?>
