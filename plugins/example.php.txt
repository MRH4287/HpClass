<?php
class test extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "Test Plugin";
  
  // Die Version des Plugins:
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  //Die Homepage des Autors:
  $this->homepage = "mrh.hes-technic.de";
  
    
  //------------------------------------------------
   
}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{
  $this->hp->info->okn("Plugin $this->name erfolgreich aktiviert!");
}


/*

Hier werden die eigentlichen Aufgaben des Plugins erledigt.
Wie zum Beispiel das hinzufügen von Weiterleitungen.

*/
function onLoad()
{
  $this->hp->info->okn("Plugin $this->name erfolgreich geladen!");

}




}



?>