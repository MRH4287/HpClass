<?php
class ApiFunctions extends Plugin
{


	function __construct($hp, $loader)
	{
		// Laden der Daten
		// Nicht Editieren!
		parent::__construct($hp, $loader);

		// Plugin Config:
		// -----------------------------------------------

		// Der Name des Plugins:
		$this->name = "ApiFunctions";

		// Die Version des Plugins:
		$this->version = "Beta 1";

		// Der Autor des Plugins:
		$this->autor = "MRH";

		//Die Homepage des Autors:
		$this->homepage = "http://mrh-development.de";

		//Notizen zu dem Plugin:
		$this->notes = "<b>Beta</b><br />Erweitert das System um Api Funktionen";
	}


	/*

	Lade alle für das System relevanten Daten.

	z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

	*/
	function OnEnable()
	{
		$this->loader->registerApiFunctions($this);

	}


	/*

	Hier werden die eigentlichen Aufgaben des Plugins erledigt.
	Wie zum Beispiel das hinzufügen von Weiterleitungen.

	*/
	function OnLoad()
	{

	}

	function OnSiteCreated()
	{

	}


	function api_write($arguments)
	{
		// Versuche Dateien zu schreiben:
		// Argumente:
		// - Dateiname
		// - Part
		// - Daten

		if (count($arguments) == 3)
		{
			$filename = $arguments[0];
			$part = $arguments[1];
			$data = $arguments[2];


			$fp = fopen('./plugins/ApiTest/'.$filename, "a");


			foreach (explode("-", $data) as $k=>$value)
			{

				if ($value != "")
				{
					fwrite($fp, pack("c*", $value));
				}

			}

			fclose($fp);

			return json_encode(array("status" => "OK", "error" => "none", "part" => $part));

		}

		return json_encode(array("status" => "Not OK", "error" => "Not all Arguments"));

	}


}



?>