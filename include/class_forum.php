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

}
?>
