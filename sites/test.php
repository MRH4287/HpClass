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
$info = $hp->getinfo();
$lbs = $hp->lbsites;
$fp = $hp->fp;
$right = $hp->right;
$subpages = $hp->subpages;

$site = new siteTemplate($hp);
$site->load("email");

$site->set("HTTP_HOST", $_SERVER['HTTP_HOST']);
$site->set("PHP_SELF", $_SERVER['PHP_SELF']);

$site->get();
$data = $site->getVars();

$site->get("LostPW");
$data = array_merge($data, $site->getVars());


echo "<pre>";
var_dump($data);
echo "</pre>";





?>


