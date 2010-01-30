<?php
class widgets
{
var $hp;
var $placed = array();
var $template = array();

//Definiert alle Platzhalter im Template:
var $placeholder = array("placeholder1", "placeholder2", "placeholder3", "placeholder4", "placeholder5");
// Leeres Array für die Widgets:
var $widgets = array();

// Der Text, der Statt den Widgets angezeigt wird, wenn dragdrop geöffnet wurde:
var $replace = "
<table width=\"100%\" height=\"50\">

<tr>
<td>
<div id=\"<!--ID-->\" class=\"dropp\"></div>

<script>

Droppables.add('<!--ID-->',{onDrop: function(drag, base) {

xajax_dragevent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});
 

</script>

</td>
</tr>
</table>
";



function sethp($hp)
{
$this->hp = $hp;
}



function replace()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;
$get = $hp->get();


// Fügt Widgets aus den Templates hinzu:
$this->incwidgetfiles();


$superadmin = in_array($_SESSION['username'], $hp->getsuperadmin());

 // Datenbank Abfrage, ob bereits ein Widget verschoben wurde:
$sql = "SELECT * FROM `$dbpräfix"."widget`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

 if (($get['site'] == "dragdrop") and $superadmin)
 {
 $del1 = "<div id=\"$row->ID\" class=widget-del>";
 $del2 = "<br><a href=# onclick=xajax_widget_del('$row->ID')>Löschen</a></div>";
 }

 //$temp->addtemp($row->ID, $this->widgets[$row->source]);
 $this->template[$row->ID] = $del1.$this->widgets[$row->source].$del2;
 $this->placed[] = $row->source;
 $this->placed[] = $row->ID;

}


// Wenn die Seite dragdrop offen ist, ersetze alle Placeholder mit dem Drroppable Code:
 if (($get['site'] == "dragdrop") and ($superadmin))
 {

foreach ($this->placeholder as $key=>$value) {
	if (!in_array($value, $this->placed))
	{
	$temp->addtemp($value, str_replace("<!--ID-->", $value, $this->replace));
	//$this->template[$value] = str_replace("<!--ID-->", $value, $this->replace);
	}
}

 }





}



function addtotemp()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;

$template = $hp->template->spezialsigs($this->template);



foreach ($template as $key=>$value) {
	
	$temp->addtemp($key, $value);
	
}

}


function getwidgets()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$temp = $hp->template;


$widgets_replace = $hp->template->spezialsigs($this->widgets);
$widgets = array();

foreach ($widgets_replace as $key=>$value) {
	 if (!in_array($key, $this->placed))
   {
   $widgets[$key] = $value;
   }
}


return $widgets;
}




function addwidget($name, $value)
{
$this->widgets[$name] = $value;
}


function addplaceholder($name)
{

if (!in_array($name, $this->placeholder))
{
$this->placeholder = $name;
}


}
 
 
 
 function incwidgetfiles()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
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
        
        
        

    }
  } 


 } 
}

 
 
 
 }
?>