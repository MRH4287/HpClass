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

if (!$right[$level]['upload'])
{
echo "Sie haben keine Berechtigung, Daten hoch zu laden!";

} else
{



if (isset($post['sendfiles']))
{
$numsendfiles = count($_Files);
$datum = date('j').".".date('n').".".date('y');
$posttitel=$post['titel'];
$username=$_SESSION['username'];
$beschreibung=$post['S1'];
$dlevel=$post['level'];
$kat = $post['kat'];


$phproot=substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-strlen($_SERVER['SCRIPT_NAME']));

/*
	foreach($_FILES as $dateiinformationen)
	{
		if($dateiinformationen['error']!=0)
		{
			$error->error("Fehler {$dateiinformationen['error']}.<br/>\n","2");
			continue;
		}
		
		if($dateiinformationen['name']=='') continue;
				
		
		$speicherort=$dateiinformationen['tmp_name'];
		
		$datei=fopen($speicherort,'r');
		$daten=fread($datei,filesize($speicherort));
		fclose($datei);
				
	

		$dateiname=$hp->escapestring($dateiinformationen['name'],$db);
		$dateityp=$hp->escapestring($dateiinformationen['type'],$db);
		$daten=$hp->escapestring($daten,$db);

		$sql = 	"INSERT INTO `".$dbpräfix."download` (titel, dateiname, datum, autor, beschreibung, level, dateityp, datei, Zeitstempel) \n".
				"VALUES ('$posttitel', '$dateiname', '$datum', '$username', '$beschreibung', '$dlevel', '$dateityp', '$daten', NOW()) \n";
		$result=$hp->mysqlquery($sql);
		if(!$result)
		{
			$error->error("Fehler beim Schreiben der Daten in die Datenbank.","2");
			
			exit;
		}
		
		
	}

*/

foreach($_FILES as $strFieldName => $arrPostFiles)
  {
  if ($arrPostFiles['size'] > 0)
     {
     $strFileName = $arrPostFiles['name'];
     $intFileSize = $arrPostFiles['size'];
     $strFileMIME = $arrPostFiles['type'];
     $strFileTemp = $arrPostFiles['tmp_name'];
     
    // echo "Datei <b>$strFileName</b> erfolgreich hochgeladen";
    $dateiname=$strFileName;
    
  $ext = explode(".", $strFileName);
  $ext = $ext[count($ext) - 1];
  $ext = strtolower($ext);
  if ($ext == "php")
  {
  $error->error("PHP Dateien sind nicht erlaubt!");
  echo "Fehler: PHP Dateien sind nicht erlaubt!<br>";
  echo "<a href=\"index.php?site=upload\">Zurück</a>";
  
  } else
  {
  
    
$eintrag = "INSERT INTO `".$dbpräfix."download`
(dateiname, titel, datum, autor, beschreibung, level, kat, Zeitstempel)
VALUES
('$dateiname', '$posttitel', '$datum', '$username', '$beschreibung', '$dlevel', '$kat', NOW())";
$eintragen = mysql_query($eintrag);
move_uploaded_file($strFileTemp, "downloads/$strFileName");
echo mysql_error();
if ($eintragen== true)
{
echo "Erfolgeich eingetragen!";
}
}
     }
   }



if ($result== true)
{
echo "Erfolgeich eingetragen!";
}
     
   
} elseif (isset($get['upload'])) {
?>

<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423"><b><font size="4">Hochladen als: <?php echo $_SESSION['username'];?>
    
    <form enctype="multipart/form-data" action="index.php?site=upload" method=post>
          </font></b>
      <p>&nbsp;
      <table border="0" width="333" align="center">
        <tr>
          <td width="95">Titel:</td>
          <td width="222">
              <input type="text" name="titel" size="20" maxlength="20">
            
          </td>
        </tr>
                <tr>
          <td width="95">Level:</td>
          <td width="222">
              <input type="text" name="level" size="20" maxlength="20" value="0">
            
          </td>
        </tr>
        <tr>
        
          <td width="95">Datei: </td>
          <td width="222">
<input name="userfile" type="file"></td>
        </tr>
        <tr>
          <td width="95">Beschreibung:</td>
          <td width="222">

   <textarea rows="3" name="S1" cols="24" ></textarea>
               </td>
        </tr>
         <tr>
          <td width="95">Katigorie:</td>
          <td width="222">
          
          <select name="kat">
      <?php
       $abfrage = "SELECT * FROM ".$dbpräfix."download_kat";
$ergebnis = $hp->mysqlquery($abfrage);
   echo mysql_error(); 
 
while($row2 = mysql_fetch_object($ergebnis))
   {
      
      ?>   
          <option value="<?php echo $row2->ID?>"><?php echo $row2->name; ?></option>   
   <?php
  } 
   ?>
      </select> <a href="index.php?site=upload&katnew">Katigorien erstellen</a>
               </td>
        </tr>
        <tr>
          <td width="95"></td>
          <td width="222">

               </td>
        </tr>
        <tr>
          <td colspan="2" width="261">
            <p align="center"><input type="submit" value="Daten Abschicken" name="sendfiles">      
      
      </form>
      
      
      
          </td>
        </tr>
      </table>
      &nbsp;
      <p>&nbsp;      
      
      
      
      
      
      </td>
  </tr>
</table>
<?php } elseif (isset($get['filechange']))
{

 $abfrage2 = "SELECT * FROM ".$dbpräfix."download WHERE `ID` = '".$get['filechange']."'";
$ergebnis2 = $hp->mysqlquery($abfrage2);
    
 
while($row = mysql_fetch_object($ergebnis2))
   {

?>

<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423">
    
    <form enctype="multipart/form-data" action="index.php?site=upload" method=post>
          </font>
      <p>&nbsp;
      <table border="0" width="333" align="center">
        <tr>
          <td width="95">Titel:</td>
          <td width="222">
              <input type="text" name="titel" size="20" maxlength="20" value="<?php echo $row->titel?>">
            
          </td>
        </tr>
                <tr>
          <td width="95">Level:</td>
          <td width="222">
              <input type="text" name="level" size="20" maxlength="20" value="<?php echo $row->level?>">
            
          </td>
        </tr>
        <tr>
        
          <td width="95">Datei: </td>
          <td width="222">
<input name="userfile" type="file"></td>
        </tr>
        <tr>
          <td width="95">Beschreibung:</td>
          <td width="222">

   <textarea rows="3" name="S1" cols="24" ><?php echo $row->beschreibung?></textarea>
               </td>
        </tr>
                <tr>
          <td width="95">Katigorie:</td>
          <td width="222">
          
          <select name="kat">
      <?php
       $abfrage = "SELECT * FROM ".$dbpräfix."download_kat";
$ergebnis = $hp->mysqlquery($abfrage);
   echo mysql_error(); 
 
while($row2 = mysql_fetch_object($ergebnis))
   {
      
      ?>   
          <option value="<?php echo $row2->ID?>"><?php echo $row2->name; ?></option>   
   <?php
  } 
   ?>
      </select> <a href="index.php?site=upload&katnew">Katigorien erstellen</a>
               </td>
        </tr>
        
        <tr>
          <td width="95"></td>
          <td width="222">

               </td>
        </tr>
        <tr>
          <td colspan="2" width="261">
            <p align="center"><input type="submit" value="Daten Abschicken" name="changefile">      
      <input type="hidden" value="<?php echo $row->ID?>" name="ID">
      </form>
      
      
      
          </td>
        </tr>
      </table>
</td>
</tr>
</table>

<?php
}

} elseif (isset($post['changefile']))
{

$datum = date('j').".".date('n').".".date('y');
$posttitel=$post['titel'];
$username=$_SESSION['username'];
$beschreibung=$post['S1'];
$dlevel=$post['level'];
$dID = $post['ID'];
$dkat = $post['kat'];
$posttitel = $hp->escapestring($posttitel);
$beschreibung = $hp->escapestring($beschreibung);
$dlevel = $hp->escapestring($dlevel);
$fp = $this->firephp;


if ($_FILES['userfile']['error'] != "4")
{
foreach($_FILES as $strFieldName => $arrPostFiles)
  {
  if ($arrPostFiles['size'] > 0)
     {
     $strFileName = $arrPostFiles['name'];
     $intFileSize = $arrPostFiles['size'];
     $strFileMIME = $arrPostFiles['type'];
     $strFileTemp = $arrPostFiles['tmp_name'];
     
    // echo "Datei <b>$strFileName</b> erfolgreich hochgeladen";
    $dateiname=$strFileName;
    
  $ext = explode(".", $strFileName);
  $ext = $ext[count($ext) - 1];
  $ext = strtolower($ext);
  if ($ext == "php")
  {
  $error->error("PHP Dateien sind nicht erlaubt!");
  echo "Fehler: PHP Dateien sind nicht erlaubt!<br>";
  echo "<a href=\"index.php?site=upload\">Zurück</a>";
  
  } else
  {
  
    
$sql = "UPDATE `".$dbpräfix."download` SET `titel` = '$posttitel',
`level` = '$dlevel',
`kat` = '$dkat',
`dateiname` = '$dateiname',
`Zeitstempel` = now(),
`beschreibung` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";

$eintragen = $hp->mysqlquery($sql);
echo mysql_error();


move_uploaded_file($strFileTemp, "downloads/$strFileName");
echo mysql_error();
if ($eintragen== true)
{
$info->okm("Datei erfolgreich Modifiziert!");
echo "Datei erfolgreich modifiziert!<br><a href=index.php?site=upload>zurück</a>";
}
}
     }
   }

} else
{
$sql = "UPDATE `".$dbpräfix."download` SET `titel` = '$posttitel',
`level` = '$dlevel',
`kat` = '$dkat',

`beschreibung` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";

$res = $hp->mysqlquery($sql);
echo mysql_error();
if ($res) 
{
$info->okm("Datei erfolgreich Modifiziert!");
echo "Datei erfolgreich modifiziert!<br><a href=index.php?site=upload>zurück</a>";
}


}







} elseif (isset($get['katnew']))
{
?>
<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423"><b><font size="4">
    
    <form enctype="multipart/form-data" action="index.php?site=upload" method=post>
          </font></b>
      <p>&nbsp;
      <table border="0" width="333" align="center">
        <tr>
          <td width="95">Titel:</td>
          <td width="222">
              <input type="text" name="titel" size="20" maxlength="20">
            
          </td>
        </tr>
                <tr>
          <td width="95">Level:</td>
          <td width="222">
              <input type="text" name="level" size="20" maxlength="20" value="0">
            
          </td>
        </tr>

        <tr>
          <td width="95">Beschreibung:</td>
          <td width="222">

   <textarea rows="3" name="S1" cols="24" ></textarea>
               </td>
        </tr>

        <tr>
          <td width="95"></td>
          <td width="222">

               </td>
        </tr>
        <tr>
          <td colspan="2" width="261">
            <p align="center"><input type="submit" value="Daten Abschicken" name="katnew">      
      
      </form>
      
      
      
          </td>
        </tr>
      </table>
</td></tr></table>

<?php
} elseif (isset($post['katnew']))
{

$posttitel=$post['titel'];
$beschreibung=$post['S1'];
$dlevel=$post['level'];

$posttitel = $hp->escapestring($posttitel);
$beschreibung = $hp->escapestring($beschreibung);
$dlevel = $hp->escapestring($dlevel);



$eintrag = "INSERT INTO `".$dbpräfix."download_kat`
(name, description, level)
VALUES
('$posttitel', '$beschreibung', '$dlevel')";
$eintragen = mysql_query($eintrag);

echo mysql_error();
if ($eintragen== true)
{
echo "Erfolgeich erstellt!";
echo "<br><a href=index.php?site=upload>Download Manager</a>";
}



} elseif (isset($get['katchange']))
{
 $abfrage2 = "SELECT * FROM ".$dbpräfix."download_kat WHERE `ID` = '".$get['katchange']."'";
$ergebnis2 = $hp->mysqlquery($abfrage2);
    
 
while($row = mysql_fetch_object($ergebnis2))
   {
?>
<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423"><b><font size="4">
    
    <form enctype="multipart/form-data" action="index.php?site=upload" method=post>
          </font></b>
      <p>&nbsp;
      <table border="0" width="333" align="center">
        <tr>
          <td width="95">Titel:</td>
          <td width="222">
              <input type="text" name="titel" size="20" maxlength="20" value="<?php echo $row->name?>">
            
          </td>
        </tr>
                <tr>
          <td width="95">Level:</td>
          <td width="222">
              <input type="text" name="level" size="20" maxlength="20" value="<?php echo $row->level?>">
            
          </td>
        </tr>

        <tr>
          <td width="95">Beschreibung:</td>
          <td width="222">

   <textarea rows="3" name="S1" cols="24" ><?php echo $row->description?></textarea>
               </td>
        </tr>

        <tr>
          <td width="95"></td>
          <td width="222">

               </td>
        </tr>
        <tr>
          <td colspan="2" width="261">
            <p align="center"><input type="submit" value="Daten Abschicken" name="katchange">      
      <input type="hidden" value="<?php echo $row->ID?>" name="ID">
      </form>
      
      
      
          </td>
        </tr>
      </table>
</td></tr></table>

<?php
}
} elseif (isset($post['katchange']))
{


$posttitel=$post['titel'];
$beschreibung=$post['S1'];
$dlevel=$post['level'];
$dID=$post['ID'];

$posttitel = $hp->escapestring($posttitel);
$beschreibung = $hp->escapestring($beschreibung);
$dlevel = $hp->escapestring($dlevel);



$sql = "UPDATE `".$dbpräfix."download_kat` SET `name` = '$posttitel',
`level` = '$dlevel',


`description` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";

$res = $hp->mysqlquery($sql);
echo mysql_error();

if ($res) 
{
$info->okm("Katigorie erfolgreich Modifiziert!");
echo "Katigorie erfolgreich modifiziert!<br><a href=index.php?site=upload>zurück</a>";
}


} elseif (isset($get['katdel']))
{

  $abfrage = "SELECT * FROM ".$dbpräfix."download WHERE `kat` = '".$get['katdel']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
$x = 0;   
while($row = mysql_fetch_object($ergebnis))
   {
   $x= $x +1;
}

if ($x != 0)
{
$error->error("Sie können keine Katigorien löschen, in denen sich Dateien befinden!","2");
echo "<a href=index.php?site=upload>Zurück</a>";
} else
{
$del = $get['katdel'];
$info->info("Möchten Sie die Katigorie wirklick löschen? <a href=index.php?site=upload&katdel2=$del>Ja</a> <a href=index.php>Nein</a>");
}


} elseif (isset($get['katdel2']))
{

$abfrage = "DELETE FROM ".$dbpräfix."download_kat WHERE `ID`=".$_GET['katdel2'];
$ergebnis = $hp->mysqlquery($abfrage);
    

    
  if ($ergebnis == true) 
  {
 echo $lang->word('delok');
 echo "<br><a href=index.php?site=upload>Zurück</a>";
  } else
  {
  $error->error("Fehler: ".mysql_error(),"2");
  }

} else
{
?>

<style type="text/css">
<!--
.Stil1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style>

<div align="center" class="Stil1">
  <p>Datei Manager</p>
  <p>&nbsp;</p>
</div>
<center><table width="731" height="148" border="0">
  <tr>
    <th height="26"><div align="center"><a href="index.php?site=download&change">Dateien bearbeiten</a></div></th>
    <td><div align="center"><strong><a href="index.php?site=upload&upload">Datei hochladen</a></strong></div></td>
  </tr>

  <tr>
    <th height="21"><div align="center"><a href="index.php?site=upload&katnew">Katigorie erstellen</a></div></th>
    <td><div align="center"><strong><a href="index.php?site=download&katchange">Katigorien bearbeiten</a></strong></div></td>
  </tr>
</table></center>


<?php
}

}?>
