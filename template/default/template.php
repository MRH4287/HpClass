<?php

$hp = $this->hp;
$dbpräfix = $hp->getpräfix();

$right = $hp->right;


$template['member'] = "";
$levels = $right->getlevels();


$ranks = array();
$sql = "SELECT * FROM `$dbpräfix"."ranks`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
  $ranks[$row->level] = $row->name;
}


$data = array();
$sql = "SELECT * FROM `$dbpräfix"."user`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
  $data[$row->level][] = $row;

}

foreach ($levels as $k=>$level)
{
  if ($level != 0)
  {
    if (isset($ranks[$level]))
    {
      $levelname = $ranks[$level];
    } else
    {
      $levelname = $level;
    }
  
    $template['member'].="<tr><td ><b>$levelname:</b></td></tr>";
    if (isset($data[$level]))
    {
      foreach ($data[$level] as $key=>$row)
      { 
         $template['member'].="<tr><td ><a href=index.php?site=user&show=$row->user>&raquo;&nbsp;$row->user</a></td></tr>";
      }
    }
  
  }
}


?>
