<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();
$lang = $hp->getlangclass();
$error = $hp->geterror();


    $site = new siteTemplate($hp);
    $site->right("adminsite");
    $site->load("admin");
    $site->display(); 
    

  
?>