<?php

$placeholder = array(); 
$widget = array ();

$text = '<table bgcolor="#89A9B8"  width="170" border="0" cellpadding="0" cellspacing="0" class="news"  align="center" style="border:solid 1px black" >
<tr>
<td class="rubrik">&nbsp;Umfrage</td>
</tr>
<tr>
<td >
<p align=center><!--CONTENT--></p>
</td>
</tr>
</table>';

  //vote#6

$sql = "SELECT * FROM `$dbpräfix"."vote`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

$widget["vote-".$row->ID] = str_replace("<!--CONTENT-->", "<!--vote#".$row->ID."-->", $text);

}

    



?>