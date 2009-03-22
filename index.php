<?php
session_start();
ob_start();

//----------------------------Eibinden der Include Dateien!-------------------
require_once 'include/config.php';
require_once 'include/class.php';
require_once 'include/class_lang.php';
require_once 'include/class_template.php';
require_once 'include/class_error.php';
require_once 'include/class_info.php';
require_once 'include/FirePHP.class.php';
//----------------------------------------------------------------------------

//--------------------------------------Class Area----------------------------
$hp = new HP;
$lang = new lang;
$temp = new template;
$error = new errorclass;
$info = new infoclass;
// ----------------------------------------------------------------------------

//--------------------------------------SET Area-------------------------------
$hp->setlang($lang);
$hp->seterror($error);
$hp->setinfo($info);
$hp->setfirephp($firephp);
$hp->settemplate($temp);
$lang->seterror($error);
$lang->sethp($hp);
$temp->seterror($error);
$temp->sethp($hp);
$temp->setlang($lang);
$error->sethp($hp);
$info->init($lang, $error, $hp);
// ----------------------------------------------------------------------------

//-----------------------------lang Config-------------------------------------
$lang->settempfolder($design); // Benötigt wegen der möglichkeit, dass Sprachdateien im template Ordner liegen
$lang->init("de");
//-----------------------------------------------------------------------------


//-----------------------------------MYSQL Area (DB Verbindung)----------------
$hp->setdata($dbserver, $dbuser, $dbpass, $dbpräfix, $dbdatenbank);
$hp->connect();
//-----------------------------------------------------------------------------

//-----------------------------Input / Cofig Handling--------------------------
$hp->handelinput($_GET, $_POST);
$hp->handelconfig();
//-----------------------------------------------------------------------------

//-----------------------------right / config----------------------------------
$right = $hp->getright();
$config = $hp->getconfig();
//-----------------------------------------------------------------------------


//-------------------------------Module----------------------------------------
include 'include/modulscheck.php';
//-----------------------------------------------------------------------------

// Sonstigel
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();


//------------------------------Template Steuerung-----------------------------
// 1. Inportieren der Template Dateien
include 'include/template.php';
include 'include/login.php';


// 2. Übergeben des Template Arrays am die Funktion
$temp->settemplate($template);
// 3. Laden der HTML Datei
$temp->load($design);

// 4. Ausgeben des Headers
echo $temp->gettemp('header');
// 5. Einbinden der PHP Datei
$hp->inc();
// 6. Ausgeben des Footers
echo $temp->gettemp('footer');
//-----------------------------------------------------------------------------

//----------------------------Info und Error Handling--------------------------
$info->getmessages();
$error->showerrors();
//-----------------------------------------------------------------------------


ob_end_flush();
?>
