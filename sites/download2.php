<?php
session_start();

if (!isset($site))
{
  require "../include/class.php";
  require_once "../include/standalone.php";
  require_once "../include/base/picture.php";

  //Standalone:
  $hp = new Standalone("../include");

	$dateiid=(int)$_GET['id'];

  // Site Config:
  $right = $hp->getright();
  $level = $_SESSION['level'];
  $get = $hp->get();
  $post = $hp->post();
  $dbpräfix = $hp->getpräfix();
  
  
	$sql = "SELECT * FROM `".$dbpräfix."download` WHERE `id` = '$dateiid'";
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
  	    
  	header("Content-type: {$datei['dateityp']}");
  	header("Content-disposition: attachement; filename={$datei['dateiname']}");
  	
  	print $datei['datei'];	
  
  } else
  {
    echo "Sie haben nicht die nötige berechtigung, diese Datei herunter zu laden!";
  }

	
} else
{
  echo "Diese Seite kann nur direkt aufgerufen werden!";
}
?>