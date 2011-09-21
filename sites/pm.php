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


$datum = date('j').".".date('n').".".date('y');

if (isset($_SESSION['username']))
{
  if (isset($post['del']))
  {
    $dellarr = array();
    $dellarr = $post['sel'];

    foreach ($dellarr as $id)
    {

      $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `ID` = '".$id."' ORDER BY `ID`;";
      $ergebnis = $hp->mysqlquery($abfrage);
      $row = mysql_fetch_object($ergebnis);
    
      if (strtolower($_SESSION['username']) == strtolower("$row->zu"))
      {
       $eintrag = "DELETE FROM `".$dbprefix."pm` WHERE `ID`= ".$id;
       $eintragen = $hp->mysqlquery($eintrag);

       if ($eintragen == true)
       {
        $info->okn($lang->word('delok'));
       }
      } 
      else
      { 
        $error->error($lang->word('cantdelmessage'),"2", __FILE__.":".__LINE__);
      }
    } 


  } elseif (isset($post['mark']))
  {

    $sellarr = array();
    $sellarr = $post['sel'];

    foreach ($sellarr as $id)
    {

     $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `ID` = '".$id."' ORDER BY `ID`;";
     $ergebnis = $hp->mysqlquery($abfrage);
     $row = mysql_fetch_object($ergebnis);
    
     if (strtolower($_SESSION['username']) == strtolower("$row->zu"))
     {
        $eintrag = "UPDATE `".$dbprefix."pm` SET `gelesen` = 1 WHERE `ID`= ".$id;
        $eintragen = $hp->mysqlquery($eintrag);


        if ($eintragen == true)
        {
          $info->okn("Als gelesen makiert");
        }

      }
    }
    
  } elseif (isset($get['del']))
  {
    $del = $get['del'];
    $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `ID` = '".$del."';";
    $ergebnis = $hp->mysqlquery($abfrage);
    $row = mysql_fetch_object($ergebnis);
  
    if ($_SESSION['username'] == "$row->zu")
    {
      $eintrag = "DELETE FROM `".$dbprefix."pm` WHERE `ID`= ".$del;
      $eintragen =$hp->mysqlquery($eintrag);


      if ($eintragen == true)
      {
        $info->okn($lang->word('delok'));
      } 
    }
    else
    {
      $error->error($lang->word('cantdelmessage'),"2", __FILE__.":".__LINE__);
    }
  } elseif (isset ($post['post']) and isset($get["new"]))
  {
    $von = $_SESSION['username'];
    $zu = $post['zu'];
    $text = $post['Text'];
    $timestamp = $post['timestamp'];
    $Betreff = $post['Betreff'];
    if (!isset($Betreff) or $Betreff == "")
    {
      $Betreff = "Kein Betreff angegeben";
    }
    $abfrage = "SELECT ID FROM ".$dbprefix."pm WHERE `timestamp` = '".$timestamp."'";
    $ergebnis = $hp->mysqlquery($abfrage);
    $number = mysql_num_rows($ergebnis);
      
    if ($number == 0)
    {
      $eintrag = "INSERT INTO `".$dbprefix."pm`
      (von, datum, zu, text, Betreff, gelesen, timestamp)
      VALUES
      ('$von', '$datum', '$zu', '$text', '$Betreff', '0', '$timestamp')";

      $eintragen = $hp->mysqlquery($eintrag);
      if ($eintragen == true)
      {
        $info->okn($lang->word('postok'));
      }
       
    } else
    {
      $error->error($lang->word('doublepost'),"2");
    }
  }


  if (!isset($get['read']) and !isset($get['new']) and !isset($post["post"]) and  !isset($get['ausgang']))
  {

    $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `zu` = '".$_SESSION['username']."' ORDER BY `ID` DESC;";
    $ergebnis = $hp->mysqlquery($abfrage);
    if ($ergebnis == false)
    {
     $error->error(mysql_error(), "2");
    }
  
    $site = new siteTemplate($hp);
    $site->load("pm_list");  
  

    $content = "";
    while($row = mysql_fetch_object($ergebnis))
    {
     $data = array(
       "ID" => $row->ID,
       "von" => $row->von,
       "Betreff" => ($row->gelesen == 0) ? "<b>$row->Betreff</b>" : $row->Betreff,
       "Datum" => $row->Datum,
       "gelesen" => ""   
      );
     
      $content .= $site->getNode("Line", $data);
    }
  
    $site->set("gelesen", "");
    $site->set("Line", $content);
  
    $site->display();
    
 } elseif (isset($get['read']))
 {

  $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `ID` = '".$get['read']."' ORDER BY `ID`;";
  $ergebnis = $hp->mysqlquery($abfrage);
  $row = mysql_fetch_object($ergebnis);
  
  if ((strtolower($_SESSION['username']) == strtolower("$row->zu")) or strtolower($_SESSION['username']) == strtolower("$row->von"))
  {
    if ($_SESSION['username'] != "$row->von") 
    { 
       $eingabe2 = "UPDATE `".$dbprefix."pm` SET `gelesen` = '1' WHERE `ID` = $row->ID;";
       $ergebnis2 = $hp->mysqlquery($eingabe2);
    }   

     $site = new siteTemplate($hp);
     $site->load("pm_show");  
     
     $data = array(
       "von" => $row->von,
       "zu" => $row->zu,
       "Datum" => $row->Datum,
       "Betreff" => $row->Betreff,
       "Text" => $row->Text,
       "Delet" => ""    
     );
     
     $site->setArray($data);
     
     if ($_SESSION['username'] != "$row->von") 
     {
        $data = array(
          "ID" => $row->ID
        );
        
        $site->set("Delet", $site->getNode("Delet", $data));
        
     }
     
     $site->display();
  
  
  } else
  {
     $error->error($lang->word('messagenotforyou'),"2");
     $error->error($lang->word('messagenotforyou'),"1");
  }

  //POST DEL
 } 
 elseif (isset($get['new']))
 {
    $site = new siteTemplate($hp);
    $site->load("pm_edit");
    
    $data = array(
      "site" => "new",
      "zu" => (isset($get["zu"])) ? $get["zu"] : "",
      "Betreff" => (isset($get['bet'])) ? "RE: ".$get['bet'] : "",
      "Text" => "",
      "time" => time()    
    );
    
    $site->setArray($data);
    
    $site->display();


 }
 elseif (isset($get['ausgang']))
 {


  $abfrage = "SELECT * FROM ".$dbprefix."pm  WHERE `von` = '".$_SESSION['username']."' ORDER BY `ID`;";
  $ergebnis =$hp->mysqlquery($abfrage);

 

  $site = new siteTemplate($hp);
  $site->load("pm_list");  
  

  $content = "";
  while($row = mysql_fetch_object($ergebnis))
  {
    $data = array(
      "ID" => $row->ID,
      "von" => $row->von,
      "Betreff" => ($row->gelesen == 0) ? "<b>$row->Betreff</b>" : $row->Betreff,
      "Datum" => $row->Datum,
      "gelesen" => ($row->gelesen == 1) ? $lang->word("yes") : $lang->word("no")   
     );
     
     $content .= $site->getNode("Line", $data);
   }
   
   $site->set("gelesen", $lang->word("gelesen"));
   $site->set("Line", $content);
  
   $site->display();
 }

}
else
{
    $error->error($lang->word('login'),"2");
}

?>