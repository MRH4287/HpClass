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


  if (!isset ($_SESSION['username'])) 
  {
    $error->error($lang->word('noright2'),"3");
  } else
  {

    if ((!$right[$level]['useragree']) and ((!$right[$level]['userdisagree'])))
    {
      $error->error($lang->word('noright2'),"2");
    } else
    {
      if (!$right[$level]['useragree'] and isset($get['register']))
      {
        $error->error($lang->word('kberechacanwae'),"2");

      } elseif (isset($get['register']))
      {
        $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter WHERE `user`= '".$get['register']."'";
        $ergebnis = $hp->mysqlquery($abfrage);
    
        $row = mysql_fetch_object($ergebnis);
        
        $user=$row->user;
        $passwort123=$row->pass;
        $name=$row->name;
        $nachname=$row->nachname;
        $datum=$row->datum;
   
  
        $eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['register']."'";
        $eintragen1 = $hp->mysqlquery($eintrag);
        

        $eintrag = "INSERT INTO `".$dbpräfix."user`
        (user, pass, name, nachname, datum, level)
        VALUES
        ('$user', '$passwort123', '$name', '$nachname', '$datum', '1')";
        $eintragen2 = $hp->mysqlquery($eintrag);

        if ($eintragen1 == true and $eintragen2 == true)
        {
          $info->okn($lang->word('postok'));
        } else { $error->error("Fehler: ".mysql_error(),"2"); }
      }


      if (!$right[$level]['userdisagree'] and isset($get['delet']))
      {
        $error->error($lang->word('kberechdeanwae'),"2");

      } elseif (isset($get['delet']))
      {
        $eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['delet']."'";
        $eintragen = $hp->mysqlquery($eintrag);
        if ($eintragen == true)
        {
          $info->okn($lang->word('delok'));
        } else 
        { 
          $error->error("Fehler: ".mysql_error(),"2");
        }
      }


      $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
      $ergebnis = $hp->mysqlquery($abfrage);
    
      $site = new siteTemplate($hp);
      $site->load("anwaerter");
    
      $content = "";
      while($row = mysql_fetch_object($ergebnis))
      {
        $data = array(
          "user" => $row->user,
          "name" => $row->name,
          "nachname" => $row->nachname,
          "datum" => $row->datum,
          "text" => $row->text
        );

        $content .= $site->getNode("Line", $data);

      } 
      $site->set("Content", $content);
      
      $site->display();

    }
  }
?>
