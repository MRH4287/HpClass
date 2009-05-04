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

$ok = true;
// Prüfen der Zugriffsberechtigung
if (!isset($_SESSION['username']) and !isset($post))
{
echo $lang->word('noright')."<br>".$lang->word("login")."<br>";
echo $lang->word('wrotenews')."<br>";
echo $lang->word('wrotenews2');
$ok = false;
}

if (!$right[$level]['adminsite'])
{

echo $lang->word('noright2');
$ok = false;
}

if ($ok)
{


//News Delet
if (isset($post['newsdel']))
{
if (!$right[$level]['newsdel'])
{
echo $lang->word('nodelnews')."<br>".$lang->word('questions-webmaster');

} else
{



$eintrag = "DELETE FROM `".$dbpräfix."news` WHERE `ID`= ".$post['newsiddel'];
$eintragen = mysql_query($eintrag);
    if ($eintragen == false)
{
$error->error(mysql_error(), "2");
}
$eintrag2 = "DELETE FROM `".$dbpräfix."kommentar` WHERE `zuid`= ".$post['newsiddel'];
$eintragen2 = mysql_query($eintrag2);

    if ($eintragen2 == false)
{
$error->error(mysql_error(), "2");
}


}
if ($eintragen == true and $eintragen2 == true)
{
$goodn =$lang->word('delok');
} else
{
$error->error($lang->word('error-del')." ".mysql_error(), "2");
}
}


 


//News Schreiben Variablen Deklaration
$newskat=$post['newskat'];
$newstitel=$post['newstitel'];
$newstitel = str_replace('<',"&lt;" ,$newstitel);

$newstext=$post['newstext'];

$newstext = str_replace('<?',"&lt;?" ,$newstext);
$newstext = str_replace('[bild]',"<img src=\"smilies/" ,$newstext);
$newstext = str_replace('[/bild]',"\">" ,$newstext);
$newsersteller=$_SESSION['username'];

$newslevel=$post['newslevel'];
$newstyp=$post['newstyp'];
$newsdatum = date('j').".".date('n').".".date('y');
//$newstext = mysql_real_escape_string($newstext);
$newstitel = mysql_real_escape_string($newstitel);
$newsersteller = mysql_real_escape_string($newsersteller);
//$newstext = str_replace("\n",'<br>',$newstext);

//News schreiben!
if (isset($post['newswrite']))
{


if (!$right[$level]['newswrite'])
{
echo $lang->word('nonewswrite')."<br>".$lang->word('questions-webmaster');

} else
{


if (isset($newstitel) and isset($newsersteller) and isset($newsdatum) and isset($newstext) and isset($newslevel))
{

$eintrag = "INSERT INTO `".$dbpräfix."news`
(ersteller, datum, titel, typ, text, level)
VALUES
('$newsersteller', '$newsdatum', '$newstitel', '$newstyp', '$newstext', '$newslevel')";
$eintragen = $hp->mysqlquery($eintrag);


if ($eintragen == true)
{
$goodn=$lang->word('postok');


} else
{$error->error($lang->word('error-post').": ".mysql_error(), "2");}
} else
{
$error->error($lang->word('error-post'),"2");
}
}
}
//Beginn der Haupseite


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

<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
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
<p align="center"><font size="5"><u>Administration:</u></font></p>


<p align="left"><font size="3"><u><?=$lang->word('newnews')?></u></font></p>
<form method="POST" action="index.php?site=admin">

  <p align="left"><?=$lang->word('headline')?><br>
  <input type="text" name="newstitel" size="80"></p>
    <p align="left">Typ: <select size="1" name="newstyp"> 
    <option selected>Info</option>
    <option>Event</option>
    <option>Gameserver</option>
    <option>Member</option>
  </select> Level <input type="text" name="newslevel" size="1" value ="0">
<input name="newskat" type="hidden" value="Wii">

  <p align="left">

  <textarea rows="15" id="t1" name="newstext" cols="60"></textarea><!--<input type="submit" value="<?=$lang->word('post')?>" name="newswrite">--></p>
 <p align="left"> <button type="submit" name="newswrite"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></p>
</form>
<?
} // Wegen Rechte

if (!$right[$level]['newsedit'])
{


} else
{


 ?>
<p align="left">&nbsp;</p>
<p align="left"><b><a href="index.php?site=news&delet=true"><?=$lang->word('editnews')?></a></b></p>
<?
} // Wegen Rechte



if ($right[$level]['useragree'])
{

?>
<p align="left"><a href=index.php?site=anwaerter><?=$lang->word('anwerter')?><? $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());
    $number=0;
while($row = mysql_fetch_object($ergebnis))
   { $number=$number+1; }
   echo " ($number)";
    ?></a></p> <br>
    <?  }
    
    if ($right[$level]['upload'])
{ ?>
    
    <p align="left"><a href=index.php?site=upload><?=$lang->word('upload')?></a></p>
    
    <?
    }
 if ($right[$level]['moduladmin'])
{ ?>
    
    <p align="left"><a href=index.php?site=modulmanager><?=$lang->word('modulman')?></a></p>
    
    <?
    }   
    
    
 } // OK     
     ?>
