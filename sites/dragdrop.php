<?php

// Site Config:
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();


$site = new siteTemplate($hp);
$site->load("dragdrop");

$site->display();


?>
 