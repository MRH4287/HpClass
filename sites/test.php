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


$site = new siteTemplate($hp);
$site->load("profil");
echo "<pre>";
print_r($site->getPlaceholder($site->getNode("Main", array())));
echo "</pre>";




?>


