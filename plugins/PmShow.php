<?php
class PmShow extends Plugin
{

  
  function __construct($hp, $loader)
  {
    // Laden der Daten
    // Nicht Editieren!
    parent::__construct($hp, $loader);
    
    // Plugin Config:
    // -----------------------------------------------
    
    // Der Name des Plugins:
    $this->name = "PM - Show";
    
    // Die Version des Plugins:
    $this->version = "1.0.0";
    
    // Der Autor des Plugins:
    $this->autor = "MRH";
    
    //Die Homepage des Autors:
    $this->homepage = "http://mrh-development.de";
    
    //Notizen zu dem Plugin:
    $this->notes = "Zeigt eine Info Zeile an, wenn der Benutzer eine ungelesene PM hat.";
    
      
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
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
      if (isset($_SESSION['username']))
      {
        $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `zu` = '".$_SESSION['username']."' AND `gelesen` = '0';";
    
        $erg = $hp->mysqlquery($abfrage);
          if (mysql_num_rows($erg) >= 1)
          {
            $info->info("<a href=index.php?site=pm>Sie haben ungelesene Pm`s!</a>");
          }
       }
  }
  

}



?>