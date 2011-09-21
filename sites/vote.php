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
  $lbs = $hp->lbsites;
  $xajaxF = $hp->xajaxF;
  
  
  if (!$right[$level]['manage_vote'])
  {
    $site = new siteTemplate($hp);
    $site->right("manage_vote");  
    $site->display(); 
  } else
  {
  
  
    if (isset($get['addvote']))
    {
    
      $site = new siteTemplate($hp);
      $site->load("vote");
         
      
      $data = array(
        "update" => "false",
        "ID" => "",
        "titel" => "",
        "Answers" => "",
        "tag" =>  date("d", time()),
        "monat" => date("m", time()),
        "year" => date("y", time())
        
      
      );
      
      $site->setArray($data);
      
      $site->display("Edit-Vote");
  
  
      $xajaxF->open("xajax_calender_vote();");
      
    } elseif (isset($post['addvote']))
    {
      $titel = $post['titel'];
      $antwort = $post['antwort'];
      $day = $post['day'];
      $month = $post['month'];
      $year = $post['year'];
      $hour = $post['hour'];
      $min = $post['min'];
      $user = $_SESSION['ID'];
      $time = time();
      
      $timestamp = mktime($hour, $min, 0, $month, $day, $year);
      $antworten = "";
      
      foreach ($antwort as $key=>$value) 
      {
      	
        if ($value != "")
        {
           if ($antworten == "")
           {
             $antworten = "$value";
           } else
           {
             $antworten .= "<!--!>$value";
           }   
        }
      }
      
      
      $sql = "INSERT INTO `$dbprefix"."vote` (
      `ID` ,
      `name` ,
      `userid` ,
      `antworten` ,
      `ergebnisse` ,
      `timestamp` ,
      `upto`
      )
      VALUES (
      NULL , '$titel', '$user', '$antworten', '', '$time', '$timestamp'
      );
      ";
      $erg = $hp->mysqlquery($sql);
      //$this->fp->log($sql);
      
      $info->okn("Umfrage erfolgreich eingetragen!");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Erfolreich eingetragen<br><a href=index.php?site=vote>Zurück</a>");
      $site->display();
          
    
    } elseif (isset($get['editvote']))
    {
      $id = $get['editvote'];
      
      $sql = "SELECT * FROM `$dbprefix"."vote` WHERE `ID` = $id";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
      
      $site = new siteTemplate($hp);
      $site->load("vote");
         
      
      $data = array(
        "update" => "true",
        "ID" => $id,
        "titel" => $row->name,
        "Answers" => "",
        "tag" =>  date("d", time()),
        "monat" => date("m", time()),
        "year" => date("y", time())
        
      
      );
      
      $site->setArray($data);
      


      $content = "";
      $antworten = explode("<!--!>", $row->antworten);
      $i = 0;
      foreach ($antworten as $key=>$value) 
      {
        	$i++;
        	$data = array(
            "ID" => $i,
            "update" => "true",
            "value" => $value           
          
          );
          
          $content .= $site->getNode("Edit-Antwort", $data);

       }
       
       $site->set("Answers", $content);    
        
       $site->display("Edit-Vote");


       $xajaxF->open("xajax_calender_vote();");
       $xajaxF->open("checkvote(true);");
      
      
    } elseif (isset($post['editvote']))
    {
      $ID = $post['ID'];
      $titel = $post['titel'];
      $antwort = $post['antwort'];
      $day = $post['day'];
      $month = $post['month'];
      $year = $post['year'];
      $hour = $post['hour'];
      $min = $post['min'];
      $user = $_SESSION['ID'];
      $time = time();
      
      $timestamp = mktime($hour, $min, 0, $month, $day, $year);
      
      $antworten = "";
      
      foreach ($antwort as $key=>$value) 
      {
        	
        if ($value != "")
        {
          if ($antworten == "")
          {
            $antworten = "$value";
          } else
          {
           $antworten .= "<!--!>$value";
          }
        }
        
      }
      
      
      $sql = "UPDATE `$dbprefix"."vote` SET `name` = '$titel', `antworten` = '$antworten', `upto` = '$timestamp' WHERE `ID` = '$ID';";
      $erg = $hp->mysqlquery($sql);
      
      
      $info->okn("Umfrage erfolgreich aktualisiert!");
      $site = new siteTemplate($hp);
      $site->load("info");                                                                   
      $site->set("info", "Erfolreich aktualisiert<br><a href=index.php?site=vote>Zurück</a>");
      $site->display();
      
      
      
    } elseif (isset($post['votedel']))
    {
      $sql = "DELETE FROM `$dbprefix"."vote` WHERE `ID` = ".$post['voteiddel'];
      $erg = $hp->mysqlquery($sql);
    
      $info->okm("Umfrage erfolgreich gelöscht!");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Umfrage erfolgreich gelöscht.<br><a href=index.php?site=vote>Zurück</a>");
      $site->display();
    
    
    } else
    {
      
      $sql = "SELECT * FROM `$dbprefix"."vote`";
      $erg = $hp->mysqlquery($sql);
  
      $contentMain = "";
      while ($row = mysql_fetch_object($erg))
      {
      
          $site = new siteTemplate($hp);
          $site->load("vote");  
          
          $ergebniss = explode("<!--!>", $row->ergebnisse);
          $voted = count($ergebniss);
          if ($ergebniss[0] == "")
          {
            $voted--;
          }
          $whov = explode("<!--!>", $row->voted);
          
          $data = array(
            "name" => $row->name,
            "ID" => $row->ID
          );
          
          $site->setArray($data);
              
          $content = "";    
          if ($row->upto > time())
          {
            
           if (isset($_SESSION['ID']) && !in_array($_SESSION['ID'], $whov))
           { 
        
              $answers = explode("<!--!>", $row->antworten);
              
              $votes = "";
              foreach ($answers as $key=>$value) 
              {
                
                  $data2 = array(
                  "ID" => $data["ID"],
                  "key" => $key,
                  "value" => $value
                  );
                
                  $votes .= $site->getNode("Vote-Element", $data2);
                  
              }
              
              $data2 = array_merge(array(), $data);
              $data2["votes"] = $votes;
              
              $content = $site->getNode("Vote-List", $data2);
    
            } else
            {
              $content = $site->getNode("Vote-Voted", $data);
            }
              
          } else
          {
            $content = $site->getNode("Vote-Out", $data);
          }
                  
          $site->set("content", $content);
          $site->set("votes", $voted);
                
          $container = new siteTemplate($hp);
          $container->load("vote");
          $container->set("Content", $site->get("Vote"));
                
                
          $contentMain .=  $container->get("Main-Vote-Container");
         
         
      }
      
      $site = new siteTemplate($hp);
      $site->load("vote");
      $site->set("Votes", $contentMain);
      $site->display();
    
      
    }
  
  } // Right
?>