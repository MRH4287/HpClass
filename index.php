<?php
ob_start();
session_start();

//------------------------------Zeitmessung-------------------------------------
$startzeit = explode(" ", microtime());
$startzeit = $startzeit[0]+$startzeit[1];
//------------------------------------------------------------------------------


//------------------------Einbinden der Include Dateien-------------------------
require_once 'include/config.php';
require_once 'include/class.php';
require_once 'include/class_lang.php';
require_once 'include/class_template.php';
require_once 'include/class_error.php';
require_once 'include/class_info.php';
require_once 'include/class_res.php';
require_once 'include/class_game.php';
require_once 'include/class_skill.php';
require_once 'include/class_tech.php';
require_once 'include/class_lbsites.php';
require_once 'include/xajax_core/xajax.inc.php';
require_once 'include/FirePHP.class.php';
require_once 'include/class_xajax_funk.php';
//------------------------------------------------------------------------------

//---------------------------Definieren der Klassen-----------------------------
$hp = new HP;
$xajaxF = new Xajax_Funktions;
$lang = new lang;
$temp = new template;
$error = new errorclass;
$info = new infoclass;
$res = new res;
$game = new game;
$lbsites = new lbsites;
$skill = new skill;
$tech = new tech;
//------------------------------------------------------------------------------

//------------------------------Variablenübergabe-------------------------------
$lang->init("de");
$hp->setlang($lang);
$hp->seterror($error);
$hp->setfirephp($firephp);
$lang->seterror($error);
$temp->seterror($error);
$temp->sethp($hp);
$temp->setlang($lang);
$error->sethp($hp);
$res->sethp($hp);
$res->setlang($lang);
$res->seterror($error);
$res->setinfo($info);
$game->sethp($hp);
$game->setlang($lang);
$game->seterror($error);
$game->setinfo($info);
$skill->sethp($hp);
$tech->sethp($hp);
$hp->setinfo($info);
$hp->setres($res);
$hp->setgame($game);
$hp->skill = $skill;
$hp->tech = $tech;
$xajaxF->sethp($hp);
$lbsites->sethp($hp);
$hp->setlbsites($lbsites);
//------------------------------------------------------------------------------

//----------------------------------MYSQL---------------------------------------
$hp->setdata($dbserver, $dbuser, $dbpass, $dbpräfix, $dbdatenbank);
$hp->connect();
//------------------------------------------------------------------------------

//------------------------- Class Inits und Handlers----------------------------
$info->init($lang, $error, $hp);
$hp->handelinput($_GET, $_POST);

//-------------------Handler/ Inits, die MYSQL zugriff benötigen----------------
$res->checkres();
$right = $hp->getright();
$config = $hp->getconfig();
$hp->handelconfig();
$game->handleconfig();
// ---------------------------------Misc----------------------------------------
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();

//--------------------------------Xajax Request---------------------------------
$xajaxF->processRequest();


//----------------------------------Includes------------------------------------
//@include 'include/iesperre.php';        // Verhindert, dass ein IE bassierter Browser auf die Seite kommt (Fehlermeldung)
require_once 'include/template.php';    // Templatevariablen
require_once 'include/login.php';       // Login Bereich (wird später als Variable eingebunden)
require_once 'include/modulscheck.php'; // Überprüft die Module (autoruns etc.)
//------------------------------------------------------------------------------

//---------------------------------Template-------------------------------------
$temp->settemplate($template);
$temp->load($design);
// Ausgeben der Error / Info Platzhalter
$error->outputdiv();
$info->outputdiv();

echo $temp->gettemp('header');
//------- Einbinden der Seite-----------
$hp->inc();
//--------------------------------------

echo $temp->gettemp('footer');
//------------------------------------------------------------------------------

//----------------------------------XAJAX---------------------------------------
echo $xajaxF->printjs();
// Ausführen der JS Funktionen
include 'js/xajax.php';
//------------------------------------------------------------------------------

//------------------------------Zeitmessung-------------------------------------
include "include/zeitmessung.php";

//--------------------------Error / Info Handler--------------------------------
$info->getmessages();
$error->showerrors();


ob_end_flush();
?>