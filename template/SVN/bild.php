<?php
$PATH = "thumbs"; //Where to save and store the thumbnails?
$path = "../../.svn/entries";
if (file_exists($path))
{
$svn = true;
$LABEL = "Aktuelle SVN Version";
} else
{
$LABEL = "";
$svn = false;
}

//END OF CONFIG
header('Content-type: image/jpeg');

if(!function_exists("imagecreatetruecolor")) return;
if ($svn)
{
$content = file($path);
$VERSION = trim($content[3]);
$PATH = $PATH."/".$VERSION.".jpg";
} else
{
$VERSION = "Kein SVN!";
}

$IMG = imagecreatefrompng("background_new.png");
$width = ImageSX($IMG);
$heigth = ImageSY($IMG);
//Label at the bottom
imagefilledrectangle(
	$IMG,
	0,$heigth - 16, //x,y
	$width,$heigth, //width,height
	imagecolorallocatealpha($IMG,255,255,255,90)
);
$size = 18;
$tb = imagettfbbox($size,0,"coolvetica.ttf",$LABEL); //Get the size of the TTF-Font
$x = ceil(($width - $tb[2])/2);
$y = ceil(($heigth - $tb[5])/2);

//A little text on the bottom
imagettftext(
	$IMG,
	$size,0, //size,angle
	$x,$heigth-16, //x,y
	imagecolorallocate($IMG,0,0,0),
	"coolvetica.ttf",
	$LABEL
);
imagettftext(
	$IMG,
	$size,0, //size,angle
	$x -1,$heigth-15, //x,y
	imagecolorallocate($IMG,255,255,255),
	"coolvetica.ttf",
	$LABEL
);
//The actual version in the center of the picture
if ($svn)
{
$size = 55;
} else
{
$size = 38;
}
$tb = imagettfbbox($size,0,"coolvetica.ttf",$VERSION); //Get the size of the TTF-Font
$x = ceil(($width - $tb[2])/2);
$y = ceil(($heigth - $tb[5])/2);
imagettftext(
	$IMG,
	$size,0, //size,angle
	$x,$y, //x,y
	imagecolorallocate($IMG,0,0,0),
	"coolvetica.ttf",
	$VERSION
);
imagettftext(
	$IMG,
	$size,0, //size,angle
	$x+2,$y+2, //x,y
	imagecolorallocate($IMG,255,255,255),
	"coolvetica.ttf",
	$VERSION
);


imagejpeg($IMG,NULL,85);
imagedestroy($IMG); //You can't die! We saved you!
?>
