<script type="text/javascript">
function toggleDetails(element, block)
{
	var b = document.getElementById(block);
	if(b.style.display == 'none')
	{
		b.style.display = 'block';
	//	element.parentNode.getElementsByTagName('img')[0].src = '/bilder/minus.png';

	}
	else
	{
		b.style.display = 'none';
//		element.parentNode.getElementsByTagName('img')[0].src = '/bilder/plus.png';
	
	}
	
	return false;
}

</script>


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
$info = $hp->getinfo();


if (isset ($get['del2']) and $_SESSION['level'] = 3)
{
/*
$abfrage = "DELETE FROM ".$dbpräfix."download WHERE `ID`=".$get['del'];
$ergebnis = $hp->mysqlquery($abfrage);
*/
  $abfrage = "SELECT * FROM ".$dbpräfix."download WHERE `ID` = '".$_GET['del2']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
    
while($row = mysql_fetch_object($ergebnis))
   {
 unlink("downloads/$row->dateiname");
 }  
$abfrage = "DELETE FROM ".$dbpräfix."download WHERE `ID`=".$_GET['del2'];
$ergebnis = $hp->mysqlquery($abfrage);
    

    
  if ($ergebnis == true) 
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

} elseif (isset ($get['del']) and $right[$level]['upload'])
{
$del = $get['del'];
$info->info("Möchten Sie die Datei wirklick löschen? <a href=index.php?site=download&del2=$del>Ja</a> <a href=index.php>Nein</a>");
}
else {

if (!isset($get['id']))

{

  $abfrage = "SELECT * FROM ".$dbpräfix."download_kat";
$ergebnis = $hp->mysqlquery($abfrage);
    
echo '<div align="center" style="background-color:#c9c9c9">';    
while($row = mysql_fetch_object($ergebnis))
   {
   if ($_SESSION['level'] >= "$row->level")
   {
?>
<p align="left"><strong><a href=# onclick="return toggleDetails(this,'dl<?php echo $row->ID?>');"><?php echo $row->name?></a><?php  if (isset($get['katchange'])) {  echo " -- <a href=index.php?site=upload&katchange=$row->ID>Bearbeiten</a> -- <a href=index.php?site=upload&katdel=$row->ID>Löschen</a>";  }  ?></strong>:</p>
<?php


  $abfrage2 = "SELECT * FROM ".$dbpräfix."download WHERE `kat` = '$row->ID'";
$ergebnis2 = $hp->mysqlquery($abfrage2);
    
echo '<div align="left" style="background-color:#e9e9e9; display:block;" id="dl'.$row->ID.'">'; 
echo "<ul>";  
while($row2 = mysql_fetch_object($ergebnis2))
   {
   if ($_SESSION['level'] == "$row->level" or $_SESSION['level'] > "$row->level")
   {
  ?>   
  <li><a href=index.php?site=download&id=<?php echo "$row2->ID"?>><?php echo "$row2->titel"?></a> <?php  if (isset($get['change'])) {  echo " -- <a href=index.php?site=upload&filechange=$row2->ID>Bearbeiten</a>";  }  ?></li>
<?php

}
}
echo "</ul></div>";
}
}

echo "</div>";


/*
   $abfrage = "SELECT * FROM ".$dbpräfix."download";
$ergebnis = $hp->mysqlquery($abfrage);
    
    
while($row = mysql_fetch_object($ergebnis))
   {
   if ($_SESSION['level'] == "$row->level" or $_SESSION['level'] > "$row->level")
   {
?>
  <tr>
    <td width="219" align="center" height="5"><a href=index.php?site=download&id=<?="$row->ID"?>><?="$row->titel"?></a></td>
    <td width="138" align="center" height="5"><?="$row->autor"?></td>
    <td width="84" align="center" height="5"><?="$row->datum"?></td>
    <td width="248" align="center" height="5"><?="$row->beschreibung"?></td>
  </tr>

<? } } ?> 
</table> 
<?
*/
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
      <p align="center"><?php echo $lang->word('description')?>:</p>
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
    <?php if (!file_exists("downloads/$row->dateiname")) { ?>
    
      <p align="center"><a href=sites/download2.php?id=<?php echo "$row->ID"?>><img border="0" src="images/download.gif" width="108" height="34"></a><br>
     
      <?php
      } else
      {
      $datei=str_replace(" ", "%20", $row->dateiname);
      
      ?>
    <p align="center"><a href=downloads/<?php echo $datei?>><img border="0" src="images/download.gif" width="108" height="34"></a><br>  
      <?php
      }
      
      
      
       if ($right[$level]['upload']) { ?>
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
