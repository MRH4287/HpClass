<?php
session_start();

if (!isset($site))
{


include "../class.php";
include "../standalone.php";

//Standalone:
$hp = new Standalone("..");


// Site Config:
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();

$dateiid=(int)$_GET['id'];



	

	$sql = "SELECT * FROM `".$dbpr�fix."usedpics` WHERE `ID` = '$dateiid'";
	$erg = $hp->mysqlquery($sql);

	$datei=mysql_fetch_assoc($erg);
	if(!$datei['data'])
	{
		$image = imagecreatefromjpeg('../../nopic.jpg');
    

    header('Content-Type: image/jpeg');
    imagejpeg($image);
    imagedestroy($image);
    exit;
    						
	}

	header("Content-type: image/jpeg");
	print $datei['data'];	



	
} else
{
echo "Diese Seite kann nur direkt aufgerufen werden!";
}
?>


