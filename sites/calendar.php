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
  $subpage = $hp->subpages;
  

  $site = new siteTemplate($hp);
  $site->load("calendar");
  
  $content = "";
  
  if (!$right[$level]["manage_calendar"])
  {
    $error->error("Sie haben nicht die benötigte Berechtigung!", "1");

  } else
  {
    if (isset($get["new"]))
    {
    
      $date = $get["new"];
      $reg = "/[0-9]{2}\\.[0-9]{2}\\.[0-9]{4}/";
      
      if (preg_match($reg, $date))
      {
        $data = array(
         "date" => $date,
         "name" => "",
         "time" => "",
         "endtime" => "",
         "enddate" => $date,
         "description" => "",
         "ID" => "",
         "update" => "false"
        );
      
        // Display:
        $display= array();
        $toDisplay = $subpage->getAllTemplatesWithDynContent("calendar");
        
        // Abfragen aller Seiten:
        $sql = "SELECT * FROM `$dbprefix"."subpages`;";
        $erg = $hp->mysqlquery($sql);
        while ($row = mysql_fetch_object($erg))
        {
          if (in_array($row->template, $toDisplay))
          {
            $display[$row->ID] = $row->name;
          } 
        
        }
          
        $cnt = "";
        foreach ($display as $ID=>$name)
        {
          $data2 = array(
              "ID" => $ID,
              "name" => $name,
              "enabled" => ($ID == 0) ? "true" : "false"
          );
          
          $cnt .= $site->getNode("Edit-Display", $data2);
        
        }
        $data["display"] = $cnt;      
      
      
        $levels = $hp->right->getlevels();
        $con = "";
        foreach ($levels as $k=>$level)
        {
          $sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
          $erg = $hp->mysqlquery($sql);
          
          if (mysql_num_rows($erg) > 0)
          {
            $row = mysql_fetch_object($erg);
            $name = $row->name;
          } else
          {
            $name = $level;
          }
          
          
          $dat = array(
            "level" => $level,
            "name" => $name,
            "aktlevel" => "0"
          );      
          
          $con .= $site->getNode("Edit-Levels", $dat);
          
        }
       
        $data["levels"] = $con;
      
      
      
        $content = $site->getNode("Edit", $data);
      
      
      } else
      {
        $error->error("Das angegebene Datum ist nicht Valid");
      }
    
    
    
    } elseif (isset($post["new"]))
    {
    
      $date = $post["date"];
      $reg = "/[0-9]{2}\\.[0-9]{2}\\.[0-9]{4}/";
      
      if (preg_match($reg, $date))
      {
        if (!isset($post["name"]) || !isset($post["time"]) || !isset($post["endtime"]) || !isset($post["enddate"]))
        {
          $content = "Fehlerhafte Daten";
          $error->error("Fehlerhafte Daten");  
          
        } elseif (preg_match($reg, $post["enddate"])) 
        {
          
          if (isset($post["display"]))
          {     
            $name = $post["name"];
            $time = $post["time"];
            $endtime = $post["endtime"];
            $display = $post["display"];
            $enddate = $post["enddate"];
            $description = $post["txt"];
            $level = $post["level"];
            
            $startData = explode(".", $date);
            $endData = explode(".", $enddate);
        
            $startDay = $startData[0];
            $startMonth = $startData[1];
            $startYear = $startData[2];
        
            $endDay = $endData[0];
            $endMonth = $endData[1];
            $endYear = $endData[2];
            
            
            if ((($endYear*12+$endMonth)*30+$endDay) >= (($startYear*12+$startMonth)*30+$startDay))
            {         
              $options = "";
              if (isset($post["options"]))
              {
                $options = $post["options"];
              }
              
              $sql = "INSERT INTO `$dbprefix"."events` (`name`, `date`, `enddate`, `start`, `end`, `level`, `display`,  `options`, `user`,  `time`, `description`) VALUES 
              ('$name', '$date', '$enddate', '$time', '$endtime', '$level', '".implode(",",$display)."', '$options', '".$_SESSION["username"]."', NOW(), '$description');";
              $erg = $hp->mysqlquery($sql);
              
              $content = "Erfolgreich erstellt";
              $info->okn("Erfolgreich erstellt");
              
            } else
            {       
              $content = "Das End-Datum muss nach dem Start-Datum liegen";
              $error->error("Das End-Datum muss nach dem Start-Datum liegen");
            }
            
          } else
          {
            $error->error("Keine Seite zum Anzeigen gewählt");
            $info->info("Sie können diese über den Unterseiten-Editor erstellen");
          } 
        } else
        {
          $content = "Fehlerhafte Daten";
          $error->error("Fehlerhafte Daten");
        }    
         
      } else
      {
        $content = "Fehlerhafte Daten";
        $error->error("Fehlerhafte Daten");
      }
  
    } elseif (isset($get["edit"]))
    {
       $sql = "SELECT * FROM `$dbprefix"."events` WHERE `ID` = '".$get["edit"]."';";
       $erg = $hp->mysqlquery($sql);
       if (mysql_num_rows($erg) > 0)
       { 
         $row = mysql_fetch_object($erg);
           
         $data = array(
          "date" => $row->date,
          "name" => $row->name,
          "time" => $row->start,
          "endtime" => $row->end,
          "enddate" => $row->enddate,
          "description" => $row->description,
          "ID" => $row->ID,
          "update" => "true"
         );
       
         $displayed = explode(",", $row->display);
       
         // Display:
         $display= array();
         $toDisplay = $subpage->getAllTemplatesWithDynContent("calendar");
         
         // Abfragen aller Seiten:
         $sql = "SELECT * FROM `$dbprefix"."subpages`;";
         $erg = $hp->mysqlquery($sql);
         while ($row2 = mysql_fetch_object($erg))
         {
           if (in_array($row2->template, $toDisplay))
           {
             $display[$row2->ID] = $row2->name;
           } 
         
         }
           
         $cnt = "";
         foreach ($display as $ID=>$name)
         {
           $data2 = array(
               "ID" => $ID,
               "name" => $name,
               "enabled" => (in_array($ID, $displayed)) ? "true" : "false"
           );
           
           $cnt .= $site->getNode("Edit-Display", $data2);
         
         }
         $data["display"] = $cnt;      
       
       
         $levels = $hp->right->getlevels();
         $con = "";
         foreach ($levels as $k=>$level)
         {
           $sql = "SELECT * FROM `$dbprefix"."ranks` WHERE `level` = '$level'";
           $erg = $hp->mysqlquery($sql);
           
           if (mysql_num_rows($erg) > 0)
           {
             $row2 = mysql_fetch_object($erg);
             $name = $row2->name;
           } else
           {
             $name = $level;
           }
           
           
           $dat = array(
             "level" => $level,
             "name" => $name,
             "aktlevel" => $row->level
           );      
           
           $con .= $site->getNode("Edit-Levels", $dat);
           
         }
        
         $data["levels"] = $con;
       
       
       
         $content = $site->getNode("Edit", $data);
        
       } else
       {
        $error->error("Dieser Kalender-Eintrag exsistiert nicht!");
        $content = "Fehler";
       }
    
    
    } elseif (isset($post["edit"]))
    {
      $date = $post["date"];
      $reg = "/[0-9]{2}\\.[0-9]{2}\\.[0-9]{4}/";
      
      if (preg_match($reg, $date))
      {
        if (!isset($post["name"]) || !isset($post["time"]) || !isset($post["endtime"]) || !isset($post["enddate"]))
        {
          $content = "Fehlerhafte Daten";
          $error->error("Fehlerhafte Daten");  
          
        } elseif (preg_match($reg, $post["enddate"])) 
        {
          
          if (isset($post["display"]))
          {     
            $name = $post["name"];
            $time = $post["time"];
            $endtime = $post["endtime"];
            $display = $post["display"];
            $enddate = $post["enddate"];
            $description = $post["txt"];
            $level = $post["level"];
            
            $startData = explode(".", $date);
            $endData = explode(".", $enddate);
        
            $startDay = $startData[0];
            $startMonth = $startData[1];
            $startYear = $startData[2];
        
            $endDay = $endData[0];
            $endMonth = $endData[1];
            $endYear = $endData[2];
            
            $ID = $post["ID"];
            
            
            if ((($endYear*12+$endMonth)*30+$endDay) >= (($startYear*12+$startMonth)*30+$startDay))
            {         
              $options = "";
              if (isset($post["options"]))
              {
                $options = $post["options"];
              }
              
              $sql = "UPDATE `$dbprefix"."events` SET `name` = '$name', `enddate` = '$enddate', `start` = '$time', `end` = '$endtime', `level` = '$level',
              `display` =  '".implode(",",$display)."', `options` = '$options', `description` = '$description' WHERE `ID` = '$ID';";
              $erg = $hp->mysqlquery($sql);
              
              $content = "Erfolgreich aktualisiert";
              $info->okn("Erfolgreich aktualisiert");
              
            } else
            {       
              $content = "Das End-Datum muss nach dem Start-Datum liegen";
              $error->error("Das End-Datum muss nach dem Start-Datum liegen");
            }
            
          } else
          {
            $error->error("Keine Seite zum Anzeigen gewählt");
            $info->info("Sie können diese über den Unterseiten-Editor erstellen");
          } 
        } else
        {
          $content = "Fehlerhafte Daten";
          $error->error("Fehlerhafte Daten");
        }    
         
      } else
      {
        $content = "Fehlerhafte Daten";
        $error->error("Fehlerhafte Daten");
      }
      
    
    } elseif (isset($post["eventdel"]))
    {
        $ID = $post["ID"];
        $sql = "DELETE FROM `$dbprefix"."events` WHERE `ID` = '$ID'";
        $erg = $hp->mysqlquery($sql);
        
        $info->okn("Event erfolgreich gelöscht");
        
        
        $content = $site->getNode("Error-Select");
    
    
    } else
    {   
      $content = $site->getNode("Error-Select");
    }
    
    $site->set("Content", $content);
    
    $site->display("Edit-Container");
    
    $this->hp->xajaxF->open("xajax_event_list()"); 
  }
  
  
  

  
  

?>