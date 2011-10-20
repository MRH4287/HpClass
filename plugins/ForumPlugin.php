<?php
class ForumPlugin extends Plugin
{


function __construct($hp, $loader)
{
  // Laden der Daten
  // Nicht Editieren!
  parent::__construct($hp, $loader);

  // Plugin Config:
  // -----------------------------------------------

  // Der Name des Plugins:
  $this->name = "Forum";

  // Die Version des Plugins:
  $this->version = "1.0.0";

  // Der Autor des Plugins:
  $this->autor = "MRH";

  //Die Homepage des Autors:
  $this->homepage = "mrh.hes-technic.de";



  //------------------------------------------------

}


/*

Lade alle für das System relevanten Daten.

z.B. Datenbank Aufrufe, Datei Aufrufe, etc.

*/
function onEnable()
{
   $right = $this->hp->right;

   $rights = array(
    array (

      "name" => "forum_nopassword",
      "desc" => "Forum: Darf Themen betrachten ohne das Passwort eizugeben",
      "cat"  => "Forum"
      ),
      array (

      "name" => "forum_edit_post",
      "desc" => "Forum: Darf Threads und Posts bearbeiten die nicht ihm gehören",
      "cat"  => "Forum"
      ),
      array (

      "name" => "forum_canusetypes",
      "desc" => "Forum: Darf die Thread Typen verwenden: (Sticky, Announce)",
      "cat"  => "Forum"
      ),
      array (

      "name" => "forum_canclosethread",
      "desc" => "Forum: Darf Threads schließen. (<b>Keiner</b> kann schreiben)",
      "cat"  => "Forum"
      ),
      array (

      "name" => "forum_edit_forum",
      "desc" => "Forum: Darf Foren bearbeiten.",
      "cat"  => "Forum"
      ),
        array (

      "name" => "forum_del_forum",
      "desc" => "Forum: Darf komplette Foren löschen.",
      "cat"  => "Forum"
      )

   );

   $right->registerArray($rights);

  $this->hp->addredirect("forum", "plugins/Forum");


}


/*

Hier werden die eigentlichen Aufgaben des Plugins erledigt.
Wie zum Beispiel das hinzufügen von Weiterleitungen.

*/
function onLoad()
{


$hp = $this->hp;
$dbprefix = $hp->getprefix();
$right = $hp->getright();
$level = $_SESSION['level'];
$info = $hp->getinfo();

if (isset($post['forum_editpost']))
{

$sql = "SELECT * FROM `$dbprefix"."posts` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);
$time = time();

if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbprefix"."posts` SET `text` = '".$post['text']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Post geändert");
}


}

if (isset($post['forum_editthread']))
{

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$titel = $post['titel'];
$level = $post['level'];
$type = $post['type'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];
$time = time();

$pw = "";
if (($passwort != "*") and ($passwort != ""))
{
$pw = "`passwort` = '".md5("pw_".$passwort)."' ,";
} elseif ($passwort == "")
{
$pw = "`passwort` = '' ,";
}
$visible2 = "0";
if ($visible == "on")
{
$visible2 = "1";
}


if (($row->userid == $_SESSION['ID']) or ($right[$level]['forum_edit_post']))
{
$sql = "UPDATE `$dbprefix"."threads` SET `text` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread geändert");
}


}

if (isset($post['forum_editforum']))
{

$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$titel = $post['titel'];
$level = $post['level'];
$type = $post['type'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];


$pw = "";
if (($passwort != "*") and ($passwort != ""))
{
$pw = "`passwort` = '".md5("pw_".$passwort)."' ,";
} elseif ($passwort == "")
{
$pw = "`passwort` = '' ,";
}
$visible2 = "0";
if ($visible == "on")
{
$visible2 = "1";
}


if (($row->userid == $_SESSION['ID']) or ($right[$level]['forum_edit_forum']))
{
$sql = "UPDATE `$dbprefix"."forums` SET `description` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Forum geändert");
}


}

if (isset($post['forum_movethread']))
{

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbprefix"."threads` SET `forumid` = '".$post['moveto']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread verschoben");
}


}


}


// ......................................................


function getusername($ID)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbprefix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->user;
}

function getrank($ID)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbprefix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$level = $row->level;

$sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = $level";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->name;

}

function getcountposts($ID)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbprefix"."posts` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count = mysql_num_rows($erg);

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count += mysql_num_rows($erg);

return $count;
}

function getimage_f($forum)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

if (!is_array($_SESSION['lasttime_t']))
{
$_SESSION['lasttime_t'] = array();
}

$lasttime = $_SESSION['lasttime_t'];

$new = false;

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `forumid` = $forum";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
if (($row->lastpost > $lasttime[$row->ID]) and ($this->user_lastlogin($_SESSION['ID']) < $row->lastpost))
{
$new = true;
}
}

$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `ID` = $forum";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$lock = false;

// Lock
if (($row->closed == "1") or ($_SESSION['level'] < $row->level))
{
$lock = true;

}



$link = "forum/forum";
if ($new)
{
$link .= "_unread";
} else
{
$link .= "_read";

}

if ($lock)
{
$link .= "_locked";
}

$link .= ".gif";

return $link;

}

function getimage_t($ID)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

if (!is_array($_SESSION['lasttime_t']))
{
$_SESSION['lasttime_t'] = array();
}

$lasttime = $_SESSION['lasttime_t'];

$new = false;

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

if (($row->lastpost > $lasttime[$ID]) and ($this->user_lastlogin($_SESSION['ID']) < $row->lastpost))
{
$new = true;
}


$lock = false;

// Lock
if (($row->closed == "1") or ($_SESSION['level'] < $row->level))
{
$lock = true;

}

$sql = "SELECT * FROM `$dbprefix"."posts` WHERE `threadid` = '$ID'";
$erg = $hp->mysqlquery($sql);
$config = $this->hp->getconfig();

$num_hot = $config['num_hot'];


if (!isset($num_hot))
{
$num_hot = 100;
}

$hot = false;
if (mysql_num_rows($erg) >= $num_hot)
{
$hot = true;
}

$mine = false;

if ($row->userid == $_SESSION['ID'])
{
$mine = true;
}


$link = "forum/";

if        ($row->type == "0")
{
$link .= "topic";
} elseif  ($row->type == "1")
{
$link .= "sticky";
} elseif  ($row->type == "2")
{
$link .= "announce";
}


if ($new)
{
$link .= "_unread";
} else
{
$link .= "_read";

}
if ($hot)
{
$link .= "_hot";
}

if ($lock)
{
$link .= "_locked";
}

if ($mine)
{
$link .= "_mine";
}

$link .= ".gif";

return $link;

}


function user_lastlogin($user)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT lastlogin FROM `$dbprefix"."user` WHERE `ID` = '$user'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->lastlogin;
}

function getvote($threadid)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$ergebnisse = $row->ergebnisse;
$erg = explode("<!--!>", $ergebnisse);
$count = 0;
$value = 0;
foreach ($erg as $key=>$wert) {
	$count++;
	$value += $wert;
}
$rating = 0;

if ($count != 0)
{
$rating = round($value / $count);
}

$r1 = "";
$r2 = "";
$r3 = "";
$r4 = "";
$r5 = "";


if ($rating >= 1)
{
$r1 = $rating;
}
if ($rating >= 2)
{
$r2 = $rating;
}
if ($rating >= 3)
{
$r3 = $rating;
}
if ($rating >= 4)
{
$r4 = $rating;
}
if ($rating == 5)
{
$r5 = $rating;
}


$text = '
<table width="115" border="0" class="forum_rating_table"  onmouseout="vote_effect('.$threadid.',0)">
  <tr>
    <td>&nbsp;</td>
    <td id="vote'.$threadid.'_1" onclick="xajax_forum_vote('.$threadid.', 1)" onmouseover="vote_effect('.$threadid.',1);" class="forum_star'.$r1.'">&nbsp;</td>
    <td id="vote'.$threadid.'_2" onclick="xajax_forum_vote('.$threadid.', 2)" onmouseover="vote_effect('.$threadid.',2);" class="forum_star'.$r2.'">&nbsp;</td>
    <td id="vote'.$threadid.'_3" onclick="xajax_forum_vote('.$threadid.', 3)" onmouseover="vote_effect('.$threadid.',3);" class="forum_star'.$r3.'">&nbsp;</td>
    <td id="vote'.$threadid.'_4" onclick="xajax_forum_vote('.$threadid.', 4)" onmouseover="vote_effect('.$threadid.',4);" class="forum_star'.$r4.'">&nbsp;</td>
    <td id="vote'.$threadid.'_5" onclick="xajax_forum_vote('.$threadid.', 5)" onmouseover="vote_effect('.$threadid.',5);" class="forum_star'.$r5.'">&nbsp;</td>
    <td>&nbsp;<input type="hidden" value="'.$rating.'" id="vote'.$threadid.'_count" /></td>
  </tr>
</table>
';
return $text;
}


function createLink($forum = 0, $thread = 0)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();


$link = '<a href="index.php?site=forum">Forum</a>';

if (($forum == 0) and ($thread != 0))
{

$sql = "SELECT forumid FROM `$dbprefix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$forum = $row->forumid;
}


if ($forum != 0)
{
$sql = "SELECT titel FROM `$dbprefix"."forums` WHERE `ID` = '$forum'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&forum='.$forum.'">'.$row->titel.'</a>';
}


if ($thread != 0)
{
$sql = "SELECT titel FROM `$dbprefix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&show='.$thread.'">'.$row->titel.'</a>';
}


return $link;

}


// LBSites


function site_forum_editpost($vars)
{
$hp = $this->hp;
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$get = $hp->get();

$sql = "SELECT * FROM `$dbprefix"."posts` WHERE `ID` = '$vars'";
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
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$get = $hp->get();
$right = $hp->getright();
$level = $_SESSION['level'];

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = '$vars'";
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
    $sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` <= '$level';";
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
$dbprefix = $hp->getprefix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;
$get = $hp->get();
$right = $hp->getright();
$level = $_SESSION['level'];

$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `ID` = '$vars'";
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
    $sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` <= '$level';";
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
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_edit_post'])
{

$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = '$vars';";
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
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_edit_post'])
{

$sql = "SELECT * FROM `$dbprefix"."posts` WHERE `ID` = '$vars';";
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
$dbprefix = $hp->getprefix();
$info = $hp->info;
$error = $hp->error;
$lang=$hp->langclass;
$fp = $hp->fp;
$right = $hp->getright();
$level = $_SESSION['level'];

if($right[$level]['forum_del_forum'])
{

$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `ID` = '$vars';";
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
$sql = "SELECT * FROM `$dbprefix"."threads` WHERE `forumid` = '$vars'";
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
$dbprefix = $hp->getprefix();
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
    $sql = "SELECT * FROM `$dbprefix"."forums`";
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




}


 function ax_forum_vote($ID, $vote)
  {
    $response = new xajaxResponse();
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $config = $hp->getconfig();
    $forum = $hp->forum;

    $ID = mysql_real_escape_string($ID);

    if ($config["xajax_workaround"])
    {
      // Workaround for older PHP-Versions

      $data = array(
        "ID", "vote"
      );

      foreach ($data as $k => $name)
      {
        $v = $$name;
        if (preg_match("/[S|N](.*)/", $v, $m))
        {
          $$name = substr($v, 1, strlen($v));
        }
      }
      // Workaround End
    }

    $sql = "SELECT * FROM `$dbprefix"."threads` WHERE `ID` = $ID";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);



    $erg = explode("<!--!>", $row->ergebnisse);
    $erg[] = $vote;

    $user =  explode("<!--!>", $row->voted);

    if ((!in_array($_SESSION['ID'], $user)) and isset($_SESSION['ID']))
    {

      $user[] = $_SESSION['ID'];

      $ergebnisse = "";
      foreach ($erg as $key=>$value)
      {
      	if ($ergebnisse != "")
      	{
          $ergebnisse .= "<!--!>".$value;
        } else
        {
          $ergebnisse = $value;
        }
      }

      $users = "";
      foreach ($user as $key=>$value)
      {
      	if ($users != "")
      	{
          $users .= "<!--!>".$value;
        } else
        {
          $users = $value;
        }
      }

      $okn = "<img src=images/ok.gif height=12 width=12>";
      $response->assign("voteok", "innerHTML", $okn);

      $sql = "UPDATE `$dbprefix"."threads` SET `ergebnisse` = '$ergebnisse', `voted` = '$users' WHERE `ID` = $ID";
      $erg = $hp->mysqlquery($sql);

      $response->assign("vote", "innerHTML", $forum->getvote($ID));

    }
    return $response;
  }


?>