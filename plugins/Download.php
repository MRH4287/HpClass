<?php
class Download extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "Download - Area Extention";
  
  // Die Version des Plugins:
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  //Die Homepage des Autors:
  $this->homepage = "http://mrh.hes-technic.de";
  
  //Notizen zu dem Plugin:
  $this->notes = "Erweitert den Download Bereich um Kategorien";
  
    
  //------------------------------------------------
   
}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{

   
}


/*

Hier werden die eigentlichen Aufgaben des Plugins erledigt.
Wie zum Beispiel das hinzufügen von Weiterleitungen.

*/
function onLoad()
{
$this->hp->addredirect("download", "plugins/Download");
$this->hp->addredirect("download2", "plugins/Download");
$this->hp->addredirect("upload", "plugins/Download");
}




}



?>