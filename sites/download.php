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


if (isset ($get['del2']) and ($right[$level]['upload_del']))
{
	/*
	$abfrage = "DELETE FROM ".$dbprefix."download WHERE `ID`=".$get['del'];
	$ergebnis = $hp->mysqlquery($abfrage);
	*/

	$abfrage = "SELECT * FROM ".$dbprefix."download WHERE `ID` = '".$_GET['del2']."'";
	$ergebnis = $hp->mysqlquery($abfrage);

	if (mysql_num_rows($ergebnis) > 0)
	{

		$row = mysql_fetch_object($ergebnis);

		@unlink("downloads/$row->dateiname");

		$abfrage = "DELETE FROM ".$dbprefix."download WHERE `ID`=".$_GET['del2'];
		$ergebnis = $hp->mysqlquery($abfrage);



		if ($ergebnis == true)
		{
			$info->okn($lang->word('delok'));

		} else
		{
			$error->error("Fehler: ".mysql_error(),"2");
		}

	} else
	{
		$error->error("Datei exsisitiert nicht!");
	}

}


if (!$right[$level]["see_downloadPage"])
{
	$error->error($lang->word('noright2'),"2");

} elseif (isset ($get['del']) and $right[$level]['upload_del'])
{
	$del = $get['del'];
	$info->info($lang["Möchten Sie die Datei wirklich löschen?"]." <a href=index.php?site=download&del2=$del>".$lang['yes']."</a> <a href=index.php>".$lang['no']."</a>");
} else
{
	if (!isset($get['id']))
	{

		$site = new siteTemplate($hp);
		$site->load("download");

		$abfrage = "SELECT * FROM ".$dbprefix."download_kat";
		$ergebnis = $hp->mysqlquery($abfrage);

		$contentcat = "";
		while($row = mysql_fetch_object($ergebnis))
		{
			if ($hp->right->isAllowed($row->level))
			{
				$abfrage2 = "SELECT * FROM ".$dbprefix."download WHERE `kat` = '$row->ID'";
				$ergebnis2 = $hp->mysqlquery($abfrage2);

				$content = "";
				while($row2 = mysql_fetch_object($ergebnis2))
				{
					if ($hp->right->isAllowed($row2->level))
					{
						$data = array(
							"titel" => $row2->titel,
							"ID" => $row2->ID,
							"change" => (isset($get["change"]) &&  $right[$level]['upload']) ? "true" : "false"
							);

						$content .= $site->getNode("DL-Element", $data);


					}
				}

				$data = array(
					"ID" => $row->ID,
					"name" => $row->name,
					"katchange" => (isset($get["katchange"]) &&  $right[$level]['upload']) ? "true" : "false",
					"Elements" => $content
					);

				$contentcat .= $site->getNode("DL-Cat", $data);

			}
		}

		$data = array(
			"Data" => $contentcat
			);

		$site->setArray($data);

		$site->display();

	} else
	{

		$abfrage = "SELECT * FROM ".$dbprefix."download WHERE `ID` = '".$get['id']."'";
		$erg = $hp->mysqlquery($abfrage);

		$site = new siteTemplate($hp);
		$site->load("download");

		if (mysql_num_rows($erg) == 1)
		{

			$row = mysql_fetch_object($erg);

			if ($hp->right->isAllowed($row->level))
			{
				$data = array(
					"titel" => $row->titel,
					"beschreibung" => $row->beschreibung,
					"datum" => $row->datum,
					"autor" => $row->autor,
					"path" => $row->dateiname,
					"ID" => $row->ID,
					"direct" => (file_exists("downloads/$row->dateiname")) ? "true" : "false"
					);

				$site->setArray($data);
				$site->display("DL-Show");

			} else
			{
				$error->error($lang->word('norightlookfile'),"2");
			}
		} else
		{
			$site = new siteTemplate($hp);
			$site->load("info");
			$site->set("info", $lang["Die gewünschte Datei existiert nicht!"]."<br><a href=?site=download>".$lang['Download-Bereich']."</a>");
			$site->display();

			$error->error($lang["Die gewünschte Datei existiert nicht!"]);

		}
	}
}
?>