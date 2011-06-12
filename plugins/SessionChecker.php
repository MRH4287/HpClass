<?php
class SessionChecker extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "Session-Check";
  
  // Die Version des Plugins:
  $this->version = "2.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  //Die Homepage des Autors:
  $this->homepage = "http://mrh.hes-technic.de";
  
  //Notizen zu dem Plugin:
  $this->notes = "Dieses System überprüft, ob die Dauer einer Session abgelaufen ist.";
  
    
  //------------------------------------------------
   
}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{

  if (isset($_SESSION["username"]))
  {
    if (!isset($_SESSION["lastuse"]))
    {
    
      $_SESSION["lastuse"] = time();
    
    }
    
    if (!isset($_SESSION["token"]))
    {
      $_SESSION["token"] =  sha1($_SESSION["username"]."-". $_SERVER['REMOTE_ADDR'] . $this->hp->config->get("design") );
    }
    $token = sha1($_SESSION["username"]."-". $_SERVER['REMOTE_ADDR'] . $this->hp->config->get("design") );
       
    
    $diff = (time() - $_SESSION["lastuse"]);
    
    if (($_SESSION["token"] != $token) || ($diff > 7200))
    {
      session_destroy();
      $this->hp->error->error("Ihrer Session ist abgelaufen. Bitte loggen Sie sich neu ein");
      
    }
  
    $_SESSION["lastuse"] = time();
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