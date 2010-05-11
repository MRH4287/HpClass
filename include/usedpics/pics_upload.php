<?php

	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}


session_start();
ini_set("html_errors", "0");

//ob_start();
include "../class.php";
include "../standalone.php";

//Standalone:
$hp = new Standalone("..");
//$hp->outputdivs();

// Site Config:
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}



$datum = time();

$phproot=substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-strlen($_SERVER['SCRIPT_NAME']));


	$dateiinformationen = $_FILES["Filedata"];
	
		if($dateiinformationen['error']!=0)
		{
			$error->error("Fehler {$dateiinformationen['error']}.","2");
			continue;
		}
		
				
		
		$speicherort=$dateiinformationen['tmp_name'];
		
		$datei=fopen($speicherort,'r');
		$daten=fread($datei,filesize($speicherort));
		fclose($datei);
				
	    $aSize = getimagesize($speicherort);

    $Width = $aSize[0];
    $Height = $aSize[1];
	

		$dateiname=$hp->escapestring($dateiinformationen['name']);
		$dateityp=$hp->escapestring($dateiinformationen['type']);
		$size = $hp->escapestring($dateiinformationen['size']);

		
		
		$daten=$hp->escapestring($daten,$db);
   $datum = time();


	$sql = "INSERT INTO `".$dbpräfix."usedpics` (data, filename, height, width, time) VALUES ('$daten', '$dateiname', '$Height', '$Width', '$datum')";
		$result=$hp->mysqlquery($sql);
     echo mysql_error();
	// Get the image and create a thumbnail
	
  $img = @imagecreatefromstring($daten);
  
  if (!$img)	
	$img = @imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
	if (!$img)
	{
  $img = @imagecreatefromgif($_FILES["Filedata"]["tmp_name"]);
  }
	if (!$img)
	{
  $img = @imagecreatefrompng($_FILES["Filedata"]["tmp_name"]);
  }	
	
	
	if (!$img) {
		//echo "ERROR:could not create image handle ". $_FILES["Filedata"]["tmp_name"].". Datei wurde trotzdem ges";
		echo "Thumbnail Bild konnte nicht erstellt werden (Datei wurde trotzdem hochgeladen)";
		exit(0);
	}

	$width = imageSX($img);
	$height = imageSY($img);

	if (!$width || !$height) {
		echo "ERROR:Invalid width or height";
		exit(0);
	}

	// Build the thumbnail
	$target_width = 100;
	$target_height = 100;
	$target_ratio = $target_width / $target_height;

	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	$new_img = ImageCreateTrueColor(100, 100);
	if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0)) {	// Fill the image black
		echo "ERROR:Could not fill new image";
		exit(0);
	}

	if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height)) {
		echo "ERROR:Could not resize image";
		exit(0);
	}

	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}
		
	// Use a output buffering to load the image into a variable
	ob_start();
	imagejpeg($new_img);
	$imagevariable = ob_get_contents();
	ob_end_clean();

	$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
	
	$_SESSION["file_info"][$file_id] = $imagevariable;

	echo "FILEID:" . $file_id;	// Return the file id to the script	




?>
