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

$local = false;
if (($_SERVER['HTTP_HOST'] == "localhost") or ($_SERVER['HTTP_HOST'] == "127.0.0.1"))
{
	$local = true;
}

if (!isset ($_SESSION['username']))
{
	$error->error($lang->word('noright2'),"3");
} else
{

	if ((!$right[$level]['useragree']) and ((!$right[$level]['userdisagree'])))
	{
		$error->error($lang->word('noright2'),"2");
	} else
	{
		if (!$right[$level]['useragree'] and isset($get['register']))
		{
			$error->error($lang->word('kberechacanwae'),"2");

		} elseif (isset($get['register']))
		{
			$abfrage = "SELECT * FROM ".$dbprefix."anwaerter WHERE `user`= '".$get['register']."'";
			$ergebnis = $hp->mysqlquery($abfrage);

			$row = mysql_fetch_object($ergebnis);

			$user=$row->user;
			$passwort123=$row->pass;
			$name=$row->name;
			$nachname=$row->nachname;
			$datum=$row->datum;
			$email = $row->email;
			$tel = $row->tel;
			$wohnort = $row->wohnort;
			$geschlecht = $row->geschlecht;



			$eintrag = "DELETE FROM `".$dbprefix."anwaerter` WHERE `user` = '".$get['register']."'";
			$eintragen1 = $hp->mysqlquery($eintrag);


			$eintrag = "INSERT INTO `".$dbprefix."user`
        (`user`, `pass`, `name`, `nachname`, `datum`, `level`, `email`, `tel`, `wohnort`, `geschlecht`)
        VALUES
        ('$user', '$passwort123', '$name', '$nachname', '$datum', '1', '$email', '$tel', '$wohnort', '$geschlecht')";
			$eintragen2 = $hp->mysqlquery($eintrag);

			if (!$local)
			{

				// Laden der Template Daten:
				$site = new siteTemplate($hp);
				$site->load("email");

				$site->set("HTTP_HOST", $_SERVER['HTTP_HOST']);
				$site->set("PHP_SELF", $_SERVER['PHP_SELF']);

				$site->get();
				$mail = $site->getVars();

				$site->get("Anwerter-OkMessage");
				$mail = array_merge($mail, $site->getVars());


				mail($email, $mail['mailbetreff'], $mail['mailtext'].$mail['mailfooter'] ,"from:".$mail['mailcomefrom']);

			}

			if ($eintragen1 == true and $eintragen2 == true)
			{
				$info->okn($lang->word('postok'));
			} else { $error->error("Fehler: ".mysql_error(),"2"); }
		}


		if (!$right[$level]['userdisagree'] and isset($get['delet']))
		{
			$error->error($lang->word('kberechdeanwae'),"2");

		} elseif (isset($get['delet']))
		{
			$eintrag = "DELETE FROM `".$dbprefix."anwaerter` WHERE `user` = '".$get['delet']."'";
			$eintragen = $hp->mysqlquery($eintrag);
			if ($eintragen == true)
			{
				$info->okn($lang->word('delok'));
			} else
			{
				$error->error("Fehler: ".mysql_error(),"2");
			}
		}


		$abfrage = "SELECT * FROM ".$dbprefix."anwaerter";
		$ergebnis = $hp->mysqlquery($abfrage);

		$site = new siteTemplate($hp);
		$site->load("anwaerter");

		$content = "";
		while($row = mysql_fetch_object($ergebnis))
		{
			$data = array(
				"user" => $row->user,
				"name" => $row->name,
				"nachname" => $row->nachname,
				"datum" => $row->datum,
				"text" => $row->text
				);

			$content .= $site->getNode("Line", $data);

		}
		$site->set("Content", $content);

		$site->display();

	}
}
?>
