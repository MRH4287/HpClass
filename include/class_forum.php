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

// Lock
// --- Level zu niedrig und Visiblle
$lock = false;


$link = "forum/forum";
if ($new)
{
$link .= "_unread";
} else
{
$link .= "_read";

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

/*
if (($row->lastpost > $lastpost) and ($this->user_lastlogin($_SESSION['ID']) < $lastpost))
{

}
*/

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

}
?>
