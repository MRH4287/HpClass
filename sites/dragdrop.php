<?php

// Site Config:
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();


$site = new siteTemplate($hp);
$site->load("dragdrop");
$site->right("superadmin");

$site->display();


?>
 