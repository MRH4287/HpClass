<?php
// In diese Datei stehen NICHT die Mysql-Daten!!

$configData = array(
  array(
    "name"      => "design",
    "desc"      => "Aktuelles Design",
    "cat"       => "System",
    "type"      => "design",
    "default"   => "default" 
  ), 
   
  array(
    "name"      => "titel",
    "desc"      => "Der Webseiten Titel",
    "cat"       => "System",
    "type"      => "string",
    "default"   => "HPClass Demo" 
  ),
   
  array(
    "name"      => "redirectlock",
    "desc"      => "Seiten, die von der Weiterleitunge ausgenommen sind",
    "cat"       => "Security",
    "type"      => "string",
    "default"   => "admin, config, right" 
  ),
  
  array(
    "name"      => "superadmin",
    "desc"      => "Definiert Superadmins, neben admin (Mit \", \" trennen)",
    "cat"       => "System",
    "type"      => "string",
    "default"   => "" 
  ),
  
  array(
    "name"      => "standardsite",
    "desc"      => "Definiert die Standard Seite",
    "cat"       => "System",
    "type"      => "string",
    "default"   => "news" 
  )

);



?>