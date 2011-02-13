<?php

if (isset($_SESSION['username']))
{
$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `zu` = '".$_SESSION['username']."' AND `gelesen` = '0' ORDER BY `ID`;";

$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();
   $i = 0; 
while($row = mysql_fetch_object($ergebnis))
   {
   $i = $i + 1;
   }
if ($i > 0)
{
$info->info("<a href=index.php?site=pm>Sie haben ungelesene Pm`s!</a>");


}

}
?>
