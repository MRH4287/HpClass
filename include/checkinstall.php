<?php
if (!file_exists("include/config.php"))
{
  echo "Es wurde keine Config-Datei gefunden.<br>Falls Sie dieses System gerade installiert haben, gehen Sie in das /install/ Verzeichnis.<br>".
  "<a href=\"./install/\">Hier gehts zur Installation</a>";
  exit;
}


$handle = @opendir("./install/update/"); 
$filearray = array();
while (false !== ($file = readdir($handle))) 
{

  $exp = explode(".",$file);
  if ($exp[1] == "php")
  {
    $filearray[]=$exp[0];
  
  }
}


$last = 1;
foreach ($filearray as $key=>$value) 
{
	if ($value > $last )
	{
    $last = $value;
  }
}


if (is_file("./version/mysql.php"))
{
  $version = file_get_contents("./version/mysql.php");
} else
{
  $version = 0;
}


if ($last > $version)
{
  echo "Es gibt ein neues Mysqlupdate!<br>Sie müssen dieses erst übernehmen, bevor Sie das System starten können!<br>".
  "<a href=\"./install/\">Hier gehts zur Installation</a>";
  exit;
}


if (!file_exists("include/api/key.php"))
{
    $password_length = 128;
    $generated_password = "";
    $valid_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!;:-_#";
    $i = 0;
    
    
    $chars_length = strlen($valid_characters) - 1;
    for($i = $password_length; $i--; )
    {
      $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
    }
    
    $userdatei = fopen ("include/api/key.php","w");
    
    fwrite($userdatei, "<?php\n");
    fwrite($userdatei, 'define("SHARED_SECRET", "'.$generated_password.'");'."\n");
    fwrite($userdatei, "?"); 
    fwrite($userdatei, ">\n");
    fclose($userdatei);

}


?>