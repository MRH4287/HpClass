<?php
class Xajax_Funktions
{
var $hp;

var $ajaxFuncPrefix = 'ax_';

var $xajax;
var $func = array();
var $call = array();

function __construct()
{               
         $this->xajax = new xajax();
         $this->registerFunctions($this);
         
         //$this->xajax->configure( 'debug', true );

}

function sethp($hp)
{
$this->hp = $hp;
}

 public function registerFunctions($object) {
    		$methods = get_class_methods($object);
    		
    		foreach ($methods as $m) {
			$p = $this->ajaxFuncPrefix;
    			if (preg_match("/^{$p}[a-z]/", $m)) {
    				$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);
    				$this->xajax->register(XAJAX_FUNCTION, array($m2, $object, $m));
    			}
    		}
    }



function printjs()
{
$this->xajax->configure('javascript URI','include/');
$this->xajax->printJavascript("include/");
}

function processRequest()
{
$this->xajax->processRequest();
}





function ax_calender_vote($month = "X", $jahr = "X")
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->firephp;
$lb = $hp->lbsites;
$config = $hp->getconfig();
$response = new xajaxResponse();


$arr_monate = array ('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
$date = getdate();


$tag = $date['mday'];
$monat2 = $date['mon'];


if ($month != "X")
{
$monat = $month;
$tag = 0;
} else
{
$monat = $monat2;
}
if ($jahr == "X")
{
$jahr = $date['year'];
}




$style = 4;


$text = '<link href="./css/style'.$style.'.css" rel="stylesheet">';

            $iWochenTag  = date("w", mktime(0, 0, 0, $monat, 1, $jahr));
            $iAnzahltage = date("t", mktime(0, 0, 0, $monat, 1, $jahr));
            $iZeilen = ($iWochenTag==1 && $iAnzahltage==28) ? 4 : (($iAnzahltage == 31 && ($iWochenTag == 6 || $iWochenTag == 0))|| ($iWochenTag  == 0 && $iAnzahltage == 30)) ? 6 : 5; 


            
                        //Nächster Monat
            if($monat==12){
                $nmonat=1;
                $njahr=$jahr+1;
            } else {
                $nmonat=$monat+1;
                $njahr=$jahr;  
            }

            //Vorheriger Monat    
            if($monat==1){
                $vmonat=12;
                $vjahr=$jahr-1;
            } else {
                $vmonat=$monat-1;
                $vjahr=$jahr;  
            }

            $iAnzahltageVormonat = date("t", mktime(0, 0, 0, $vmonat, 1, $vjahr));
            
            if ($monat == 1) { $lnzjahr = $jahr - 1; $monlz = 12; } else { $lnzjahr = $jahr; $monlz = $monat-1; }
            if ($monat == 12) { $lnvjahr = $jahr + 1; $monlv = 1; } else { $lnvjahr = $jahr; $monlv = $monat+1; }
            
            $text .= '<table id="cal"><tr>'.
                  '<th>'.
                  '<a onclick="xajax_calender_vote('.$monlz.', '.$lnzjahr.'); return false;" href="#"><img src="images/arrowp.gif" width="9" height="11" alt="&gt;"></a>' . // Xajax regelung, dass der Kalender aktualisiert wird
                  '</th>'.
                  '<th colspan="5">'.htmlentities($arr_monate[$monat-1]).' '.$jahr.'</th>'.
                  '<th >'.
                  '<a onclick="xajax_calender_vote('.$monlv.', '.$lnvjahr.'); return false;" href="#"><img src="images/arrown.gif" width="9" height="11"></a>' .
                  '</th>'.
                  
                  '</tr>
                    <tr>
                      <th >Mo</th>
                      <th >Di</th>
                      <th >Mi</th>
                      <th >Do</th>
                      <th >Fr</th>
                      <th >Sa</th>
                      <th >So</th>
                    </tr>';
            
            
            
            $iTag = 0; //Tag im Monat
            $i=0;
            do{ // while($i < $iZeilen);
                $text=$text. '<tr>';
            
                $j=1;
                do { //while($j <= 7);
            

            
                    //Hilfsvariable Mo=1, Di=2 .... So=7
                    $m = ($iWochenTag==0) ? 7 :  $iWochenTag;

                    //Nicht jeder Monat beginnt am Monat
                    if($m == $j && $j <= 7 && $iTag == 0){
                        $iTag = 1;
                    }
                    
                    
                    $preTag = ($iAnzahltageVormonat+$j-$m+1);
                    
                    if ($iTag == 0){           // Tage vorMonat
                      $evt = false;
                      $text=$text. '<td ';

                        $text=$text. 'id="amonat">'; 
                     //  echo $m-1;
                     
                     $link = '<a href="#" onClick="setdate('.$preTag.', '.($monat -1).', '.$jahr.'); return false;">'.$preTag.'</a>';
                     
                      $text=$text. $link;
              
                      $text=$text. "</td>";
                    }
                       $ntmp = 0;
                    if ($iTag > $iAnzahltage){ // Tage des Nächsten Monats
                      ++$ntmp;
                    $evt = false;
                      $text=$text. '<td ';
 $text=$text. 'id="amonat">' ;     

                          $link = '<a href="#" onClick="setdate('.$ntmp.', '.($monat +1).', '.$jahr.'); return false;">'.$ntmp.'</a>';            
                      $text=$text. $link;
                      
                     // $m + 1
                      $text=$text. '</td>';
                    }
                    
                    if ($iTag != 0 && $iTag <= $iAnzahltage){ // Tage im Monat drinnen :D
                      $evt = false;
                      $text=$text. '<td ';
 $text=$text. 'id="monat">';    
                        
     $link = '<a href="#" onClick="setdate('.$iTag.', '.$monat.', '.$jahr.'); return false;">'.$iTag.'</a>';
                     
                    //if (($daytod == $iTag) and ($montod == $monat) and ($yeartod == $jahr))
                    // {
                    // $text .="<b>$link</b>";
                    // } else
                    // {                     
                     $text=$text. $link;
                    // }          
                      
                      
                                           
                     $text=$text. '</td>'."\n";
                      ++$iTag;
                    }

                    
                } while(++$j <= 7);
            
                $text=$text. '</tr>';
            
            } while(++$i < $iZeilen);
            $text=$text. '</table>';

$response->assign("kalender_vote", "innerHTML", "$text");


return $response;
}


function ax_checkvote($titel, $answer1, $answer2, $day, $month, $year, $hour, $min, $update)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$response = new xajaxResponse();

//$fp->log("$titel, $answer1, $answer2");
if (!$update)
{
$postok = '<input type="submit" name="addvote" value="Senden" />';
} else
{
$postok = '<input type="submit" name="editvote" value="Senden" />';
}


//$fp->log("$day.$month.$year $hour:$min");
$timestamp = mktime($hour, $min, 0, $month, $day, $year);

$titelo = false;
$answer  = false;
$date = false;

if ($titel != "")
{
// Kein Titel angegeben
$response->assign("titelwarn", "innerHTML", "");
$titelo = true;

}

if (($answer1 != "") and ($answer2 != ""))
{
// Nicht genügend Anwortmöglichkeiten

$response->assign("answerwarn", "innerHTML", "");
$answer = true;
}


if ($timestamp == false)
{
// Fehlerhafte eingabe!


$response->assign("datewarn", "innerHTML", "Fehlerhafte Eingabe");



} elseif (time() > $timestamp)
{
// liegt in der Vergangenheit
$response->assign("datewarn", "innerHTML", "Das Datum liegt in der Vergangenheit");


} else
{
// OK


$response->assign("datewarn", "innerHTML", "");
$date = true;
}

if ($titelo and $answer and $date)
{
$response->assign("post", "innerHTML", $postok);
}


return $response;
}
function ax_vote($ID, $vote)
{
$response = new xajaxResponse();
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


$sql = "SELECT * FROM `$dbpräfix"."vote` WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

if (!isset($row->ID) or ($vote==""))
{
// Fehler!


$ern = "<br><center><img src=images/alert2.gif><br>Fehler beim Eintragen</center><br>";
$response->assign("voteok$ID", "innerHTML", $ern);

} else
{



$erg = explode("<!--!>", $row->ergebnisse);
$erg[] = $vote;

$user =  explode("<!--!>", $row->voted);
$user[] = $_SESSION['ID'];


$ergebnisse = "";
foreach ($erg as $key=>$value) {
	if ($ergebnisse != "")
	{
  $ergebnisse .= "<!--!>".$value;
  } else
  {
  $ergebnisse = $value;
  }
}

$users = "";
foreach ($user as $key=>$value) {
	if ($users != "")
	{
  $users .= "<!--!>".$value;
  } else
  {
  $users = $value;
  }
}



$sql = "UPDATE `$dbpräfix"."vote` SET `ergebnisse` = '$ergebnisse', `voted` = '$users' WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);


$okn = "<br><center><img src=images/ok.gif><br>Erfolgreich abgestimmt</center><br>";
$response->assign("voteok$ID", "innerHTML", $okn);

}
return $response;
}

function ax_vote_result($ID)
{
$response = new xajaxResponse();
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$sql = "SELECT * FROM `$dbpräfix"."vote` WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$antworten = explode("<!--!>", $row->antworten);
$ergebnisse = explode("<!--!>", $row->ergebnisse);

$erg = array();
$count = 0;

foreach ($ergebnisse as $key=>$value) {
	if (!isset($erg[$value])) { $erg[$value] = 0; }
	$erg[$value] = $erg[$value] + 1; 
	$count++;
}


$string = '
<table width="100%" border="0">
      <tr>
        <td width="54%">&nbsp;</td>
        <td width="18%">&nbsp;</td>
        <td width="65">&nbsp;</td>
      </tr>
      ';

foreach ($antworten as $key=>$value) {
if (!isset($erg[$key])) { $erg[$key] = 0; }
$perc = ($erg[$key] / $count) * 100;
$perc = number_format($perc, 2);
$p = $perc / 100;
$perc = $perc. "%";
	
	$maxlength = 65;
	$w = $maxlength * $p;
	
$string .= "
      <tr>
        <td>".$value."</td>
        <td>$perc</td>
        <td width=\"65\"><div ><img src=\"images/balken.png\" width=\"$w\" height=\"6\"></div></td>
      </tr>";
      }

$string .= '</table><br><center>';
$string .= "Basierend auf $count Stimmen</center>";

$text = $string;
$text = str_replace("ü", "&uuml;", $text);
$text = str_replace("Ü", "&Uuml;", $text);
$text = str_replace("ö", "&ouml;", $text);
$text = str_replace("Ö", "&Ouml;", $text);
$text = str_replace("ä", "&auml;", $text);
$text = str_replace("Ä", "&Auml;", $text);
$text = str_replace("ß", "szlig;", $text);

$response->assign("ergebnisse$ID", "innerHTML", $text);

return $response;
}





function ax_forum_vote($ID, $vote)
{
$response = new xajaxResponse();
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$forum = $hp->forum;



$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



$erg = explode("<!--!>", $row->ergebnisse);
$erg[] = $vote;

$user =  explode("<!--!>", $row->voted);

if ((!in_array($_SESSION['ID'], $user)) and isset($_SESSION['ID']))
{

$user[] = $_SESSION['ID'];

$ergebnisse = "";
foreach ($erg as $key=>$value) {
	if ($ergebnisse != "")
	{
  $ergebnisse .= "<!--!>".$value;
  } else
  {
  $ergebnisse = $value;
  }
}

$users = "";
foreach ($user as $key=>$value) {
	if ($users != "")
	{
  $users .= "<!--!>".$value;
  } else
  {
  $users = $value;
  }
}

$okn = "<img src=images/ok.gif height=12 width=12>";
$response->assign("voteok", "innerHTML", $okn);

$sql = "UPDATE `$dbpräfix"."threads` SET `ergebnisse` = '$ergebnisse', `voted` = '$users' WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);

$response->assign("vote", "innerHTML", $forum->getvote($ID));

}
return $response;
}


function ax_picturelist_delElement($id)
{
$response = new xajaxResponse();

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$right = $hp->getright();
$level = $_SESSION['level'];


if ($right[$level]["usedpics"])
{

$sql = "DELETE FROM `$dbpräfix"."usedpics` WHERE `ID` = '$id'";
$erg = $hp->mysqlquery($sql);

// Aktualisieren:

$data = "";

$sql = "SELECT * FROM `$dbpräfix"."usedpics`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

         $breite=$row->width; 
         $hoehe=$row->height; 

         $neueHoehe=100;
         $neueBreite=intval($breite*$neueHoehe/$hoehe); 
         
        $img = "<img src=\"include/usedpics/pic.php?id=$row->ID\" width=\"$neueBreite\" height=\"$neueHoehe\" onclick=\"del_a_pic($row->ID)\"> ";
       
        
        if ($data == "")
        {
        $data = "'".$img."'";
        } else
        {
          $data .= ", '".$img."'";
        }
      


}

$response->script("picturelist_data = new Array ($data);");
$response->call("picturelist_print");


}

return $response;
}

function ax_savewidgetconfig($temp, $config)
{
$response = new xajaxResponse();


$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$widget = $hp->widgets;


if (in_array($_SESSION['username'], $hp->getsuperadmin()))
{


$array = explode("<!--!>",$config);
$config = array();

foreach ($array as $key=>$value) {
	
	$array2 = explode("<!=!>", $value);
	$config[$array2[0]] = $array2[1];
	
}


$widget->saveConfig($temp, $config);

} else
{
$response->alert("wieso solltest du das wollen?!");
}

//$response->assign("testinput", "value", "hallo");
return $response;
}


// ----------------------------------< DRAG & DROP >------------------------------------------------




function decodeinfo($info)
{
$infos = array();
$split = explode("<!--!>", $info);

foreach ($split as $key=>$value) {
	
	$temp = explode(":=", $value);
	
	$infos[$temp[0]] = $temp[1];
	
}

return $infos;
}
 



         
function ax_dragevent($dropper, $drag, $infon = "", $info_droppable = "")
{
$response = new xajaxResponse();

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$fp = $hp->firephp;


$dropper = mysql_real_escape_string($dropper);
$drag = mysql_real_escape_string($drag);

//$fp->group("Dragevent:");


$superadmin = in_array($_SESSION['username'], $hp->getsuperadmin());
if ($superadmin)
{


// Dekodiere Info:
$infos = $this->decodeinfo($infon);
//$fp->log($infos, "Infos");

$infos_droppable = $this->decodeinfo($info_droppable);






if ($infos_droppable['innerHTML'] == "")
{


//$response->call("Droppables.remove", "\$('".$dropper."')");


//$response->script("$('$dropper').highlight();");


//$response->script("$('".$drag."').hide($('".$drag."'));");
//$response->call("killElement", "$drag");
//$fp->log("Kill $drag");

  $value =  $infos['innerHTML'];
      
  $value = str_replace("'", "\'", $value);
 	$value = str_replace("\n", "", $value);
 	$value = str_replace("\r", "", $value);

//$response->call("createWidgetBox",'widget_$dropper', '$drag', '".$value."');




//$text = "<div id=\"".$drag."\">".$infos['innerHTML']."</div>";

//$response->assign($dropper, "innerHTML", $text);
//$response->assign($dropper, "className", "");


$sql = "DELETE FROM `$dbpräfix"."widget` WHERE `source` = '$drag'";
$erg = $hp->mysqlquery($sql);

// Eintragen in die DB:
$sql = "INSERT INTO `$dbpräfix"."widget` (`ID`, `source`) VALUES ('$dropper', '$drag');";
$erg = $hp->mysqlquery($sql);


//$response->script('setTimeout("location.reload(true);",200);');

}
//$fp->groupend();
}


return $response;
}


function ax_widget_del($dropper, $drag, $infon = "", $info_droppable = "")
{
$response = new xajaxResponse();

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

if (in_array($_SESSION['username'], $hp->getsuperadmin()))
{
  $sql = "DELETE FROM `$dbpräfix"."widget` WHERE `source` = '$drag'";
  $erg = $hp->mysqlquery($sql);
}

return $response;
}
 
 
 
function ax_reloadWidgets()
{
$response = new xajaxResponse();
//$response->script(file_get_contents("./js/drag&drop.js"));
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


$code = array();

// Ablauf:
//Löschen aller Widgets
//Löschen aller DropBoxen

//Einfügen des Inhaltes in die Platzhalter
//Neuerstellung der Drop Boxen


//Liefert alle Widgets
//Auch die die Platziert wurden
$widgets = $hp->widgets->getwidgets(true, false);
//$fp->log($widgets, "My Widgets");

//Ersetzten der Platzhalter:
$widgets = $hp->template->spezialsigs($widgets);
  

  foreach ($widgets as $widget=>$content)
  { 
       
    $content = str_replace("\\", "\\\\", $content);
    $content = str_replace("'", "\'", $content);

    $content = str_replace("§", "&sect;", $content);
    $content = str_replace("ü", "&uuml;", $content);
    $content = str_replace("Ü", "&Uuml;", $content);
    $content = str_replace("ö", "&ouml;", $content);
    $content = str_replace("Ö", "&Ouml;", $content);
    $content = str_replace("ä", "&auml;", $content);
    $content = str_replace("Ä", "&Auml;", $content);
    $content = str_replace("ß", "&szlig;", $content);
        
    
    //$fp->log($content);
  
    // Löschen falls schon vorhanden:
    $response->script("killElement('$widget');");
           
    // Wurde dieses Widget bereits Platziert?
    if ($hp->widgets->isPlaced($widget))
    {
      //Ermmittle, wo das Widget gesetzt wurde
      $parent = $hp->widgets->getParent($widget);
              
      //$fp->log("Das Widget $widget ist in $parent gesetzt");
      
      // Lösche jeden bereits exsistierenden Inhalt raus:
      $response->assign("widget_$parent", "innerHTML", "");
            
            
         
            
      // Erstelle die Widget Daten:
      $code[] = "createWidgetBox('widget_$parent', '$widget', '$content');";   
    
    } else
    {
      // Dieses Element ist nicht gesetzt:
      
      //Erzeuge neue Instanz im widgetContainer:
      if ($hp->site == "dragdrop")
      {
         //$fp->log("Widget $widget nicht gesetzt, füge ein in WidgetContainer");
      
        $code[] =  "createWidgetBox('widgetContainer', '$widget', '$content');"; 
      
      
      } 

    }

  }
  
if ($hp->site == "dragdrop")
 {
  
 //Liefert alle Platzhalter in denen nichts gesetzt wurde:
 $placeholder = $hp->widgets->getPlaceholder();
  
  foreach ($placeholder as $key=>$name)
  {
  
    // Lösche den Platzhalter
    $response->script("killElement('$name');");
     //$fp->log("Lösche Platzhalter $name");
  
    // Lade den Wert für die Widget Container
    $wert = str_replace("<!--ID-->", $name, $hp->widgets->replace);
    
    // Setzte den Inahlt des Platzhalters zu den Ihalt
    $response->assign("widget_$name", "innerHTML", $wert);
    
    // Schreibe den Script zur erstellund der DropDown Funktion
    $script = "Droppables.add('$name',{onDrop: function(drag, base) {     
      widgetDropEvent(base.id, drag.id, getinfo(drag), getinfo(base));      
      }, hoverclass: 'hclass'});";
    //$script = str_replace("'", "\'", $script);
 	  $script = str_replace("\n", "", $script);
 	  $script = str_replace("\r", "", $script);
    $script = str_replace("\t", "", $script);
    
    
    $code[] = $script;  
      
  
  }
  

 }


// Gebe die somit gesammelten Java Script aus:
//$fp->log($code);

  foreach ($code as $i=>$script)
  {
    $script = str_replace("\"", "\\\"", $script);
    //$script = str_replace("'", "", $script);
   	$script = str_replace("\n", "", $script);
 	  $script = str_replace("\r", "", $script);
    $script = str_replace("\t", "", $script);
  
    $response->script($script);
    
  }


return $response;
}
 
 

function open($funktion)
{
$this->func[] = $funktion;
}

function getfunk($del = false)
{
$str = implode(" \n ", $this->func);   

 if ($del)
 {
 $this->func = array();
 }

return $str;

}

function ax_reloadScripts($del = true)
{
 $response = new xajaxResponse();

 $hp = $this->hp;
 $dbpräfix = $hp->getpräfix();
 $info = $hp->info;
 $error = $hp->error;
 $fp = $hp->fp;

    foreach ($this->func as $key=>$value) {
      $response->script("$value");	
    	
    }
   
 if ($del)
 {
 $this->func = array();
 }
    
  return $response;
}



// ---------------------------------- </ DRAG & DROP >------------------------------------------------



// ---------------------------------------- Erweiterungen ----------------------------------------------



function extend($path)
{
if (is_file("template/$path/xajax.php"))
{


include "template/$path/xajax.php";

try
{
$object = new XajaxTemplate();
$object->sethp($this->hp);

} catch(Exception $ex)
{
$object = null;
}



if (is_object($object))
{

$this->registerFunctions($object);


}
}
}




}



?>
