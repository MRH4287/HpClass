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

//Videos -- 31. Juli 2007 
//Sollte irgendjemand diese datei finden soll er sich bitte hier verewigen:
/*
Ich hab sie gefunden°° 26.10.08


*/
//-----------------------------------------------------------------------------
if (isset($_SESSION['username']) and ($right[$level]['addvideo']))
{
//Uploadpage für Videos ....
if (!isset($post['sendfiles']))
{

?>
<table border="1" width="433" align="center" bordercolor="#9999FF">
  <tr>
    <td width="423"><b><font size="4">Eintragen als: <?echo $_SESSION['username'];?>
    
    <form action="index.php?site=videosupload" method=post>
          </font></b>
      <p>&nbsp;
      <table border="0" width="333" align="center">
        <tr>
          <td width="95">Titel:</td>
          <td width="222">
              <input type="text" name="titel" size="27" maxlength="20">
            
          </td>
        </tr>


                <tr>
        
          <td width="95">HTML Code: </td>
          <td width="222">
<textarea name="html" rows="5" cols="20"></textarea></td>
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
<? } else
{
$user = $_SESSION['username'];
$Titel = $_POST['titel'];
$html = $_POST['html'];
$datum = date('j').".".date('n').".".date('y');


$eintrag = "INSERT INTO `".$dbpräfix."videos`
(Titel, datum, User, HTML)
VALUES
('$Titel', '$datum', '$user', '$html')";
$eintragen = $hp->mysqlquery($eintrag);

echo mysql_error();
if ($eintragen== true)
{
echo "Erfolgreich eingetragen!<br><a href=index.php?site=videos>Übersicht</a>";
} 
}
} else { echo "Sie haben nicht die nötige Berechtigung diesen Bereich zu betreten!"; }
 ?>
