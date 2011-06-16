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
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  // Die Homepage des Autors:
  $this->homepage = "http://mrh.hes-technic.de";
  
  // Notizen zu dem Plugin:
  $this->notes = "Regelt alle für das Standard Design relevanten Funktionen";
  
      
  //------------------------------------------------
   
}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{
$hp = $this->hp;


// Binde XAJAX Daten ein:

  if (is_object($hp->xajaxF))
  {
    $hp->xajaxF->registerFunctions($this);
  }

}


/*

Hier werden die eigentlichen Aufgaben des Plugins erledigt.
Wie zum Beispiel das hinzufügen von Weiterleitungen.

*/
function onLoad()
{

}

// ---------------- XAJAX -------------------

function ax_test2($a)
{
$response = new xajaxResponse();

$response->alert($a);

return $response;
}





}
?>