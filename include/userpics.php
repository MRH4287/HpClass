<?php
session_start();

if (!isset($site))
{

	require_once("../include/config.php");

//if (isset($_SESSION['bilduserid']) and ($_SESSION['bilduserid'] <> ""))
//{
//$dateiid = (int) $_SESSION['bilduserid'];
//$_SESSION['bilduserid'] = "";
//} else
//{
	$dateiid=(int)$_GET['id'];
//}

	$connection=mysql_connect($dbserver,
$dbuser,$dbpass);
	if(!$connection) 
	{
		print "Fehler bei Datenbankverbindungsaufbau.<br/>\n";
		print mysql_error($connection)."<br/>\n";			
		exit;
	}
	
	if(!mysql_select_db($dbdatenbank,$connection))
	{
		print "Fehler bei Auswahl der Datenbank '$dbdatenbank'.<br/>\n";
		print mysql_error($connection)."<br/>\n";
		exit;			
	}

	$sql = "SELECT * FROM `".$dbpräfix."user` WHERE `id` = '$dateiid'";
	$result=mysql_query($sql,$connection);
	if(!$result)
	{
		print "Fehler im SQL-Skript.<br/>\n";
		print mysql_error($connection)."<br/>\n";
		exit;					
	}		

	$datei=mysql_fetch_assoc($result);
	if(!$datei)
	{
		
    
    $image = imagecreate(200,400);
    header('Content-Type: image/jpeg');
imagejpeg($image);
imagedestroy($image);
exit;
    
    
    
    						
	}
	

	
	mysql_close($connection);

//	header("Content-type: {$datei['dateityp']}");
	header("Content-type: image/jpeg");
//	header("Content-disposition: attachement; filename={$datei['dateiname']}");
	
	print $datei['bild'];	



	
} else
{
echo "Diese Seite kann nur direkt aufgerufen werden!";
}
?>


