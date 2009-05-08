<?
class lbsites
{
var $error;
var $hp;
var $lang;
var $info;
var $fp;

function sethp($hp)
{
$this->hp = $hp;
$this->error = $hp->geterror();
$this->lang = $hp->getlangclass();
$this->info = $hp->getinfo();
$this->fp = $hp->firephp;
}

function link($site, $text, $vars = "", $class = "lbOn")
{
return '<a href="index.php?lbsite='.$site.'&vars='.$vars.'" class="'.$class.'">'.$text.'</a>';
}


function load($site, $vars)
{

ob_start("ob");

$funktions = get_class_methods($this);
?>

<table width="100%" height="100%">
<tr valign="top">
<td height=100%>


<?
if (in_array("site_".$site, $funktions))
{
$site="site_".$site;
$this->$site($vars);
} else
{
$this->fp->error("ungültige LB-Site ($site)");
echo "Seite nicht gefunden!";
}
?>
</td>
</tr>
<tr>
<td>
<?

echo "<br><br><center><a href=\"#\" onclick=\"resolution();\">Ausrichten</a><a href=\"#\" class=\"lbAction\" rel=\"deactivate\"><p align=\"right\"><img src=images/close.gif></p> </center>";
?>
</td>
</tr>
</table>
<script>resolution();</script>
<?
}

function site_Test($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."user`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
echo $row->user."<br>";
}
echo " ö Ö ä Ä ü Ü ß ^ ` ' # + * , ; |";

}

function site_newschange($site)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if (!isset ($_SESSION['username']))
{
echo "Keine Zugriffsberechtigung!";

} else if (!$right[$level]['newsedit'])
{
echo "Sie haben keine Berechtigung, Newsmeldungen zu bearbeiten!";

} else
{


    $sql = "SELECT * FROM ".$dbpräfix."news WHERE `ID` ='".$site."';";
  
   $ergebnis = $hp->mysqlquery($sql); 

while($row = mysql_fetch_object($ergebnis))
   { 
   $newstext="$row->text";
   $newstext = str_replace('<br>',"\n" ,$newstext);
   $newstext = str_replace('&lt;',"<" ,$newstext);
?>


<script type="text/javascript">
	tinyMCE.init({
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
  </select> Level <input type="text" name="newslevel" size="1" value="<?="$row->level"?>"><br>Die Berechtigungen können in der Seite "Rechte" geändert werden. Level 0 bedeutet öffentlich.</p>
  <p align="left">
  <textarea rows="15" name="newstext" cols="74" id="t1"><?=$newstext?></textarea>
  <button type="submit" name="newsedit"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button>
</form>

<? } 




}
}

function site_newnews()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$lang = $hp->getlangclass();
$right = $hp->getright();
$level = $_SESSION['level'];

if (!$right[$level]['newswrite'])
{
?>

<div align="center">
  <table border="1" width="554" height="360">
    <tr>
      <td width="554" height="360" align="center">
        <p align="center"><?=$lang->word('nonewswrite')?></p>
        <p align="center"><?=$lang->word('questions-webmaster')?></td>
    </tr>
  </table>
</div>

<?
} else
{

?>


<script type="text/javascript">
	tinyMCE.init({
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
<!-- /TinyMCE -->

<p align="left"><font size="3"><u><?=$lang->word('newnews')?></u></font></p>
<form method="POST" action="index.php?site=news">

  <p align="left"><?=$lang->word('headline')?><br>
  <input type="text" name="newstitel" size="80"></p>
    <p align="left">Typ: <select size="1" name="newstyp"> 
    <option selected>Info</option>
    <option>Event</option>
    <option>Gameserver</option>
    <option>Member</option>
  </select> Level <input type="text" name="newslevel" size="1" value ="0">
  <br>Die Berechtigungen können in der Seite "Rechte" geändert werden. Level 0 bedeutet öffentlich.

  <p align="left">

  <textarea rows="15" name="newstext" cols="74" id="t1"></textarea><!--<input type="submit" value="<?=$lang->word('post')?>" name="newswrite">--></p>
 <p align="left"> <button type="submit" name="newswrite"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></p>
</form>
<?
} // Wegen Rechte



}

function site_delnews($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['newsdel'])
{

$sql = "SELECT * FROM `$dbpräfix"."news` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie die Newsmeldung wirklich löschen?</p>
<table width="100%">
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=news">
  <p align="center"><input type="hidden" name="newsiddel" size="3" value="<?=$vars?>"><input type="submit" value="Löschen" name="newsdel"></form>
</td>
<td>
</td>
</tr>
</table>
<b>ID:</b> <?=$row->ID?><br>
<b>Newstitel:</b> <?=$row->titel?><br>
<b>Ersteller:</b> <?=$row->ersteller?><br>
<b>

<?
} else
{
echo $lang->word('noright');
}
}



} // CLASS ENDE !!

// Output buffering, da sonst alle Sonderzeichen nicht richtig dargestellt werden!
function ob($buffer)
{
$text = $buffer;
$text = str_replace("ü", "&uuml;", $text);
$text = str_replace("Ü", "&Uuml;", $text);
$text = str_replace("ö", "&ouml;", $text);
$text = str_replace("Ö", "&Ouml;", $text);
$text = str_replace("ä", "&auml;", $text);
$text = str_replace("Ä", "&Auml;", $text);
$text = str_replace("ß", "&szlig;", $text);

return $text;
}


?>