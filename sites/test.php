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
$lbs = $hp->lbsites;


echo "<b>Dies ist eine Testseite!</b><br>Solltet ihr sie finden, ignoriert sie ^^<br><br><hr>";

//print_r($_SESSION);

$error->error("TEST", "2");
$info->info("123");
$info->okn("123");

//echo $lbs->link("newschange", "Newsmeldung ändern", "1");
$site=1;
   $sql = "SELECT * FROM ".$dbpräfix."news WHERE `ID` ='".$site."';";
  
   $ergebnis = $hp->mysqlquery($sql); 

while($row = mysql_fetch_object($ergebnis))
   { 
   $newstext="$row->text";
   $newstext = str_replace('<br>',"\n" ,$newstext);
   $newstext = str_replace('&lt;',"<" ,$newstext);
?>

  



 
<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /TinyMCE -->

<form method="POST" action="index.php?site=news">

  <p align="left">Überschrift:<br>
  <input type="text" name="newstitel" size="80" value="<?="$row->titel"?>"></p>
  <input type="hidden" name="newsid" size="80" value="<?=$site?>">
  <p align="left">Datum:<br>
  <input type="text" name="newsdate" size="20" value="<?="$row->datum"?>"></p>
    <p align="left">Typ: <select size="1" name="newstyp">
    <option selected>Info</option>
    <option>Event</option>
    <option>Gameserver</option>
    <option>Member</option>
  </select> Level <input type="text" name="newslevel" size="1" value="<?="$row->level"?>"><br>0 = jeder, 1 = user, 2 = Moderator, 3 = Admin (oder jeweils alle darüber! d.h Admin kann auch 1 lesen)</p>
  <p align="left">
  <textarea rows="15" name="newstext" cols="74" id="t1"><?=$newstext?></textarea><input type="submit" value="Ändern" name="newswrite"></p>
</form>
<? } 


//$lang->savetodb();



?>

