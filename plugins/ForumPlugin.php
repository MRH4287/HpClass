<?php
class ForumPlugin extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);
  
  // Plugin Config:
  // -----------------------------------------------
  
  // Der Name des Plugins:
  $this->name = "Forum";
  
  // Die Version des Plugins:
  $this->version = "1.0.0";
  
  // Der Autor des Plugins:
  $this->autor = "MRH";
  
  //Die Homepage des Autors:
  $this->homepage = "mrh.hes-technic.de";
  
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
   $right = $this->hp->right;
   
   $rights = array(
    array (
      
      "name" => "forum_nopassword",
      "desc" => "Forum: Darf Themen betrachten ohne das Passwort eizugeben",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_edit_post",
      "desc" => "Forum: Darf Threads und Posts bearbeiten die nicht ihm gehören",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_canusetypes",
      "desc" => "Forum: Darf die Thread Typen verwenden: (Sticky, Announce)",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_canclosethread",
      "desc" => "Forum: Darf Threads schließen. (<b>Keiner</b> kann schreiben)",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_edit_forum",
      "desc" => "Forum: Darf Foren bearbeiten.",
      "cat"  => "Forum"
      ),
        array (
      
      "name" => "forum_del_forum",
      "desc" => "Forum: Darf komplette Foren löschen.",
      "cat"  => "Forum"
      )
   
   );
   
   $right->registerArray($rights);
   


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