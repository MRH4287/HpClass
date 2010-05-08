<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$lbs = $hp->lbsites;
$forum = $hp->forum;
$fp = $hp->fp;




if (!isset($_SESSION['level']))
{
$_SESSION['level'] = 0;
}
$level = $_SESSION['level'];
//  --------------------------------------------------------------------- THREADS----------------------------------------------------------------------
if (isset($get['forum']))
{

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = ".$get['forum'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


if ($row->ID != "")
{

$levelok = true;
 $visible = false;
 
 if ($row->level > $level)
 {
 $levelok = false;
 }
 
 
 if ($levelok or ($row->visible == "1"))
 {
 $visible = true;
 }
 
if (!$visible)
{

$error->error("Das gewünschte Forum exsistiert nicht!");
echo "Das gewünschte Forum exsistiert nicht oder Sie haben keine Bereichtigung diesen einzusehen!";
} else
{

if (!is_array($_SESSION['forum_canread_F']))
{
$_SESSION['forum_canread_F'] = array();
}


if (($row->passwort != "") and (!in_array($row->ID, $_SESSION['forum_canread_F'])))
{
// Passwort
?>Geben Sie das Passwort ein:

<form method="post" action="index.php?site=forum">
   Passwort:
     <input type="text" name="password" id="password">
     <input type="submit" name="sendpw_F" id="sendpw_F" value="Senden">
     <input name="thread" type="hidden" id="thread" value="<?php echo $get['forum']?>">
</form>

<?php
} else
{

$countposts = array();
$lastpost = array();
$lasttime = array();

$sql = "SELECT * FROM `$dbpräfix"."posts`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

$countposts[$row->threadid] += 1;

if ($lasttime[$row->threadid] == "")
{
$lasttime[$row->threadid] = time();
}


if ($lasttime[$row->threadid] > $row->timestamp)
{
$lastpost[$row->threadid] = $row->userid;
$lasttime[$row->threadid] = $row->timestamp;
}

}

$forumid = $get['forum'];

$_SESSION['forumid'] = $forumid;


$posts = array();

$page = $get['page'];
if (!isset($page))
{
$page = 1;
}
// -.--------------------------------------------------------------------------------------------------------------------
// Dieser Wert ist eher ein Richtwert und kann nicht 100%tig angenommen werden.
// Das Liegt an der Rundung.
$postsasite = 25;
// -.--------------------------------------------------------------------------------------------------------------------
$threads = array();
$sql = "SELECT ID, type FROM `$dbpräfix"."threads` WHERE `forumid` = '$forumid' ORDER BY `lastpost` DESC";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$threads[$row->type][] = $row->ID;
}

$i = 0;

krsort($threads);


// Annkündigungen auf jeder Seite oben -->
if (is_array($threads[2]))
{
$anzahla = count($threads[2]);

$all = 0;
foreach ($threads as $type=>$array) {
	if ($type != 2)
	{
  foreach ($array as $key=>$value) {
  	$all++;
  }
  }
}
$postsasite = $postsasite - $anzahla;


if ($postsasite <= 0)
{
$postsasite  = 1;
}
$pages = ceil($all / ($postsasite));


for ($i = 1; $i <= $pages; $i++) {

 foreach ($threads[2] as $key=>$value) {
 	$posts[$i][] = $value;
 }

}

}
// --> Ende ...

//Link:
echo $forum->createLink($forumid);


foreach ($threads as $type=>$array) {

// Da sonst Ankündigungen auf der 1. Seite doppelt wären
if ($type != 2)
{
foreach ($array as $key=>$ID) {
	
$sql = "SELECT ID FROM `$dbpräfix"."threads` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$i++;

$p = ceil($i / ($postsasite));
$posts[$p][] = $row->ID;

}
}
}

}



?>
<table width="100%" border="1">
  <tr>
    <td width="64%">Themen</td>
    <td width="7%" align="center" valign="middle"><div align="center">Antworten</div></td>
    <td width="29%"><div align="center">Letzte Antwort</div></td>
  </tr>
 
 <?php

 if ((count($posts[$page]) == 0) and ($page != "1"))
{
$this->fp->log($posts);
$page = 1;
$error->error("Diese Seite exsistiert nicht!");
}
 
 if (count($posts[$page]) != 0)
{ 
 foreach ($posts[$page] as $key=>$ID) {
 	
 
 
 $sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = '$ID'";
 $erg = $hp->mysqlquery($sql);
 while ($row = mysql_fetch_object($erg))
 {
 
 $levelok = true;
 $visible = false;
 
 if ($row->level > $level)
 {
 $levelok = false;
 }
 
 
 if ($levelok or ($row->visible == "1"))
 {
 $visible = true;
 }
 
 if ( $visible )
 {
 ?>
 
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="10%" rowspan="2"><div align="center"><img src="<?php echo $forum->getimage_t($row->ID)?>"></div></td>
        <td width="90%"><a href="index.php?site=forum&show=<?php echo $row->ID?>"><?php  
        switch ($row->type) {
        case 0:
        echo "";	
        	break;
        case 1: 
        echo "<b>Sticky:</b> ";
        break;
       case 2:
        echo "<b>Ankündigung:</b> ";
        break;
        	
        }
        
        
          ?><?php echo $row->titel?></a></td>
      </tr>
      <tr>
        <td>von <?php 
        $sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '".$row->userid."'";
        $erg2 = $hp->mysqlquery($sql);
        $row2 = mysql_fetch_object($erg2);
        
        echo "<a href=index.php?site=user&show=".$row2->user.">$row2->user</a>";
        
        
          ?> am <?php  echo date("d.m.Y H:i", $row->timestamp);  ?>  </td>
      </tr>
    </table></td>
    <td align="center" valign="middle"><?php if ($countposts[$row->ID] != "") { echo $countposts[$row->ID]; } else { echo "0"; } ?></td>
    <td><table width="100%" border="0">
      <tr>
        <td><?php  if ($lastpost[$row->ID] != "")
        {
        echo "von ";
        $sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '".$lastpost[$row->ID]."'";
        $erg2 = $hp->mysqlquery($sql);
        $row2 = mysql_fetch_object($erg2);
        
        echo "<a href=index.php?site=user&show=".$row2->user.">$row2->user</a>";
        }
        
          ?></td>
      </tr>
      <tr>
        <td><?php if ($lastpost[$row->ID] != "")
        { echo "am "; 
         echo date("d.m.Y H:i", $lasttime[$row->ID]); } ?></td>
      </tr>
    </table></td>
  </tr>
 
 <?php
 }
 }
 }
 
 }
 
 
 ?>
  
 <tr>
 <td align="right" colspan="3">
 Seite: <?php   
 $pagec = count($posts);
 $rangetof = $page - 1;
 $rangetol = $pagec - $page;
 
 if ($rangetof > 2)
 {
 ?>
 <a href="index.php?site=forum&forum=<?php echo $forumid?>&page=1">1</a> ... 
 <a href="index.php?site=forum&forum=<?php echo $forumid?>&page=<?php echo $page-1?>"><?php echo $page-1?></a>
 <?php
 } else
 {
 for ($i=1;$i < $page; $i++) {
 	?>
 	<a href="index.php?site=forum&forum=<?php echo $forumid?>&page=<?php echo $i?>"><?php echo $i?></a>
 	<?php
 }
 }
  	?>
 	<b><?php echo $page?></b>
 	<?php
 	if ($rangetol > 2)
 {
 ?>
 <a href="index.php?site=forum&forum=<?php echo $forumid?>&page=<?php echo $page+1?>"><?php echo $page+1?></a> ...
 <a href="index.php?site=forum&forum=<?php echo $forumid?>&page=<?php echo $pagec?>"><?php echo $pagec?></a>
 
 <?php
 } else
 {
 for ($i=$page+1;$i < $pagec+1; $i++) {
 	?>
 	<a href="index.php?site=forum&forum=<?php echo $forumid?>&page=<?php echo $i?>"><?php echo $i?></a>
 	<?php
 }
 }
 

  ?>
 </td>
</tr>

</table>
<?php
if ($levelok)
{
?>

<style>
#newtopic_button
{
background: url(forum/newtopic.gif) no-repeat; 
cursor: pointer;
}
#newtopic_button:hover
{
background: url(forum/newtopic_hover.gif) no-repeat; 
}

</style>

<div id="newtopic_button" width="119" height="25" onclick="window.location='index.php?site=forum&newthread';">
<table  width="119" height="25" border="0">
<tr>
<td>
</td>
</tr>
</table>
</div>

<?php
}

if ($right[$level]['forum_edit_forum'])
{

      echo "&nbsp;";
      echo $lbs->link("forum_editforum", '<img src="include/button.php?p=3&t=  Forum bearbeiten  " >', $forumid); 
}

if ($right[$level]['forum_del_forum'])
{

      echo "&nbsp;";
      echo $lbs->link("forum_delforum", '<img src="include/button.php?p=3&t=  Forum löschen  &f=red" >', $forumid); 
}

} // Secure
}
} else
{
$error->error("Das gewünschte Forum exsistiert nicht!");
echo "Das gewünschte Forum exsistiert nicht oder Sie haben keine Bereichtigung diesen einzusehen!";

}


} elseif (isset($get['show'])) 
{
//  --------------------------------------------------------------------- Eintrag----------------------------------------------------------------------



$threadid = $get['show'];

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$closed = $row->closed;

if ($row->ID == "")
{
$error->error("Der gewünschte Thread exsistiert nicht!");
echo "Der gewünschte Thread exsistiert nicht oder Sie haben keine Bereichtigung diesen einzusehen!";
} else
{

if (!is_array($_SESSION['lasttime_t']))
{
$_SESSION['lasttime_t'] = array();
}
$_SESSION['lasttime_t'][$threadid] = time();

$levelok = true;
 $visible = false;
 
 if ($row->level > $level)
 {
 $levelok = false;
 }
 
 
 if ($levelok or ($row->visible == "1"))
 {
 $visible = true;
 }
 
if (!$visible)
{

$error->error("Der gewünschte Thread exsistiert nicht!");
echo "Der gewünschte Thread exsistiert nicht oder Sie haben keine Bereichtigung diesen einzusehen!";
} else
{

if (!is_array($_SESSION['forum_canread']))
{
$_SESSION['forum_canread'] = array();
}


if (($row->passwort != "") and (!in_array($row->ID, $_SESSION['forum_canread'])) and (!$right[$level]['forum_nopassword']))
{
// Passwort
?>Geben Sie das Passwort ein:

<form method="post" action="index.php?site=forum">
   Passwort:
     <input type="text" name="password" id="password">
     <input type="submit" name="sendpw" id="sendpw" value="Senden">
     <input name="thread" type="hidden" id="thread" value="<?php echo $get['show']?>">
</form>

<?php
} else
{


$page = $get['page'];
if (!isset($page))
{
$page = 1;
}

$postsasite = 10;

$posts = array("1" => array("0"));

$i = 0;
$sql = "SELECT ID FROM `$dbpräfix"."posts` WHERE `threadid` = '$threadid'";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$i++;

$p = ceil($i / $postsasite);
$posts[$p][] = $row->ID;

}


//Link:
echo $forum->createLink(0, $threadid);


?>
<style>
.forumtitel
{
background-color:#CCCCCC;
}

.forumfooter
{
background-color:#E5E5E5;
}

</style>

<table width="100%" border="0">
<?php

if (count($posts[$page]) == 0)
{
$page = 1;
$error->error("Diese Seite exsistiert nicht!");
}

foreach ($posts[$page] as $tmp=>$ID) {
	
	if ($ID == "0")
	{
	$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = '$threadid'";
	$erg = $hp->mysqlquery($sql);
	$row = mysql_fetch_object($erg);
?>
<tr>
 <td> 
<table width="100%" border="1">
  <tr class="forumtitel" >
    <td width="15%"><?php echo $forum->getusername($row->userid)?></td>
    <td width="85%"><table width="100%" border="0">
      <tr>
        <td><?php echo $row->titel?></td>
      </tr>
      <tr>
        <td><?php echo "am "; 
         echo date("d.m.Y H:i", $row->timestamp); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="375" align="left" valign="top">
    <table width="100%" border="0" height="100%">
      <tr align="center" width="200">
        <td height="160"><img src="include/userpics.php?id=<?php echo $row->userid?>"></td>
      </tr>
      <tr>
        <td height="25" valign="top"><?php echo $forum->getrank($row->userid)?></td>
      </tr>
      <tr>
        <td valign="top">Anzahl der Beiträge: <?php echo $forum->getcountposts($row->userid)?></td>
      </tr>
    </table></td>
    <td align="left" valign="top"><?php echo $row->text?></td>
  </tr>
  <tr class="forumfooter">
    <td height="21"><a href="index.php?site=pm&new&to=<?php echo $forum->getusername($row->userid)?>"><img src="images/pm.gif" width="24" height="24"></a>
     <?php
     if (($_SESSION['ID'] == $row->userid) or ($right[$level]['forum_edit_post']))
     {
      echo $lbs->link("forum_editthread", '<img src="images/edit.gif" width="24" height="24">', $row->ID); 
      }
      if ($right[$level]['forum_edit_post'])
      {
      echo "&nbsp;";
      echo $lbs->link("forum_movethread", '<img src="include/button.php?p=3&t=Verschieben" height="24">', $row->ID); 
      echo "&nbsp;";
      echo $lbs->link("forum_delthread", '<img src="images/abort.gif" width="24" height="24">', $row->ID); 
     } 
      ?></td> 
    <td><table width="100%" border="0">
      <tr>
        <td width="80%"><?php if ($row->lastedit != "0") { echo "Letzte Bearbeitung: ";
        echo date("d.m.Y H:i", $row->lastedit);
          }  ?> &nbsp;</td>
        <td width="20%">
        <div id="vote"><?php
        // Vote
        echo $forum->getvote($row->ID);
        ?></div> <div id="voteok"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
</tr>

<?php

} else
{
$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `ID` = $ID";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>

<tr>
 <td>
<table width="100%" border="1">
  <tr class="forumtitel" >
    <td width="15%"><?php echo $forum->getusername($row->userid)?></td>
    <td width="85%"><table width="100%" border="0">
      <tr>
        <td><?php echo "am "; 
         echo date("d.m.Y H:i", $row->timestamp); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="375" align="left" valign="top">
    <table width="100%" border="0" height="100%">
      <tr align="center"  width="200">
        <td height="160"><img src="include/userpics.php?id=<?php echo $row->userid?>"></td>
      </tr>
      <tr>
        <td height="25" valign="top"><?php echo $forum->getrank($row->userid)?></td>
      </tr>
      <tr>
        <td valign="top">Anzahl der Beiträge: <?php echo $forum->getcountposts($row->userid)?></td>
      </tr>
    </table></td>
    <td align="left" valign="top"><?php echo $row->text?></td>
  </tr>
  <tr class="forumfooter">
    <td height="21"><a href="index.php?site=pm&new&to=<?php echo $forum->getusername($row->userid)?>"><img src="images/pm.gif" width="24" height="24"></a>
         <?php
     if (($_SESSION['ID'] == $row->userid) or ($right[$level]['forum_edit_post']))
     {
      echo $lbs->link("forum_editpost", '<img src="images/edit.gif" width="24" height="24">', $row->ID); 
     } 
           if ($right[$level]['forum_edit_post'])
      {
      echo "&nbsp;";
      echo $lbs->link("forum_delpost", '<img src="images/abort.gif" width="24" height="24">', $row->ID); 
      }
      ?>
    </td>
    <td><table width="100%" border="0">
      <tr>
        <td width="80%">&nbsp;</td>
        <td width="20%"></td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
</tr>
<?php

}
}
?>

<tr>
 <td align="right">
 Seite: <?php   
 $pagec = count($posts);
 $rangetof = $page - 1;
 $rangetol = $pagec - $page;
 
 if ($rangetof > 2)
 {
 ?>
 <a href="index.php?site=forum&show=<?php echo $threadid?>&page=1">1</a> ... 
 <a href="index.php?site=forum&show=<?php echo $threadid?>&page=<?php echo $page-1?>"><?php echo $page-1?></a>
 <?php
 } else
 {
 for ($i=1;$i < $page; $i++) {
 	?>
 	<a href="index.php?site=forum&show=<?php echo $threadid?>&page=<?php echo $i?>"><?php echo $i?></a>
 	<?php
 }
 }
  	?>
 	<b><?php echo $page?></b>
 	<?php
 	if ($rangetol > 2)
 {
 ?>
 <a href="index.php?site=forum&show=<?php echo $threadid?>&page=<?php echo $page+1?>"><?php echo $page+1?></a> ...
 <a href="index.php?site=forum&show=<?php echo $threadid?>&page=<?php echo $pagec?>"><?php echo $pagec?></a>
 
 <?php
 } else
 {
 for ($i=$page+1;$i < $pagec+1; $i++) {
 	?>
 	<a href="index.php?site=forum&show=<?php echo $threadid?>&page=<?php echo $i?>"><?php echo $i?></a>
 	<?php
 }
 }
 

  ?>
 </td>
</tr>
</table>

<?php
if (($closed == "1") or ($_SESSION['level'] < $row->level))
{
echo "Thema Geschlossen";
} elseif (isset($_SESSION['username']))
{

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
<center><b>Antworten</b>
<form action="index.php?site=forum" method="post">
<textarea name="text" cols="75" rows="15"></textarea></center>
<input type="hidden" name="threadid" value="<?php echo $threadid?>"><br>
<button type="submit" name="newpost"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button>
</form>
</td>
  </tr>
</table>
<?php
} // Close

} // Passwort
} // Levelok
} // Exsistenzabfrage


} elseif (isset($post['newpost']))
{
//  --------------------------------------------------------------------- Newpost----------------------------------------------------------------------

$text = $post['text'];
$text = str_replace("<iframe", " >iframe", $text);
$text = str_replace("<script", " >script", $text);
$text = str_replace("<style", " >style", $text);

$user = $_SESSION['ID'];
$threadid = $post['threadid'];


$sql = "
INSERT INTO `$dbpräfix"."posts` (
`ID` ,
`threadid` ,
`userid` ,
`text` ,
`timestamp`
)
VALUES (
NULL , '$threadid', '$user', '$text', '".time()."'
);
";
$erg = $hp->mysqlquery($sql);

$sql = "UPDATE `$dbpräfix"."threads` SET `lastpost` = '".time()."' WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);


echo "Erfolgreich erstellt<br>Weiterleitung erfolgt...";
?>
<script>
window.location="index.php?site=forum&show=<?php echo $threadid?>";
</script>
<?php

} elseif (isset($post['sendpw']))
{
//  --------------------------------------------------------------------- SendPW----------------------------------------------------------------------



$threadid = $post['thread'];
$password = $post['password'];

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$pw = $row->passwort;

if ($pw == md5($password))
{
// OK
$_SESSION['forum_canread'][] = $threadid;

echo "Passwort OK<br>Weiterleitung erfolgt...";
?>
<script>
window.location="index.php?site=forum&show=<?php echo $threadid?>";
</script>

<?php

} else
{
// Nicht ok
$error->error("Das eingegebene Passwort ist Falsch!");
echo "<a href=index.php?site=forum>Zurück</a>";
}



} elseif (isset($post['sendpw_F']))
{
//  --------------------------------------------------------------------- SendPW_F----------------------------------------------------------------------



$threadid = $post['thread'];
$password = $post['password'];

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$pw = $row->passwort;

if ($pw == md5($password))
{
// OK
$_SESSION['forum_canread_F'][] = $threadid;

echo "Passwort OK<br>Weiterleitung erfolgt...";
?>
<script>
window.location="index.php?site=forum&forum=<?php echo $threadid?>";
</script>

<?php

} else
{
// Nicht ok
$error->error("Das eingegebene Passwort ist Falsch!");
echo "<a href=index.php?site=forum>Zurück</a>";
}



} elseif (isset($get['newthread']))
{
//  --------------------------------------------------------------------- NewThread---------------------------------------------------------------------




if (isset($_SESSION['username']))
{

$forumid = $_SESSION['forumid'];

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = $forumid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



$levelok = true;
 $visible = false;
 
 if ($row->level > $level)
 {
 $levelok = false;
 }
 
 
if (!$levelok)
{
$error->error("Dieses Forum ist geschossen!");
echo "Dieses Forum ist geschlossen<br><a href=index.php?site=forum>zurück</a>";
} else
{

?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"

	});
</script>
<!-- /TinyMCE -->
<form action="index.php?site=forum" method="post">
<center><table border="1" widht="90%">
<tr>
<td>
<table width="100%" border="0">
  <tr>
    <td width="80">&nbsp;</td>
    <td colspan="2">Neues Thema</td>
  </tr>
  <tr>
    <td>Thema:</td>
    <td width="80"> &nbsp;
    <table border="0" width="100%" height="5">
    <tr>
    <td width="90%">
    <input type="text" name="titel" id="titel"></td>
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
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?php echo $row->level?>" <?php if ($row->level == "0") { echo " checked=\"true\"";} ?>>
          <?php echo $row->name?></label></td>
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
          <input type="radio" name="type" value="0" checked="true">
          Normal</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="1">
          Sticky</label></td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="type" value="2">
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
      <input type="text" name="passwort" id="passwort">
    </p>
    <p>Notiz: Frei Lassen für öffentlich. Admins können auch Passwort geschützte Themen lesen.</p></td>
  </tr>
  <tr>
    <td>Sichtbar:</td>
    <td><p>
        <input type="checkbox" name="visible" id="visible"> 
      Ja</p>
      <p>Notiz: Das Thema ist für Benutzer eines geringeren Levels Sichtbar, Sie können aber nicht Antworten.</p></td>
  </tr>
  </table>
</div> </td>

 <tr>
    <td>Text:</td>
    <td colspan="2"><textarea name="text" id="text" cols="100" rows="15"></textarea></td>
  </tr>
 <tr>
   <td>&nbsp;</td>
   <td colspan="2"><button type="submit" name="newthread"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></td>
 </tr>
</table>
</td>
</tr>
</table>
</center>
<input type="hidden" value="<?php echo $_SESSION['forumid']?>" name="forumid">
</form>
<?php
}

} else
{
echo "Bitte Melden Sie sich an um auf diese Seite zu kommen!";
}
 
 
 
 
} elseif (isset($post['newthread']))
{
//  --------------------------------------------------------------------- Post Newthread---------------------------------------------------------------



$titel = $post['titel'];
$le = $post['level'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];
$forumid = $post['forumid'];
$type = $post['type'];

if ($passwort != "")
{
$passwort = md5($passwort);
} else
{
$passwort = "";
}

$text = str_replace("<iframe", " >iframe", $text);
$text = str_replace("<script", " >script", $text);
$text = str_replace("<style", " >style", $text);

if ($visible == "on")
{
$visible = "1";
} else
{
$visible = "0";
}

if (!isset($le))
{
$le = 0;
}

if (!isset($forumid))
{
$error->error("Fehler. Bitte versuchen Sie diese Seite nicht direkt aufzurufen!");
} else
{


$sql = "
INSERT INTO `$dbpräfix"."threads` (
`ID` ,
`titel` ,
`forumid` ,
`userid` ,
`timestamp` ,
`text` ,
`level` ,
`passwort` ,
`visible` ,
`lastpost`,
`type`,
`closed`
)
VALUES (
NULL , '$titel', '$forumid', '".$_SESSION['ID']."', '".time()."', '$text', '$le', '".$passwort."', '$visible', '".time()."', '$type', '0'
);

";
$erg = $hp->mysqlquery($sql);

$info->okn("Thema erfolgreich erstellt.");
echo "Thema erfolgreich erstellt.<br><a href=index.php?site=forum&forum=$forumid>Zurück</a>";

}
// post




} elseif (isset($get['newforum']))
{
//  --------------------------------------------------------------------- NewForum---------------------------------------------------------------------




if ($right[$level]['forum_edit_forum'])
{

?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"

	});
</script>
<!-- /TinyMCE -->
<form action="index.php?site=forum" method="post">
<center><table border="1" widht="90%">
<tr>
<td>
<table width="100%" border="0">
  <tr>
    <td width="80">&nbsp;</td>
    <td colspan="2">Neues Forum</td>
  </tr>
  <tr>
    <td>Titel:</td>
    <td width="80"> &nbsp;
    <table border="0" width="100%" height="5">
    <tr>
    <td width="90%">
    <input type="text" name="titel" id="titel"></td>
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
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?php echo $row->level?>" <?php if ($row->level == "0") { echo " checked=\"true\"";} ?>>
          <?php echo $row->name?></label></td>
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
      <input type="text" name="passwort" id="passwort">
    </p>
    <p>Notiz: Frei Lassen für öffentlich.</p></td>
  </tr>
  <tr>
    <td>Sichtbar:</td>
    <td><p>
        <input type="checkbox" name="visible" id="visible"> 
      Ja</p>
      <p>Notiz: Das Forum ist für Benutzer eines geringeren Levels Sichtbar, Sie können aber nicht Antworten.</p></td>
  </tr>
  </table>
</div> </td>

 <tr>
    <td>Beschreibung:</td>
    <td colspan="2"><textarea name="text" id="text" cols="100" rows="15"></textarea></td>
  </tr>
 <tr>
   <td>&nbsp;</td>
   <td colspan="2"><button type="submit" name="newforum"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button></td>
 </tr>
</table>
</td>
</tr>
</table>
</center>

</form>
<?php


} else
{
echo "Sie dürfen diese Seite nicht betreten!";
}
 
 
 
 
} elseif (isset($post['newforum']))
{
//  --------------------------------------------------------------------- Post Newthread---------------------------------------------------------------



$titel = $post['titel'];
$le = $post['level'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];


if ($passwort != "")
{
$passwort = md5($passwort);
} else
{
$passwort = "";
}

$text = str_replace("<iframe", " >iframe", $text);
$text = str_replace("<script", " >script", $text);
$text = str_replace("<style", " >style", $text);

if ($visible == "on")
{
$visible = "1";
} else
{
$visible = "0";
}

if (!isset($le))
{
$le = 0;
}




$sql = "
INSERT INTO `$dbpräfix"."forums` (
`ID` ,
`titel` ,
`userid` ,
`timestamp` ,
`level` ,
`passwort` ,
`visible` ,
`type`,
`description`
)
VALUES (
NULL , '$titel', '".$_SESSION['ID']."', '".time()."', '$le', '".$passwort."', '$visible', '$type', '$text'
);

";
$erg = $hp->mysqlquery($sql);

$info->okn("Forum erfolgreich erstellt.");
echo "Forum erfolgreich erstellt.<br><a href=index.php?site=forum>Zurück</a>";


// post




} elseif (isset($get['closethread']))
{
//  --------------------------------------------------------------------- Closethread----------------------------------------------------------------------
$id = $get['closethread'];
if ($right[$level]['forum_canclosethread'])
{

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $id";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$close = $row->closed;

if ($row->closed == "0")
{
$closed = "1";
} else
{
$closed = 0;
}

$sql = "UPDATE `$dbpräfix"."threads` SET `closed` = '$closed' WHERE `ID` = $id";
$erg = $hp->mysqlquery($sql);

$info->okn("Thread geschlossen / geöffnet");

$this->fp->log($sql);
echo "OK";
} else
{
echo "Fehler!";
}

?>
<script>
window.location="index.php?site=forum&show=<?php echo $id?>";
</script>
<?php


} elseif (isset($post['delthread']))
{
//  --------------------------------------------------------------------- Delthread----------------------------------------------------------------------
$threadid = $post['postid'];

if ($right[$level]['forum_edit_post'])
{
$sql = "DELETE FROM `$dbpräfix"."threads` WHERE `ID` = '$threadid'";
$erg = $hp->mysqlquery($sql);

$sql = "DELETE FROM `$dbpräfix"."posts` WHERE `threadid` = '$threadid'";
$erg = $hp->mysqlquery($sql);

} else
{
$error->error("Sie haben keine Berechtigung Threads zu löschen!");
}

$info->okn("Thread gelöscht");
} elseif (isset($post['delpost']))
{
//  --------------------------------------------------------------------- Delpost----------------------------------------------------------------------
$postid = $post['postid'];

if ($right[$level]['forum_edit_post'])
{
$sql = "DELETE FROM `$dbpräfix"."posts` WHERE `ID` = '$postid'";
$erg = $hp->mysqlquery($sql);

} else
{
$error->error("Sie haben keine Berechtigung Posts zu löschen!");
}

$info->okn("Post gelöscht");

} elseif (isset($post['delforum']))
{
//  --------------------------------------------------------------------- Delforum----------------------------------------------------------------------
$postid = $post['postid'];

if ($right[$level]['forum_del_forum'])
{
$sql = "DELETE FROM `$dbpräfix"."forums` WHERE `ID` = '$postid'";
$erg = $hp->mysqlquery($sql);

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `forumid` = '$postid'";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$threadid = $row->ID;
$sql = "DELETE FROM `$dbpräfix"."posts` WHERE `threadid` = '$threadid'";
$erg = $hp->mysqlquery($sql);

}
$sql = "DELETE FROM `$dbpräfix"."threads` WHERE `forumid` = '$postid'";
$erg = $hp->mysqlquery($sql);

$info->okn("Forum mit allen Themen gelöscht");
} else
{
$error->error("Sie haben keine Berechtigung Foren zu löschen!");
}

} else 
{
//  --------------------------------------------------------------------- FORUM----------------------------------------------------------------------



$countthreads = array();
$forums = array();
$countposts = array();
$lastpost = array();
$lasttime = array();
$lastthread = array();


$sql = "SELECT * FROM `$dbpräfix"."threads`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$countthreads[$row->forumid] += 1;
$forums[$row->forumid][] = $row->ID;




}



foreach ($forums as $key=>$value) {
	$lasttime[$key] = time();
}


foreach ($forums as $forumid=>$threads) {
		
	foreach ($threads as $key=>$value) {
 	
 	$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `threadid` = '$value'";
 	$erg = $hp->mysqlquery($sql);
 	while ($row = mysql_fetch_object($erg))
 	{
   $countposts[$forumid] += 1;
   

   
   if ($lasttime[$forumid] > $row->timestamp )
   {
   $lasttime[$forumid] = $row->timestamp;
   $lastpost[$forumid] = $row->userid;
   $lastthread[$forumid] = $row->threadid;
   }
   
   }
 	
 }
	
}

//Link:
echo $forum->createLink();

?>


<table width="100%" border="1">
  <tr>
    <td width="59%">Forum</td>
    <td width="7%" align="center" valign="middle"><div align="center">Themen</div></td>
    <td width="7%" align="center" valign="middle"><div align="center">Beiträge</div></td>
    <td width="27%"><div align="center">Letzter Beitrag</div></td>
  </tr>
  
  <?php
  
  $sql = "SELECT * FROM `$dbpräfix"."forums`";
  $erg = $hp->mysqlquery($sql);
  
  while ($row = mysql_fetch_object($erg))
  {
   $levelok = true;
 $visible = false;
 
 if ($row->level > $level)
 {
 $levelok = false;
 }
 
 
 if ($levelok or ($row->visible == "1"))
 {
 $visible = true;
 }
 
 if ( $visible )
 {
  ?>
  
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="10%" rowspan="2"><div align="center"><img src="<?php echo $forum->getimage_f($row->ID)?>"></div></td>
        <td width="90%"><a href=index.php?site=forum&forum=<?php echo $row->ID?>><?php echo $row->titel?></a></td>
      </tr>
      <tr>
        <td><?php echo $row->description?></td>
      </tr>
    </table></td>
    <td align="center" valign="middle"><?php echo $countthreads[$row->ID]?></td>
    <td align="center" valign="middle"><?php if ($countposts[$row->ID] != "") { echo $countposts[$row->ID]; } else { echo "0"; }?></td>
    <td><table width="100%" border="0">
      <tr>
        <td><?php if ($lastpost[$row->ID] != "")
        {
        $sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` ='".$lastthread[$row->ID]."'";
        $erg2 = $hp->mysqlquery($sql);
        $row2 = mysql_fetch_object($erg2);
        
        echo "<a href=index.php?site=forum&show=".$row2->ID.">$row2->titel</a>";
        
        echo " von ";
        $sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '".$lastpost[$row->ID]."'";
        $erg2 = $hp->mysqlquery($sql);
        $row2 = mysql_fetch_object($erg2);
        
        echo "<a href=index.php?site=user&show=".$row2->user.">$row2->user</a>";
        }
        
          ?></td>
      </tr>
      <tr>
        <td><?php if ($lastpost[$row->ID] != "")
        {  echo date("d.m.Y H:i", $lasttime[$row->ID]);
        } ?></td>
      </tr>
    </table></td>
  </tr>
  
<?php
}
}


?>
</table>
<?php
if ($right[$level]['forum_edit_forum'])
{

      echo "&nbsp;";
      echo "<a href=?site=forum&newforum>".'<img src="include/button.php?p=3&t=Neues Forum">'."</a>"; 
}
}
?>