<?php
if (!file_exists("include/config.php"))
{
	echo "Es wurde keine Config-Datei gefunden.<br>Falls Sie dieses System gerade installiert haben, gehen Sie in das /install/ Verzeichnis.<br>".
		"<a href=\"./install/\">Hier gehts zur Installation</a>";
	exit;
}


$handle = @opendir("./install/update/");
$filearray = array();
while (false !== ($file = readdir($handle)))
{

	$exp = explode(".",$file);
	if ($exp[1] == "php")
	{
		$filearray[]=$exp[0];

	}
}


$last = 1;
foreach ($filearray as $key=>$value)
{
	if ($value > $last )
	{
		$last = $value;
	}
}


if (is_file("./version/mysql.php"))
{
	$version = file_get_contents("./version/mysql.php");
} else
{
	$version = 0;
}


if ($last > $version)
{
	echo "Es gibt ein neues Mysqlupdate!<br>Sie müssen dieses erst übernehmen, bevor Sie das System starten können!<br>".
		"<a href=\"./install/\">Hier gehts zur Installation</a>";
	exit;
}


if (!file_exists("include/api/key.php"))
{
	$password_length = 128;
	$generated_password = "";
	$valid_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$i = 0;


	$chars_length = strlen($valid_characters) - 1;
	for($i = $password_length; $i--; )
	{
		$generated_password .= $valid_characters[mt_rand(0, $chars_length)];
	}

	$userdatei = fopen ("include/api/key.php","w");

	fwrite($userdatei, "<?php\n");
	fwrite($userdatei, 'define("SHARED_SECRET", "'.$generated_password.'");'."\n");
	fwrite($userdatei, "?");
	fwrite($userdatei, ">\n");
	fclose($userdatei);

}

if (isset($_GET["ee"]) && isset($_GET["mrh"]))
{

	$data = "R0lGODlhIwAwALMAAL2bAIBCAACAAOvFAIlURGZEMACAgMDAwICAgP9yOgD/AP/zAAAA//8A/////3SxWiH/C05FVFNDQVBFMi4".
		"wAwEAAAAh/hsBAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAIfkECSgADwAsAAAAACMAMAAABP7wyUmrvXiWnbvdBQVyXjaG2lleJyq17v".
		"rA4gYMoDxvA5CngMUCR/KABkJfjCcc/kxMIbGGbE47UWlxV7XGWF3tshBOeoJNMTVt7qDTV015Aajb73a283Nj3waAVoA9bD1fEnVhc4Vwd".
		"W5/epFSPQArb5KSlSV9mJg9m52hmhmcoZKfpE0+l5Grqm5JBQcOrGkADgcFaKMXaBsIuZiyCBttGLsgtarJxr3Llz1hrs0WfQkJpk3XQ7y9".
		"19tJPQE3b98J3d7ftqpp5ujp2ULX78elnZQ6iDaLhrr5D39gtABEr1cVJbqSMeNmqYshcroeOiw4gVW0JFLs0cGAQuOQSEpzUNUI8WbRqVc".
		"jSWaMR+dgSg52TIK080EFQJbUXu5jKZJFC2WZSoxAJLPRii0eC1F8xCkPN0f/AE6sOPUfGnwUEuW0BLVX1woRAAAh+QQJKAAPACwAAAAAIw".
		"AwAAAE/vDJV+q8OOtaLrdaiH3dRJZieHprmp0oVQED57LFANhmASyLGsjFGQB3sYoRKIxtlMDgULZkTp9VaTIXtRKzWhZ4AXD9uuHeuJw6d".
		"4WjNWBOr8/R0hcNTRv4o35+bkw7GnNZY2iIc219eI9MOmxmkJVHNw97lpA6N4ObeJMhmqCPnSJuO5+hPlGiGWc7Bw6rrg4HrWRtQBUIuJUF".
		"BwgVl6hHHG46VUi5rxipuZWqrm1GCQl8BNqf10HOz9fdruGD4Qnf4OFvAOdZ5ujpeMp41/CwpJaSmBJ0M4k6MxiZ0dQERhFC8FYxW4gs1Cg".
		"wAGmoiggxYcUlZ+atM/MHkKk3TKdYPOtYStALHhPwbQrZ48OFWpacwUhZqss3gz4ScUKHE+YjezJ4qNx548pQRUAbYbzjLSmqJSEJOoUF9Z".
		"XUfS8JGcKI9aVAQ18xRAAAOw==";
	include "include/base/picture.php";
	$picture = new Picture();


	$key = $_GET["ee"];

	if (($key%42 == 27) && ($key%27 == 3) && ($key%3 == 0))
	{

		$picture->setAsBase64($data, 35, 48);

		$picture->display();
		exit;
	}

}


?>