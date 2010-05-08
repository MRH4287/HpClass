<?php
class forum
{
var $hp;


function sethp($hp)
{
$this->hp = $hp;
}



function getusername($ID)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->user;
}

function getrank($ID)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."user` WHERE `ID` = '$ID'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$level = $row->level;

$sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` = $level";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->name;

}

function getcountposts($ID)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count = mysql_num_rows($erg);

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `userid` = $ID";
$erg = $hp->mysqlquery($sql);
$count += mysql_num_rows($erg);

return $count;
}

function getimage_f($forum)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
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

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `forumid` = $forum";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
if (($row->lastpost > $lasttime[$row->ID]) and ($this->user_lastlogin($_SESSION['ID']) < $row->lastpost))
{
$new = true;
}
}

$sql = "SELECT * FROM `$dbpräfix"."forums` WHERE `ID` = $forum";
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
$dbpräfix = $hp->getpräfix();
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

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $ID";
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

$sql = "SELECT * FROM `$dbpräfix"."posts` WHERE `threadid` = '$ID'";
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
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT lastlogin FROM `$dbpräfix"."user` WHERE `ID` = '$user'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

return $row->lastlogin;
}

function getvote($threadid)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `ID` = $threadid";
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
$dbpräfix = $hp->getpräfix();


$link = '<a href="index.php?site=forum">Forum</a>';

if (($forum == 0) and ($thread != 0))
{

$sql = "SELECT forumid FROM `$dbpräfix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$forum = $row->forumid;
}


if ($forum != 0)
{
$sql = "SELECT titel FROM `$dbpräfix"."forums` WHERE `ID` = '$forum'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&forum='.$forum.'">'.$row->titel.'</a>';
}


if ($thread != 0)
{
$sql = "SELECT titel FROM `$dbpräfix"."threads` WHERE `ID` = '$thread'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);


$link .=" > ".'<a href="index.php?site=forum&show='.$thread.'">'.$row->titel.'</a>';
}


return $link;

}

}
?>
