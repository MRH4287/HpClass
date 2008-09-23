<?php
session_start();

//Includes
include 'include/config.php';
include 'include/class.php';
include 'include/class_lang.php';
include 'include/class_template.php';
include 'include/class_error.php';
include 'include/class_info.php';
include 'include/functions.php';

//$_SESSION = array ();



$hp = new HP;
$lang = new lang;
$temp = new template;
$error = new errorclass;
$info = new infoclass;



$lang->init("de");
$hp->setlang($lang);
$hp->seterror($error);
$hp->setinfo($info);
$lang->seterror($error);
$temp->seterror($error);
$temp->sethp($hp);
$temp->setlang($lang);
$error->sethp($hp);

$info->init($lang, $error, $hp);



$hp->handelinput($_GET, $_POST);


$hp->setdata($dbserver, $dbuser, $dbpass, $dbpräfix, $dbdatenbank);
$hp->connect();

$right = $hp->getright();
$level = $_SESSION['level'];

//Includes die HP benötigen
include 'include/template.php';
include 'include/login.php';
include 'include/modulscheck.php';

$get = $hp->get();
$post = $hp->post();


$temp->settemplate($template);


$temp->load($design);




echo $temp->gettemp('header');


$hp->inc();


echo $temp->gettemp('footer');






$info->getmessages();
$error->showerrors();
?>
