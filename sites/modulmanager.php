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

if (!$right[$level]['moduladmin'])
{
$error->error("Sie haben kein Recht Module zu installieren!","2");
echo "Sie haben kein Recht Module zu Installieren!<br><a href=index.php>Zurück</a>";
} else
{

echo "<center><h4>Modul Manager</h4></center>";
echo "<br>";



if (isset($get['ak']))
{
$abfrage = "SELECT * FROM ".$dbpräfix."modul WHERE `ID` = '".$get['ak']."'";
$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();
    
while($row = mysql_fetch_object($ergebnis))
   {
   $name = $row->Name;
   }


$sql = 'UPDATE `'.$dbpräfix.'modul` SET `active` = \'1\' WHERE `ID` = '.$get['ak'];

$hp->mysqlquery($sql);

$info->okm("Das Modul $name wurde erfolgreich Aktiviert!");

} else

if (isset($get['deak']))
{
$abfrage = "SELECT * FROM ".$dbpräfix."modul WHERE `ID` = '".$get['deak']."'";
$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();
    
while($row = mysql_fetch_object($ergebnis))
   {
   $name = $row->Name;
   }


$sql = 'UPDATE `'.$dbpräfix.'modul` SET `active` = \'0\' WHERE `ID` = '.$get['deak'];

$hp->mysqlquery($sql);

$info->okm("Das Modul $name wurde erfolgreich Deaktiviert!");

} else
if (isset($get['deinstall']))
{

$abfrage = "SELECT * FROM ".$dbpräfix."modul WHERE `ID` = '".$get['deinstall']."'";
$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();
    
while($row = mysql_fetch_object($ergebnis))
   {
   $name = $row->Name;
   $path = $row->path;
   }

if (file_exists("modul/$path/unistall.php"))
{
include "modul/$path/unistall.php";
}


$abfrage = "DELETE FROM ".$dbpräfix."modul WHERE `ID` = '".$get['deinstall']."'";
$hp->mysqlquery($abfrage);
echo mysql_error();


$info->okm("Das Modul $name wurde erfolgreich Deinstalliert! (Für eine vollständige Löschung, Ordner löschen!)");


} else
if (isset($get['reinst']))
{
$value = $get['reinst'];

rename("moduls/$value/install.php.txt", "moduls/$value/install.php");
$info->okm("Das Modul im Ordner $value ist wider zur Installation bereit!");



}





echo "<b>Liste Installierter Module:</b>";

?>
<br>
<br>
<table width="745" border="0">
  <tr>
    <th width="99" scope="col">Name</th>
    <th width="101" scope="col">Pfad</th>
    <th width="359" scope="col">Beschreibung</th>
    <th colspan="2" scope="col">Optionen</th>
  </tr>
<?  
$abfrage = "SELECT * FROM ".$dbpräfix."modul";
$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();
    
while($row = mysql_fetch_object($ergebnis))
   {

?>  
  <tr>
    <td><?=$row->Name?></td>
    <td><?=$row->path?></td>
    <td><?=$row->description?></td>
    <?
    if ($row->active == "1")
    {
    ?>
    <td width="88"><a href="index.php?site=modulmanager&deak=<?=$row->ID?>">Deaktivieren</a></td>
    <td width="86"><a href="index.php?site=modulmanager&deinstall=<?=$row->ID?>">Deinstallieren</a></td>
    <?
    } else
    {
    ?>
    <td width="88"><a href="index.php?site=modulmanager&ak=<?=$row->ID?>">Aktivieren</a></td>
    <td width="86"><a href="index.php?site=modulmanager&deinstall=<?=$row->ID?>">Deinstallieren</a></td>
    <?
    }
    ?>  
  </tr>
<? 
$installed[]=$row->path;
}  
echo "</table><br><br>";
echo "<b>Liste der deinstallierten Module:</b>";

?>
<br>
<br>
<table width="745" border="0">
  <tr>

    <th width="101" scope="col">Pfad</th>

    <th colspan="2" scope="col">Optionen</th>
  </tr>
  <?
  
$filearray = array();
$modulsvor = false;
$handle = @opendir("./moduls"); 
while (false !== ($file = readdir($handle))) {

$exp = explode(".",$file);
if (count($exp) == 1)
{
$filearray[]=$file;
}

}

foreach ($filearray as $key=>$value) {

$handle = @opendir("./moduls/$value"); 
while (false !== ($file = readdir($handle))) {

if (($file == "install.php.txt") and ($value != "Muster") and (!in_array($value, $installed)))
{

  
  
  ?>
    <tr>

    <td><?=$value?></td>


    
    <td width="88"><a href="index.php?site=modulmanager&reinst=<?=$value?>">Neu installieren</a></td>
    
 
  </tr>
  
  <?
  }
  }
  }
  ?>

</table>
<?


// ...

} // Recht
?>
