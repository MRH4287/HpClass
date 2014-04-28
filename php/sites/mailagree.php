<?php

// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();

$site = new siteTemplate($hp);
$site->load("info");
$site->set("info", $lang['Sie haben nicht die benötigte Berechtigung!']);

if (isset($get['user']) and isset($get['code']))
{
	$codeg = $get['code'];

	$abfrage = "SELECT * FROM ".$dbprefix."anwaerter WHERE `user`= '".$get['user']."'";
	$ergebnis = $hp->mysqlquery($abfrage);

	$row = mysql_fetch_object($ergebnis);

	$user="$row->user";
	$passwort123="$row->pass";
	$name="$row->name";
	$nachname="$row->nachname";
	$email="$row->email";
	$datum="$row->datum";
	$wohnort = "$row->wohnort";
	$geschlecht = "$row->geschlecht";
	$tel = "$row->tel";

	$code = $row->code;

	if ($codeg != $code)
	{
		$site->set("info", $lang['Der angegebene Sicherheitscode stimmt nicht überein!']);
	} else
	{
		if (isset($user) and ($user != ""))
		{
			$eintrag = "DELETE FROM `".$dbprefix."anwaerter` WHERE `user` = '".$get['user']."'";
			$eintragen1 = $hp->mysqlquery($eintrag);

			$eintrag = "INSERT INTO `".$dbprefix."user`
        (user, pass, name, nachname, datum, level, email, wohnort, tel, geschlecht)
        VALUES
        ('$user', '$passwort123', '$name', '$nachname', '$datum', '1', '$email', '$wohnort',  '$tel', '$geschlecht')";
			$eintragen2 = $hp->mysqlquery($eintrag);

			if (($eintragen1 == true) and ($eintragen2 == true))
			{
				$site->set("info", "<br>".$lang['Vielen Dank'].",<br>".$lang['Ihre Registration wurde erfolgreich abgeschlossen']."!");

				foreach ($hp->superadmin as $key=>$superadmin)
				{
					$hp->PM($superadmin, "System", "Neuer User", "Ein neuer User:<br>$user", $datum, $dbprefix);
				}

			} else
			{
				$site->set("info", $lang['error'].": <br>Melden Sie sich bitte umgehen bei dem zuständigem Administrator! <br>".mysql_error());
			}

		} else
		{
			$site->set("info", $lang["Ihre E-Mailadresse wurde bereits verifiziert!"]);
		}

	}

}
$site->display();
?>