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


$phproot=substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-strlen($_SERVER['SCRIPT_NAME']));


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




if ($result== true)
{
echo "Erfolgeich eingetragen!";
}
     
   
} else {
?>

<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423"><b><font size="4">Hochladen als: <?echo $_SESSION['username'];?>
    
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
<?}

}?>
