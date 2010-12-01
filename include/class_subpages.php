<?php
class subpages
{
var $hp;


function sethp($hp)
{
$this->hp = $hp;
}

function getChilds($parent)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

$sql = "SELECT ID FROM `$dbpräfix"."subpages` WHERE `parent` = '$parent' ORDER BY `order` ASC;";
$erg = $hp->mysqlquery($sql);

$childs = array();

  while ($row = mysql_fetch_object($erg))
  {

  $childs[] = $this->getSite($row->ID);

  }

  return $childs;

}


function haveChilds($parent)
{
 return (count($this->getChilds($parent) > 0));
}


function getSite($site)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$game = $hp->game;
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


  
  $sql = "SELECT * FROM `$dbpräfix"."subpages` WHERE `ID` = '$site' OR `name` = '$site';";
  $erg = $hp->mysqlquery($sql);

  $array = mysql_fetch_array($erg);
  
    if (is_array($array))
    {
  
      $fp->log($array['ID']);
     $childs = $this->getChilds($array['ID']);
      if ($childs != false)
      {
      $array['childs'] = $childs;
       
      }
    }

  return ((is_array($array)) ? $array : false);
  
}


function printTree($element, $depth = 0)
{

 $string = "".$depth."<!>".$element['ID']."<!>".$element['name']."</el>";
 
 if (is_array($element['childs']))
 {
  foreach ($element['childs'] as $key=>$value) {
  	
  	$string .= $this->printTree($value, $depth+1);
  	
  }

 }
 return $string;

}



}
?>