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
$bildok = true;
	$datei=mysql_fetch_assoc($result);
	if(!$datei['bild'])
	{
		$bildok = false;
    		$image2 = imagecreatefromjpeg('../nopic.jpg');				    						
	}
	

	
	mysql_close($connection);

if (!isset($_GET['org']))
{

  // DB Abfrage
  $breite=$datei['width']; 
  $hoehe=$datei['height']; 

 $neueBreite=150; 


if ($breite != 0)
{

 
  $neueHoehe=intval($hoehe*$neueBreite/$breite); 
} else
{
$bildok = false;

	    $aSize = getimagesize("../nopic.jpg");
    $breite = $aSize[0];
    $hoehe = $aSize[1];
$neueHoehe=intval($hoehe*$neueBreite/$breite); 
}
 $data =$datei['bild'];



 try
{
set_error_handler(create_function('', "throw new Exception(); return true;"));

if ($bildok)
{
$altesBild=@imagecreatefromstring($data);
} else
{
$altesBild = $image2;
}
        $neuesBild=ImageCreateTrueColor($neueBreite,$neueHoehe); 
        imagecopyresampled($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe); 
        header('Content-Type: image/jpeg');
        ImageJPEG($neuesBild); 
        imagedestroy($neuesBild);
        exit;
        $ok = true;

} catch (Exception $e)
{
 header("Content-type: image/jpeg");
        	print $datei['bild'];	
}
  


  
} else
{

if ($bildok)
{
	header("Content-type: image/jpeg");
	print $datei['bild'];	
} else
{
  header('Content-Type: image/jpeg');
imagejpeg($image2);
imagedestroy($image2);
exit;

}


}


	
} else
{
echo "Diese Seite kann nur direkt aufgerufen werden!";
}
?>


