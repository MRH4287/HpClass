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


// Prüfen der Zugriffsberechtigung
if (!isset($_SESSION['username']) and !isset($post))
{
echo $lang->word('noright')."<br>".$lang->word("login")."<br>";
echo $lang->word('wrotenews')."<br>";
echo $lang->word('wrotenews2');
exit;
}

if (!$right[$level]['adminsite'])
{

echo $lang->word('noright2');
exit;
}




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

<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "t1",
		//save_callback : "customSave",
		
		plugins : "devkit,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		theme_advanced_buttons1_add_before : "",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,ltr,rtl,separator,fullscreen",
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
<p align="center"><font size="5"><u>Administration:</u></font></p>
<p align="center"><?=$goodn?></p>

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
     ?>
