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

?>
<table width="100%" border="1">
  <tr>
    <td width="64%">Themen</td>
    <td width="7%" align="center" valign="middle"><div align="center">Beiträge</div></td>
    <td width="29%"><div align="center">Letzter Beitrag</div></td>
  </tr>
 
 <?
 
 $sql = "SELECT * FROM `$dbpräfix"."threads` WHERE `forumid` = '$forumid'";
 $erg = $hp->mysqlquery($sql);
 while ($row = mysql_fetch_object($erg))
 {
 
 
 
 ?>
 
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="10%" rowspan="2"><div align="center">Bild</div></td>
        <td width="90%"><?=$row->titel?></td>
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
 
 ?>
  
 
</table>
<?

} else
{
$countthreads = array();
$forums = array();
$countposts = array();
$lastpost = array();
$lasttime = array();


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