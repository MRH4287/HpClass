<?php
class ICalFunction extends Plugin
{


	function __construct($hp, $loader)
	{
		// Laden der Daten
		// Nicht Editieren!
		parent::__construct($hp, $loader);

		// Plugin Config:
		// -----------------------------------------------

		// Der Name des Plugins:
		$this->name = "ICal Functions";

		// Die Version des Plugins:
		$this->version = "1.0";

		// Der Autor des Plugins:
		$this->autor = "MRH";

		//Die Homepage des Autors:
		$this->homepage = "http://mrh-development.de";

		//Notizen zu dem Plugin:
		$this->notes = "Outlook Support";
	}


	/*

	Lade alle für das System relevanten Daten.

	z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

	*/
	function OnEnable()
	{
		$hp = $this->hp;
	
		$configs = array(
		  array(
		    "name"      => "calendar_display_iCalLink",
		    "desc"      => "Zeige einen Verweiß auf den iCal Kalender innerhalb der Kalender Seiten",
		    "cat"       => "System",
		    "type"      => "bool",
		    "default"   => true
		  )

		);

		$hp->config->registerArray($configs);
	}


	/*

	Hier werden die eigentlichen Aufgaben des Plugins erledigt.
	Wie zum Beispiel das hinzufügen von Weiterleitungen.

	*/
	function OnLoad()
	{		
		$hp = $this->hp;
		$get = $hp->get();
		
		if (isset($get['iCal']))
		{
			header("Content-Type: text/Calendar");
		
			$subpage = null;
			
			if (isset($get['subpage']))
			{
				$subpage = intval($get['subpage']);
			}
			
			$site = new pluginTemplate($hp, "iCal");
			$site->load("config");
			$site->get("Main");
			$vars = $site->getVars();
						
			$site = new pluginTemplate($hp, "iCal");
			$site->setArray($vars);
			$site->load("data");
			
			$data = $this->getEvents($subpage);
			
			
			$site->set("events", $data);
			
			
			$site->display();
			
			exit;
		}

	}

	function OnSiteCreated()
	{
		
	}

	private function getEvents($subpage = null)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$right = $hp->right;

		$sql = "SELECT ID, name, date, enddate, start, end, description, display, DATE_FORMAT(time, '%Y%m%dT%H%i%s') as time FROM `$dbprefix"."events` WHERE `level` = '0';";
		$erg = $hp->mysqlquery($sql);
		
		$data = array();
		
		while ($row = mysql_fetch_array($erg))
		{
			$subpages = explode(",", $row['display']);
			
			if (($subpage != null) && (!in_array($subpage, $subpages)))
			{
				continue;
			}
			
			$res = $this->getEventData($row);
		
			if ($res === null)
			{
				continue;
			}
			
			$data[] = $res;	
		}
		
		return $data;
	
	}
	
	private function getEventData($row)
	{
		$data = array();
	
		$data['Created'] = $row['time'];
	
	
		$timeReg = "/([0-2]?[0-9]):([0-5]?[0-9])/";
		$dateReg = "/([0-3]?[0-9]).([0-1]?[0-9]).([1-2][0-9][0-9][0-9])/";

		// TimeStart ---
		preg_match($timeReg, $row['start'], $resTime);
		preg_match($dateReg, $row['date'], $resDate);
		
		if (count($resTime) == 3 && count($resDate) == 4)
		{
			$resDate = $this->getTwoDigitsArray($resDate);
			$resTime = $this->getTwoDigitsArray($resTime);
		
			$data["TimeStart"] = $resDate[3].$resDate[2].$resDate[1]."T".$resTime[1].$resTime[2]."00";
		
		} else
		{
			return null;
		}

		// Time-End --
		
		preg_match($timeReg, $row['end'], $resTime);
		preg_match($dateReg, $row['enddate'], $resDate);
		
		if (count($resTime) == 3 && count($resDate) == 4)
		{
			$resDate = $this->getTwoDigitsArray($resDate);
			$resTime = $this->getTwoDigitsArray($resTime);
		
			$data["TimeEnd"] = $resDate[3].$resDate[2].$resDate[1]."T".$resTime[1].$resTime[2]."00";
		
		} else
		{
			return null;
		}
		
		// Description ---
		
		$description = str_replace(array("\n", "\r"), "", $row['description']);
		
		$data["Description"] = strip_tags($description);
		$data["HTMLDescription"] = $description;
		
		// Name --
		
		$data['Name'] = $row['name'];
		
		// UID --
		
		$data['UID'] = md5($row['ID']);
		
		// LastModified --
		
		$data['LastModified'] = date("Ymd")."T".date("Hi")."00";
		
		
		return $data;
	}
	
	
	private function makeTwoDigits($in)
	{
		if (strlen($in) < 2)
		{
			$in = "0". $in;
		}
		
		return $in;
	}
	
	private function getTwoDigitsArray($data)
	{
		$out = array();
		
		foreach ($data as $key => $value)
		{
			$out[$key] = $this->makeTwoDigits($value);
		}
	
		return $out;
	}
	
}



?>