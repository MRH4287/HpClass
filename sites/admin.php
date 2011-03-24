<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();


// Prüfen der Zugriffsberechtigung
  if (!isset($_SESSION['username']))
  {
    $error->error($lang->word('noright'), "2");
    $error->error($lang->word('noright'), "1");
  } elseif (!$right[$level]['adminsite'])
  {
    $error->error($lang->word('noright2'));
  } 
  else
  {
    $site = new siteTemplate($hp);
    $site->load("admin");
    $site->display(); 
    
  }
  
?>