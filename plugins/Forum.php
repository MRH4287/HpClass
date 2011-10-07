<?php
class Forum extends Plugin
{

  
  function __construct($hp, $loader)
  {
    // Laden der Daten
    // Nicht Editieren!
    parent::__construct($hp, $loader);
    
    // Plugin Config:
    // -----------------------------------------------
    
    // Der Name des Plugins:
    $this->name = "Foren System";
    
    // Die Version des Plugins:
    $this->version = "Beta 1";
    
    // Der Autor des Plugins:
    $this->autor = "MRH";
    
    //Die Homepage des Autors:
    $this->homepage = "http://mrh-development.de";
    
    //Notizen zu dem Plugin:
    $this->notes = "Foren System";
  }
  
  
  /*
  
  Lade alle für das System relevanten Daten.
  
  z.B. Datenbank Aufrufe, Datei Aufrufe, etc.
  
  */
  function onEnable()
  {
    

    $this->hp->addredirect("forum", "plugins/Forum");
  }
  
  
  /*
  
  Hier werden die eigentlichen Aufgaben des Plugins erledigt.
  Wie zum Beispiel das hinzufügen von Weiterleitungen.
  
  */
  function onLoad()
  {
    
    pluginTemplate::extend($this);
    
  }
  
  
  
  /*
  
    Diese Funktion läd alle für eine unterseite relevanten Daten
  
  */
  public function temp_ForumLoadContent($args, $context)
  {
    
     $functions = get_class_methods($this);
     
     $site = 'site_'.$args[0];
     
     if (in_array($site, $functions))
     {
        $this->$site($args, $context);
     }
     
    // Always return an empty string
    return '';
  
  
  }
  
  // --------------------   Context für Unterseiten  ---------------------------
  
  
 
  public function site_test($args, $context)
  {
    
    $s = '';
    foreach ($context->getVars() as $k=>$v)
    {
      $s .= "[$k] => $v, ";
    }
    
    
    $context->set('test', $s);  
  
  } 
  
  
  
  
  

}



?>