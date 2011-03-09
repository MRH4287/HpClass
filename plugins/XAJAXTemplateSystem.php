<?php
class XAJAXTemplateSystem extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "XAJAX - Template Steuerung";
  
  // Die Version des Plugins:
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  // Die Homepage des Autors:
  $this->homepage = "http://mrh.hes-technic.de";
  
  // Notizen zu dem Plugin:
  $this->notes = "Ermöglicht Template bedingtes Laden von dynamischen Inhalten";
  
  // Systemrelevant
  $this->isEnabled = true;
  $this->lock = true;
      
  //------------------------------------------------
   
}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $config = $hp->getconfig();
  $error = $hp->error;
  $fp = $hp->fp;

  if (is_object($hp->xajaxF))
  {
    $hp->xajaxF->extend($config['design']);
  }

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