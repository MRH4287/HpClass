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

Lade alle f¸r das System relevanten Daten.

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
      "desc" => "Forum: Darf Threads und Posts bearbeiten die nicht ihm gehˆren",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_canusetypes",
      "desc" => "Forum: Darf die Thread Typen verwenden: (Sticky, Announce)",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_canclosethread",
      "desc" => "Forum: Darf Threads schlieﬂen. (<b>Keiner</b> kann schreiben)",
      "cat"  => "Forum"
      ),
      array (
      
      "name" => "forum_edit_forum",
      "desc" => "Forum: Darf Foren bearbeiten.",
      "cat"  => "Forum"
      ),
        array (
      
      "name" => "forum_del_forum",
      "desc" => "Forum: Darf komplette Foren lˆschen.",
      "cat"  => "Forum"
      )
   
   );
   
   $right->registerArray($rights);
   
  $this->hp->addredirect("forum", "plugins/Forum");


}


/*

Hier werden die eigentlichen Aufgaben des Plugins erledigt.
Wie zum Beispiel das hinzuf¸gen von Weiterleitungen.

*/
function onLoad()
{


$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
$right = $hp->getright();
$level = $_SESSION['level'];
$info = $hp->getinfo();

if (isset($post['forum_editpost']))
{

$sql = "SELECT * FROM `$dbpr‰fix"."posts` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);
$time = time();

if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbpr‰fix"."posts` SET `text` = '".$post['text']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Post ge‰ndert");
}


}

if (isset($post['forum_editthread']))
{

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `ID` = ".$post['postid'];
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
$sql = "UPDATE `$dbpr‰fix"."threads` SET `text` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."', `lastedit` = '$time' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread ge‰ndert");
}


}

if (isset($post['forum_editforum']))
{

$sql = "SELECT * FROM `$dbpr‰fix"."forums` WHERE `ID` = ".$post['postid'];
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
$sql = "UPDATE `$dbpr‰fix"."forums` SET `description` = '".$post['text']."', `level` = '$level', `type` = '$type', $pw `visible` = '$visible2', `titel` = '".$post['titel']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Forum ge‰ndert");
}


}

if (isset($post['forum_movethread']))
{

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



if (($row->userid == $_SESSION['ID']) or $right[$level]['forum_edit_post'])
{
$sql = "UPDATE `$dbpr‰fix"."threads` SET `forumid` = '".$post['moveto']."' WHERE `ID` = ".$post['postid'];
$erg = $hp->mysqlquery($sql);
$info->okn("Thread verschoben");
}


}
  

}


// ......................................................


function getusername($ID)
{
$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpr‰fix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->user;
}

function getrank($ID)
{
$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpr‰fix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$level = $row->level;

$sql = "SELECT * FROM `$dbpr‰fix"."ranks` WHERE `level` = $level";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->name;

}

function getcountposts($ID)
{
$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpr‰fix"."posts` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count = mysql_num_rows($erg);

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count += mysql_num_rows($erg);

return $count;
}

function getimage_f($forum)
{
$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
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

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `forumid` = $forum";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
if (($row->lastpost > $lasttime[$row->ID]) and ($this->user_lastlogin($_SESSION['ID']) < $row->lastpost))
{
$new = true;
}
}

$sql = "SELECT * FROM `$dbpr‰fix"."forums` WHERE `ID` = $forum";
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
$dbpr‰fix = $hp->getpr‰fix();
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

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `ID` = $ID";
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

$sql = "SELECT * FROM `$dbpr‰fix"."posts` WHERE `threadid` = '$ID'";
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
$dbpr‰fix = $hp->getpr‰fix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT lastlogin FROM `$dbpr‰fix"."user` WHERE `ID` = '$user'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->lastlogin;
}

function getvote($threadid)
{
$hp = $this->hp;
$dbpr‰fix = $hp->getpr‰fix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpr‰fix"."threads` WHERE `ID` = $threadid";
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
$dbpr‰fix = $hp->getpr‰fix();


$link = '<a href="index.php?site=forum">Forum</a>';

if (($forum == 0) and ($thread != 0))
{

$sql = "SELECT forumid FROM `$dbpr‰fix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$forum = $row->forumid;
}


if ($forum != 0)
{
$sql = "SELECT titel FROM `$dbpr‰fix"."forums` WHERE `ID` = '$forum'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&forum='.$forum.'">'.$row->titel.'</a>';
}


if ($thread != 0)
{
$sql = "SELECT titel FROM `$dbpr‰fix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&show='.$thread.'">'.$row->titel.'</a>';
}


return $link;

}





}



?>