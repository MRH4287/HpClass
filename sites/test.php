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
$site->load("news");

$site->set("WriteNews", "NW");

$data = array("Titel" => "Das System funktioniert!", 
"Level" => "(Level 1)", "ersteller" => "mrh", "datum" => "heute", "Content" => "Es geht!");
$news = $site->getNode("News", $data);
$site->set("News", $news);




$site->display();



?>


