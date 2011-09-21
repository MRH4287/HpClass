<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$lbs = $hp->lbsites;
$fp = $hp->fp;
$right = $hp->right;
$subpages = $hp->subpages;

$site = new siteTemplate($hp);
$site->load("login");

$site->set("username", "test");


$site->display("Links");







?>


