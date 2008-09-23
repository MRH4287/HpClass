<?php
session_start();

if (!isset($site))
{

	require_once("../include/config.php");

	$dateiid=(int)$_GET['id'];


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

	$sql = "SELECT * FROM `".$dbpräfix."download` WHERE `id` = '$dateiid'";
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
		print "Id $dateiid existiert nicht.<br/>\n";
		exit;							
	}
	
if ($_SESSION['level'] >= $datei['level'])
{
	
	mysql_close($connection);

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
