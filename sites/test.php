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


echo "<b>Dies ist eine Testseite!</b><br>Solltet ihr sie finden, ignoriert sie ^^<br><br><hr>";

//print_r($_SESSION);

$error->error("TEST", "2");
$info->info("123");
$info->okn("123");

echo $lbs->link("newnews", "Newsmeldung ändern", "1");
echo "<br>"; 
$password_length = 5;
$generated_password = "";
$valid_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$i = 0;

for ($a = 0; $a < 5; $a++) {
 $chars_length = strlen($valid_characters) - 1;
 for($i = $password_length; $i--; ) {
  $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
 }
	if ($a != 4)
	{
  $generated_password .= "-";
  }
	
}
 
 echo $generated_password;

echo "<br><br>";
echo time();

//$lang->savetodb();



?>

