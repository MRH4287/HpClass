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


$datum = date('j').".".date('n').".".date('y');

if (isset($_SESSION['username']))
{

 ?>
 <center><table width="21%" border="0">
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="col"><div align="center"><span class="Stil2"><a href="index.php?site=pm">PM-Menu</a></span></div></th>
  </tr>
</table></center>

<?php
if (!isset($get['read']) and !isset($post['del']) and !isset($post['mark']) and !isset($get['new']) and !isset($get['del']) and !isset($get['ausgang']) and !isset($get['report']) and !isset($get['getres']))
{

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `zu` = '".$_SESSION['username']."' ORDER BY `ID` DESC;";
$ergebnis = $hp->mysqlquery($abfrage);
if ($ergebnis == false)
{
$error->error(mysql_error(), "2");
}
    

 

?>
 <script type="text/javascript">
<!-- Begin
function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
	return false;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
	return false;
}
//  End -->
</script>


<form action="index.php?site=pm" method="POST" name="selform">
<table width="100%" border="0">
  <tr bgcolor="<?php echo $defaultcolor?>">
    <th height="25" scope="col"><!--ID:--></th>
    <th scope="col"><?php echo $lang->word('absender')?>:</th>
    <th scope="col"><?php echo $lang->word('betreff')?>:</th>
    <th scope="col"><?php echo $lang->word('datum')?>:</th>
    <th scope="col">&nbsp;</th>
  </tr>
  
  <?php
  $numbers = 0;
  while($row = mysql_fetch_object($ergebnis))
  {
  $numbers = $numbers +1;
   ?>
   <tr bgcolor="#4E6F8">
    <td scope="col"><div align="center"><!--<?php echo "$row->ID"?>--><input type="checkbox" name="sel[]" value="<?php echo "$row->ID"?>">
                                                              </div></td>
    <td scope="col"><div align="center"><?php echo "$row->von"?></div></td>
    <td scope="col"><div align="center"><a href="index.php?site=pm&read=<?php echo "$row->ID"?>"><?php if ("$row->gelesen" == "0") { echo "<b>"; } ?><?php echo "$row->Betreff"?><?php if ("$row->gelesen" == "0") { echo "</b>"; } ?></a></div></td>
    <td scope="col"><div align="center"><?php echo "$row->Datum"?></div></td>
    <td scope="col"><div align="center"><!--<a href=index.php?site=pm&del=<?php echo "$row->ID"?>>Löschen</a>--></div></td>
 </tr>
 
 
  <?php 
  
  }?>  

</table>
<!--
  <input type=button  value="Check All"	onClick="checkAll(document.selform)">
 <input type=button value="Uncheck All"	onClick="uncheckAll(document.selform)">
-->
<a href="#" onClick="checkAll(document.selform)">Check all</a>
<a href="#" onClick="uncheckAll(document.selform)">Uncheck all</a>

  <table width="100%" border="0">
  <?php if ($numbers != 0){ ?>
  <tr>
  <th bgcolor="#4E6F8" width="25%"><input type="submit" name="del" value="<?php echo $lang->word('delet')?>"><input type="submit" name="mark" value="Als gelesen makieren"></form></th>
    <th bgcolor="<?php echo $defaultcolor?>" width="50%"><a href="index.php?site=pm&new"><?php echo $lang->word('newpm')?></a>
                                      </th>
    <td width="25%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href="index.php?site=pm&ausgang"><?php echo $lang->word('postausgang')?></a></div></td>
  </tr>
  <?php } else { ?>
   <tr>
    <th bgcolor="<?php echo $defaultcolor?>" width="50%"><a href="index.php?site=pm&new"><?php echo $lang->word('newpm')?></a>
                                      </th>
    <td width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href="index.php?site=pm&ausgang"><?php echo $lang->word('postausgang')?></a></div></td>
  </tr> 
  <?php } ?>
</table>


<?php
} elseif (isset($get['read']))
{
ob_start(showcontent);
$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `ID` = '".$get['read']."' ORDER BY `ID`;";
$ergebnis = $hp->mysqlquery($abfrage);
      while($row = mysql_fetch_object($ergebnis))
  {
    if ((strtolower($_SESSION['username']) == strtolower("$row->zu")) or strtolower($_SESSION['username']) == strtolower("$row->von"))
  {
 if ($_SESSION['username'] != "$row->von") { 
  $eingabe2 = "UPDATE `".$dbpräfix."pm` SET `gelesen` = '1' WHERE `ID` = $row->ID;";

$ergebnis2 = $hp->mysqlquery($eingabe2);
}   

  
?>

<table width="100%" border="0">
<?php if ($_SESSION['username'] != "$row->von") { ?>
  <tr>
    <th width="22%" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('von')?>:</th>
    <th width="78%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=user&show=<?php echo "$row->von"?>><?php echo "$row->von"?></a></div></th>
  </tr>
  <?php } else { ?>
    <tr>
    <th width="22%" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('von')?>:</th>
    <th width="78%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=user&show=<?php echo "$row->von"?>><?php echo "$row->von"?></div></th>
  </tr>
    <tr>
    <th width="22%" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('für')?>:</th>
    <th width="78%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=user&show=<?php echo "$row->zu"?>><?php echo "$row->zu"?></div></th>
  </tr>
  
  <?php } ?>
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('datum')?>:</th>
    <td bgcolor="<?php echo $defaultcolor?>"><div align="center"><?php echo "$row->Datum"?></div></td>
  </tr>
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('betreff')?></th>
    <td bgcolor="<?php echo $defaultcolor?>"><div align="center"><?php echo "$row->Betreff"?></div></td>
  </tr>
  <tr>
    <th colspan="2" scope="row" ><br><div align="left"><?php echo "$row->Text"?></div></th>
  </tr>
  
  </table>
  <?php if ($_SESSION['username'] != "$row->von") { ?>
  <table width="100%" border="0">
  
  <tr>
        <th width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href="index.php?site=pm&new&to=<?php echo "$row->von"?>&bet=<?php echo "$row->Betreff"?>"><?php echo $lang->word('antworten')?></a></div></th>
    <td width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=pm&del=<?php echo "$row->ID"?>><?php echo $lang->word('delet')?></a></div></td>
  </tr>
</table>


<?php
ob_end_flush();
}
} else
{
$error->error($lang->word('messagenotforyou'),"2");
}

}
//POST DEL
}elseif (isset($post['del']))
{
$dellarr = array();
$dellarr = $post['sel'];

foreach ($dellarr as $id)
{

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `ID` = '".$id."' ORDER BY `ID`;";
$ergebnis = $hp->mysqlquery($abfrage);
      while($row = mysql_fetch_object($ergebnis))
  {
  if (strtolower($_SESSION['username']) == strtolower("$row->zu"))
  {
  $ok=true;
  }
  }
  if ($ok == true)
  {
  $eintrag = "DELETE FROM `".$dbpräfix."pm` WHERE `ID`= ".$id;
$eintragen = $hp->mysqlquery($eintrag);


if ($eintragen == true)
{
echo $lang->word('delok')."<br>";
} else
{
echo mysql_error();
}
  }
  else
  {
  $error->error($lang->word('cantdelmessage'),"2", __FILE__.":".__LINE__);
  }
} //foreach

} elseif (isset($post['mark']))
{

$sellarr = array();
$sellarr = $post['sel'];

foreach ($sellarr as $id)
{

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `ID` = '".$id."' ORDER BY `ID`;";
$ergebnis = $hp->mysqlquery($abfrage);
      while($row = mysql_fetch_object($ergebnis))
  {
  if (strtolower($_SESSION['username']) == strtolower("$row->zu"))
  {
  $ok=true;
  }
  }
  if ($ok == true)
  {
  $eintrag = "UPDATE `".$dbpräfix."pm` SET `gelesen` = 1 WHERE `ID`= ".$id;
$eintragen = $hp->mysqlquery($eintrag);


if ($eintragen == true)
{
echo "Als gelesen makiert<br>";
} else
{
echo mysql_error();
}

}
}
//GET DEL
} elseif (isset($get['del']))
{
$del = $get['del'];

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `ID` = '".$del."' ORDER BY `ID`;";
$ergebnis = $hp->mysqlquery($abfrage);
      while($row = mysql_fetch_object($ergebnis))
  {
  if ($_SESSION['username'] == "$row->zu")
  {
  $ok=true;
  }
  }
  if ($ok == true)
  {
  $eintrag = "DELETE FROM `".$dbpräfix."pm` WHERE `ID`= ".$del;
$eintragen =$hp->mysqlquery($eintrag);


if ($eintragen == true)
{
echo $lang->word('delok')."<br>";
} else
{
echo mysql_error();
}
  }
  else
  {
  $error->error($lang->word('cantdelmessage'),"2", __FILE__.":".__LINE__);
  }
 //foreach


}elseif (isset($get['new']))
{

if (isset($get['bet']))

{
$bet = "RE: ".$get['bet'];
}

if (isset ($post['post']))
{
//POST
$von = $_SESSION['username'];
$zu = $post['zu'];
$text = $post['Text'];
$text = str_replace('[bild]',"<img src=\"smilies/" ,$text);
$text = str_replace('[/bild]',"\">" ,$text);
//$text =  str_replace("\n",'<br>',$text);
$timestamp = $post['timestamp'];
$Betreff = $post['Betreff'];
if (!isset($Betreff) or $Betreff == "")
{
$Betreff = "Nix (Kein Betreff angegeben)";
}

$abfrage = "SELECT * FROM ".$dbpräfix."pm WHERE `timestamp` = '".$timestamp."'";
$ergebnis = $hp->mysqlquery($abfrage);
 
  $nummer = 0;  
while($row = mysql_fetch_object($ergebnis))
{
$nummer++;
}

if ($nummer == 0)
{
$eintrag = "INSERT INTO `".$dbpräfix."pm`
(von, datum, zu, text, Betreff, gelesen, timestamp)
VALUES
('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$timestamp')";

$eintragen = $hp->mysqlquery($eintrag);
if ($eintragen == true)
{
echo $lang->word('postok');

} else
{
echo mysql_error();
}
} else
{
$error->error($lang->word('doublepost'),"2");
}

} else {

?>

 <script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "ta",
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
<?php echo '<form action="index.php?site=pm&new&post&" '. SID .'method="post">' ?>
<table width="100%" border="0">
  <tr>
    <th colspan="2" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('newpm')?>: </th>
  </tr>
  <tr>
    <th width="31%" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('empfänger')?>:</th>
    <th width="69%" scope="col"><div align="center">
      <label>
      <input name="zu" size="80"  type="text" value="<?php echo $get['to']?>" />
      </label>
    </div></th>
  </tr>
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('betreff')?>:</th>
    <td><div align="center"><input type="text" name="Betreff" size="80" value="<?php echo $bet?>" /></div></td>
  </tr>
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('text')?>:</th>
    <td><div align="center">
      <label>
      <textarea name="Text" cols="80" rows="20" id="ta"></textarea>
      </label>
    </div></td>
  </tr>
</table>
  <table width="100%" border="0">
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" width="50%"><input type="submit" name="post" value="<?php echo $lang->word('post')?>">
                                      </th>
    <td width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=pm><?php echo $lang->word('back')?></a></div></td>
  </tr>
</table>
<input name="timestamp" size="200"  type="hidden" value="<?php echo time()?>" />
</form>
  <table width="100%" border="0">
 <!-- <tr>
    <th bgcolor="<?php echo $defaultcolor?>" width="50%"><a href="smilies.php" onclick="FensterOeffnen(this.href); return false">Smilies</a></th>
    <td width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"> <a href="#" onClick="oeffnenFernbedienung('buttons/fb.php');">Öffne Button-Liste</a></div></td>
  </tr> -->
</table>
<?php
}



}elseif (isset($get['ausgang']))
{
//Ausgang

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `von` = '".$_SESSION['username']."' ORDER BY `ID`;";
$ergebnis =$hp->mysqlquery($abfrage);

 

?>
<table width="100%" border="0">
  <tr bgcolor="<?php echo $defaultcolor?>">
    <th height="25" scope="col">ID:</th>
    <th scope="col"><?php echo $lang->word('empfänger')?>:</th>
    <th scope="col"><?php echo $lang->word('betreff')?>:</th>
    <th scope="col"><?php echo $lang->word('datum')?>:</th>
    <th scope="col"><?php echo $lang->word('gelesen')?>:</th>
  </tr>
  
  <?php
  while($row = mysql_fetch_object($ergebnis))
  {
   ?>
   <tr bgcolor="<?php echo $defaultcolor?>">
    <td scope="col"><div align="center"><?php echo "$row->ID"?></div></td>
    <td scope="col"><div align="center"><?php echo "$row->zu"?></div></td>
    <td scope="col"><div align="center"><a href="index.php?site=pm&read=<?php echo "$row->ID"?>"><?php echo "$row->Betreff"?></a></div></td>
    <td scope="col"><div align="center"><?php echo "$row->Datum"?></div></td>
    <td scope="col"><div align="center"><?php if ("$row->gelesen" == "1") { echo "<b>".$lang->word('yes')."</b>"; } else { echo $lang->word('no'); } ?></div></td>
  </tr>
  <?php 
  
  }?>  
  
</table>
<?php
}


}
elseif (!isset($get['report']))
{
$error->error($lang->word('login'),"2");

}

if (isset($get['report']))
{
if (!isset($get['post']))
{
?>
 <script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "ta",
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
<form action="index.php?site=pm&report&post" method="post">
<table width="100%" border="0">
  <tr>
    <th colspan="2" bgcolor="<?php echo $defaultcolor?>" scope="col"><?php echo $lang->word('posterror')?>: </th>
  </tr>

  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('betreff')?>:</th>
    <td><div align="center"><input type="text" name="Betreff" size="80" value="<?php echo $bet?>" /></div></td>
  </tr>
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" scope="row"><?php echo $lang->word('text')?>:</th>
    <td><div align="center">
      <label>
      <textarea name="Text" cols="80" rows="20" id="ta"></textarea>
      </label>
    </div></td>
  </tr>
</table>
  <table width="100%" border="0">
  <tr>
    <th bgcolor="<?php echo $defaultcolor?>" width="50%"><input type="submit" name="post" value="<?php echo $lang->word('post')?>">
                                      </th>
    <td width="50%" bgcolor="<?php echo $defaultcolor?>"><div align="center"><a href=index.php?site=pm><?php echo $lang->word('back')?></a></div></td>
  </tr>
</table>
<input name="timestamp" size="200"  type="hidden" value="<?php echo time()?>" />
<input name="zu" size="80"  type="hidden" value="mrh" />
</form>

<?php
} else
{
//POST

//POST
if (isset($_SESSION['username'])) { $von = $_SESSION['username']; } else {$von = "Anonym"; }

$zu = $post['zu'];
$text = $post['Text'];
//$text =  str_replace("\n",'<br>',$text);
$Betreff = "ERROR: ".$post['Betreff'];
$timestamp = $post['timestamp'];

$text = str_replace("<iframe", " >iframe", $text);
$text = str_replace("<script", " >script", $text);
$text = str_replace("<style", " >style", $text);

$abfrage = "SELECT * FROM ".$dbpräfix."pm WHERE `timestamp` = '".$timestamp."'";
$ergebnis = $hp->mysqlquery($abfrage);
 
  $nummer = 0;  
while($row = mysql_fetch_object($ergebnis))
{
$nummer++;
}
if ($nummer == 0)
{
$eintrag = "INSERT INTO `".$dbpräfix."pm`
(von, datum, zu, text, Betreff, gelesen, timestamp)
VALUES
('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$timestamp')";

$eintragen = $hp->mysqlquery($eintrag);
if ($eintragen == true)
{
echo $lang->word('postok');

} else
{
echo mysql_error();
}
} else
{
$error->error($lang->word('doublepost'),"2");
}
}
} 
?>




