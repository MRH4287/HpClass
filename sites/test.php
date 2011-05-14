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


//$site = new siteTemplate($hp);
//$site->load("news");
//echo "<pre>";
//print_r($site->getPlaceholder($site->getNode("LbSite-Edit", array())));
//echo "</pre>";


echo "Ist Level 1 Höher als 0: ".(($right->isAllowed("0","1")) ? "JA" : "Nein")."<br>";
echo "Ist Level 2 Höher als 0: ".(($right->isAllowed("0","2")) ? "JA" : "Nein")."<br>";
echo "Ist Level 1 Höher als 2: ".(($right->isAllowed("2","1")) ? "JA" : "Nein")."<br>";
echo "Ist Level 4 Höher als 3: ".(($right->isAllowed("3","4")) ? "JA" : "Nein")."<br>";


?>


