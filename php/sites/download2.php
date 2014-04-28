<?php
session_start();

if (!isset($site))
{
	require "../include/class.php";
	require_once "../include/standalone.php";
	require_once "../include/base/picture.php";

	//Standalone:
	$hp = new Standalone("../");

	$dateiid=(int)$_GET['id'];

	// Site Config:
	$right = $hp->getright();
	$level = $_SESSION['level'];
	$get = $hp->get();
	$post = $hp->post();
	$dbprefix = $hp->getprefix();
	$lang = $hp->getlangclass();


	$sql = "SELECT * FROM `".$dbprefix."download` WHERE `id` = '$dateiid'";
	$erg = $hp->mysqlquery($sql);
	if(!$erg)
	{
		print "Fehler im SQL-Skript.<br/>\n";
		print mysql_error()."<br/>\n";
		exit;
	}

	$datei=mysql_fetch_assoc($erg);
	if(!$datei)
	{
		print "Id $dateiid existiert nicht.<br/>\n";
		exit;
	}
	
	if ($hp->right->isAllowed($datei['level']))
	{
		//Ermitteln ob die Datei lokal oder in der Datenbank vorhanden ist:
		
		$path = "../downloads/".$datei['dateiname'];
		if (file_exists($path))
		{

			//$mmtype = mime_content_type($path);

			//$datei=fopen($path,'r');
			//$daten=fread($datei,filesize($path));
			//fclose($datei);


			//echo $mmtype."<br/>";

			header("Content-type: {$mmtype}");
			header("Content-disposition: attachement; filename={$datei['dateiname']}");
			
			//print $daten;	
			readfile($path);


		} else
		{
			header("Content-type: {$datei['dateityp']}");
			header("Content-disposition: attachement; filename={$datei['dateiname']}");
			
			print $datei['datei'];	
		}

	} else
	{
		echo $lang['Sie haben nicht die benötigte Berechtigung!'];
	}

	
} else
{
	echo $lang['Diese Seite kann nur direkt aufgerufen werden!'];
}
?>