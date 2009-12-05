<?php
class widgets
{
var $hp;
var $placed = array();
var $template = array();

//Definiert alle Platzhalter im Template:
var $placeholder = array("placeholder1", "placeholder2", "placeholder3");
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


function setwidgets()
{

// -------------------------------- WIDGETS: ----------------------------------------------



$this->addwidget('navigation', 
'
<table  width="170" border="0" cellpadding="0" cellspacing="0" bgcolor="#D5E0E6"   id="menu" align="center" style="border:solid 1px black;" >
<tr>
<td class="rubrik">&nbsp;Navigation</td>
</tr>
<tr>
<td ><a href="index.php?site=news" >&raquo;&nbsp;News</a></td>
</tr>
<tr>
<td><a href="index.php?site=user" >&raquo;&nbsp;Mitglieder</a></td>
</tr>
<tr>
<td ><a href="index.php?site=forum" >&raquo;&nbsp;Forum</a></td>
</tr>
<tr>
<td><a href="index.php?site=gb" >&raquo;&nbsp;G&auml;stebuch</a></td>
</tr>
<!--
<tr>
<td >
<a href="index.php?site=videos" >&raquo;&nbsp;Video Galerie</a></td>
</tr>
-->
<tr>
<td >
<a href="index.php?site=download" >&raquo;&nbsp;Download-Area</a></td>
</tr>
<tr>
<td >
<a href="index.php?site=pm&report" >&raquo;&nbsp;Fehler Melden</a></td>
</tr>
<tr>
<td >
<a href="index.php?site=impressum" >&raquo;&nbsp;Impressum</a></td>
</tr>
<!--<br>-->

<!--<br>-->

</table>
');

//$this->addwidget("test", "Das ist ein TEst");




}


// ....................................................................................

function __construct()
{
$this->setwidgets();
}


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




 // Datenbank Abfrage, ob bereits ein Widget verschoben wurde:
$sql = "SELECT * FROM `$dbpräfix"."template`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

 if ($get['site'] == "dragdrop")
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
 if ($get['site'] == "dragdrop")
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



foreach ($this->template as $key=>$value) {
	
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



$widgets = array();

foreach ($this->widgets as $key=>$value) {
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

 
 
 }
?>