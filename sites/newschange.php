<?php
session_start();


//@include ("../include/config.php");

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

$eingabe = "UPDATE `".$dbpräfix."news` SET `datum` = '$newsdatum', `titel` = '$newstitel', `typ` = '$newstyp', `text` = '$newstext', `level`= '$newslevel' WHERE `ID` = '".$newsidchange."';";

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
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

	});
</script>

<form method="POST" action="../index.php?site=newschange">

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
  <p align="left">Text:
  <textarea rows="15" name="newstext" cols="74" id="t1"><?=$newstext?></textarea><input type="submit" value="Ändern" name="newswrite"></p>
</form>
<? } 
?>
<a href =../index.php>Zurück zur Startseite</a>
<?
}

echo "<br>$error2<br>";
?>
