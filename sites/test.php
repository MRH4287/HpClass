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

?>
<a href="index.php?lbsite=delnews&vars=4" class="lbOn">Test</a>

<?

//$lang->savetodb();



?>

