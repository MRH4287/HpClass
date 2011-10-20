<?php
class widgets
{
var $hp;
var $placed = array();
var $template = array();
var $tempconfig = array();

//Definiert alle Platzhalter im Template:
var $placeholder = array("placeholder1", "placeholder2", "placeholder3", "placeholder4", "placeholder5");
// Leeres Array für die Widgets:
var $widgets = array();

// Der Text, der Statt den Widgets angezeigt wird, wenn dragdrop geöffnet wurde:
public $replace = "
<table width=\"100%\" height=\"50\">

<tr>
<td>
<div id=\"<!--ID-->\" class=\"dropp\"></div>

</td>
</tr>
</table>
";
/*
  <script>

Droppables.add('<!--ID-->',{onDrop: function(drag, base) {

widgetDropEvent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});


</script>
*/


function sethp($hp)
{
$this->hp = $hp;
}



function replace()
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;
$get = $hp->get();


// Fügt Widgets aus den Templates hinzu:
$this->incwidgetfiles();


$superadmin = ( isset($_SESSION['username']) and in_array($_SESSION['username'], $hp->getsuperadmin()));

 // Datenbank Abfrage, ob bereits ein Widget verschoben wurde:
$sql = "SELECT * FROM `$dbprefix"."widget`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

 if (isset($this->widgets[$row->source]))
 {
  //$temp->addtemp($row->ID, $this->widgets[$row->source]);
  $value = $this->widgets[$row->source];
  $this->template[$row->ID] = "<div id='widget_".$row->ID."'>$value</div>";
  $this->placed[] = $row->source;
  $this->placed[] = $row->ID;
 }

}


// Wenn die Seite dragdrop offen ist, ersetze alle Placeholder mit dem Droppable Code:


foreach ($this->placeholder as $key=>$value) {
	if (!in_array($value, $this->placed))
	{
	 if (($hp->site == "dragdrop") and ($superadmin))
    {
  	$temp->addtemp($value, str_replace("<!--ID-->", $value, "<div id='widget_".$value."'></div>"));         //$this->replace

	 } else
	 {
    $temp->addtemp($value, "<div id='widget_".$value."'></div>");
   }
}

 }


}


function getParent($widget)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbprefix"."widget` WHERE `source` = '$widget';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->ID;

}



function addtotemp()
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;

$template = $hp->template->spezialsigs($this->template);



foreach ($template as $key=>$value) {
	
	$temp->addtemp($key, $value);
	
}

}


function isPlaced($widget)
{
return in_array($widget, $this->placed);
}

function getwidgets($placed = false, $config = true)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;
$lbs = $hp->lbsites;

$widgets_replace = $hp->template->spezialsigs($this->widgets);


$widgets = array();


foreach ($widgets_replace as $key=>$value) {


	 if (!in_array($key, $this->placed) or $placed)
   {
   $widgets[$key] = $value;


   if (array_key_exists($key, $this->tempconfig) and $config)
   {
   $widgets[$key] .= "<center>".$lbs->link($this->tempconfig[$key], "<img src=\"images/edit.gif\">")."</center>";

   }


   }
}


return $widgets;
}


function getPlaceholder($emptyOnly = true)
{

if (!$emptyOnly)
{
return $this->placeholder;
}  else
{

  $result = array();

  foreach ($this->placeholder as $key=>$value) {
	
	   if (!in_array($value, $this->placed))
     {
      $result[] = $value;
     }	
	
  }

  return $result;
}
}


function addwidget($name, $value)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->firephp;
$config = $hp->getconfig();

$this->widgets[$name] = $value;
}


function addplaceholder($name)
{

if (!in_array($name, $this->placeholder))
{
$this->placeholder[] = $name;
}


}



 function incwidgetfiles()
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->firephp;
$config = $hp->getconfig();

$design = $config['design'];

  if (is_dir("template/".$design."/widgets/"))
  {


  $handle = @opendir("./template/".$design."/widgets/");
    while ($file = @readdir($handle)) {



	     $n= @explode(".",$file);
       $art = @strtolower($n[1]);


    if ($art == "php")
    {
        $widget = array();
        $placeholder = array();
        if (file_exists("./template/".$design."/widgets/$file"))
        include ("./template/".$design."/widgets/$file");

        foreach ($widget as $key=>$value) {
        	
        	if (($key != "") and ($value != ""))
        	{
          $this->addwidget($key, $value);
          }
        	
        }

          foreach ($placeholder as $key=>$value) {
        	
        	if ($value != "")
        	{
          $this->addplaceholder($value);
          }
        	
        }

      if (isset($tempconfig) && is_array($tempconfig))
      {
        $this->tempconfig = array_merge($this->tempconfig, $tempconfig);
      //  print_r($this->tempconfig);
      }


    }
  }


 }
}


function getConfig($widget)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();


$sql = "SELECT * FROM `$dbprefix"."widgetconfig` WHERE `widget` = '$widget';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$config = unserialize($row->config);

if (!is_array($config))
{
$config = array();
}

return $config;
}

function saveConfig($widget, $config)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();

$text = serialize($config);

$sql = "REPLACE INTO `$dbprefix"."widgetconfig` (`widget`, `config`) VALUES ('$widget', '$text');";
$erg = $hp->mysqlquery($sql);

}


 }
?>