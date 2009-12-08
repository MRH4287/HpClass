<?php
class Xajax_Funktions
{
var $hp;

var $ajaxFuncPrefix = 'ax_';

var $xajax;
var $func = array();

function __construct()
{
         $this->xajax = new xajax();
         $this->registerFunctions();


}


function sethp($hp)
{
$this->hp = $hp;
}

 public function registerFunctions() {
    		$methods = get_class_methods($this);
    		
    		foreach ($methods as $m) {
			$p = $this->ajaxFuncPrefix;
    			if (preg_match("/^{$p}[a-z]/", $m)) {
    				$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);
    				$this->xajax->registerFunction(array($m2, &$this, $m));
    			}
    		}
    }


function printjs()
{

$this->xajax->printJavascript("include/");
}

function processRequest()
{
$this->xajax->processRequest();
}


function ax_checkvote($titel, $answer1, $answer2, $day, $month, $year, $hour, $min, $update)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
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
$perc = ($erg[$key] / $count) * 100;
if ($perc != 100)
{
$perca =str_Split($perc);
$perc = $perca[0].$perca[1].$perca[2].$perca[3].$perca[4];
}
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



function ax_calender($month = "X", $jahr = "X")
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
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

            $iWochenTag  = date(w, mktime(0, 0, 0, $monat, 1, $jahr));
            $iAnzahltage = date(t, mktime(0, 0, 0, $monat, 1, $jahr));
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

            $iAnzahltageVormonat = date(t, mktime(0, 0, 0, $vmonat, 1, $vjahr));
            
            if ($monat == 1) { $lnzjahr = $jahr - 1; $monlz = 12; } else { $lnzjahr = $jahr; $monlz = $monat-1; }
            if ($monat == 12) { $lnvjahr = $jahr + 1; $monlv = 1; } else { $lnvjahr = $jahr; $monlv = $monat+1; }
            
            $text .= '<table id="cal"><tr>'.
                  '<th>'.
                  '<a onclick="xajax_calender('.$monlz.', '.$lnzjahr.')" href="#"><img src="images/arrowp.gif" width="9" height="11" alt="&gt;"></a>' . // Xajax regelung, dass der Kalender aktualisiert wird
                  '</th>'.
                  '<th colspan="5">'.htmlentities($arr_monate[$monat-1]).' '.$jahr.'</th>'.
                  '<th >'.
                  '<a onclick="xajax_calender('.$monlv.', '.$lnvjahr.')" href="#"><img src="images/arrown.gif" width="9" height="11"></a>' .
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
                     
                     $link = '<a href="#" onClick="setdate('.$preTag.', '.($monat -1).', '.$jahr.')">'.$preTag.'</a>';
                     
                      $text=$text. $link;
              
                      $text=$text. "</td>";
                    }
                    
                    if ($iTag > $iAnzahltage){ // Tage des Nächsten Monats
                      ++$ntmp;
                    $evt = false;
                      $text=$text. '<td ';
 $text=$text. 'id="amonat">' ;     

                          $link = '<a href="#" onClick="setdate('.$ntmp.', '.($monat +1).', '.$jahr.')">'.$ntmp.'</a>';            
                      $text=$text. $link;
                      
                     // $m + 1
                      $text=$text. '</td>';
                    }
                    
                    if ($iTag != 0 && $iTag <= $iAnzahltage){ // Tage im Monat drinnen :D
                      $evt = false;
                      $text=$text. '<td ';
 $text=$text. 'id="monat">';    
                        
     $link = '<a href="#" onClick="setdate('.$iTag.', '.$monat.', '.$jahr.')">'.$iTag.'</a>';
                     
                                          if (($daytod == $iTag) and ($montod == $monat) and ($yeartod == $jahr))
                     {
                     $text .="<b>$link</b>";
                     } else
                     {                     
                     $text=$text. $link;
                     }          
                      
                      
                                           
                     $text=$text. '</td>'."\n";
                      ++$iTag;
                    }

                    
                } while(++$j <= 7);
            
                $text=$text. '</tr>';
            
            } while(++$i < $iZeilen);
            $text=$text. '</table>';

$response->assign("kalender", "innerHTML", "$text");


return $response;
}


function ax_forum_vote($ID, $vote)
{
$response = new xajaxResponse();
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


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


}
return $response;
}



function open($funktion)
{
$this->func[] = $funktion;
}

function getfunk()
{
return implode(" \n ", $this->func);
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

$superadmin = in_array($_SESSION['username'], $hp->getsuperadmin());
if ($superadmin)
{


// Dekodiere Info:
$infos = $this->decodeinfo($infon);
$infos_droppable = $this->decodeinfo($info_droppable);





if ($infos_droppable['innerHTML'] == "")
{


$response->script("Droppables.remove($('".$dropper."'));");



$response->script("$('$dropper').highlight();");

$response->script("$('".$drag."').hide($('".$drag."'));");




$text = "<div id=\"".$drag."\">".$infos['innerHTML']."</div>";

$response->assign($dropper, "innerHTML", $text);
$response->assign($dropper, "className", "");


// Eintragen in die DB:
$sql = "INSERT INTO `$dbpräfix"."widget` (`ID`, `source`) VALUES ('$dropper', '$drag');";
$erg = $hp->mysqlquery($sql);


$response->script('setTimeout("location.reload(true);",200);');

}

}


return $response;
}


function ax_widget_del($ID)
{
$response = new xajaxResponse();

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

if (in_array($_SESSION['username'], $hp->getsuperadmin()))
{

$ID = mysql_escape_string($ID);

$sql = "DELETE FROM `$dbpräfix"."widget` WHERE `ID` = '$ID';";
$erg = $hp->mysqlquery($sql);



$response->assign($ID, "innerHTML", "entfernt");

$response->script('setTimeout("location.reload(true);",200);');

}

return $response;
}
 


// ---------------------------------- </ DRAG & DROP >------------------------------------------------




 function ax_test($a)
 {
 $response = new xajaxResponse();
 
 return $response;
 }













}
?>
