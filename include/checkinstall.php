<?php
if (!file_exists("include/config.php"))
{
echo "Es wurde keine Config-Datei gefunden.<br>Falls Sie dieses System gerade installiert haben, gehen Sie in das /install/ Verzeichnis.<br>".
"<a href=\"./install/\">Hier gehts zur Installation</a>";
exit;
}


$handle = @opendir("./install/update/"); 
$filearray = array();
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if ($exp[1] == "php")
{
$filearray[]=$exp[0];

}
}


$last = 1;
foreach ($filearray as $key=>$value) {
	if ($value > $last )
	{
  $last = $value;
  }
}

$version = file_get_contents("./version/mysql.php");

if ($last > $version)
{
echo "Es gibt ein neues Mysqlupdate!<br>Sie müssen dieses erst übernehmen, bevor Sie das System starten können!<br>".
"<a href=\"./install/\">Hier gehts zur Installation</a>";
exit;
}



?>
