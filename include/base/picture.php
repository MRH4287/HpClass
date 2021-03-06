<?php

class Picture
{

	var $image = null;
	var $width = 0;
	var $height = 0;

	function setAsBase64($data, $width, $height)
	{
		$this->setAsString(base64_decode($data), $width, $height);
	}

	function setAsString($data, $width, $height)
	{
		$this->image = imagecreatefromstring($data);
		$this->width = $width;
		$this->height = $height;

	}

	function setJPG($file)
	{
		$this->image = imagecreatefromjpeg($file);

		$aSize = getimagesize($file);
		$this->width = $aSize[0];
		$this->height = $aSize[1];

	}

	function setPNG($file)
	{
		$this->image = imagecreatefrompng($file);

		$aSize = getimagesize($file);
		$this->width = $aSize[0];
		$this->height = $aSize[1];

	}


	function display($wantedWidth = null, $wantedHeight = null)
	{
		// $neueHoehe=intval($hoehe*$neueBreite/$breite);
		$newWidth = 0;
		$newHeight = 0;

		if (($wantedWidth != null) && ($wantedHeight == null))
		{
			$newHeight = ($this->height * $wantedWidth) / $this->width;
			$newWidth = $wantedWidth;

		} elseif (($wantedWidth == null) && ($wantedHeight != null))
		{
			$newWidth = ($this->width * $wantedHeight) / $this->height;
			$newHeight = $wantedHeight;

		} elseif (($wantedWidth != null) && ($wantedHeight != null))
		{
			$newWidth = $wantedWidth;
			$newHeight = $wantedHeight;

		} else
		{
			$newWidth = $this->width;
			$newHeight = $this->height;


		}





		$neuesBild=ImageCreateTrueColor($newWidth,$newHeight);
		imagecopyresampled($neuesBild,$this->image,0,0,0,0,$newWidth,$newHeight,$this->width,$this->height);

		header('Content-Type: image/jpeg');
		ImageJPEG($neuesBild);
		imagedestroy($neuesBild);

	}



}


?>