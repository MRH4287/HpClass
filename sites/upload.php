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
$config = $hp->getconfig();


$restExt = array( "php", "js", "ph4", "ph5", "cgi", "htm", "html", "swf", "vbs",
	"asp", "pl", "exe", "dll", "ocx", "php3", "php4", "php5", "css",
	"xht", "xhtm", "xhtml", "sht", "shtm", "shtml", "ssi" );



if (!$right[$level]['upload'])
{
	$site = new siteTemplate($hp);
	$site->load("info");
	$site->set("info", $lang['Sie haben keine Berechtigung, Daten hoch zu laden']."!</br><a href=?site=upload>".$lang['upload']."</a>");
	$site->display();

} else
{

	if (isset($post['sendfiles']))
	{
		$numsendfiles = count($_FILES);
		$datum = date('j').".".date('n').".".date('y');
		$posttitel=$post['titel'];
		$username=$_SESSION['username'];
		$beschreibung=$post['S1'];
		$dlevel=$post['level'];
		$kat = $post['kat'];


		$phproot=substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-strlen($_SERVER['SCRIPT_NAME']));

		if ($config["download_useDB"])
		{


			foreach($_FILES as $dateiinformationen)
			{
				if($dateiinformationen['error']!=0)
				{
					$error->error("Fehler {$dateiinformationen['error']}.<br/>\n","2");
					continue;
				}
				
				if($dateiinformationen['name']=='')
				{
					continue;
				}

				$speicherort=$dateiinformationen['tmp_name'];
				
				$datei=fopen($speicherort,'r');
				$daten=fread($datei,filesize($speicherort));
				fclose($datei);

				$dateiname=$hp->escapestring($dateiinformationen['name']);
				$dateityp=$hp->escapestring($dateiinformationen['type']);
				$daten=$hp->escapestring($daten);
				
				if ($dateiinformationen['size'] > 3145728)
				{
					$site = new siteTemplate($hp);
					$site->load("info");
					$site->set("info", sprintf($lang['Die gesendete Datei war zu groß! (Maximalgröße: %sMB)'], '3')."<br /><a href=?site=upload>".$lang['back']."</a>");
					$site->display();

				} else
				{

					$ext = explode(".", $dateiname);
					$ext = $ext[count($ext) - 1];
					$ext = strtolower($ext);
					if (in_array($ext, $restExt))
					{
						$error->error($lang['Dieser Datei-Typ ist nicht erlaubt']."!");

						$site = new siteTemplate($hp);
						$site->load("info");
						$site->set("info", $lang['error'].": ".$lang['Dieser Datei-Typ ist nicht erlaubt']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
						$site->display();

					} else
					{

						$sql = 	"INSERT INTO `".$dbprefix."download` (titel, dateiname, datum, autor, beschreibung, level, dateityp, datei, kat, Zeitstempel) \n".
							"VALUES ('$posttitel', '$dateiname', '$datum', '$username', '$beschreibung', '$dlevel', '$dateityp', '$daten', '$kat', NOW()) \n";
						$result=$hp->mysqlquery($sql);
						if($result)
						{
							$info->okn($lang['Erfolgreich erstellt']."!");
							$site = new siteTemplate($hp);
							$site->load("info");
							$site->set("info", $lang['Erfolgreich erstellt']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
							$site->display();


						} else
						{
							$error->error($lang["Fehler beim Schreiben der Daten in die Datenbank"],"2");
							
						}
					}
				}
			}

		} else
		{
			if (isset($_FILES))
			{
				foreach($_FILES as $strFieldName => $arrPostFiles)
				{
					if ($arrPostFiles['size'] > 0)
					{
						$strFileName = $arrPostFiles['name'];
						$intFileSize = $arrPostFiles['size'];
						$strFileMIME = $arrPostFiles['type'];
						$strFileTemp = $arrPostFiles['tmp_name'];


						$dateiname=$strFileName;

						$ext = explode(".", $strFileName);
						$ext = $ext[count($ext) - 1];
						$ext = strtolower($ext);
						if (in_array($ext, $restExt))
						{
							$error->error($lang['Dieser Datei-Typ ist nicht erlaubt']."!");

							$site = new siteTemplate($hp);
							$site->load("info");
							$site->set("info", $lang['error'].": ".$lang['Dieser Datei-Typ ist nicht erlaubt']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
							$site->display();

						} else
						{
							$eintrag = "INSERT INTO `".$dbprefix."download`
                (dateiname, titel, datum, autor, beschreibung, level, kat, Zeitstempel)
                VALUES
                ('$dateiname', '$posttitel', '$datum', '$username', '$beschreibung', '$dlevel', '$kat', NOW())";
							$eintragen = mysql_query($eintrag);
							move_uploaded_file($strFileTemp, "downloads/$strFileName");
							if ($eintragen== true)
							{
								$info->okn("Erfolgeich eingetragen!");
								$site = new siteTemplate($hp);
								$site->load("info");
								$site->set("info", $lang['Erfolgreich erstellt']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
								$site->display();
							}
						}
					}
				}
			}
		}


	} elseif (isset($get['upload']))
	{

		$site = new siteTemplate($hp);
		$site->load("upload");


		$levels = $hp->right->getlevels();
		$content = "";
		foreach ($levels as $k=>$level)
		{
			$sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
			$erg = $hp->mysqlquery($sql);

			if (mysql_num_rows($erg) > 0)
			{
				$row = mysql_fetch_object($erg);
				$name = $row->name;
			} else
			{
				$name = $level;
			}


			$data = array(
				"level" => $level,
				"name" => $name,
				"aktlevel" => "0"
				);

			$content .= $site->getNode("Edit-Levels", $data);

		}

		$site->set("levels", $content);

		$abfrage = "SELECT * FROM ".$dbprefix."download_kat";
		$ergebnis = $hp->mysqlquery($abfrage);

		$kats = "";
		while($row2 = mysql_fetch_object($ergebnis))
		{
			$data = array(
				"ID" => $row2->ID,
				"name" => $row2->name,
				"selected" => "false"
				);

			$kats .= $site->getNode("Option", $data);

		}


		$data = array(
			"update" => "false",
			"titel" => "",
			"beschreibung" => "",
			"username" => $_SESSION["username"],
			"kats" => $kats

			);

		$site->setArray($data);

		$site->display("Upload");


	} elseif (isset($get['filechange']))
	{

		$abfrage2 = "SELECT * FROM ".$dbprefix."download WHERE `ID` = '".$get['filechange']."'";
		$ergebnis2 = $hp->mysqlquery($abfrage2);

		if (mysql_num_rows($ergebnis2) > 0)
		{

			$row = mysql_fetch_object($ergebnis2);


			$site = new siteTemplate($hp);
			$site->load("upload");


			$levels = $hp->right->getlevels();
			$content = "";
			foreach ($levels as $k=>$level)
			{
				$sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
				$ergl = $hp->mysqlquery($sql);

				if (mysql_num_rows($ergl) > 0)
				{
					$rowl = mysql_fetch_object($ergl);
					$name = $rowl->name;
				} else
				{
					$name = $level;
				}


				$data = array(
					"level" => $level,
					"name" => $name,
					"aktlevel" => $row->level
					);

				$content .= $site->getNode("Edit-Levels", $data);

			}

			$site->set("levels", $content);

			$abfrage = "SELECT * FROM ".$dbprefix."download_kat";
			$ergebnis = $hp->mysqlquery($abfrage);

			$kats = "";
			while($row2 = mysql_fetch_object($ergebnis))
			{
				$data = array(
					"ID" => $row2->ID,
					"name" => $row2->name,
					"selected" => ($row2->ID == $row->kat) ? "true" : "false"
					);

				$kats .= $site->getNode("Option", $data);

			}


			$data = array(
				"update" => "true",
				"titel" => $row->titel,
				"beschreibung" => $row->beschreibung,
				"username" => $_SESSION["username"],
				"kats" => $kats,
				"ID" => $row->ID

				);

			$site->setArray($data);

			$site->display("Upload");

		}

	} elseif (isset($post['changefile']))
	{

		$datum = date('j').".".date('n').".".date('y');
		$posttitel=$post['titel'];
		$username=$_SESSION['username'];
		$beschreibung=$post['S1'];
		$dlevel=$post['level'];
		$dID = $post['ID'];
		$dkat = $post['kat'];
		$posttitel = $hp->escapestring($posttitel);
		$beschreibung = $hp->escapestring($beschreibung);
		$dlevel = $hp->escapestring($dlevel);
		$fp = $this->firephp;

		$sql = "UPDATE `".$dbprefix."download` SET `titel` = '$posttitel',
    `level` = '$dlevel',
    `kat` = '$dkat',
    `beschreibung` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";

		$res = $hp->mysqlquery($sql);

		if ($res)
		{
			$info->okm($lang['Erfolgreich aktualisiert']."!");
		}

	} elseif (isset($get['katnew']))
	{

		$site = new siteTemplate($hp);
		$site->load("upload");


		$levels = $hp->right->getlevels();
		$content = "";
		foreach ($levels as $k=>$level)
		{
			$sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
			$erg = $hp->mysqlquery($sql);

			if (mysql_num_rows($erg) > 0)
			{
				$row = mysql_fetch_object($erg);
				$name = $row->name;
			} else
			{
				$name = $level;
			}


			$data = array(
				"level" => $level,
				"name" => $name,
				"aktlevel" => "0"
				);

			$content .= $site->getNode("Edit-Levels", $data);

		}

		$site->set("levels", $content);

		$data = array(
			"update" => "false",
			"titel" => "",
			"beschreibung" => "",
			"ID" => "0"
			);

		$site->setArray($data);

		$site->display("Kat-Edit");


	} elseif (isset($post['katnew']))
	{

		$posttitel=$post['titel'];
		$beschreibung=$post['S1'];
		$dlevel=$post['level'];

		$posttitel = $hp->escapestring($posttitel);
		$beschreibung = $hp->escapestring($beschreibung);
		$dlevel = $hp->escapestring($dlevel);



		$eintrag = "INSERT INTO `".$dbprefix."download_kat`
    (name, description, level)
    VALUES
    ('$posttitel', '$beschreibung', '$dlevel')";
		$eintragen = mysql_query($eintrag);

		echo mysql_error();
		if ($eintragen== true)
		{
			$info->okn($lang['Erfolgreich erstellt']."!");
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang['Erfolgreich erstellt']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
			$site->display();
		}



	} elseif (isset($get['katchange']))
	{
		$abfrage2 = "SELECT * FROM ".$dbprefix."download_kat WHERE `ID` = '".$get['katchange']."'";
		$ergebnis2 = $hp->mysqlquery($abfrage2);

		if (mysql_num_rows($ergebnis2) > 0)
		{

			$row = mysql_fetch_object($ergebnis2);


			$site = new siteTemplate($hp);
			$site->load("upload");


			$levels = $hp->right->getlevels();
			$content = "";
			foreach ($levels as $k=>$level)
			{
				$sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
				$ergl = $hp->mysqlquery($sql);

				if (mysql_num_rows($ergl) > 0)
				{
					$rowl = mysql_fetch_object($ergl);
					$name = $rowl->name;
				} else
				{
					$name = $level;
				}


				$data = array(
					"level" => $level,
					"name" => $name,
					"aktlevel" => $row->level
					);

				$content .= $site->getNode("Edit-Levels", $data);

			}

			$site->set("levels", $content);


			$data = array(
				"update" => "true",
				"titel" => $row->name,
				"beschreibung" => $row->description,
				"ID" => $row->ID

				);

			$site->setArray($data);

			$site->display("Kat-Edit");

		}

	} elseif (isset($post['katchange']))
	{


		$posttitel=$post['titel'];
		$beschreibung=$post['S1'];
		$dlevel=$post['level'];
		$dID=$post['ID'];

		$posttitel = $hp->escapestring($posttitel);
		$beschreibung = $hp->escapestring($beschreibung);
		$dlevel = $hp->escapestring($dlevel);



		$sql = "UPDATE `".$dbprefix."download_kat` SET `name` = '$posttitel',
    `level` = '$dlevel', `description` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";

		$res = $hp->mysqlquery($sql);

		if ($res)
		{
			$info->okn("Erfolgeich bearbeitet!");
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang['Erfolgreich aktualisiert']."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
			$site->display();
		}


	} elseif (isset($get['katdel']) && $right[$level]['upload_del'])
	{

		$abfrage = "SELECT * FROM ".$dbprefix."download WHERE `kat` = '".$get['katdel']."'";
		$ergebnis = $hp->mysqlquery($abfrage);

		$x = mysql_num_rows($ergebnis);

		if ($x != 0)
		{
			$error->error($lang["Sie können keine Kategorien löschen, in denen sich Dateien befinden"]."!","2");
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang["Sie können keine Kategorien löschen, in denen sich Dateien befinden"]."!<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
			$site->display();

		} else
		{
			$del = $get['katdel'];
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang["Möchten Sie die Kategorie wirklich löschen?"]." <a href=index.php?site=upload&katdel2=$del>".$lang['yes']."</a> <a href=index.php>".$lang['no']."</a>");
			$site->display();
		}


	} elseif (isset($get['katdel2']))
	{

		$abfrage = "DELETE FROM ".$dbprefix."download_kat WHERE `ID`=".$_GET['katdel2'];
		$ergebnis = $hp->mysqlquery($abfrage);

		if ($ergebnis == true)
		{
			$info->okn($lang['delok']);
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang['delok']."<br><a href=\"index.php?site=upload\">".$lang['back']."</a>");
			$site->display();

		}

	} else
	{

		$site = new siteTemplate($hp);
		$site->load("upload");
		$site->display();

	}

}
?>