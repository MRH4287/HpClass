<?php
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();




$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '3'";
$ergebnis = $hp->mysqlquery($abfrage);
    $template['member']="<tr><td ><b>Administratoren:</b></td></tr>";
while($row = mysql_fetch_object($ergebnis))
   { 
   $template['member']=$template['member']."<tr><td ><a href=index.php?site=user&show=$row->user>&raquo;&nbsp;$row->user</a></td></tr>";
   }
  
   $abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '2'";
$ergebnis = $hp->mysqlquery($abfrage);
    $template['member']=$template['member']."<tr><td ><b>Moderatoren:</b></td></tr>";
while($row = mysql_fetch_object($ergebnis))
   { 
   $template['member']=$template['member']."<tr><td ><a href=index.php?site=user&show=$row->user>&raquo;&nbsp;$row->user</a></td></tr>";
   }
   $abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `level` = '1'";
$ergebnis = $hp->mysqlquery($abfrage);
    $template['member']=$template['member']."<tr><td ><b>User:</b></td></tr>";
while($row = mysql_fetch_object($ergebnis))
   { 
   $template['member']=$template['member']."<tr><td ><a href=index.php?site=user&show=$row->user>&raquo;&nbsp;$row->user</a></td></tr>";
   }

?>
