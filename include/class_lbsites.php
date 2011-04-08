<?php
class lbsites
{
var $error;
var $hp;
var $lang;
var $info;
var $fp;
var $liste = array();
var $funkadd = array();

function sethp($hp)
{
$this->hp = $hp;
$this->error = $hp->geterror();
$this->lang = $hp->getlangclass();
$this->info = $hp->getinfo();
$this->fp = $hp->firephp;
}

function addFunctions($ext)
{

if (is_object($ext))
{
//$ext->sethp($this->hp);

$funktions = get_class_methods($ext);

foreach ($funktions as $key=>$value) {

  $split = explode("_", $value);
  if ((count($split) > 1) && ($split[1] != ""))
  {
	$this->liste[$value] = $ext;
	$this->funkadd[] = $value;
	}
}

    

}


}

function link($site, $text, $vars = "", $class = "lbOn")
{
return '<a href="index.php?lbsite='.$site.'&vars='.$vars.'" class="'.$class.'">'.$text.'</a>';
}


function load($site, $vars)
{
  $hp = $this->hp;
  ob_start("ob");
  
  $funktions = get_class_methods($this);
  $site = "site_".$site;
  
  
  $siteT = new siteTemplate($hp);
  $siteT->load("lbsite_main");

  $content = "";
  if (in_array($site, $this->funkadd))
  {
   $ext = $this->liste[$site];
    
   $content = $ext->$site($vars);
  } else
  if (in_array($site, $funktions))
  {
  
   $content = $this->$site($vars);
  } else
  {
    $this->fp->error("ungültige LB-Site ($site)");
    $content = "Seite nicht gefunden!";
  }
  
  $siteT->set("Content", $content);
  $siteT->display();

}

function site_Test($vars)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $lang=$hp->langclass;
  $fp = $hp->fp;

  
  $content = "";
  $sql = "SELECT * FROM `$dbpräfix"."user`";
  $erg = $hp->mysqlquery($sql);
  while ($row = mysql_fetch_object($erg))
  {
  $content .= $row->user."<br>";
  }
  $content .= " ö Ö ä Ä ü Ü ß ^ ` ' # + * , ; |";
  return $content;
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
  
  $site = new siteTemplate($hp);
  
  
  if($right[$level]['manage_vote'])
  {
    $site->load("vote");
    $sql = "SELECT * FROM `$dbpräfix"."vote` WHERE `ID` = '$vars';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
  
    $data = array(
      "ID" => $vars,
      "name" => $row->name,
      "vars" => $vars
    );
    
    $site->setArray($data);

    return $site->get("LbSite-Del");
  } else
  {
     $site->load("info");
     $site->set("info",$lang->word('noright'));
     
     return $site->get();
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
     $error->error("Keine Zugriffsberechtigung!", "1");
  
  } else if (!$right[$level]['newsedit'])
  {
  
    $error->error("Sie haben keine Berechtigung, Newsmeldungen zu bearbeiten!", "1");
  
  } else
  {
    $sql = "SELECT * FROM ".$dbpräfix."news WHERE `ID` ='".$site."';";
    $ergebnis = $hp->mysqlquery($sql); 
    $row = mysql_fetch_object($ergebnis);
    $newstext="$row->text";
    $newstext = str_replace('<br>',"\n" ,$newstext);
    $newstext = str_replace('&lt;',"<" ,$newstext);
    
    $siteT = new siteTemplate($hp);
    $siteT->load("news");
    
    $data = array(
      
      "titel" => $row->titel,
      "site" => $site,
      "datum" => $row->datum,
      "datDisabled" => "false",
      "level" => $row->level,
      "newstext" => $newstext,
      "sitename" => "newsedit"    
    );
    
    $siteT->setArray($data);
  
    $data = "";
    
    $sql = "SELECT * FROM `$dbpräfix"."usedpics`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
    
       $breite=$row->width; 
       $hoehe=$row->height; 
      
       $neueHoehe=100;
       $neueBreite=intval($breite*$neueHoehe/$hoehe); 
      
       $img = "<img src=\"include/usedpics/pic.php?id=$row->ID\" width=\"$neueBreite\" height=\"$neueHoehe\"\> ";
                   
       if ($data == "")
       {
          $data = "'".$img."'";
          
       } else
       {
          $data .= ", '".$img."'";
       }
  
    }
    
    $siteT->set("picturelist", $data);
    
    return $siteT->get("LbSite-Edit");

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
  $error->error($lang->word('nonewswrite'), "1");

} else
{
    $siteT = new siteTemplate($hp);
    $siteT->load("news");
    
    $data = array(
      
      "titel" => "",
      "site" => "0",
      "datDisabled" => "true",
      "datum" => date("y.n.y"),
      "level" => "0",
      "newstext" => "",
      "sitename" => "newswrite"    
    );
    
    $siteT->setArray($data);



    $data = "";

    $sql = "SELECT * FROM `$dbpräfix"."usedpics`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
    
       $breite=$row->width; 
       $hoehe=$row->height; 
      
       $neueHoehe=100;
       $neueBreite=intval($breite*$neueHoehe/$hoehe); 
      
       $img = "<img src=\"include/usedpics/pic.php?id=$row->ID\" width=\"$neueBreite\" height=\"$neueHoehe\"\> ";
                   
       if ($data == "")
       {
          $data = "'".$img."'";
          
       } else
       {
          $data .= ", '".$img."'";
       }
  
    }
    
    $siteT->set("picturelist", $data);
    
    return $siteT->get("LbSite-Edit");

}
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
    
    $site = new siteTemplate($hp);
    $site->load("news");
    
    $data = array(
      "ID" => $row->ID,
      "titel" => $row->titel,
      "ersteller" => $row->ersteller
    );
    
    $site->setArray($data);

    return $site->get("LbSite-Del");
    
    
  } else
  {
    $error->error($lang->word('noright'), "1");
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
<form action="index.php?site=forum&show=<?php echo $row->threadid?>" method="post">
<textarea name="text" cols="100" rows="15"><?php echo $row->text?></textarea>
<input type="hidden" name="postid" value="<?php echo $row->ID?>"><br>
<button type="submit" name="forum_editpost"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button>
</form>
</td>
  </tr>
</table>
<?php

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

<form action="index.php?site=forum&show=<?php echo $row->ID?>" method="post">
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
    <input type="text" name="titel" id="titel" value="<?php echo $row->titel?>"></td>
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
    <?php
    $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` <= '$level';";
    $erg2 = $hp->mysqlquery($sql);
    while ($row2 = mysql_fetch_object($erg2))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?php echo $row2->level?>" <?php if ($row->level == $row2->level) { echo " checked=\"true\"";} ?>>
          <?php echo $row2->name?></label></td>
      </tr>
     <?php
    }
     ?> 
    </table>
      <p>Notiz: Jeder Benutzer eines höheren Levels kann dieses Forum trotzdem lesen!</p></td>
  </tr>
  <?php if ($right[$level]['forum_canusetypes']) { ?>
    <tr>
    <td>Type</td>
    <td>
   <table width="100%">
      <tr>
        <td><label>
          <input type="radio" name="type" value="0"  <?php if ($row->type == "0") { echo " checked=\"true\"";} ?>>
          Normal</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="1"   <?php if ($row->type == "1") { echo " checked=\"true\"";} ?>>
          Sticky</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="2"   <?php if ($row->type == "2") { echo " checked=\"true\"";} ?>>
          Announce</label></td>
      </tr>
   
   
   </table>
   
   </td>
  </tr>
 <?php } else
 {
 ?>
 <input type="hidden" name="type" value="0">
 <?php
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
        <input type="checkbox" name="visible" id="visible"  <?php if ($row->visible == "1") { echo " checked=\"true\"";} ?>> 
      Ja</p>
      <p>Notiz: Das Thema ist für Benutzer eines geringeren Levels Sichtbar, Sie können aber nicht Antworten.</p></td>
  </tr>
  </table>
</div> </td>

 <tr>
    <td>Text:</td>
    <td colspan="2"><textarea name="text" id="text" cols="100" rows="15"><?php echo $row->text?></textarea></td>
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
<input type="hidden" name="postid" value="<?php echo $vars?>">
</form>

<?php

}

function site_forum_editforum($vars)
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

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = '$vars'";
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

<form action="index.php?site=forum" method="post">
<center><table border="1" width="90%">
<tr>
<td>
<table width="100%" border="0">
  <tr>
    <td width="80">&nbsp;</td>
    <td colspan="2">Bearbeiten</td>
  </tr>
  <tr>
    <td>Titel:</td>
    <td width="80"> &nbsp;
    <table border="0" width="100%" height="5">
    <tr>
    <td width="90%">
    <input type="text" name="titel" id="titel" value="<?php echo $row->titel?>"></td>
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
    <?php
    $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` <= '$level';";
    $erg2 = $hp->mysqlquery($sql);
    while ($row2 = mysql_fetch_object($erg2))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?php echo $row2->level?>" <?php if ($row->level == $row2->level) { echo " checked=\"true\"";} ?>>
          <?php echo $row2->name?></label></td>
      </tr>
     <?php
    }
     ?> 
    </table>
      <p>Notiz: Jeder Benutzer eines höheren Levels kann dieses Forum trotzdem lesen!</p></td>
  </tr>
  
 <input type="hidden" name="type" value="0">
 
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
        <input type="checkbox" name="visible" id="visible"  <?php if ($row->visible == "1") { echo " checked=\"true\"";} ?>> 
      Ja</p>
      <p>Notiz: Das Thema ist für Benutzer eines geringeren Levels Sichtbar, Sie können aber nicht Antworten.</p></td>
  </tr>
  </table>
</div> </td>

 <tr>
    <td>Beschreibung:</td>
    <td colspan="2"><textarea name="text" id="text" cols="100" rows="15"><?php echo $row->description?></textarea></td>
  </tr>
 <tr>
   <td>&nbsp;</td>
   <td colspan="2"><button type="submit" name="forum_editforum"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></td>
 </tr>
</table>
</td>
</tr>
</table>
</center>
<input type="hidden" name="postid" value="<?php echo $vars?>">
</form>

<?php

}

function site_forum_delthread($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_edit_post'])
{

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie das Thema und alle Beträge wirklich endgültig löschen?</p>
<table width="100%">
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=forum">
  <p align="center"><input type="hidden" name="postid" size="3" value="<?php echo $vars?>"><input type="submit" value="Löschen" name="delthread"></form>
</td>
<td>
</td>
</tr>
</table>
<b>ID:</b> <?php echo $row->ID?><br>
<b>Titel:</b> <?php echo $row->titel?><br>
<b>

<?php
} else
{
echo $lang->word('noright');
}
}

function site_forum_delpost($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_edit_post'])
{

$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie den Beitrag endgültig löschen?</p>
<table width="100%">
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=forum">
  <p align="center"><input type="hidden" name="postid" size="3" value="<?php echo $vars?>"><input type="submit" value="Löschen" name="delpost"></form>
</td>
<td>
</td>
</tr>
</table>
<b>ID:</b> <?php echo $row->ID?><br>
<b>

<?php
} else
{
echo $lang->word('noright');
}
}

function site_forum_delforum($vars)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_del_forum'])
{

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = '$vars';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<p id="highlight">Möchten Sie das <b>Komplette Forum</b> wirklich endgültig löschen?</p>
<table width="100%">
<tr valign="bottom">
<td>
<form method="POST" action="index.php?site=forum">
  <p align="center"><input type="hidden" name="postid" size="3" value="<?php echo $vars?>"><input type="submit" value="Löschen" name="delforum"></form>
</td>
<td>
</td>
</tr>
</table>
<b>Themen:</b> <br>
<ul>
<?php
$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `forumid` = '$vars'";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
echo "<li>$row->titel</li>";
}

?>
</ul>
<b>

<?php
} else
{
echo $lang->word('noright');
}
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
<form action="index.php?site=forum&show=<?php echo $vars?>" method="post">
<table width="200" border="0">
  <tr>
    <td>&nbsp;</td>
    <td>Verschieben</td>
  </tr>
  <tr>
    <td>Wohin:</td>
    <td><select name="moveto" id="moveto">
    <?php
    $sql = "SELECT * FROM `$dbpräfix"."forums`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
    
    ?>
    <option value="<?php echo $row->ID?>"><?php echo $row->titel?></option>
    <?php
    }
    ?>
    
    </select>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><button type="submit" name="forum_movethread"> <img src="images/ok.gif"> </button></td>
  </tr>
</table>
<input type="hidden" name="postid" value="<?php echo $vars?>">
</form>
<?php

}



 function inctempfiles()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->firephp;
$config = $hp->getconfig();

$design = $config['design'];

  if (is_dir("template/".$design."/lbsites/"))
  {


  $handle = @opendir("./template/".$design."/lbsites/"); 
    while ($file = @readdir($handle)) {

	     $n= @explode(".",$file);
       $art = @strtolower($n[1]);


    if ($art == "php")
    {
        $widget = array();
        $placeholder = array();
        if (file_exists("./template/".$design."/lbsites/$file"))
        include ("./template/".$design."/lbsites/$file");

        $ext = @new lbsitesTemplate();
        
        if (is_object($ext))
        {
        $this->addFunctions($ext);
        $ext->sethp($hp);
        }
               

    }
  } 


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