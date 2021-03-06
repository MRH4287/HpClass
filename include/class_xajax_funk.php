<?php
class Xajax_Funktions
{
	var $hp;

	var $ajaxFuncPrefix = 'ax_';

	var $xajax;
	var $func = array();
	var $call = array();

	function __construct()
	{
		$this->xajax = new xajax();
		$this->registerFunctions($this);

		//$this->xajax->configure( 'debug', true );

	}

	function sethp($hp)
	{
		$this->hp = $hp;
	}

	public function registerFunctions($object) {
		$methods = get_class_methods($object);
		
		foreach ($methods as $m) {
			$p = $this->ajaxFuncPrefix;
			if (preg_match("/^{$p}[a-z]/", $m)) {
				$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);
				$this->xajax->register(XAJAX_FUNCTION, array($m2, $object, $m));
			}
		}
	}



	function printjs()
	{
		$this->xajax->configure('javascript URI','include/');
		$this->xajax->printJavascript("include/");
	}

	function processRequest()
	{
		$this->xajax->processRequest();
	}


	function ax_event_list($month = "X", $jahr = "X")
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$game = $hp->game;
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->firephp;
		$lb = $hp->lbsites;
		$config = $hp->getconfig();
		$response = new xajaxResponse();
		$lb = $hp->lbsites;
		$subpages = $hp->subpages;

		$arr_monate = array ('Januar', 'Februar', 'M�rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
		$date = getdate();


		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"month" => $month,
				"jahr" => $jahr
				);

			foreach ($data as $k => $v)
			{
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$k = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		$tag = $date['mday'];
		$monat2 = $date['mon'];


		if ($month != "X")
		{
			$monat = $month;
			$tag = 0;
		} else
		{
			$monat = $monat2;
		}
		if ($jahr == "X")
		{
			$jahr = $date['year'];
		}


		// Ermitteln des letzten / n�chsten Monats
		if ($monat == 1)
		{
			$lnzjahr = $jahr - 1;
			$monlz = 12;
		} else
		{
			$lnzjahr = $jahr;
			$monlz = $monat-1;
		}

		if ($monat == 12)
		{
			$lnvjahr = $jahr + 1;
			$monlv = 1;
		} else
		{
			$lnvjahr = $jahr;
			$monlv = $monat+1;
		}

		if ($monat < 10)
		{
			$monat = "0$monat";
		}

		$site = new siteTemplate($hp);
		$site->load("calendar");

		$mainData = array(
			"lastmonth" => $monlz,
			"lastyear" => $lnzjahr,
			"month" => htmlentities($arr_monate[$monat-1]),
			"year" => $jahr,
			"nextmonth" => $monlv,
			"nextyear" => $lnvjahr
			);

		$site->setArray($mainData);

		$iAnzahltage = date("t", mktime(0, 0, 0, $monat, 1, $jahr));

		$contentMain = "";

		for ($i=0; $i<$iAnzahltage; $i++)
		{
			
			$d = $i+1;
			if ($d < 10)
			{
				$d = "0$d";
			}

			$events = $subpages->getEvents("$d.$monat.$jahr");

			$content = "";

			foreach ($events as $k=>$row)
			{
				
				$data = array(
					"time" => $row->name,
					"ID" => $row->ID
					);

				$content .= $site->getNode("Event-Day-Event", $data);
				
			}

			$data = array(

				"day" => $i+1,
				"link" => "$d.$monat.$jahr",
				"Content" => $content

				);

			$contentMain .= $site->getNode("Event-Day", $data);
			
		}

		$site->set("Content", $contentMain);


		$response->assign("calender_list", "innerHTML",  self::prepare($site->get("Event-List")) );


		return $response;
	}

	function ax_calender_vote($month = "X", $jahr = "X")
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->firephp;
		$lb = $hp->lbsites;
		$config = $hp->getconfig();
		$response = new xajaxResponse();

		$arr_monate = array ('Januar', 'Februar', 'M�rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
		$date = getdate();

		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"month" => $month,
				"jahr" => $jahr
				);

			foreach ($data as $k => $v)
			{
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$k = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		$tag = $date['mday'];
		$monat2 = $date['mon'];


		if ($month != "X")
		{
			$monat = $month;
			$tag = 0;
		} else
		{
			$monat = $monat2;
		}
		if ($jahr == "X")
		{
			$jahr = $date['year'];
		}




		$style = 4;


		$text = '<link href="./css/style'.$style.'.css" rel="stylesheet">';

		$iWochenTag  = date("w", mktime(0, 0, 0, $monat, 1, $jahr));
		$iAnzahltage = date("t", mktime(0, 0, 0, $monat, 1, $jahr));
		$iZeilen = ($iWochenTag==1 && $iAnzahltage==28) ? 4 : (($iAnzahltage == 31 && ($iWochenTag == 6 || $iWochenTag == 0))|| ($iWochenTag  == 0 && $iAnzahltage == 30)) ? 6 : 5;



		//N�chster Monat
		if($monat==12){
			$nmonat=1;
			$njahr=$jahr+1;
		} else {
			$nmonat=$monat+1;
			$njahr=$jahr;
		}

		//Vorheriger Monat
		if($monat==1){
			$vmonat=12;
			$vjahr=$jahr-1;
		} else {
			$vmonat=$monat-1;
			$vjahr=$jahr;
		}

		$iAnzahltageVormonat = date("t", mktime(0, 0, 0, $vmonat, 1, $vjahr));

		if ($monat == 1) { $lnzjahr = $jahr - 1; $monlz = 12; } else { $lnzjahr = $jahr; $monlz = $monat-1; }
		if ($monat == 12) { $lnvjahr = $jahr + 1; $monlv = 1; } else { $lnvjahr = $jahr; $monlv = $monat+1; }

		$text .= '<table id="cal"><tr>'.
			'<th>'.
			'<a onclick="xajax_calender_vote('.$monlz.', '.$lnzjahr.'); return false;" href="#"><img src="images/arrowp.gif" width="9" height="11" alt="&gt;"></a>' . // Xajax regelung, dass der Kalender aktualisiert wird
			'</th>'.
			'<th colspan="5">'.htmlentities($arr_monate[$monat-1]).' '.$jahr.'</th>'.
			'<th >'.
			'<a onclick="xajax_calender_vote('.$monlv.', '.$lnvjahr.'); return false;" href="#"><img src="images/arrown.gif" width="9" height="11"></a>' .
			'</th>'.

			'</tr>
                        <tr>
                          <th >Mo</th>
                          <th >Di</th>
                          <th >Mi</th>
                          <th >Do</th>
                          <th >Fr</th>
                          <th >Sa</th>
                          <th >So</th>
                        </tr>';



		$iTag = 0; //Tag im Monat
		$i=0;
		do{ // while($i < $iZeilen);
			$text=$text. '<tr>';

			$j=1;
			do { //while($j <= 7);



				//Hilfsvariable Mo=1, Di=2 .... So=7
				$m = ($iWochenTag==0) ? 7 :  $iWochenTag;

				//Nicht jeder Monat beginnt am Monat
				if($m == $j && $j <= 7 && $iTag == 0){
					$iTag = 1;
				}


				$preTag = ($iAnzahltageVormonat+$j-$m+1);

				if ($iTag == 0){           // Tage vorMonat
					$evt = false;
					$text=$text. '<td ';

					$text=$text. 'id="amonat">';
					//  echo $m-1;

					$link = '<a href="#" onClick="setdate('.$preTag.', '.($monat -1).', '.$jahr.'); return false;">'.$preTag.'</a>';

					$text=$text. $link;

					$text=$text. "</td>";
				}
				$ntmp = 0;
				if ($iTag > $iAnzahltage){ // Tage des N�chsten Monats
					++$ntmp;
					$evt = false;
					$text=$text. '<td ';
					$text=$text. 'id="amonat">' ;

					$link = '<a href="#" onClick="setdate('.$ntmp.', '.($monat +1).', '.$jahr.'); return false;">'.$ntmp.'</a>';
					$text=$text. $link;

					// $m + 1
					$text=$text. '</td>';
				}

				if ($iTag != 0 && $iTag <= $iAnzahltage){ // Tage im Monat drinnen :D
					$evt = false;
					$text=$text. '<td ';
					$text=$text. 'id="monat">';

					$link = '<a href="#" onClick="setdate('.$iTag.', '.$monat.', '.$jahr.'); return false;">'.$iTag.'</a>';

					//if (($daytod == $iTag) and ($montod == $monat) and ($yeartod == $jahr))
					// {
					// $text .="<b>$link</b>";
					// } else
					// {
					$text=$text. $link;
					// }



					$text=$text. '</td>'."\n";
					++$iTag;
				}


			} while(++$j <= 7);

			$text=$text. '</tr>';

		} while(++$i < $iZeilen);
		$text=$text. '</table>';

		$response->assign("kalender_vote", "innerHTML", "$text");


		return $response;
	}


	function ax_checkvote($titel, $answer1, $answer2, $day, $month, $year, $hour, $min, $update)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();
		$response = new xajaxResponse();


		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"titel" => $titel,
				"answer1" => $answer1,
				"answer2" => $answer2,
				"day" => $day,
				"month" => $month,
				"year" => $year,
				"hour" => $hour,
				"min" => $min,
				"update" => $update
				);

			foreach ($data as $k => $v)
			{
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$k = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		//$fp->log("$titel, $answer1, $answer2");
		if (!$update)
		{
			$postok = '<input type="submit" name="addvote" value="Senden" />';
		} else
		{
			$postok = '<input type="submit" name="editvote" value="Senden" />';
		}


		//$fp->log("$day.$month.$year $hour:$min");
		$timestamp = mktime($hour, $min, 0, $month, $day, $year);

		$titelo = false;
		$answer  = false;
		$date = false;

		if ($titel != "")
		{
			// Kein Titel angegeben
			$response->assign("titelwarn", "innerHTML", "");
			$titelo = true;

		}

		if (($answer1 != "") and ($answer2 != ""))
		{
			// Nicht gen�gend Anwortm�glichkeiten

			$response->assign("answerwarn", "innerHTML", "");
			$answer = true;
		}


		if ($timestamp == false)
		{
			// Fehlerhafte eingabe!


			$response->assign("datewarn", "innerHTML", "Fehlerhafte Eingabe");



		} elseif (time() > $timestamp)
		{
			// liegt in der Vergangenheit
			$response->assign("datewarn", "innerHTML", "Das Datum liegt in der Vergangenheit");


		} else
		{
			// OK


			$response->assign("datewarn", "innerHTML", "");
			$date = true;
		}

		if ($titelo and $answer and $date)
		{
			$response->assign("post", "innerHTML", $postok);
		}


		return $response;
	}


	function ax_vote($ID, $vote)
	{
		$response = new xajaxResponse();
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$game = $hp->game;
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();
		$ID = mysql_real_escape_string($ID);
		$vote = mysql_real_escape_string($vote);

		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"ID", "vote"
				);

			foreach ($data as $k => $name)
			{
				$v = $$name;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$name = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		$sql = "SELECT * FROM `$dbprefix"."vote` WHERE `ID` = $ID";
		$erg = $hp->mysqlquery($sql);
		$row = mysql_fetch_object($erg);

		if (!isset($row->ID) or ($vote==""))
		{
			// Fehler!


			$ern = "<br><center><img src=images/alert2.gif><br>Fehler beim Eintragen</center><br>";
			$response->assign("voteok$ID", "innerHTML", $ern);

		} else
		{



			$erg = explode("<!--!>", $row->ergebnisse);
			$erg[] = $vote;

			$user =  explode("<!--!>", $row->voted);
			$user[] = $_SESSION['ID'];


			$ergebnisse = "";
			foreach ($erg as $key=>$value)
			{
				if ($ergebnisse != "")
				{
					$ergebnisse .= "<!--!>".$value;
				} else
				{
					$ergebnisse = $value;
				}
			}

			$users = "";
			foreach ($user as $key=>$value)
			{
				if ($users != "")
				{
					$users .= "<!--!>".$value;
				} else
				{
					$users = $value;
				}
			}



			$sql = "UPDATE `$dbprefix"."vote` SET `ergebnisse` = '$ergebnisse', `voted` = '$users' WHERE `ID` = $ID";
			$erg = $hp->mysqlquery($sql);


			$okn = "<br><center><img src=images/ok.gif><br>Erfolgreich abgestimmt</center><br>";
			$response->assign("voteok$ID", "innerHTML", $okn);

		}

		return $response;
	}

	function ax_vote_result($ID)
	{
		$response = new xajaxResponse();
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$game = $hp->game;
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();
		$ID = mysql_escape_string($ID);

		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"ID"
				);

			foreach ($data as $k => $name)
			{
				$v = $$name;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$name = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}


		$sql = "SELECT * FROM `$dbprefix"."vote` WHERE `ID` = $ID";
		$erg = $hp->mysqlquery($sql);
		$row = mysql_fetch_object($erg);

		$antworten = explode("<!--!>", $row->antworten);
		$ergebnisse = explode("<!--!>", $row->ergebnisse);

		$erg = array();
		$count = 0;

		foreach ($ergebnisse as $key=>$value)
		{
			if (!isset($erg[$value]))
			{
				$erg[$value] = 0;
			}
			$erg[$value] = $erg[$value] + 1;
			$count++;
		}

		$site = new siteTemplate($hp);
		$site->load("vote");


		$content = "";
		foreach ($antworten as $key=>$value)
		{
			if (!isset($erg[$key]))
			{
				$erg[$key] = 0;
			}
			$perc = ($erg[$key] / $count) * 100;
			$perc = number_format($perc, 2);
			$p = $perc / 100;
			$perc = $perc. "%";
			
			$maxlength = 65;
			$w = $maxlength * $p;

			$data = array(
				"name" => $value,
				"width" => $w,
				"perc" => $perc
				);

			$content .= $site->getNode("Vote-Erg-El", $data);

		}

		$site->set("votes", $content);
		$site->set("count", $count);

		$text = $site->get("Vote-Erg-View");

		$text = str_replace("�", "&uuml;", $text);
		$text = str_replace("�", "&Uuml;", $text);
		$text = str_replace("�", "&ouml;", $text);
		$text = str_replace("�", "&Ouml;", $text);
		$text = str_replace("�", "&auml;", $text);
		$text = str_replace("�", "&Auml;", $text);
		$text = str_replace("�", "szlig;", $text);

		$response->assign("ergebnisse$ID", "innerHTML", $text);

		return $response;
	}






	function ax_picturelist_delElement($id)
	{
		$response = new xajaxResponse();

		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$right = $hp->getright();
		$level = $_SESSION['level'];
		$config = $hp->getconfig();

		$id = mysql_real_escape_string($id);

		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"id"
				);

			foreach ($data as $k => $name)
			{
				$v = $$name;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$name = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		if ($right[$level]["usedpics"])
		{

			$sql = "DELETE FROM `$dbprefix"."usedpics` WHERE `ID` = '$id'";
			$erg = $hp->mysqlquery($sql);

		}

		return $response;
	}

	function ax_savewidgetconfig($temp, $config)
	{
		$response = new xajaxResponse();

		$temp = mysql_real_escape_string($temp);
		$config = mysql_real_escape_string($config);

		$conf = $hp->getconfig();
		if ($conf["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"temp", "config"
				);

			foreach ($data as $k => $name)
			{
				$v = $$name;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$name = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$widget = $hp->widgets;


		if (in_array($_SESSION['username'], $hp->getsuperadmin()))
		{


			$array = explode("<!--!>",$config);
			$config = array();

			foreach ($array as $key=>$value)
			{
				
				$array2 = explode("<!=!>", $value);
				$config[$array2[0]] = $array2[1];
				
			}


			$widget->saveConfig($temp, $config);

		} else
		{
			$response->alert("wieso solltest du das wollen?!");
		}

		//$response->assign("testinput", "value", "hallo");
		return $response;
	}


	// ----------------------------------< DRAG & DROP >------------------------------------------------


	


	// ---------------------------------- </ DRAG & DROP >------------------------------------------------


	//----------------------------------- Plugin System --------------------------------------------------


	function ax_pluginEnable($name)
	{
		$response = new xajaxResponse();
		$name =  mysql_real_escape_string($name);
		$hp = $this->hp;

		$config = $hp->getconfig();
		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"name"
				);

			foreach ($data as $k => $n)
			{
				$v = $$n;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$n = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$pluginloader = $hp->pluginloader;

		if (isset($_SESSION['username']) && in_array($_SESSION['username'], $hp->getsuperadmin()))
		{

			if ($pluginloader->enablePlugin($name))
			{
				$response->assign("pluginData-$name", "innerHTML", '<img src="./images/on.gif" alt="ON" onclick="xajax_pluginDisable(\''.$name.'\')">');

			}
		}

		return $response;
	}

	function ax_pluginDisable($name)
	{
		$response = new xajaxResponse();
		$name = mysql_real_escape_string($name);
		$hp = $this->hp;

		$config = $hp->getconfig();

		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"name"
				);

			foreach ($data as $k => $n)
			{
				$v = $$n;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$n = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}


		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$pluginloader = $hp->pluginloader;

		if (in_array($_SESSION['username'], $hp->getsuperadmin()))
		{

			if ($pluginloader->disablePlugin($name))
			{
				$response->assign("pluginData-$name", "innerHTML", '<img src="./images/off.gif" alt="OFF" onclick="xajax_pluginEnable(\''.$name.'\')">');

			}
		}

		return $response;
	}


	// ------------------------------------- Subpage - Editor ----------------------------------------------

	function ax_subpageTemplateChange($tempname)
	{
		$response = new xajaxResponse();

		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$subpages = $hp->subpages;
		$rightO = $hp->right;

		$tempname = mysql_real_escape_string($tempname);

		$config = $hp->getconfig();
		if ($config["xajax_workaround"])
		{
			// Workaround for older PHP-Versions

			$data = array(
				"tempname"
				);

			foreach ($data as $k => $name)
			{
				$v = $$name;
				if (preg_match("/[S|N](.*)/", $v, $m))
				{
					$$name = substr($v, 1, strlen($v));
				}
			}
			// Workaround End
		}

		// Seiten�berpr�fung:
		$tpC = $subpages->getTemplateConfig($tempname);

		if (($hp->site == "subpage") && ($tpC != false))
		{

			$site = new siteTemplate($hp);
			$site->load("subpage");

			$content = "";
			foreach($tpC["template"] as $ID=>$type)
			{
				switch($type)
				{
					case "textbox":

						$data = array(
							"name" => $ID,
							"value" => "",
							"ID" => "tp_".$ID
							);

						$content .= $site->getNode("TextBox", $data);
						break;

					case "textarea":

						$data = array(
							"name" => $ID,
							"value" => "",
							"ID" => "tp_".$ID
							);

						$content .= $site->getNode("TextArea", $data);
						break;


					case "checkbox":

						$data = array(
							"name" => $ID,
							"checked" => "",
							"ID" => "tp_".$ID
							);

						$content .= $site->getNode("CheckBox", $data);
						break;

					case "combobox":

						$options = "";
						if (isset($tpC["data"][$ID]) and is_array($tpC["data"][$ID]))
						{
							foreach ($tpC["data"][$ID] as $k=>$value)
							{
								$data = array(

									"ID" => $value,
									"value" => $value,
									"slected" => ""
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

			$response->assign("SubpageData", "innerHTML", $content);
			$response->script('	tinyMCE.init({

  		mode : "textareas",
  		theme : "advanced",
  		plugins : "style,table,advimage,advlink,insertdatetime,media,searchreplace,paste,fullscreen",


  		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
  		theme_advanced_buttons2 : "pasteword,search,replace,bullist,numlist,outdent,indent,blockquote,undo,redo,link,unlink,anchor,image,code,forecolor,backcolor",
  		theme_advanced_buttons3 : "tablecontrols,hr,removeformat,visualaid,sub,sup,charmap,emotions,iespell,media,advhr,fullscreen",
  		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,styleprops,cite,abbr,acronym,del,ins,attribs,visualchars,nonbreaking,template,pagebreak",
  		theme_advanced_toolbar_location : "top",
  		theme_advanced_toolbar_align : "left",

  	});');

		} else
		{
			$response->assign("SubpageData", "innerHTML", "");
		}

		return $response;
	}


	// ---------------------------------------- Globale Funktionen -----------------------------------------

	/**
	 * Bereitet die Ausgaben entsprechend vor um sie zu senden
	 * @var $input string Der String der �berpr�ft werden soll
	 * @return string �berpr�fter String
	 */
	public static function prepare($input)
	{
		$output = $input;
		$output = str_replace("�", "&sect;", $output);
		$output = str_replace("�", "&uuml;", $output);
		$output = str_replace("�", "&Uuml;", $output);
		$output = str_replace("�", "&ouml;", $output);
		$output = str_replace("�", "&Ouml;", $output);
		$output = str_replace("�", "&auml;", $output);
		$output = str_replace("�", "&Auml;", $output);
		$output = str_replace("�", "&szlig;", $output);
		
		return $output;
	}


	// ---------------------------------------- Erweiterungen ----------------------------------------------

	function extend($path)
	{
		if (is_file("template/$path/xajax.php"))
		{


			include "template/$path/xajax.php";

			try
			{
				$object = new XajaxTemplate();
				$object->sethp($this->hp);

			} catch(Exception $ex)
			{
				$object = null;
			}

			if (is_object($object))
			{

				$this->registerFunctions($object);

			}
		}
	}

}
?>