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
    "desc"      => "Seiten, die von der Weiterleitung ausgenommen sind",
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
  ),
  
  array(
    "name"      => "user_mailagree",
    "desc"      => "Sollen sich Benutzer selbst über E-Mail freischalten können",
    "cat"       => "System",
    "type"      => "bool",
    "default"   => true 
  ), 
  
  array(
    "name"      => "download_useDB",
    "desc"      => "Sollen die Download-Daten in die Datenbank gespeichert werden (Max: 3MB)",
    "cat"       => "System",
    "type"      => "bool",
    "default"   => false 
  )

);



?>