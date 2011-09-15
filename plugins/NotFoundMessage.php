<?php
class NotFoundMessage extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "Talking 404-Error";
  
  // Die Version des Plugins:
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  //Die Homepage des Autors:
  $this->homepage = "http://mrh-development.de";
  
  //Notizen zu dem Plugin:
  $this->notes = "Dieses Plugin hat eine Reihe von Sprüchen, die kommen,<br>wenn ein 404 Fehler aufgerufen wurde.<br>".
  "Dieses Plugin ist mit vielen Designs nicht Kompatibel!";
  
    
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
  $this->hp->addredirect("404", "plugins/NotFoundMessage");
}




}



?>