<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();


if (isset($post['changelevel'])) 
{
  $leveltemp=$post['level'];
  $eingabe = "UPDATE `".$dbprefix."user` SET `level` = '$leveltemp' WHERE `user` = '".$get['change']."';";
  $ergebnis = $hp->mysqlquery($eingabe);
}

if (isset ($get['delet']))
{
  $hp->info->info("Den User ".$get['delet']." Wirklich endgültig löschen? <a href=index.php?site=user&delet2=".$get['delet'].">Ja</a>");
}

if (isset ($get['delet2']))
{
  if (!$right[$level]['userdelet']) 
  {
   $error->error($lang->word('noright'),"1");
  } else
  {
    $eingabe = "DELETE FROM `".$dbprefix."user` WHERE `ID` = '".$get['delet2']."';";
    $ergebnis = $hp->mysqlquery($eingabe);
    if ($ergebnis==true) {
      $info->okn($lang->word('delok'));
    } 
  }
}

if (!isset($get['show']) && $right[$_SESSION['level']]["see_userPage"]) 
{

  $abfrage = "SELECT * FROM ".$dbprefix."user ORDER BY `level` DESC ";
  $ergebnis = $hp->mysqlquery($abfrage);

  $site = new siteTemplate($hp);
  $site->load("user_list");
  $site->right("see_userPage");
   
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

} else if ($right[$_SESSION['level']]["see_userPage"])
{
  $site = new siteTemplate($hp);
  $site->load("user_show");
  $site->right("see_userPage");


  $ranks = $hp->right->getLevelNames();

  
  $abfrage = "SELECT * FROM ".$dbprefix."user WHERE `user` = '".$get['show']."'";

  $ergebnis = $hp->mysqlquery($abfrage);
  $row = mysql_fetch_object($ergebnis);
  
  if ((mysql_num_rows($ergebnis) > 0) && ($row->user == $get['show']))
  {
     
    
     $data = array(
        "ID" => $row->ID,
        "user" => $row->user,
        "name" => $row->name,
        "nachname" => $row->nachname,
        "level" => isset($ranks[$row->level]) ? $ranks[$row->level] : $row->level,
        "alter" => $row->alter,
        "wohnort" => $row->wohnort,
        "geburtstag" => $row->geburtstag,
        "clan" => $row->clan,
        "clantag" => $row->clantag,
        "clanhomepage" => $row->clanhomepage,
        "clanhistory" => $row->clanhistory,
        "lastlogin" => $row->lastlogin,
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
     
    $levels = $hp->right->getlevels();
    $content = "";
    foreach ($levels as $k=>$levelD)
    {
       $name = isset($ranks[$levelD]) ? $ranks[$levelD] : $levelD;
            
       $data = array(
         "selected" => ($row->level == $levelD) ? "selected" : "",
         "ID" => $levelD,
         "name" => $name    
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
} else
{
    $site = new siteTemplate($hp);
    $site->load("info");
    $site->set("info", "Sie haben kein Recht diese Seite zu betreten!");
    $site->display();
}

?>