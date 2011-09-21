<?php
// Site Config:
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$fp = $hp->fp;


if (isset($post["s"]) or isset($get["s"]))
{
  if (isset($get["s"]))
  {
    $s = $get["s"];
  } else
  {
    $s = $post["s"];  
  }
  
  $s = str_replace("ü", "&uuml;", $s);
  $s = str_replace("Ü", "&Uuml;", $s);
  $s = str_replace("ö", "&ouml;", $s);
  $s = str_replace("Ö", "&Ouml;", $s);
  $s = str_replace("ä", "&auml;", $s);
  $s = str_replace("Ä", "&Auml;", $s);
  $s = str_replace("ß", "&szlig;", $s);
  
  $resultSub = array();

  // Frage alle Einträge im Subpage System ab:
  $sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `name` LIKE '%$s%' OR `content` LIKE '%$s%';";
  $erg = $hp->mysqlquery($sql);
  while ($row = mysql_fetch_array($erg))
  {
   $resultSub[] = $row;  
  }
  
  $resultNews = array();
  
  //Frage alle Newsmeldungen / Projekte ab
  $sql = "SELECT * FROM `$dbprefix"."news` WHERE `text` LIKE '%$s%' OR `titel` LIKE '%$s%';";
  $erg = $hp->mysqlquery($sql);
  while ($row = mysql_fetch_array($erg))
  {
   $resultNews[] = $row;  
  }

  echo "<h2>TestAusgabe</h2>";


  echo "<pre>";
  echo "Ergebnisse Unterseiten:\r\n";
  print_r($resultSub);
  echo "Ergebnisse Newsmeldungen / Projekte:\r\n";
  print_r($resultNews);
  echo "</pre>";


}






?>