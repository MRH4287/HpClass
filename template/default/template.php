<?php
//$template['header'] ="";

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();

$sqlr = "SELECT * FROM `$dbpräfix"."ranks` ORDER BY `level` DESC";
$ergr = $hp->mysqlquery($sqlr);

$template['member'] = "";
while ($rowr = mysql_fetch_object($ergr))
{
if ($rowr->level != 0)
{

$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '$rowr->level'";
$ergebnis = $hp->mysqlquery($abfrage);
    $template['member'].="<tr><td ><b>$rowr->name:</b></td></tr>";
while($row = mysql_fetch_object($ergebnis))
   { 
   $template['member'].="<tr><td ><a href=index.php?site=user&show=$row->user>&raquo;&nbsp;$row->user</a></td></tr>";
   }

}
}


?>
