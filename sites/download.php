<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();


if (isset ($get['del']) and $_SESSION['level'] = 3)
{

$abfrage = "DELETE FROM ".$dbpräfix."download WHERE `ID`=".$get['del'];
$ergebnis = $hp->mysqlquery($abfrage);
    
  if ($ergebinis == true) 
  {
 echo $lang->word('delok');
  } else
  {
  $error->error("Fehler: ".mysql_error(),"2");
  }
  

}

?>
<p align="center"><font face="Comic Sans MS" size="6">Download - Area</font></p>
<p align="center">&nbsp;</p><br>
<?php
if (!isset($_SESSION['username'])) 
{
$error->error($lang->word('noright2'),"2");

} else {

if (!isset($get['id']))

{
?>
<table border="1" width="723" align="center" height="29">
  <tr>
    <td width="219" align="center" height="1"><?php echo $lang->word('titel')?>:</td>
    <td width="138" align="center" height="1"><?php echo $lang->word('uploadedby')?>:</td>
    <td width="84" align="center" height="1"><?php echo $lang->word('datum')?>:</td>
    <td width="248" align="center" height="1"><?php echo $lang->word('description')?>:</td>
  </tr>
<?php

   $abfrage = "SELECT * FROM ".$dbpräfix."download";
$ergebnis = $hp->mysqlquery($abfrage);
    
    
while($row = mysql_fetch_object($ergebnis))
   {
   if ($_SESSION['level'] == "$row->level" or $_SESSION['level'] > "$row->level")
   {
?>
  <tr>
    <td width="219" align="center" height="5"><a href=index.php?site=download&id=<?php echo "$row->ID"?>><?php echo "$row->titel"?></a></td>
    <td width="138" align="center" height="5"><?php echo "$row->autor"?></td>
    <td width="84" align="center" height="5"><?php echo "$row->datum"?></td>
    <td width="248" align="center" height="5"><?php echo "$row->beschreibung"?></td>
  </tr>

<?php } } ?> 
</table> 
<?php

} else {

   $abfrage = "SELECT * FROM ".$dbpräfix."download WHERE `ID` = '".$get['id']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
    
while($row = mysql_fetch_object($ergebnis))
   {
   if ($_SESSION['level'] == "$row->level" or $_SESSION['level'] > "$row->level") 
   {
    ?>
<table border="1" width="130" align="center" bordercolor="#9999FF">
  <tr>
    <td>
<table border="1" width="565" align="center" height="81" bordercolor="#9999FF">
  <tr>
    <td width="141" height="36">
      <p align="center"><?php echo $lang->word('titel')?>:</p>
    </td>
    <td width="408" height="36"><?php echo "$row->titel"?></td>
  </tr>
  <tr>
    <td width="141" height="36">
      <p align="center"><?php echo $lang->word('descrition')?>:</p>
    </td>
    <td width="408" height="36"><?php echo "$row->beschreibung"?></td>
  </tr>
  <tr>
    <td width="141" height="36">
      <p align="center"><?php echo $lang->word('uploadedam')?>:</td>
    <td width="408" height="36"><?php echo "$row->datum"?></td>
  </tr>
  <tr>
    <td width="141" height="15">
      <p align="center"><?php echo $lang->word('uploadedby')?>:</td>
    <td width="408" height="15"><?php echo "$row->autor"?></td>
  </tr>
  <tr>
    <td width="549" height="36" colspan="2">
      <p align="center"><a href=sites/download2.php?id=<?php echo "$row->ID"?>><img border="0" src="images/download.gif" width="108" height="34"></a><br>
      <?php if ($_SESSION['level'] == 3) { ?>
      <p align="center"><a href=index.php?site=download&del=<?php echo "$row->ID"?>>Löschen</a></td>
      <?php }?>
  </tr>
</table>

    </td>
  </tr>
</table>
<?php
} else
{
$error->error($lang->word('norightlookfile'),"2");
}
}
 } 
 }?>
