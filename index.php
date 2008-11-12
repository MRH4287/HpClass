<?php
session_start();
ob_start();

//Includes
require_once 'include/config.php';
require_once 'include/class.php';
require_once 'include/class_lang.php';
require_once 'include/class_template.php';
require_once 'include/class_error.php';
require_once 'include/class_info.php';
require_once 'include/FirePHP.class.php';


$hp = new HP;
$lang = new lang;
$temp = new template;
$error = new errorclass;
$info = new infoclass;



$lang->init("de");
$hp->setlang($lang);
$hp->seterror($error);
$hp->setinfo($info);
$hp->setfirephp($firephp);
$lang->seterror($error);
$temp->seterror($error);
$temp->sethp($hp);
$temp->setlang($lang);
$error->sethp($hp);

$info->init($lang, $error, $hp);

$hp->setdata($dbserver, $dbuser, $dbpass, $dbpräfix, $dbdatenbank);
$hp->connect();

$hp->handelinput($_GET, $_POST);


$right = $hp->getright();
$config = $hp->getconfig();
$hp->handelconfig();

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
ob_end_flush();
?>
