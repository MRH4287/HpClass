<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$config = $hp->getconfig();


$restExt = array( "php", "js", "ph4", "ph5", "cgi", "html", "html", "swf" );



if (!$right[$level]['upload'])
{
  $site = new siteTemplate($hp);
  $site->load("info");
  $site->set("info", "Sie haben keine Berechtigung, Daten hoch zu laden!</br><a href=?site=upload>Upload</a>");
  $site->display();

} else
{

  if (isset($post['sendfiles']))
  {
    $numsendfiles = count($_FILES);
    $datum = date('j').".".date('n').".".date('y');
    $posttitel=$post['titel'];
    $username=$_SESSION['username'];
    $beschreibung=$post['S1'];
    $dlevel=$post['level'];
    $kat = $post['kat'];
    
    
    $phproot=substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-strlen($_SERVER['SCRIPT_NAME']));
    
    if ($config["download_useDB"])
    {
    
    
    	foreach($_FILES as $dateiinformationen)
    	{
      		if($dateiinformationen['error']!=0)
      		{
      			$error->error("Fehler {$dateiinformationen['error']}.<br/>\n","2");
      			continue;
      		}
      		
      		if($dateiinformationen['name']=='') 
          { 
            continue;
          }

      		$speicherort=$dateiinformationen['tmp_name'];
      		
      		$datei=fopen($speicherort,'r');
      		$daten=fread($datei,filesize($speicherort));
      		fclose($datei);

      		$dateiname=$hp->escapestring($dateiinformationen['name']);
      		$dateityp=$hp->escapestring($dateiinformationen['type']);
      		$daten=$hp->escapestring($daten);
      		
      		if ($dateiinformationen['size'] > 3145728)
          {
             $site = new siteTemplate($hp);
             $site->load("info");
             $site->set("info", "Die gesendete Datei war zu gro�! (Maximalgr��e: 3MB)<br /><a href=?site=upload>Zur�ck</a>");
             $site->display();
            
          } else
          {
      
            $ext = explode(".", $dateiname);
            $ext = $ext[count($ext) - 1];
            $ext = strtolower($ext);
            if (in_array($ext, $restExt))
            {
              $error->error("Dieser Datei-Typ ist nicht erlaubt!");
                  
              $site = new siteTemplate($hp);
              $site->load("info");
              $site->set("info", "Fehler: Dieser Datei-Typ ist nicht erlaubt!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
              $site->display();
                  
            } else
            {
        
          		$sql = 	"INSERT INTO `".$dbpr�fix."download` (titel, dateiname, datum, autor, beschreibung, level, dateityp, datei, kat, Zeitstempel) \n".
          				"VALUES ('$posttitel', '$dateiname', '$datum', '$username', '$beschreibung', '$dlevel', '$dateityp', '$daten', '$kat', NOW()) \n";
              $result=$hp->mysqlquery($sql);
          		if($result)
          		{
                $info->okn("Erfolgeich eingetragen!");
                $site = new siteTemplate($hp);
                $site->load("info");
                $site->set("info", "Erfolgeich eingetragen!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
                $site->display();
                
                
              } else
          		{
          			$error->error("Fehler beim Schreiben der Daten in die Datenbank.","2");
          			
          		}
        		}
      		}
    	}

    } else
    {
      if (isset($_FILES))
      {
        foreach($_FILES as $strFieldName => $arrPostFiles)
        {
          if ($arrPostFiles['size'] > 0)
          {
             $strFileName = $arrPostFiles['name'];
             $intFileSize = $arrPostFiles['size'];
             $strFileMIME = $arrPostFiles['type'];
             $strFileTemp = $arrPostFiles['tmp_name'];
             
  
              $dateiname=$strFileName;
              
              $ext = explode(".", $strFileName);
              $ext = $ext[count($ext) - 1];
              $ext = strtolower($ext);
              if (in_array($ext, $restExt))
              {
                $error->error("Dieser Datei-Typ ist nicht erlaubt!");
                    
                $site = new siteTemplate($hp);
                $site->load("info");
                $site->set("info", "Fehler: Dieser Datei-Typ ist nicht erlaubt!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
                $site->display();
                
              } else
              {
                $eintrag = "INSERT INTO `".$dbpr�fix."download`
                (dateiname, titel, datum, autor, beschreibung, level, kat, Zeitstempel)
                VALUES
                ('$dateiname', '$posttitel', '$datum', '$username', '$beschreibung', '$dlevel', '$kat', NOW())";
                $eintragen = mysql_query($eintrag);
                move_uploaded_file($strFileTemp, "downloads/$strFileName");
                if ($eintragen== true)
                {
                  $info->okn("Erfolgeich eingetragen!");
                  $site = new siteTemplate($hp);
                  $site->load("info");
                  $site->set("info", "Erfolgeich eingetragen!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
                  $site->display();
                }
              }
          }
        }
      }
    }  
        
     
  } elseif (isset($get['upload'])) 
  {
  
    $site = new siteTemplate($hp);
    $site->load("upload");
      
  
    $levels = $hp->right->getlevels();
    $content = "";
    foreach ($levels as $k=>$level)
    {
      $sql = "SELECT * FROM `$dbpr�fix"."ranks` WHERE `level` = '$level'";
      $erg = $hp->mysqlquery($sql);
      
      if (mysql_num_rows($erg) > 0)
      {
        $row = mysql_fetch_object($erg);
        $name = $row->name;
      } else
      {
        $name = $level;
      }
      
      
      $data = array(
        "level" => $level,
        "name" => $name,
        "aktlevel" => "0"
      );      
      
      $content .= $site->getNode("Edit-Levels", $data);
      
    }
   
    $site->set("levels", $content);
  
    $abfrage = "SELECT * FROM ".$dbpr�fix."download_kat";
    $ergebnis = $hp->mysqlquery($abfrage);
     
    $kats = "";
    while($row2 = mysql_fetch_object($ergebnis))
    {
      $data = array(
        "ID" => $row2->ID,
        "name" => $row2->name,
        "selected" => "false"      
      );
    
       $kats .= $site->getNode("Option", $data);
       
    }
  
  
    $data = array(
      "update" => "false",
      "titel" => "",
      "beschreibung" => "",
      "username" => $_SESSION["username"],
      "kats" => $kats
    
    );
    
    $site->setArray($data);
    
    $site->display("Upload");
    
  
  } elseif (isset($get['filechange']))
  {
  
    $abfrage2 = "SELECT * FROM ".$dbpr�fix."download WHERE `ID` = '".$get['filechange']."'";
    $ergebnis2 = $hp->mysqlquery($abfrage2);
      
    if (mysql_num_rows($ergebnis2) > 0)
    {

      $row = mysql_fetch_object($ergebnis2);
    
    
      $site = new siteTemplate($hp);
      $site->load("upload");
        
    
      $levels = $hp->right->getlevels();
      $content = "";
      foreach ($levels as $k=>$level)
      {
        $sql = "SELECT * FROM `$dbpr�fix"."ranks` WHERE `level` = '$level'";
        $ergl = $hp->mysqlquery($sql);
        
        if (mysql_num_rows($ergl) > 0)
        {
          $rowl = mysql_fetch_object($ergl);
          $name = $rowl->name;
        } else
        {
          $name = $level;
        }
        
        
        $data = array(
          "level" => $level,
          "name" => $name,
          "aktlevel" => $row->level
        );      
        
        $content .= $site->getNode("Edit-Levels", $data);
        
      }
     
      $site->set("levels", $content);
    
      $abfrage = "SELECT * FROM ".$dbpr�fix."download_kat";
      $ergebnis = $hp->mysqlquery($abfrage);
       
      $kats = "";
      while($row2 = mysql_fetch_object($ergebnis))
      {
        $data = array(
          "ID" => $row2->ID,
          "name" => $row2->name,
          "selected" => ($row2->ID == $row->kat) ? "true" : "false"      
        );
      
         $kats .= $site->getNode("Option", $data);
         
      }
    
    
      $data = array(
        "update" => "true",
        "titel" => $row->titel,
        "beschreibung" => $row->beschreibung,
        "username" => $_SESSION["username"],
        "kats" => $kats,
        "ID" => $row->ID
      
      );
      
      $site->setArray($data);
      
      $site->display("Upload");
    
    }
  
  } elseif (isset($post['changefile']))
  {
  
    $datum = date('j').".".date('n').".".date('y');
    $posttitel=$post['titel'];
    $username=$_SESSION['username'];
    $beschreibung=$post['S1'];
    $dlevel=$post['level'];
    $dID = $post['ID'];
    $dkat = $post['kat'];
    $posttitel = $hp->escapestring($posttitel);
    $beschreibung = $hp->escapestring($beschreibung);
    $dlevel = $hp->escapestring($dlevel);
    $fp = $this->firephp;

    $sql = "UPDATE `".$dbpr�fix."download` SET `titel` = '$posttitel',
    `level` = '$dlevel',
    `kat` = '$dkat',    
    `beschreibung` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";
    
    $res = $hp->mysqlquery($sql);
    
    if ($res) 
    {
      $info->okm("Datei erfolgreich Modifiziert!");
    }
   
  } elseif (isset($get['katnew']))
  {
  
    $site = new siteTemplate($hp);
    $site->load("upload");
      
  
    $levels = $hp->right->getlevels();
    $content = "";
    foreach ($levels as $k=>$level)
    {
      $sql = "SELECT * FROM `$dbpr�fix"."ranks` WHERE `level` = '$level'";
      $erg = $hp->mysqlquery($sql);
      
      if (mysql_num_rows($erg) > 0)
      {
        $row = mysql_fetch_object($erg);
        $name = $row->name;
      } else
      {
        $name = $level;
      }
      
      
      $data = array(
        "level" => $level,
        "name" => $name,
        "aktlevel" => "0"
      );      
      
      $content .= $site->getNode("Edit-Levels", $data);
      
    }
   
    $site->set("levels", $content);
    
    $data = array(
      "update" => "false",
      "titel" => "",
      "beschreibung" => "",
      "ID" => "0"
    );
    
    $site->setArray($data);
    
    $site->display("Kat-Edit");
    
    
  } elseif (isset($post['katnew']))
  {
  
    $posttitel=$post['titel'];
    $beschreibung=$post['S1'];
    $dlevel=$post['level'];
    
    $posttitel = $hp->escapestring($posttitel);
    $beschreibung = $hp->escapestring($beschreibung);
    $dlevel = $hp->escapestring($dlevel);
    
    
    
    $eintrag = "INSERT INTO `".$dbpr�fix."download_kat`
    (name, description, level)
    VALUES
    ('$posttitel', '$beschreibung', '$dlevel')";
    $eintragen = mysql_query($eintrag);
    
    echo mysql_error();
    if ($eintragen== true)
    {
      $info->okn("Erfolgeich erstellt!");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Erfolgeich erstellt!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
      $site->display();
    }
    
  
  
  } elseif (isset($get['katchange']))
  {
    $abfrage2 = "SELECT * FROM ".$dbpr�fix."download_kat WHERE `ID` = '".$get['katchange']."'";
    $ergebnis2 = $hp->mysqlquery($abfrage2);
      
    if (mysql_num_rows($ergebnis2) > 0)
    {

      $row = mysql_fetch_object($ergebnis2);
    
    
      $site = new siteTemplate($hp);
      $site->load("upload");
        
    
      $levels = $hp->right->getlevels();
      $content = "";
      foreach ($levels as $k=>$level)
      {
        $sql = "SELECT * FROM `$dbpr�fix"."ranks` WHERE `level` = '$level'";
        $ergl = $hp->mysqlquery($sql);
        
        if (mysql_num_rows($ergl) > 0)
        {
          $rowl = mysql_fetch_object($ergl);
          $name = $rowl->name;
        } else
        {
          $name = $level;
        }
        
        
        $data = array(
          "level" => $level,
          "name" => $name,
          "aktlevel" => $row->level
        );      
        
        $content .= $site->getNode("Edit-Levels", $data);
        
      }
     
      $site->set("levels", $content);
    

      $data = array(
        "update" => "true",
        "titel" => $row->name,
        "beschreibung" => $row->description,
        "ID" => $row->ID
      
      );
      
      $site->setArray($data);
      
      $site->display("Kat-Edit");
    
    }

  } elseif (isset($post['katchange']))
  {
  
  
    $posttitel=$post['titel'];
    $beschreibung=$post['S1'];
    $dlevel=$post['level'];
    $dID=$post['ID'];
    
    $posttitel = $hp->escapestring($posttitel);
    $beschreibung = $hp->escapestring($beschreibung);
    $dlevel = $hp->escapestring($dlevel);
    
    
    
    $sql = "UPDATE `".$dbpr�fix."download_kat` SET `name` = '$posttitel',
    `level` = '$dlevel', `description` = '$beschreibung' WHERE `ID` ='".$dID."' LIMIT 1 ;";
    
    $res = $hp->mysqlquery($sql);
    
    if ($res) 
    {
      $info->okn("Erfolgeich bearbeitet!");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Erfolgeich bearbeitet!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
      $site->display();
    }
  
  
  } elseif (isset($get['katdel']) && $right[$level]['upload_del'])
  {
  
    $abfrage = "SELECT * FROM ".$dbpr�fix."download WHERE `kat` = '".$get['katdel']."'";
    $ergebnis = $hp->mysqlquery($abfrage);
        
    $x = mysql_num_rows($ergebnis);
    
    if ($x != 0)
    {
      $error->error("Sie k�nnen keine Katigorien l�schen, in denen sich Dateien befinden!","2");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Sie k�nnen keine Katigorien l�schen, in denen sich Dateien befinden!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
      $site->display();
      
    } else
    {
      $del = $get['katdel'];
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "M�chten Sie die Katigorie wirklick l�schen? <a href=index.php?site=upload&katdel2=$del>Ja</a> <a href=index.php>Nein</a>");
      $site->display();
    }
    
  
  } elseif (isset($get['katdel2']))
  {
  
    $abfrage = "DELETE FROM ".$dbpr�fix."download_kat WHERE `ID`=".$_GET['katdel2'];
    $ergebnis = $hp->mysqlquery($abfrage);
 
    if ($ergebnis == true) 
    {
      $info->okn("Erfolgreich gel�scht!");
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Erfolgreich gel�scht!<br><a href=\"index.php?site=upload\">Zur�ck</a>");
      $site->display();
      
    }
    
  } else
  {
  
      $site = new siteTemplate($hp);
      $site->load("upload");
      $site->display();
  
  }

}
?>