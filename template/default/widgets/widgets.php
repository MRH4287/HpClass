<?php

// Gibt an, welche Placeholder auf der Seite verwedet werden.
// Gilt nur für zusätzliche Platzhalter.
// Standardmäßig sind definiert:
// placeholder1, placeholder2, placeholder3, placeholder4, placeholder5

$placeholder = array();

// In dieser Datei werden Widgets definiert, die Später über das Design aufgerufen werden.


// Widgetconfig:
// Hier werden die Unterseiten von Lbsites definiert, die als Konfigurationsseite angezeigt werden sollen, wenn die Widgets in
// der Liste sind.
//$tempconfig = array ("SVN" => "Test");


//$widget = array("test" => "inhalt");


$widget = array (

// SVN Widget:
"SVN" => '
<table bgcolor="#89A9B8"  width="170" border="0" cellpadding="0" cellspacing="0" class="news"  align="center" style="border:solid 1px black" >
<tr>
<td class="rubrik">&nbsp;SVN</td>
</tr>
<tr>
<td >
<center><img src="template/SVN/bild.php" height="100" width="100"></center>
</td>
</tr>
</table>
',

// Mitglieder
"Member"=> '
<table  width="170" border="0" cellpadding="0" cellspacing="0"  bgcolor="#D5E0E6"  id="menu" align="center" style="border:solid 1px black;" >
<tr>
<td class="rubrik" width="165">&nbsp;Mitglieder</td>
</tr>
<!--member-->

</table>'

);


?>