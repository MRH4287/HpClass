<?php
class TemplateExtensionsPlugin extends Plugin
{


	function __construct($hp, $loader)
	{
		// Laden der Daten
		// Nicht Editieren!
		parent::__construct($hp, $loader);

		// Plugin Config:
		// -----------------------------------------------

		// Der Name des Plugins:
		$this->name = "Template Extensions";

		// Die Version des Plugins:
		$this->version = "1.0.0";

		// Der Autor des Plugins:
		$this->autor = "MRH";

		//Die Homepage des Autors:
		$this->homepage = "http://mrh-development.de";

		//Notizen zu dem Plugin:
		$this->notes = "Erweitert die Templates um mathematische und logische Funktionen";

		$this->lock = true;
		$this->isEnabled = true;
		
		//------------------------------------------------

	}


	/*

	Lade alle für das System relevanten Daten.

	z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

	*/
	function onEnable()
	{
		if (file_exists('./plugins/templateExtensions/templateExtensions.php'))
		{
			include './plugins/templateExtensions/templateExtensions.php';
			
			$ext = new templateExtensions();
			siteTemplate::extend($ext);
			
		}
		
		
	}

	/*

	Wird aufgerufen, wenn die Seite komplette aufgebaut wurde.

	*/
	function OnSiteCreated()
	{
		
	}


	/*

	Hier werden die eigentlichen Aufgaben des Plugins erledigt.
	Wie zum Beispiel das hinzufügen von Weiterleitungen.

	*/
	function onLoad()
	{

	}


}



?>