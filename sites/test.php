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



echo "<b>Dies ist eine Testseite!</b><br>Solltet ihr sie finden, ignoriert sie ^^<br><br><hr>";

//print_r($_SESSION);

$error->error("TEST", "2");
$info->info("123");
$info->okn("123");


$sql = "SHOW TABLES LIKE '$dbpräfix"."langsam';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_array($erg);
print_r($row);

//$lang->savetodb();



?>

