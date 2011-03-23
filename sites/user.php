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

if (isset($post['changelevel'])) 
{
  $leveltemp=$post['level'];
  $eingabe = "UPDATE `".$dbpräfix."user` SET `level` = '$leveltemp' WHERE `user` = '".$get['change']."';";
  $ergebnis = $hp->mysqlquery($eingabe);
  echo mysql_error();
}

if (isset ($get['delet']))
{
  $hp->info->info("Den User ".$get['delet']." Wirklich endgültig löschen? <a href=index.php?site=user&delet2=".$get['delet'].">Ja</a>");
}

if (isset ($get['delet2']))
{
  if (!$right[$level]['userdelet']) 
  {
   echo $lang->word('noright');
  } else
  {
    $eingabe = "DELETE FROM `".$dbpräfix."user` WHERE `user` = '".$get['delet2']."';";
    $ergebnis = $hp->mysqlquery($eingabe);
    echo mysql_error();
    if ($ergebnis==true) {
      echo $lang->word('delok');
    } 
  }
}

if (!isset($get['show'])) 
{

  $abfrage = "SELECT * FROM ".$dbpräfix."user ORDER BY `level` DESC ";
  $ergebnis = $hp->mysqlquery($abfrage);

  $site = new siteTemplate($hp);
  $site->load("user_list");

   
  $content = "";
  while($row = mysql_fetch_object($ergebnis))
  {
    $data = array(
      "user" => $row->user,
      "datum" => $row->datum,
      "name" => $row->name,
      "nachname" => $row->nachname    
    );
    
    $content .= $site->getNode("Users", $data);

  }

  $site->set("Users", $content);
  
  $site->display();

} else
{
  $site = new siteTemplate($hp);
  $site->load("user_show");

  $abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `user` = '".$get['show']."'";

  $ergebnis = $hp->mysqlquery($abfrage);
  $row = mysql_fetch_object($ergebnis);
  
  if ($row->user == $get['show'])
  {
  
    // Level Name:
    $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` = '$row->level'";
    $erg2 = $hp->mysqlquery($sql);
    $level = mysql_fetch_object($erg2);
  
  
   $data = array(
      "ID" => $row->ID,
      "user" => $row->user,
      "name" => $row->name,
      "nachname" => $row->nachname,
      "level" => $level->name,
      "alter" => $row->alter,
      "wohnort" => $row->wohnort,
      "geburtstag" => $row->geburtstag,
      "clan" => $row->clan,
      "clantag" => $row->clantag,
      "clanhomepage" => $row->clanhomepage,
      "clanhistory" => $row->clanhistory,
      "lastlogin" => ($row->lastlogin != 0)?  date("d.m.Y H:i s", $row->lastlogin) : $lang->word('never'),
      "user" => $row->user
    );

  
   $site->setArray($data);
                
    $EMData = array(
    "email" => $row->email
   );
   $site->set("EMail", $site->getNode("EMail", $EMData));
  
   $DLData = array
   (
    "ID" => $row->ID
   );
   
   $site->set("Deluser", $site->getNode("Deluser", $DLData));
   
   // Changelevel:
   
   // Ermittlung aller verfügbaren Levels
   $sql = "SELECT * FROM `".$dbpräfix."ranks`";
   $erg2 = $hp->mysqlquery($sql);
   $levels = array();
    while ($row2 = mysql_fetch_object($erg2))
    {
     if (!in_array($row2, $levels))
     {
       $levels[] = $row2;
     } 
   }
   sort($levels);

   // changelevel - Options

   $content = "";
   foreach ($levels as $key => $level)
   {
     $data = array(
       "selected" => ($row->level == $level->level) ? "selected" : "",
       "ID" => $level->level,
       "name" => $level->name    
     );
   
     $content .= $site->getNode("Options", $data);  
   
   }
   
   $data = array(
     
     "user" => $row->user,
     "count" => count($levels),
     "Options" => $content 
   
   );
   
   $site->set("changelevel", $site->getNode("LevelChange", $data));
  
  
   $site->display();
  } else
  {
    $error->error("Der Benutzer exsistiert nicht!", "1");
  }
}

?>