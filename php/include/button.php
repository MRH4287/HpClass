<?php
$font = 10;
if (isset($_GET['p']))
{
	$bild = $_GET['p'];
}

if (isset($_GET['f']))
{
	$farbe1 = $_GET['f'];
} else
{
	$farbe1 = "black";
}

if (isset($_GET['t']))
{
	$text = $_GET['t'];
} else
{
	$text = "";
}



if (isset($bild) and ($bild !== '')){

	$bild= "../images/button/button$bild.png";

	$pos = strrpos($bild, '.') +1;
	$file_ext= substr($bild,$pos);
	switch (strtolower($file_ext))
	{
		case 'png' :
			$image = @imagecreatefrompng($bild);
			break;
		case 'jpg':
		case 'jpeg':
			$image = @imagecreatefromjpeg($bild);
			break;

	}
} else
{
	$image= imagecreate(30,30);
	$text="Error";
	$farbe1 = "red";
}

$white= imagecolorallocate($image, 255, 255, 255);
$black= imagecolorallocate($image, 0, 0, 0);
$red= imagecolorallocate($image, 255, 0, 0);
$green= imagecolorallocate($image, 0, 255, 0);
$blue= imagecolorallocate($image, 0, 0, 255);
$yellow= imagecolorallocate($image, 255, 255, 0);

$farbe = $black;
switch (strtolower($farbe1))
{
	case 'black' :
		$farbe = $black;
		break;
	case 'white' :
		$farbe = $white;
		break;
	case 'red' :
		$farbe = $red;
		break;
	case 'green' :
		$farbe = $green;
		break;
	case 'yellow' :
		$farbe = $yellow;
		break;
	case 'blue' :
		$farbe = $blue;
		break;

}



//$image = imagecreate(50, 50);
$höhe = imagesy($image);
$breite = imagesx($image);


//imagestring($image, 5, 0, 0, 'Test', $red);
$str_height = imagefontheight($font);
$str_width = imagefontwidth($font) * strLen($text);
$str_width = $str_width;
while ($str_width > $breite and $font > 1)
{
	$font = $font -1;
	$str_height = imagefontheight($font);
	$str_width = imagefontwidth($font) * strLen($text);
}
$str_width = imagefontwidth($font) * strLen($text);
$str_x = (int) ($breite - $str_width) / 2;
$str_x = $str_x;
$str_y = (int) ($höhe - $str_height) / 2;
imagestring($image, $font, $str_x, $str_y, $text, $farbe);
//imagefill($image, 0, 0, $black);

header('Content-Type: image/jpeg');
imagejpeg($image);
iaimagedestroy($image);
?>
