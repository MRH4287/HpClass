<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();

$result = false;

if (isset($post['sendfiles']))
{
	$numsendfiles = count($_FILES);
	//echo $numsendfiles ."<br>";


	foreach($_FILES as $dateiinformationen)
	{
		if($dateiinformationen['error']!=0)
		{
			continue;
		}

		if($dateiinformationen['name']=='') continue;
		//        echo $dateiinformationen['name']."<br>";

		$name=$dateiinformationen['name'];
		$testarray = explode(".", ($name));
		$index = count($testarray)-1;
		$ext= $testarray[$index];
		if (($ext != "gif") and ($ext != "jpg") and($ext != "png"))
		{
			echo $lang['Bitte benutzen sie eines der folgenden Formate'].":<br><b>gif, jpg, png</b><br>";

		} else {


			$speicherort=$dateiinformationen['tmp_name'];
			//    echo $dateiinformationen['tmp_name']."<br>";
			$datei=fopen($speicherort,'r');
			$daten=fread($datei,filesize($speicherort));
			fclose($datei);

			$aSize = getimagesize($speicherort);

			$Width = $aSize[0];
			$Height = $aSize[1];


			$dateiname=mysql_real_escape_string( $dateiinformationen['name']);
			$dateityp=mysql_real_escape_string($dateiinformationen['type']);
			$daten=mysql_real_escape_string($daten);

			$sql = "UPDATE `".$dbprefix."user` SET `bild` = '$daten', `width` = '$Width', `height` = '$Height'  WHERE `user` = '".$_SESSION['username']."';";
			//    echo $sql."<br>";
			$result=$hp->mysqlquery($sql);
			if(!$result)
			{
				print $lang['Fehler beim Schreiben der Daten in die Datenbank'].".<br/>\n";
				print mysql_error()."<br/>\n";
				exit;
			}


		}
	}



	if ($result== true)
	{
		echo $lang['Erfolgreich aktualisiert']."!<br><a href=index.php?site=profil>".$lang['back']."</a>";
	} else
	{
		echo $lang['Fehler beim Schreiben der Daten in die Datenbank']."!<br>".mysql_error();
	}

}
?>
