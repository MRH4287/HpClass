<?php
$allowed_ext = array('jpg','jpeg','png','gif');


//ob_start();
include "../class.php";
include "../standalone.php";

function exit_status($str)
{
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name)
{
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}

//Standalone:
$hp = new Standalone("../../");
//$hp->outputdivs();

// Site Config:
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();

// Check the upload
if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
	exit_status('ERROR:invalid upload');
	exit(0);
}

$pic = $_FILES['Filedata'];

if(!in_array(get_extension($pic['name']),$allowed_ext)){
	exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
}	

if($pic['error']!=0)
{
	echo "ERROR: ".$pic['error'];
	exit(0);
}

$speicherort=$pic['tmp_name'];

$datei=fopen($speicherort,'r');
$daten=fread($datei,filesize($speicherort));
fclose($datei);

$aSize = getimagesize($speicherort);

$width = $aSize[0];
$height = $aSize[1];

$dateiname=$hp->escapestring($pic['name']);
$size = $hp->escapestring($pic['size']);

$daten=$hp->escapestring($daten);

$sql = "INSERT INTO `".$dbprefix."usedpics` (data, filename, height, width, time) VALUES ('$daten', '$dateiname', '$height', '$width', NOW())";
$result=$hp->mysqlquery($sql);


exit_status('File was uploaded successfuly!');

?>
