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
require_once 'include/class_right.php';
require_once 'include/class_config.php';
require_once 'include/class_widgets.php';
require_once 'include/class_subpages.php';
require_once 'include/class_pluginloader.php';
require_once 'include/base/SiteTemplate.php';
require_once 'include/base/subpageTemplate.php';
require_once 'include/base/pluginTemplate.php';

//----------------------------------------------------------------------------

//--------------------------------------Class Area----------------------------
$hp             = new HP;
$lang           = new lang;
$temp           = new template($hp);
$error          = new errorclass;
$info           = new infoclass;
$lbsites        = new lbsites;
$xajaxF         = new Xajax_Funktions;
$right          = new right;
$config         = new config;
$widgets        = new widgets;
$subpages       = new subpages;
$pluginloader   = new PluginLoader;
// ----------------------------------------------------------------------------

//-----------------------------------MYSQL Area (DB Verbindung)----------------
$hp->setdata($dbserver, $dbuser, $dbpass, $dbprefix, $dbdatenbank);
$hp->connect();
//-----------------------------------------------------------------------------


//--------------------------------------SET Area-------------------------------
$config->sethp($hp);
$hp->setconfig($config);
$right->sethp($hp);
$hp->setright($right);
$hp->setlang($lang);
$hp->seterror($error);
$hp->setinfo($info);
$hp->setfirephp($firephp);
$hp->settemplate($temp);
$lang->seterror($error);
$lang->sethp($hp);
$error->sethp($hp);
$info->init($lang, $error, $hp);
$xajaxF->sethp($hp);
$lbsites->sethp($hp);
$hp->setlbsites($lbsites);
$hp->setxajaxF($xajaxF);
$widgets->sethp($hp);
$hp->setwidgets($widgets);
$subpages->sethp($hp);
$hp->setsubpages($subpages);
$pluginloader->sethp($hp);
$hp->setpluginloader($pluginloader);
// ----------------------------------------------------------------------------

//------------------------------Plugin System----------------------------------
$pluginloader->Init();


//-----------------------------lang Config-------------------------------------
$lang->init("de");
//-----------------------------------------------------------------------------


//-----------------------------Input / Config Handling-------------------------
$hp->handelinput($_GET, $_POST);
$hp->handelconfig();
//-----------------------------------------------------------------------------

//-----------------------------right / config----------------------------------
$right = $hp->getright();
$config = $hp->getconfig();
//-----------------------------------------------------------------------------


//------------------------------Plugin System----------------------------------
$pluginloader->Load();

//----------------------------Sonstiges----------------------------------------
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();

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
 $temp->load($design);
 
//--------------------------------Xajax Request--------------------------------
$xajaxF->processRequest();


// 4. Ausgeben des Headers
echo $temp->gettemp('header');
// 5. Einbinden der PHP Datei
$hp->inc();
// 6. Ausgeben des Footers
echo $temp->gettemp('footer');
//-----------------------------------------------------------------------------


//------------------------------------------------------------------------------


//----------------------------------XAJAX---------------------------------------
echo $xajaxF->printjs();
// Ausführen der JS Funktionen
include 'js/xajax.php';

//----------------------------Info und Error Handling--------------------------
$info->getmessages();
$error->showerrors();
//-----------------------------------------------------------------------------


$pluginloader->Save();

ob_end_flush();
?>
<!--PHP System by MRH-->