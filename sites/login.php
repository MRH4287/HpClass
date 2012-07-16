<?php
session_start();
include("../include/config.php");
//MySQL Connect

$db = mysql_connect($dbserver,
	$dbuser,$dbpass)
	or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";

mysql_select_db($dbdatenbank, $db)
	or print "Die Datenbank existiert nicht.";


$user=$_POST['user'];
$passwort=$_POST['passwort'];

$user = mysql_real_escape_string($user);


$ok = false;
if (isset($_POST['login']))
{

	$abfrage = "SELECT * FROM ".$dbprefix."user";
	$ergebnis = mysql_query($abfrage);


	while($row = mysql_fetch_object($ergebnis))
	{

		$passwortCH = sha1("pw_".$passwort.$row->ID);

		if ($user == "$row->user" and $passwortCH == "$row->pass")
		{
			$_SESSION['username']="$user";
			$ok=true;
		} elseif ($user == "$row->user" and $passwortCH != $row->pass) // Kein SH1
		{
			if (($passwort == md5("pw_".$row->pass)) or (md5("pw_".$passwort) == $row->pass)
				or (md5("pw_".$passwort.$row->ID) == $row->pass))
			{
				// Kein SHA1 ...
				$sql = "UPDATE `".$dbprefix."user` SET `pass` = '".sha1("pw_".$_POST['passwort'].$row->ID)."' WHERE `user` = '".$user."'";
				mysql_query($sql);
				echo mysql_error();
				$_SESSION['username']="$user";
				$ok = true;
			}

		}
	}
	if ($ok)
	{

		$abfrage = "SELECT * FROM ".$dbprefix."user WHERE `user` = '".$user."'";
		$ergebnis =  mysql_query($abfrage);
		while($row = mysql_fetch_object($ergebnis))
		{
			$_SESSION['level']="$row->level";
			$_SESSION['ID'] = $row->ID;
		}

		$eingabe2 = "UPDATE `".$dbprefix."user` SET `lastlogin` = NOW() WHERE `user` = '".$user."';";
		$ergebnis2 = mysql_query($eingabe2);
		echo mysql_error();
	}
}
if (isset($_GET['logout']))
{
	$user = $_SESSION['username'];
	session_unregister("username");
	session_unregister("level");
	setcookie ("username", "", time() -1);
	setcookie ("level", "", time() -1);
	session_destroy();
}
if ($ok == true)
{
	if (isset($_GET['logout']))
	{
		$loginok="lo";
	}else{
		$loginok="j";
	}
} else
{
	$loginok="n";
}
if (!isset($_POST['login']))
{
	$loginok="j";
}

header ("Location: ../index.php?login=$loginok");

?>
