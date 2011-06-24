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

//$site = new siteTemplate($hp);
//$site->load("news");
//echo "<pre>";
//print_r($site->getPlaceholder($site->getNode("LbSite-Edit", array())));
//echo "</pre>";

$data = $subpages->getEvents("24.06.2011");
print_r($data);


?>


