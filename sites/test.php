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
$info = $hp->getinfo();
$lbs = $hp->lbsites;
$fp = $hp->fp;


$site = new siteTemplate($hp);
$site->load("user_show");
echo "<pre>";
print_r($site->getPlaceholder($site->getNode("LevelChange", array())));
echo "</pre>";




?>


