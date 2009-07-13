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

if (isset($post['sendfiles']))
{
$numsendfiles = count($_FILES);
//echo $numsendfiles ."<br>";


	foreach($_FILES as $dateiinformationen)
	{
		if($dateiinformationen['error']!=0)
		{
			print "Fehler {$dateiinformationen['error']}.<br/>\n";
			continue;
		}
		
		if($dateiinformationen['name']=='') continue;
		//		echo $dateiinformationen['name']."<br>";
		
		        $name=$dateiinformationen['name'];    
      $testarray = explode(".", ($name));
      $index = count($testarray)-1;
      $ext= $testarray[$index];
      if (($ext != "gif") and ($ext != "jpg") and($ext != "png"))
      {
      echo "Bitte benutzen sie eines der Folgenden Formaten:<br><b>gif, jpg, png</b><br>";
      
      } else {
      
		
		$speicherort=$dateiinformationen['tmp_name'];
	//	echo $dateiinformationen['tmp_name']."<br>";
		$datei=fopen($speicherort,'r');
		$daten=fread($datei,filesize($speicherort));
		fclose($datei);
				
	    $aSize = getimagesize($speicherort);

    $Width = $aSize[0];
    $Height = $aSize[1];
		

		$dateiname=mysql_real_escape_string( $dateiinformationen['name']);
		$dateityp=mysql_real_escape_string($dateiinformationen['type']);
		$daten=mysql_real_escape_string($daten);

	$sql = "UPDATE `".$dbpräfix."user` SET `bild` = '$daten', `width` = '$Width', `height` = '$Height'  WHERE `user` = '".$_SESSION['username']."';";
	//	echo $sql."<br>";
    $result=$hp->mysqlquery($sql);
		if(!$result)
		{
			print "Fehler beim Schreiben der Daten in die Datenbank.<br/>\n";
			print mysql_error($db)."<br/>\n";
			exit;
		}
		
		
	}
}



if ($result== true)
{
echo "Erfolgreich modifiziert!<br><a href=index.php?site=profil>Zurueck</a>";
} else
{
echo "Fehler beim eintragen!<br>".mysql_error();
}

}
?>
