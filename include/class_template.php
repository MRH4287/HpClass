<?php
class template
{

var $template = array();
var $temp;
var $error;
var $hp;
var $lang;
var $path;



function seterror($error)
{
$this->error=$error;
}

function gettemp($part)
{

return $this->template[$part];

}

function settemplate($temp)
{
if (!is_array($this->temp))
{
$this->temp = array();
}
$this->temp=array_merge($temp,$this->temp);
}


function load($path)
{
$this->path = $path;

if (!file_exists("template/$path.html"))
{

$this->template=array();
$this->error->error("Template $path not found!", "2");
if (file_exists("template/default.html"))
{
$path = "default";
} else
{
$this->error->error("Standard Template wurde nicht gefunden!","3");
}
}

$temp = file_get_contents("template/$path.html");


   $data = explode("<!--next-->", $temp);

  $temp = $this->loadtemplatefile($path);
 if (is_array($temp))
 {
 $this->temp = array_merge($this->temp, $temp); 
 } 
 
 $this->addVote();

 $data = $this->spezialsigs($data);
 

   
$this->template['header'].=$data[0];

$this->template['footer'].=$data[1];


}

function addtemp($temp, $wort)
{
$this->temp[$temp] = $wort;
}

function spezialsigs($data)
{

foreach ($data as $key=>$value) {

foreach ($this->temp as $key2=>$value2) {

$value = str_replace("<!--$key2-->", $value2, $value);	
}


$data[$key] = $value;
	
}
return $data;
}

function loadtemplatefile($path)
{
if (file_exists("template/$path/template.php"))
{

include "template/$path/template.php";

}

return $template;

}

function getHeader()
{
return $this->temp['header'];
}


function sethp($hp)
{
$this->hp = $hp;
}

function setlang($lang)
{
$this->lang=$lang;
}


function getloginconfig($path)
{

if (is_file("template/$path/login.php"))
{

include "template/$path/login.php";

return $config;
} else
{
return null;
}

}

function addVote()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


$sql = "SELECT * FROM `$dbpräfix"."vote`";
$erg = $hp->mysqlquery($sql);


while ($row = mysql_fetch_object($erg))
{
$text = "";


$ergebniss = explode("<!--!>", $row->ergebnisse);
$voted = count($ergebniss);
if ($ergebniss[0] == "")
{
$voted--;
}
$whov = explode("<!--!>", $row->voted);

$text = '

<table width="160" border="0">
   <tr>
    <td><strong>'.$row->name.'</strong></td>
  </tr>
  <tr>
    <td>
    <div id="ergebnisse'. $row->ID.'">';
    
    
   
   if ($row->upto > time())
    {
    
   if (!in_array($_SESSION['ID'], $whov))
   { 

   $text .= '<div id="voteok'.$row->ID.'">
      <table width="160">';
      
      $answers = explode("<!--!>", $row->antworten);
    
      foreach ($answers as $key=>$value) {
      
   $text .= '<tr>
          <td><label  onclick="setvote('.$key.', \''.$row->ID.'\');">
            <input type="radio" name="answer" value="'.$row->ID.'"/>
            '.$value.'</label></td>
        </tr>';
        
        }
        
        $text .= '
    </table>      
      <p>';
       if (isset($_SESSION['ID'])) { 
        $text .= '
        <input type="submit" name="button" id="button" value="Senden" onclick="postvote(\''.$row->ID.'\');" />
        <input type="hidden" name="vote" id="vote'.$row->ID.'" />';
         } else { $text .= "<b>Zum Abstimmen müssen Sie sich einloggen!</b>"; } 
         $text .='
      </p>
      </div>';
      
      } else
      {
      $text .= "<br><center><img src=images/alert.gif><br>Bereits abgestimmt</center><br>";
      }
      
      } else
      {
      $text .= "<br><center><img src=images/alert.gif><br>Abstimmung abgelaufen</center><br>";
      }
      
      $text .='
      <p>Bereits <strong> '.$voted.' </strong>Stimmen abgegeben.<br />
     <input type="button" onclick="xajax_vote_result('.$row->ID.')" value="Ergebnisse"></p>
     
      </div>
      
    </td>
  </tr>';

  if ($right[$level]['manage_vote'])
  {
  
  $text .= '
  <tr>
   <td>
   <a href="index.php?site=vote&editvote='.$row->ID.'">Bearbeiten</a> \ ';
    $text .= $lbs->link("delvote", "Löchen", $row->ID);
    $text .= '
   </td> 
  </tr>';
  
  }
  
 $text .= '</table>';

 $this->temp['vote#'.$row->ID] = $text;
 
 
}

}


}
?>
