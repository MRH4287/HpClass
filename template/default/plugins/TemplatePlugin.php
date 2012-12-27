<?php
class TemplatePlugin extends Plugin
{
	function __construct($hp, $loader)
	{
		// Laden der Daten
		// Nicht Editieren!
		parent::__construct($hp, $loader);

		// Plugin Config:
		// -----------------------------------------------

		// Der Name des Plugins:
		$this->name = "Template - Default";

		// Die Version des Plugins:
		$this->version = "2.0.0";

		// Der Autor des Plugins:
		$this->autor = "MRH";

		// Die Homepage des Autors:
		$this->homepage = "http://www.mrh-development.de";

		// Notizen zu dem Plugin:
		$this->notes = "Regelt alle für das Standard Design relevanten Funktionen";

	}



	function onEnable()
	{
	$hp = $this->hp;



	}


	function OnSiteCreated()
	{

	}


	function onLoad()
	{

	}

}
?>