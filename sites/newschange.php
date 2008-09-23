<?php
session_start();
@include '../include/functions.php';

@include ("../include/config.php");

$hp = $this;
if (is_object($hp))
{
$obj = true;
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();



} else
{
include ("../include/config.php");
$db = mysql_connect($dbserver,
$dbuser,$dbpass)
or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";
 
mysql_select_db($dbdatenbank, $db)
or print "Die Datenbank existiert nicht.";    

}








// Rechte
$level = $_SESSION['level'];
$abfrage = "SELECT * FROM `".$dbpräfix."right`";
//$ergebnis = SQLexec($abfrage, "index");
if (is_object($hp))
{
$ergebnisss = $hp->mysqlquery($abfrage);
} else
{
$ergebnisss = mysql_query($abfrage);
}
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
   $rlevel="$row->level";
   if ("$row->ok" == "true")
   {
   $value = true;
   } else
   {
   $value = false;
   }
   $rright = "$row->right";
   
   $right[$rlevel][$rright] = $value;
   }


$kat=$_GET['kat'];

if (!isset ($_SESSION['username']))
{
echo "Keine Zugriffsberechtigung!";
exit;
} 

if (!$right[$level]['newsedit'])
{
echo "Sie haben keine Berechtigung, Newsmeldungen zu bearbeiten!";
exit;
}

//include ("../include/config.php");

//$db = mysql_connect($dbserver,
//$dbuser,$dbpass)
//or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";
 
//mysql_select_db($dbdatenbank, $db)
//or print "Die Datenbank existiert nicht.";

if (!isset ($_SESSION['username']))
{
echo "Keine Zugriffsberechtigung!";
exit;
}

?>
<p align="center"><font size="5">News Bearbeiten:</font></p>
<p align="center"><?=$error2?></p>
<p align="left"><font size="3"><u>Neue News:</u></font></p>
 <br>
<?

$newsidchange=$_POST['newsid'];
//$newsidchange=$_GET['newsid'];
//if (!isset($newsidchange))
//{
//echo "Ausnahme Fehler!<br>";
//}

if ($_POST ['newswrite'] == "Ändern") {
$newsdatum=$_POST['newsdate'];
$newstitel=$_POST['newstitel'];
$newstitel = str_replace('<',"&lt;" ,$newstitel);
$newstyp=$_POST['newstyp'];
$newstext=$_POST['newstext'];
$newstext = str_replace('<?',"&lt;?" ,$newstext);
//$newstext = str_replace("\n",'<br>',$newstext);
$newstext = str_replace('[bild]',"<img src=\"smilies/" ,$newstext);
$newstext = str_replace('[/bild]',"\">" ,$newstext);
$newslevel=$_POST['newslevel'];
//$newstext = mysql_real_escape_string($newstext);
$newstitel = mysql_real_escape_string($newstitel);
$newsersteller = mysql_real_escape_string($newsersteller);

$eingabe = "UPDATE `".$dbpräfix."news` SET `datum` = '$newsdatum', `titel` = '$newstitel', `typ` = '$newstyp', `text` = '$newstext', `level`= '$newslevel' WHERE `ID` = ".$newsidchange.";";

$ergebnis = mysql_query($eingabe);
  if ($ergebnis == true)
  {
  $error2="Erfolgreich geändert!<br>".mysql_error();
  } else {
  $error2=mysql_error();
  }
 ?>
 <a href =index.php>Zurück zur Startseite</a>
 <?
 } else {
 

$newsidchange=$_GET['newsid'];
//if (!isset($newsidchange))
//{
//echo "Ausnahme Fehler!<br>";
//}    

include ("../include/config.php");

$db = mysql_connect($dbserver,
$dbuser,$dbpass)
or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";
 
mysql_select_db($dbdatenbank, $db)
or print "Die Datenbank existiert nicht.";    

    $abfrage = "SELECT * FROM ".$dbpräfix."news WHERE `ID` =".$newsidchange;
  
$ergebnis = mysql_query($abfrage);
    

while($row = mysql_fetch_object($ergebnis))
   { 
   $newstext="$row->text";
   $newstext = str_replace('<br>',"\n" ,$newstext);
   $newstext = str_replace('&lt;',"<" ,$newstext);
?>
<script type="text/javascript">
function FensterOeffnen (Adresse) {
  MeinFenster = window.open(Adresse, "Zweitfenster", "width=600,height=600,left=100,top=200,scrollbars=1");
  MeinFenster.focus();
}
</script>
<script language="javascript" type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "t1",
		//save_callback : "customSave",
		
		plugins : "devkit,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		theme_advanced_buttons1_add_before : "",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,|,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
	//	theme_advanced_path_location : "bottom",

	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		media_external_list_url : "example_media_list.js",
		template_external_list_url : "example_template_list.js",
		file_browser_callback : "fileBrowserCallBack",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,		
	});
	</script>

<form method="POST" action="../index.php?site=newschange&kat=<?=$kat?>">

  <p align="left">Überschrift:<br>
  <input type="text" name="newstitel" size="80" value="<?="$row->titel"?>"></p>
  <input type="hidden" name="newsid" size="80" value="<?=$newsidchange?>">
  <p align="left">Datum:<br>
  <input type="text" name="newsdate" size="20" value="<?="$row->datum"?>"></p>
    <p align="left">Typ: <select size="1" name="newstyp">
    <option selected>Info</option>
    <option>Event</option>
    <option>Gameserver</option>
    <option>Member</option>
  </select> Level <input type="text" name="newslevel" size="1" value="<?="$row->level"?>"><br>0 = jeder, 1 = user, 2 = Moderator, 3 = Admin (oder jeweils alle darüber! d.h Admin kann auch 1 lesen)</p>
  <p align="left">Text: (HTML erlaubt)  &lt;br&gt; entspricht einem Return <script language="JavaScript">
 function oeffnenFernbedienung(url){
     var fernbedienung = window.open(url, "Buttons",
         "width=300,height=500,scrollbars=yes,resizable=yes");

 }
 </script>
<a href="#" onClick="oeffnenFernbedienung('../buttons/fb.php');">Öffne Button-Liste</a>  <a href="../smilies.php" onclick="FensterOeffnen(this.href); return false">Smilies</a> <br>
  <textarea rows="15" name="newstext" cols="74" id="t1"><?=$newstext?></textarea><input type="submit" value="Ändern" name="newswrite"></p>
</form>
<? } 
?>
<a href =../index.php>Zurück zur Startseite</a>
<?
}

echo "<br>$error2<br>";
?>
