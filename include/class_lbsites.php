<?php
class lbsites
{
  var $error;
  var $hp;
  var $lang;
  var $info;
  var $fp;
  var $liste = array();
  var $funkadd = array();
  
  function __construct()
  {
    $this->extend($this);
  }
  
  function sethp($hp)
  {
    $this->hp = $hp;
    $this->error = $hp->geterror();
    $this->lang = $hp->getlangclass();
    $this->info = $hp->getinfo();
    $this->fp = $hp->firephp;
  }
  
  function extend($ext)
  {
    
    if (is_object($ext))
    {
      //$ext->sethp($this->hp);
      
      $funktions = get_class_methods($ext);
      
      foreach ($funktions as $key=>$value) 
      {
        $split = explode("_", $value);
        if ((count($split) > 1) && ($split[0] == "site") && ($split[1] != ""))
        {
        	$this->liste[$value] = $ext;
        	$this->funkadd[] = $value;
        }
      }
    
    }
  
  }
  
  function link($site, $text, $vars = "", $class = "lbOn")
  {
    return '<a href="index.php?lbsite='.$site.'&vars='.$vars.'" class="'.$class.'">'.$text.'</a>';
  }
  
  
  function load($site, $vars)
  {
    $hp = $this->hp;
    ob_start("ob");
    
    $site = "site_".$site;
    
    
    $siteT = new siteTemplate($hp);
    $siteT->load("lbsite_main");
  
    $content = "";
    if (in_array($site, $this->funkadd))
    {
       $ext = $this->liste[$site];  
       $content = $ext->$site($vars);
       
    } else
    {
      $this->fp->error("ung�ltige LB-Site ($site)");
      $content = $siteT->get('notFound');
    }
    
    $siteT->set("Content", $content);
    $siteT->display();
  
	header("HTTP/1.0 204 No Content");
  
    ob_end_flush();
  
  }
  
  function site_Test($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
  
    
    $content = "";
    $sql = "SELECT * FROM `$dbprefix"."user`";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
      $content .= $row->user."<br>";
    }
    $content .= " � � � � � � � ^ ` ' # + * , ; |";
    $content .= $this->link("Test", "Test");
    
     
    return $content;
  }
  
  function site_delvote($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
    $right = $hp->getright();
    $level = $_SESSION['level'];
    
    $site = new siteTemplate($hp);
    
    
    if($right[$level]['manage_vote'])
    {
      $site->load("vote");
      $sql = "SELECT * FROM `$dbprefix"."vote` WHERE `ID` = '$vars';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
    
      $data = array(
        "ID" => $vars,
        "name" => $row->name,
        "vars" => $vars
      );
      
      $site->setArray($data);
  
      return $site->get("LbSite-Del");
    } else
    {
       $site->load("info");
       $site->set("info",$lang->word('noright'));
       
       return $site->get();
    }
    
    
  }
  
  function site_newschange($site)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $right = $hp->getright();
    $level = $_SESSION['level'];
    
    if (!isset ($_SESSION['username']))
    {
       $error->error("Keine Zugriffsberechtigung!", "1");
    
    } else if (!$right[$level]['newsedit'])
    {
    
      $error->error("Sie haben keine Berechtigung, Newsmeldungen zu bearbeiten!", "1");
    
    } else
    {
      $sql = "SELECT * FROM ".$dbprefix."news WHERE `ID` ='".$site."';";
      $ergebnis = $hp->mysqlquery($sql); 
      $row = mysql_fetch_object($ergebnis);
      $newstext="$row->text";
      $newstext = str_replace('<br>',"\n" ,$newstext);
      $newstext = str_replace('&lt;',"<" ,$newstext);
      
      $siteT = new siteTemplate($hp);
      $siteT->load("news");
      
      $data = array(
        
        "titel" => $row->titel,
        "site" => $site,
        "datum" => $row->datum,
        "datDisabled" => "false",
        "level" => $row->level,
        "newstext" => $newstext,
        "sitename" => "newsedit"    
      );
      
      $siteT->setArray($data);
    
      $data = "";
      
      $sql = "SELECT * FROM `$dbprefix"."usedpics`";
      $erg = $hp->mysqlquery($sql);
      while ($row = mysql_fetch_object($erg))
      {
      
         $breite=$row->width; 
         $hoehe=$row->height; 
        
         $neueHoehe=100;
         $neueBreite=intval($breite*$neueHoehe/$hoehe); 
        
         $img = "<img src=\"include/image.php?id=$row->ID&source=usedpic\" width=\"$neueBreite\" height=\"$neueHoehe\"\> ";
                     
         if ($data == "")
         {
            $data = "'".$img."'";
            
         } else
         {
            $data .= ", '".$img."'";
         }
    
      }
      
      $siteT->set("picturelist", $data);
      
      return $siteT->get("LbSite-Edit");
  
    }
  }
  
  function site_newnews()
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $lang = $hp->getlangclass();
    $right = $hp->getright();
    $level = $_SESSION['level'];
  
    if (!$right[$level]['newswrite'])
    {
      $error->error($lang->word('nonewswrite'), "1");
    
    } else
    {
      $siteT = new siteTemplate($hp);
      $siteT->load("news");
      
      $data = array(
        
        "titel" => "",
        "site" => "0",
        "datDisabled" => "true",
        "datum" => date("y.n.y"),
        "level" => "0",
        "newstext" => "",
        "sitename" => "newswrite"    
      );
      
      $siteT->setArray($data);
  
      $data = "";
  
      $sql = "SELECT * FROM `$dbprefix"."usedpics`";
      $erg = $hp->mysqlquery($sql);
      while ($row = mysql_fetch_object($erg))
      {
      
         $breite=$row->width; 
         $hoehe=$row->height; 
        
         $neueHoehe=100;
         $neueBreite=intval($breite*$neueHoehe/$hoehe); 
        
         $img = "<img src=\"include/image.php?id=$row->ID&source=usedpic\" width=\"$neueBreite\" height=\"$neueHoehe\"\> ";
                     
         if ($data == "")
         {
            $data = "'".$img."'";
            
         } else
         {
            $data .= ", '".$img."'";
         }
    
      }
      
      $siteT->set("picturelist", $data);
      
      return $siteT->get("LbSite-Edit");
  
    }
  }
  
  function site_delnews($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
    $right = $hp->getright();
    $level = $_SESSION['level'];
    
    if($right[$level]['newsdel'])
    {
      
      $sql = "SELECT * FROM `$dbprefix"."news` WHERE `ID` = '$vars';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
      
      $site = new siteTemplate($hp);
      $site->load("news");
      
      $data = array(
        "ID" => $row->ID,
        "titel" => $row->titel,
        "ersteller" => $row->ersteller
      );
      
      $site->setArray($data);
  
      return $site->get("LbSite-Del");
      
      
    } else
    {
      $error->error($lang->word('noright'), "1");
    }
  }
  
  
  function site_eventdel($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
    $right = $hp->getright();
    $level = $_SESSION['level'];
    
    if($right[$level]['manage_calendar'])
    {
      
      $sql = "SELECT * FROM `$dbprefix"."events` WHERE `ID` = '$vars';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_array($erg);
      
      $site = new siteTemplate($hp);
      $site->load("calendar");
      

      $site->setArray($row);
  
      return $site->get("LbSite-Del");
      
      
    } else
    {
      $error->error($lang->word('noright'), "1");
    }
  }

  function site_eventday($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
    $right = $hp->getright();
    $O_right = $hp->right;
    $subpages = $hp->subpages;
    
    $arr_monate = array ('Januar', 'Februar', 'M�rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'); 


    
    $date = explode(".", $vars);
    
    if (count($date) != 3)
    {
      return "Fehlerhafte Eingabedaten!";
    } else
    {
    
      $events = $subpages->getEvents($vars);
      $site = new siteTemplate($hp);
      $site->load("calendar");
      
      $day = $date[0];
      $month  = $date[1];
      $year  = $date[2];
      
      $Sdate = "$day. ".htmlentities($arr_monate[$month-1])." ".$year;
      
      $content = "";
      
      foreach ($events as $key => $row)
      {
      
        if ($O_right->isAllowed($row->level))
        {
      
          $data = array(
            "name" => $row->name,
            "ID"  => $row->ID,
            "date" => $row->date,
            "enddate" => $row->enddate,
            "start" => $row->start,
            "end" => $row->end,
            "description" => substr($row->description, 0, 200) . ((strlen( $row->description) > 200) ? " ..." : "" )        
                
          );
      
          $content .= $site->getNode("ShowDay-Element", $data);
      
        }
      
      }
      
      $data = array(
        "date" => $Sdate,
        "Content" => $content
        
      
      );
      
      $site->setArray($data);
      
      return $site->get("LbSite-ShowDay");
 
    } 

  }

  function site_event($vars)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $lang=$hp->langclass;
    $fp = $hp->fp;
    $right = $hp->getright();
    $O_right = $hp->right;
    $subpages = $hp->subpages;
    
  
    $sql = "SELECT * FROM `$dbprefix"."events` WHERE `ID` = '$vars';";
    $erg = $hp->mysqlquery($sql);
    if (mysql_num_rows($erg) > 0)
    {
    
      $row = mysql_fetch_object($erg);
       
      $site = new siteTemplate($hp);
      $site->load("calendar");
      

      if ($O_right->isAllowed($row->level))
      {
         $data = array(
          "name" => $row->name,
          "ID"  => $row->ID,
          "date" => $row->date,
          "enddate" => $row->enddate,
          "start" => $row->start,
          "end" => $row->end,
          "description" => $row->description                   
        );
    
        $site->setArray($data);
      
        return $site->get("LbSite-ShowSingle");
    
      }
      
      return "Ung�ltige Daten!";

      
    }
      
    

  }


   

} // CLASS ENDE !!

// Output buffering, da sonst alle Sonderzeichen nicht richtig dargestellt werden!
function ob($buffer)
{
$text = $buffer;
$text = str_replace("�", "&uuml;", $text);
$text = str_replace("�", "&Uuml;", $text);
$text = str_replace("�", "&ouml;", $text);
$text = str_replace("�", "&Ouml;", $text);
$text = str_replace("�", "&auml;", $text);
$text = str_replace("�", "&Auml;", $text);
$text = str_replace("�", "&szlig;", $text);

return $text;
}


?>