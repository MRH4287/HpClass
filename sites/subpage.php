<?php
// Site Config:
$hp = $this;
$right = $hp->getright();
$rightO = $hp->right;
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$subpages = $hp->subpages;
$xajaxF = $hp->xajaxF;
$fp = $hp->fp;


if (!$right[$level]["manage_subpage"])
{
	$error->error($lang['Sie haben nicht die ben�tigte Berechtigung!'], "1");

} elseif (isset($get["new"]))
{
	$site = new siteTemplate($hp);
	$site->load("subpage");

	$subpage = isset($get["sub"]);
	$subID = 0;
	if (isset($get["subID"]))
	{
		$subID = $get["subID"];
	}


	//Rufe eine Liste aller Templates auf:

	$templates = $subpages->getAllTemplates();

	$template = "";
	$f = true;
	$ft = "";
	foreach ($templates as $ID=>$name)
	{
		if ($subpage && ($subpages->templateCanHaveChilds($ID)))
		{
			continue;
		}

		$data = array(
			"selected" => ($f)? "selected" : "",
			"value" => $name,
			"ID" => $ID

			);

		$template .= $site->getNode("ComboBoxOption", $data);

		// Ermitteln des ersten Templates:
		if ($f)
		{
			$ft = $ID;
		}

		$f = false;
	}

	$data = array(
		"Templates" =>$template
		);

	$site->set("TemplateSelector", $site->getNode("TemplateSelector", $data));

	// Lade die Daten f�r das erste Template:
	$this->hp->template->addScriptToHeader("xajax_subpageTemplateChange('$ft');");

	$site->set("site", "new");
	$site->set("subpage_name", "");
	$site->set("edit", "false");
	$site->set("ID", "");
	$site->set("subpage", ($subpage) ? "true" : "false");
	$site->set("Content", "<img src=images/loading.gif alt=\"Loading\" />");

	if ($subpage)
	{
		// Ermittle alle Seiten, die das DynTemplate "Navigation" haben
		$display= array();
		$toDisplay = $subpages->getAllTemplatesWithDynContent("navigation");

		// Abfragen aller Seiten:
		$sql = "SELECT * FROM `$dbprefix"."subpages`;";
		$erg = $hp->mysqlquery($sql);
		while ($row = mysql_fetch_object($erg))
		{
			if (in_array($row->template, $toDisplay))
			{
				$display[$row->ID] = $row->name;
			}

		}



		$content = "";
		foreach ($display as $key => $value)
		{
			$data = array(
				"ID" => $key,
				"value" => $value,
				"selected" => ($subID == $key) ? "selected=selected" : ""

				);
			$content .= $site->getNode("ComboBoxOption", $data);


		}

		$site->set("SubpageOptions", $content);
		$site->set("SubpageKat", "");

	}


	$site->display();

} elseif (isset($post["new"]))
{

	$site = new siteTemplate($hp);
	$site->load("info");

	$subpageName = $post["subpage_name"];

	if ((!stristr($subpageName, '�')) && (!stristr($subpageName, '�')) && (!stristr($subpageName, '�')) && (!stristr($subpageName, '�')))
	{

		if ($subpages->getSite($subpageName) == false)
		{
			$templateName = $post["template_name"];
			$tpC = $subpages->getTemplateConfig($templateName);

			if (($tpC != false) && (isset($tpC["template"])))
			{
				// Abfragen der Daten:

				$data = "";
				foreach ($tpC["template"] as $name=>$type)
				{
					if (isset($post["tp_$name"]))
					{
						$value = $post["tp_$name"];

						if ($data != "")
						{
							$data .= "<!--!>";
						}

						$data .= "$name<!=!>$value";
					}
				}

				$subpage = "0";
				$subpageKat = "";
				if (isset($post["subpage"]))
				{
					$subpage = $post["subpage"];
					$subpageKat = $post["subpageKat"];
				}


				//Speichern der Unterseite:
				$sql = "INSERT INTO `$dbprefix"."subpages` (`name`, `content`, `template`, `created`, `parent`, `parent_kat`) VALUES ('$subpageName', '$data', '$templateName', NOW(), '$subpage', '$subpageKat');";
				$erg = $hp->mysqlquery($sql);

				$site->set("info", $lang['Unterseite erfolgreich erstellt']."!<br><a href=?site=subpage>".$lang['back']."</a>");



			} else
			{
				$site->set("info", $lang['Das gew�hlte Template ist nicht verf�gbar']."!<br><a href=?site=subpage>".$lang['back']."</a>");
			}

		} else
		{
			$site->set("info", $lang['Es existiert bereits eine Seite mit diesen Namen']."!<br><a href=?site=subpage>".$lang['back']."</a>");
		}

	} else
	{
		$site->set("info", $lang['Die Zeichen &uuml; &ouml; &auml; und &szlig; sind im Namen nicht erlaubt']."!<br><a href=?site=subpage>".$lang['back']."</a>");
	}

	$site->display();

} elseif (isset($get["edit"]))
{
	$siteData = $subpages->getSite($get["edit"]);
	$tempData = $subpages->getTemplateData($get["edit"]);

	if ($siteData != false)
	{
		$subpage = ($siteData["parent"] != "0");

		$site = new siteTemplate($hp);
		$site->load("subpage");

		$site->set("TemplateSelector", $siteData["template"]);
		$site->set("site", "edit");
		$site->set("edit", "true");
		$site->set("subpage", ($subpage) ? "true" : "false");
		$site->set("subpage_name", $siteData['name']);
		$site->set("ID", $siteData["ID"]);

		// Seiten�berpr�fung:
		$tpC = $subpages->getTemplateConfig($siteData["template"]);

		$subpage = ($siteData["parent"] != 0);

		$content = "";
		foreach($tpC["template"] as $ID=>$type)
		{
			switch($type)
			{
				case "textbox":

					$current = (isset($tempData[$ID])) ? $tempData[$ID] : '';

					$data = array(
						"name" => $ID,
						"value" => $current,
						"ID" => "tp_".$ID
						);

					$content .= $site->getNode("TextBox", $data);


					break;


				case "textarea":

					$current = (isset($tempData[$ID])) ? $tempData[$ID] : '';
					$data = array(
						"name" => $ID,
						"value" => $current,
						"ID" => "tp_".$ID
						);

					$content .= $site->getNode("TextArea", $data);


					break;


				case "checkbox":

					$current = (isset($tempData[$ID])) ? $tempData[$ID] : '';
					$data = array(
						"name" => $ID,
						"checked" => $current,
						"ID" => "tp_".$ID
						);

					$content .= $site->getNode("CheckBox", $data);


					break;

				case "combobox":

					$options = "";
					$current = (isset($tempData[$ID])) ? $tempData[$ID] : '';

					if (isset($tpC["data"][$ID]) and is_array($tpC["data"][$ID]))
					{
						foreach ($tpC["data"][$ID] as $k=>$value)
						{
							$data = array(

								"ID" => $value,
								"value" => $value,
								"selected" => ($value == $current)? "selected" : ""
								);

							$options .= $site->getNode("ComboBoxOption", $data);

						}
					}
					$data = array(
						"name" => $ID,
						"ID" => "tp_".$ID,
						"Options" => $options
						);

					$content .= $site->getNode("ComboBox", $data);


					break;

				case "level":

					$current = (isset($tempData[$ID])) ? $tempData[$ID] : '';
					$levels = $rightO->getlevels();
					$options = "";

					foreach ($levels as $k=>$value)
					{
						$data = array(

							"ID" => $value,
							"value" => $value,
							"selected" => ($value == $current)? "selected" : ""
							);

						$options .= $site->getNode("ComboBoxOption", $data);

					}

					$data = array(
						"name" => $ID,
						"ID" => "tp_".$ID,
						"Options" => $options
						);

					$content .= $site->getNode("ComboBox", $data);


					break;

			}
		}
		$site->set("Content", $content);

		if ($subpage)
		{
			// Ermittle alle Seiten, die das DynTemplate "Navigation" haben
			$display= array();
			$toDisplay = $subpages->getAllTemplatesWithDynContent("navigation");

			// Abfragen aller Seiten:
			$sql = "SELECT * FROM `$dbprefix"."subpages`;";
			$erg = $hp->mysqlquery($sql);
			while ($row = mysql_fetch_object($erg))
			{
				if (in_array($row->template, $toDisplay))
				{
					$display[$row->ID] = $row->name;
				}

			}



			$content = "";
			foreach ($display as $key => $value)
			{
				$data = array(
					"ID" => $key,
					"value" => $value,
					"selected" => ($siteData["parent"] == $key) ? "selected=selected" : ""

					);
				$content .= $site->getNode("ComboBoxOption", $data);


			}

			$site->set("SubpageOptions", $content);
			$site->set("SubpageKat", $siteData["parent_kat"]);

		}


		$site->display();

	} else
	{
		$site = new siteTemplate($hp);
		$site->load("info");
		$site->set("info", $lang['Die gew�nschte Unterseite ist nicht verf�gbar']."!<br><a href=?site=subpage>".$lang['back']."</a>");
		$site->display();
	}

} elseif (isset($post["edit"]))
{
	$site = new siteTemplate($hp);
	$site->load("info");

	$ID = $post["ID"];
	$templateData = $subpages->getSite($ID);
	if ($templateData != false)
	{
		$templateName = $templateData["template"];
		$tpC = $subpages->getTemplateConfig($templateName);

		if (($tpC != false) && (isset($tpC["template"])))
		{
			// Abfragen der Daten:

			$data = "";
			foreach ($tpC["template"] as $name=>$type)
			{
				if (isset($post["tp_$name"]))
				{
					$value = $post["tp_$name"];

					if ($data != "")
					{
						$data .= "<!--!>";
					}

					$data .= "$name<!=!>$value";
				}
			}

			$subpage = "0";
			$subpageKat = "";
			if (isset($post["subpage"]))
			{
				$subpage = $post["subpage"];
				$subpageKat = $post["subpageKat"];
			}

			//Speichern der Unterseite:
			$sql = "UPDATE `$dbprefix"."subpages` SET `content` = '$data', `parent` = '$subpage', `parent_kat` = '$subpageKat' WHERE `ID` = '$ID';";
			$erg = $hp->mysqlquery($sql);

			$site->set("info", $lang['Unterseite erfolgreich modifiziert']."!<br><a href=?site=subpage>".$lang['back']."</a>");



		} else
		{
			$site->set("info", $lang['Das gew�hlte Template ist nicht verf�gbar']."!<br><a href=?site=subpage>".$lang['back']."</a>");
		}

	} else
	{
		$site->set("info", $lang['Die gew�nschte Unterseite ist nicht verf�gbar']."!<br><a href=?site=subpage>".$lang['back']."</a>");
	}

	$site->display();


} elseif (isset($get["list"]))
{

	//List_Element
	$site = new siteTemplate($hp);
	$site->load("subpage");
	$subpage = false;

	if (isset($get["sub"]))
	{
		$subpage = true;
		$sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `parent` = '".$get["sub"]."';";

	} else
	{
		$sql = "SELECT * FROM `$dbprefix"."subpages`;";

	}

	$erg = $hp->mysqlquery($sql);

	$templates = $subpages->getAllTemplates();

	$elements = "";
	while ($row = mysql_fetch_object($erg))
	{
		$data = array(
			"name" => $row->name,
			"template" => $templates[$row->template],
			"ID" => $row->ID,
			"hasChilds" => ($subpages->siteCanHaveChilds($row->name)) ? "true" : "false"
			);

		$elements .= $site->getNode("List_Element", $data);

	}

	$site->set("Elements", $elements);
	$site->set("headline", ($subpage) ? $lang["Liste der zugeordneten Unterseiten"] : $lang["Liste der Vorhandenen Unterseiten"]);
	$site->set("subpage", ($subpage) ? "true" : "false");

	if ($subpage)
	{
		$site->set("subID", $get["sub"]);
	}

	$site->display("List");


} elseif (isset($get["del"]))
{
	// Ist die Best�tigung vorhanden?
	if (isset($get["ok"]))
	{
		$sql = "DELETE FROM `$dbprefix"."subpages` WHERE `ID` = '".$get["del"]."';";
		$erg = $hp->mysqlquery($sql);

		$site = new siteTemplate($hp);
		$site->load("info");
		$site->set("info", sprintf($lang['Unterseite %s erfolgreich gel�scht'], '"'.$get["del"].'"')."!<br><a href=?site=subpage&list>".$lang['back']."</a>");
		$site->display();

	} else
	{
		$site = new siteTemplate($hp);
		$site->load("info");
		$site->set("info", sprintf($lang['M�chten Sie die Unterseite %s wirklich l�schen?'], '"'.$get["del"].'"')."<br><a href=?site=subpage&del=".$get["del"]."&ok>".$lang['yes']."</a><br><a href=?site=subpage&list>".$lang['back']."</a>");
		$site->display();

	}


} else
{
	$site = new siteTemplate($hp);
	$site->load("subpage");
	$site->display("Menu");

}


?>