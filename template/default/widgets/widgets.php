<?php

// Gibt an, welche Placeholder auf der Seite verwedet werden.
// Gilt nur für zusätzliche Platzhalter.
// Standardmäßig sind definiert:
// placeholder1, placeholder2, placeholder3, placeholder4, placeholder5

$placeholder = array();

// In dieser Datei werden Widgets definiert, die Später über das Design aufgerufen werden.


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

</table>',

// Uhrzeit:
"Uhr" =>'<table bgcolor="#89A9B8"  width="170" border="0" cellpadding="0" cellspacing="0" class="news"  align="center" style="border:solid 1px black" >
<tr>
<td class="rubrik">&nbsp;Zeit</td>
</tr>
<tr>
<td >

<div name="uhrzeit" id="uhrzeit"></div>
<script type=\'text/javascript\'>
<!--
function zeit()
{
var heute = new Date();
        var std = heute.getHours();
        var min = heute.getMinutes();
        var sek = heute.getSeconds();


        if(std < 10){std = "0" + std};
        if(min < 10){min = "0" + min};
        if(sek < 10){sek = "0" + sek};
                
        var genzei = std + ":" + min + " " + sek;


        document.getElementById(\'uhrzeit\').innerHTML="<center>"+genzei+"</center>";

        setTimeout("zeit()",1000)
}

zeit();
//-->
</script>     
</td>
</tr>
</table>'

);


?>