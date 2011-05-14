<?php

// In dieser Datei sind alle Rechte, die vom System benutzt werden gespeichert:
$levels = array(
          array("0", null), 
          array("1", "0"), 
          array("2", "1"), 
          array("3", "2"), 
          array("4", "3")
        );


$registed = array(

  array (
  
  "name" => "adminsite",
  "desc" => "Erlaubnis zum Betreten der Administrationsseite",
  "cat"  => "System"
  ),
  
  array (
  
  "name" => "newswrite",
  "desc" => "Erlaubnis zum Newsmeldungen schreiben",
  "cat"  => "System"
  ),
   
  array (
  
  "name" => "newsdel",
  "desc" => "Erlaubnis um Newsmeldungen zu löschen",
  "cat"  => "System"
  ),
  array (
  
  "name" => "newsedit",
  "desc" => "Erlaubnis um Newsmeldungen zu bearbeiten",
  "cat"  => "System"
  ),
  array (
  
  "name" => "readl1",
  "desc" => "Erlaubnis um Newsmeldungen des Level 1 zu lesen",
  "cat"  => "System"
  ),
  array (
  
  "name" => "readl2",
  "desc" => "Erlaubnis um Newsmeldungen des Level 2 zu lesen",
  "cat"  => "System"
  ),
  array (
  
  "name" => "readl3",
  "desc" => "Erlaubnis um Newsmeldungen des Level 3 zu lesen",
  "cat"  => "System"
  ),
  array (
  
  "name" => "manage_vote",
  "desc" => "Das Rechte Umfragen zu Managen",
  "cat"  => "System"
  ),
  array (
  
  "name" => "see_email",
  "desc" => "Das Recht die E-Mail Adresse in der user Seite einzusehen",
  "cat"  => "System"
  ),
  array(
  
  "name" => "manage_subpage",
  "desc" => "Das Recht Unterseiten zu bearbeiten",
  "cat"  => "System"
  
  ),
  array (
  
  "name" => "upload",
  "desc" => "Das Recht Daten hochzuladen",
  "cat"  => "Download"
  ),
  array (
  
  "name" => "upload_del",
  "desc" => "Das Recht Daten zu löschen",
  "cat"  => "Download"
  ),
  array (
  
  "name" => "useragree",
  "desc" => "Das Recht einen Benutzer anzunehmen",
  "cat"  => "Kritisch"
  ),
  array (
  
  "name" => "userdisagree",
  "desc" => "Erlaubnis um ein Anwärter abzulehnen",
  "cat"  => "Kritisch"
  ),
  array (
  
  "name" => "userchangelevel",
  "desc" => "Erlaubnis um das Level eines Benutzers zu ändern",
  "cat"  => "Kritisch"
  ),
  array (
  
  "name" => "userdelet",
  "desc" => "Erlaubnis um einen User zu löschen",
  "cat"  => "Kritisch"
  )
    

);

/*

,
  array (
  
  "name" => "",
  "desc" => "",
  "cat"  => ""
  ),


*/


?>