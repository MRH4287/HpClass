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
<center><a href="#" onclick="resolution();">Ausrichten</a><br>

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

echo "<br><br><a href=\"#\" class=\"lbAction\" rel=\"deactivate\"><p align=\"right\"><img src=images/close.gif></p> </center>";
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

function site_delvote($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['manage_vote'])
{

$sql = "SELECT * FROM `$dbpräfix"."vote` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie die Umfrage wirklich entfernen?</p>
<table width="100%">
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=vote">
  <p align="center"><input type="hidden" name="voteiddel" size="3" value="<?=$vars?>"><input type="submit" value="Löschen" name="votedel"></form>
</td>
<td>
</td>
</tr>
</table>
<b>ID:</b> <?=$row->ID?><br>
<b>Titel:</b> <?=$row->name?><br>
<b>

<?
} else
{
echo $lang->word('noright');
}
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


function site_forum_editpost($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$get = $hp->get();

$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `ID` = '$vars'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


?>
<table width="60%" border="0" align="center">
  <tr>
    <td><script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"

	});
</script>
<!-- /TinyMCE -->
<center><b>Bearbeiten</b></center>
<form action="index.php?site=forum&show=<?=$row->threadid?>" method="post">
<textarea name="text" cols="100" rows="15"><?=$row->text?></textarea>
<input type="hidden" name="postid" value="<?=$row->ID?>"><br>
<button type="submit" name="forum_editpost"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button>
</form>
</td>
  </tr>
</table>
<?

}

function site_forum_editthread($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$get = $hp->get();
$right = $hp->getright();
$level = $_SESSION['level'];

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = '$vars'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);




?>
<table width="60%" border="0" align="center">
  <tr>
    <td><script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"

	});
</script>
<!-- /TinyMCE -->

<form action="index.php?site=forum&show=<?=$row->ID?>" method="post">
<center><table border="1" widht="90%">
<tr>
<td>
<table width="100%" border="0">
  <tr>
    <td width="80">&nbsp;</td>
    <td colspan="2">Bearbeiten</td>
  </tr>
  <tr>
    <td>Thema:</td>
    <td width="80"> &nbsp;
    <table border="0" width="100%" height="5">
    <tr>
    <td width="90%">
    <input type="text" name="titel" id="titel" value="<?=$row->titel?>"></td>
    </td>
    <td width="10%">
    <a href="#" onclick="document.getElementById('more').style.display = '';">Erweitert</a>
    </td>
    </tr>
    </table>
  </tr>
 
 <tr>
  <td colspan="3">
  <div id="more" style="display:none;">
  <table border="0" width="100%">
  <tr>
    <td width="80">Level:</td>
    
    <td width="85%"><table width="100%">
    <?
    $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` <= '$level';";
    $erg2 = $hp->mysqlquery($sql);
    while ($row2 = mysql_fetch_object($erg2))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?=$row2->level?>" <? if ($row->level == $row2->level) { echo " checked=\"true\"";} ?>>
          <?=$row2->name?></label></td>
      </tr>
     <?
    }
     ?> 
    </table>
      <p>Notiz: Jeder Benutzer eines höheren Levels kann dieses Forum trotzdem lesen!</p></td>
  </tr>
  <? if ($right[$level]['forum_canusetypes']) { ?>
    <tr>
    <td>Type</td>
    <td>
   <table width="100%">
      <tr>
        <td><label>
          <input type="radio" name="type" value="0"  <? if ($row->type == "0") { echo " checked=\"true\"";} ?>>
          Normal</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="1"   <? if ($row->type == "1") { echo " checked=\"true\"";} ?>>
          Sticky</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="2"   <? if ($row->type == "2") { echo " checked=\"true\"";} ?>>
          Announce</label></td>
      </tr>
   
   
   </table>
   
   </td>
  </tr>
 <? } else
 {
 ?>
 <input type="hidden" name="type" value="0">
 <?
 } ?> 
  <tr>
    <td>Passwort</td>
    <td><p>
      <input type="text" name="passwort" id="passwort"  value="*">
    </p>
    <p>Notiz: Frei Lassen für öffentlich. * Lassen für keine Änderung.</p></td>
  </tr>
  <tr>
    <td>Sichtbar:</td>
    <td><p>
        <input type="checkbox" name="visible" id="visible"  <? if ($row->visible == "1") { echo " checked=\"true\"";} ?>> 
      Ja</p>
      <p>Notiz: Das Thema ist für Benutzer eines geringeren Levels Sichtbar, Sie können aber nicht Antworten.</p></td>
  </tr>
  </table>
</div> </td>

 <tr>
    <td>Text:</td>
    <td colspan="2"><textarea name="text" id="text" cols="100" rows="15"><?=$row->text?></textarea></td>
  </tr>
 <tr>
   <td>&nbsp;</td>
   <td colspan="2"><button type="submit" name="forum_editthread"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></td>
 </tr>
</table>
</td>
</tr>
</table>
</center>
<input type="hidden" name="postid" value="<?=$vars?>">
</form>

<?

}


function site_forum_movethread($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;



?>
<br>
<form action="index.php?site=forum&show=<?=$vars?>" method="post">
<table width="200" border="0">
  <tr>
    <td>&nbsp;</td>
    <td>Verschieben</td>
  </tr>
  <tr>
    <td>Wohin:</td>
    <td><select name="moveto" id="moveto">
    <?
    $sql = "SELECT * FROM `$dbpräfix"."forums`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
    
    ?>
    <option value="<?=$row->ID?>"><?=$row->titel?></option>
    <?
    }
    ?>
    
    </select>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><button type="submit" name="forum_movethread"> <img src="images/ok.gif"> </button></td>
  </tr>
</table>
<input type="hidden" name="postid" value="<?=$vars?>">
</form>
<?

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