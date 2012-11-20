<?php
class RSSFunction extends Plugin
{
	function __construct($hp, $loader)
	{
		// Laden der Daten
		// Nicht Editieren!
		parent::__construct($hp, $loader);

		// Plugin Config:
		// -----------------------------------------------

		// Der Name des Plugins:
		$this->name = "RSS Functions";

		// Die Version des Plugins:
		$this->version = "1.0";

		// Der Autor des Plugins:
		$this->autor = "MRH";

		//Die Homepage des Autors:
		$this->homepage = "http://mrh-development.de";

		//Notizen zu dem Plugin:
		$this->notes = "RSS-News Feed Support";
	}


	/*

	Lade alle für das System relevanten Daten.

	z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

	*/
	function OnEnable()
	{
	}


	/*

	Hier werden die eigentlichen Aufgaben des Plugins erledigt.
	Wie zum Beispiel das hinzufügen von Weiterleitungen.

	*/
	function OnLoad()
	{		

		$hp = $this->hp;
		$get = $hp->get();
		
		if (isset($get['rss']))
		{
			header("Content-type: text/xml");
			
						
			$site = new pluginTemplate($hp, "rss");
			$site->load("data");
			
			$data = $this->getNews();
						
			$site->set("items", $data);
			$site->set("HTTP_HOST", $_SERVER['HTTP_HOST']);
			$site->set("PHP_SELF", $_SERVER['PHP_SELF']);
			
			if (count($data) >= 1)
			{
				$site->set("lastEntry", $data[0]);
			}
			else
			{
				$site->set("lastEntry", array());
			}
			
			$site->set("timeNow", date("D, d.m.Y H:i:s"));
			
			
			$site->display();
			
			exit;
		}

	}

	function OnSiteCreated()
	{
		
	}

	private function getNews()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$right = $hp->right;

		$sql = "SELECT ID, ersteller, datum, titel as title, text FROM `$dbprefix"."news` WHERE `level` = '0' ORDER BY ID DESC;";
		$erg = $hp->mysqlquery($sql);
		
		$data = array();
				
		if (mysql_num_rows($erg) >= 1)
		{
			while ($row = mysql_fetch_array($erg))
			{				
				$data[] = $row;	
			}
		}	

		return $data;
	}
	
		
}



?>