<?php
session_start();
ob_start();

//----------------------------Eibinden der Include Dateien!-------------------
require_once 'include/checkinstall.php';
require_once 'include/config.php';
require_once 'include/class.php';
require_once 'include/class_lang.php';
require_once 'include/class_template.php';
require_once 'include/class_error.php';
require_once 'include/class_info.php';
require_once 'include/class_lbsites.php';
require_once 'include/xajax_core/xajax.inc.php';
require_once 'include/FirePHP.class.php';
require_once 'include/class_xajax_funk.php';
require_once 'include/class_forum.php';
require_once 'include/class_widgets.php';
//----------------------------------------------------------------------------

//--------------------------------------Class Area----------------------------
$hp         = new HP;
$lang       = new lang;
$temp       = new template;
$error      = new errorclass;
$info       = new infoclass;
$lbsites    = new lbsites;
$xajaxF     = new Xajax_Funktions;
$forum      = new forum;
$widgets    = new widgets;
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
$xajaxF->sethp($hp);
$lbsites->sethp($hp);
$hp->setlbsites($lbsites);
$hp->setxajaxF($xajaxF);
$forum->sethp($hp);
$hp->setforum($forum);
$widgets->sethp($hp);
$hp->setwidgets($widgets);
// ----------------------------------------------------------------------------

//-----------------------------------MYSQL Area (DB Verbindung)----------------
$hp->setdata($dbserver, $dbuser, $dbpass, $dbpräfix, $dbdatenbank);
$hp->connect();
//-----------------------------------------------------------------------------

//-----------------------------lang Config-------------------------------------
$lang->init("de");
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

//----------------------------Sonstiges----------------------------------------
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();

//--------------------------------Xajax Request--------------------------------
$xajaxF->processRequest();


//--------------------------------Widgets---------------------------------------
 $widgets->replace();


//------------------------------Template Steuerung-----------------------------
// 1. Inportieren der Template Dateien
include 'include/template.php';
include 'include/login.php';


// 2. Übergeben des Template Arrays am die Funktion
$temp->settemplate($template);

//-----------Widgets----------
 $widgets->addtotemp();



// 3. Laden der HTML Datei
$error->outputdiv();
$info->outputdiv();
$temp->load($design);

// 4. Ausgeben des Headers
echo $temp->gettemp('header');
// 5. Einbinden der PHP Datei
$hp->inc();
// 6. Ausgeben des Footers
echo $temp->gettemp('footer');
//-----------------------------------------------------------------------------

//----------------------------------XAJAX---------------------------------------
echo $xajaxF->printjs();
// Ausführen der JS Funktionen
include 'js/xajax.php';
//------------------------------------------------------------------------------


//----------------------------Info und Error Handling--------------------------
$info->getmessages();
$error->showerrors();
//-----------------------------------------------------------------------------


ob_end_flush();
?>
<!--PHP System by MRH-->