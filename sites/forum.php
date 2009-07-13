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
$info = $hp->getinfo();
$lbs = $hp->lbsites;
$forum = $hp->forum;
$fp = $hp->fp;




if (!isset($_SESSION['level']))
{
$_SESSION['level'] = 0;
}


if (isset($get['forum']))
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

$postsasite = 20;

$i = 0;
$sql = "SELECT ID FROM `$dbpräfix"."threads` WHERE `forumid` = '$forumid' ORDER BY `lastpost` DESC";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$i++;

$p = ceil($i / ($postsasite));
$posts[$p][] = $row->ID;

}



?>
<table width="100%" border="1">
  <tr>
    <td width="64%">Themen</td>
    <td width="7%" align="center" valign="middle"><div align="center">Antworten</div></td>
    <td width="29%"><div align="center">Letzte Antwort</div></td>
  </tr>
 
 <?

 if (count($posts[$page]) == 0)
{
$this->fp->log($posts);
$page = 1;
$error->error("Diese Seite exsistiert nicht!");
}
 
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
        <td width="10%" rowspan="2"><div align="center">Bild</div></td>
        <td width="90%"><a href="index.php?site=forum&show=<?=$row->ID?>"><?=$row->titel?></a></td>
      </tr>
      <tr>
        <td>von <? 
        $sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '".$row->userid."'";
        $erg2 = $hp->mysqlquery($sql);
        $row2 = mysql_fetch_object($erg2);
        
        echo "<a href=index.php?site=user&show=".$row2->user.">$row2->user</a>";
        
        
          ?> am <?  echo date("d.m.Y H:i", $row->timestamp);  ?>  </td>
      </tr>
    </table></td>
    <td align="center" valign="middle"><? if ($countposts[$row->ID] != "") { echo $countposts[$row->ID]; } else { echo "0"; } ?></td>
    <td><table width="100%" border="0">
      <tr>
        <td><?  if ($lastpost[$row->ID] != "")
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
        <td><? if ($lastpost[$row->ID] != "")
        { echo "am "; 
         echo date("d.m.Y H:i", $lasttime[$row->ID]); } ?></td>
      </tr>
    </table></td>
  </tr>
 
 <?
 }
 }
 }
 
 ?>
  
 <tr>
 <td align="right" colspan="3">
 Seite: <?   
 $pagec = count($posts);
 $rangetof = $page - 1;
 $rangetol = $pagec - $page;
 
 if ($rangetof > 2)
 {
 ?>
 <a href="index.php?site=forum&forum=<?=$forumid?>&page=1">1</a> ... 
 <a href="index.php?site=forum&forum=<?=$forumid?>&page=<?=$page-1?>"><?=$page-1?></a>
 <?
 } else
 {
 for ($i=1;$i < $page; $i++) {
 	?>
 	<a href="index.php?site=forum&forum=<?=$forumid?>&page=<?=$i?>"><?=$i?></a>
 	<?
 }
 }
  	?>
 	<b><?=$page?></b>
 	<?
 	if ($rangetol > 2)
 {
 ?>
 <a href="index.php?site=forum&forum=<?=$forumid?>&page=<?=$page+1?>"><?=$page+1?></a> ...
 <a href="index.php?site=forum&forum=<?=$forumid?>&page=<?=$pagec?>"><?=$pagec?></a>
 
 <?
 } else
 {
 for ($i=$page+1;$i < $pagec+1; $i++) {
 	?>
 	<a href="index.php?site=forum&forum=<?=$forumid?>&page=<?=$i?>"><?=$i?></a>
 	<?
 }
 }
 

  ?>
 </td>
</tr>

</table>
<?




} elseif (isset($get['show'])) 
{




$threadid = $get['show'];

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $threadid";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

if ($row->ID == "")
{
$error->error("Der gewünschte Thread exsistiert nicht!");
echo "Der gewünschte Thread exsistiert nicht oder Sie haben keine Bereichtigung diesen einzusehen!";
} else
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
     <input name="thread" type="hidden" id="thread" value="<?=$get['show']?>">
</form>

<?
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
<?

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
    <td width="15%"><?=$forum->getusername($row->userid)?></td>
    <td width="85%"><table width="100%" border="0">
      <tr>
        <td><?=$row->titel?></td>
      </tr>
      <tr>
        <td><? echo "am "; 
         echo date("d.m.Y H:i", $row->timestamp); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="375" align="left" valign="top">
    <table width="100%" border="0" height="100%">
      <tr align="center" width="200">
        <td height="160"><img src="include/userpics.php?id=<?=$row->userid?>"></td>
      </tr>
      <tr>
        <td height="25" valign="top"><?=$forum->getrank($row->userid)?></td>
      </tr>
      <tr>
        <td valign="top">Anzahl der Beiträge: <?=$forum->getcountposts($row->userid)?></td>
      </tr>
    </table></td>
    <td align="left" valign="top"><?=$row->text?></td>
  </tr>
  <tr class="forumfooter">
    <td height="21"><a href="index.php?site=pm&new&to=<?=$forum->getusername($row->userid)?>"><img src="images/pm.gif" width="24" height="24"></a></td>
    <td><table width="100%" border="0">
      <tr>
        <td width="80%">&nbsp;</td>
        <td width="20%">Vote</td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
</tr>

<?

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
    <td width="15%"><?=$forum->getusername($row->userid)?></td>
    <td width="85%"><table width="100%" border="0">
      <tr>
        <td><? echo "am "; 
         echo date("d.m.Y H:i", $row->timestamp); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="375" align="left" valign="top">
    <table width="100%" border="0" height="100%">
      <tr align="center"  width="200">
        <td height="160"><img src="include/userpics.php?id=<?=$row->userid?>"></td>
      </tr>
      <tr>
        <td height="25" valign="top"><?=$forum->getrank($row->userid)?></td>
      </tr>
      <tr>
        <td valign="top">Anzahl der Beiträge: <?=$forum->getcountposts($row->userid)?></td>
      </tr>
    </table></td>
    <td align="left" valign="top"><?=$row->text?></td>
  </tr>
  <tr class="forumfooter">
    <td height="21"><a href="index.php?site=pm&new&to=<?=$forum->getusername($row->userid)?>"><img src="images/pm.gif" width="24" height="24"></a></td>
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
<?

}
}
?>

<tr>
 <td align="right">
 Seite: <?   
 $pagec = count($posts);
 $rangetof = $page - 1;
 $rangetol = $pagec - $page;
 
 if ($rangetof > 2)
 {
 ?>
 <a href="index.php?site=forum&show=<?=$threadid?>&page=1">1</a> ... 
 <a href="index.php?site=forum&show=<?=$threadid?>&page=<?=$page-1?>"><?=$page-1?></a>
 <?
 } else
 {
 for ($i=1;$i < $page; $i++) {
 	?>
 	<a href="index.php?site=forum&show=<?=$threadid?>&page=<?=$i?>"><?=$i?></a>
 	<?
 }
 }
  	?>
 	<b><?=$page?></b>
 	<?
 	if ($rangetol > 2)
 {
 ?>
 <a href="index.php?site=forum&show=<?=$threadid?>&page=<?=$page+1?>"><?=$page+1?></a> ...
 <a href="index.php?site=forum&show=<?=$threadid?>&page=<?=$pagec?>"><?=$pagec?></a>
 
 <?
 } else
 {
 for ($i=$page+1;$i < $pagec+1; $i++) {
 	?>
 	<a href="index.php?site=forum&show=<?=$threadid?>&page=<?=$i?>"><?=$i?></a>
 	<?
 }
 }
 

  ?>
 </td>
</tr>
</table>


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
<center><b>Antworten</b></center>
<form action="index.php?site=forum" method="post">
<textarea name="text" cols="100" rows="15"></textarea>
<input type="hidden" name="threadid" value="<?=$threadid?>"><br>
<button type="submit" name="newpost"> <img src="images/ok.gif"> </button> <button type="reset"> <img src="images/abort.gif"> </button>
</form>
</td>
  </tr>
</table>


<?
} // Passwort
} // Levelok
} // Exsistenzabfrage


} elseif (isset($post['newpost']))
{
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
window.location="index.php?site=forum&show=<?=$threadid?>";
</script>
<?

} elseif (isset($post['sendpw']))
{





/*

*/

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
window.location="index.php?site=forum&show=<?=$threadid?>";
</script>

<?

} else
{
// Nicht ok
$error->error("Das eingegebene Passwort ist Falsch!");
echo "<a href=index.php?site=forum>Zurück</a>";
}



} elseif (isset($get['newthread']))
{





if (isset($_SESSION['username']))
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
    <?
    $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` <= '$level';";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
        ?>
    
      <tr>
        <td><label>
          <input type="radio" name="level" value="<?=$row->level?>">
          <?=$row->name?></label></td>
      </tr>
     <?
    }
     ?> 
    </table>
      <p>Notiz: Jeder Benutzer eines höheren Levels kann dieses Forum trotzdem lesen!</p></td>
  </tr>
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
    <td colspan="2"><textarea name="text" id="text" cols="45" rows="5"></textarea></td>
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
<input type="hidden" value="<?=$_SESSION['forumid']?>" name="forumid">
</form>
<?
} else
{
echo "Bitte Melden Sie sich an um auf diese Seite zu kommen!";
}
 
 
 
 
} elseif (isset($post['newthread']))
{




$titel = $post['titel'];
$le = $post['level'];
$passwort = $post['passwort'];
$visible = $post['visible'];
$text = $post['text'];
$forumid = $post['forumid'];

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
`closed`
)
VALUES (
NULL , '$titel', '$forumid', '".$_SESSION['ID']."', '".time()."', '$text', '$le', '".$passwort."', '$visible', '".time()."', '0'
);

";
$erg = $hp->mysqlquery($sql);

$info->okn("Thema erfolgreich erstellt.");
echo "Thema erfolgreich erstellt.<br><a href=index.php?site=forum&forum=$forumid>Zurück</a>";

}
// post




} else
{




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


?>


<table width="100%" border="1">
  <tr>
    <td width="59%">Forum</td>
    <td width="7%" align="center" valign="middle"><div align="center">Themen</div></td>
    <td width="7%" align="center" valign="middle"><div align="center">Beiträge</div></td>
    <td width="27%"><div align="center">Letzter Beitrag</div></td>
  </tr>
  
  <?
  
  $sql = "SELECT * FROM `$dbpräfix"."forums`";
  $erg = $hp->mysqlquery($sql);
  
  while ($row = mysql_fetch_object($erg))
  {
  
  ?>
  
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="10%" rowspan="2"><div align="center">Bild</div></td>
        <td width="90%"><a href=index.php?site=forum&forum=<?=$row->ID?>><?=$row->titel?></a></td>
      </tr>
      <tr>
        <td><?=$row->description?></td>
      </tr>
    </table></td>
    <td align="center" valign="middle"><?=$countthreads[$row->ID]?></td>
    <td align="center" valign="middle"><? if ($countposts[$row->ID] != "") { echo $countposts[$row->ID]; } else { echo "0"; }?></td>
    <td><table width="100%" border="0">
      <tr>
        <td><? if ($lastpost[$row->ID] != "")
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
        <td><? if ($lastpost[$row->ID] != "")
        {  echo date("d.m.Y H:i", $lasttime[$row->ID]);
        } ?></td>
      </tr>
    </table></td>
  </tr>
  
<?
}


?>
</table>

<?
}
?>