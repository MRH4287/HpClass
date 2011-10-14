<?php
class subpages
{
  var $hp;
  
  var $templatePath;
  var $dynContent = array();
  
  var $dynContentPrefix = "dy_";
  
  private $display = array();
  
  private $visible = array();
  
  
  function __construct()
  {
    // Konfiguration:
  
    // Der Pfad zu dem Templates der Unterseiten:
    $this->templatePath = array("subpages/", "template/#!Design#/subpages/");
    $this->registerFunctions($this);
  
  }
  
  
  
  function sethp($hp)
  {
    $this->hp = $hp;
  }
  
  
  public function registerFunctions($object) 
  {
  	$methods = get_class_methods($object);
  	
  	foreach ($methods as $m) 
     {
     $p = $this->dynContentPrefix;
  		 if (preg_match("/^{$p}[a-z]/", $m)) 
        {
  			$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);
  			//$this->xajax->registerFunction(array($m2, &$object, $m));
  			$data = array();
  			$data["name"] = $m2;
  			$data["function"] = $m;
  			$data["object"] = $object;
  			
  			$this->dynContent[$m2] = $data;	
  		}
  	}
  }
  
  
  function addDisplay($site)
  {
    if (is_array($site))
    {
      foreach ($site as $k=>$s)
      {
        $this->addDisplay($s);
      }
    
    } else
    {
      $this->display[] = $site;
    
    }
  
  }
  
  
  function loadTemplateFile($template)
  {
    $path = "template/$template/dynamicContent.php";
  
    if ((file_exists($path)) && (is_file($path)))
    {
    
      include $path;
  
      $obj = new dynContent();
      $obj->sethp($this->hp);
    
      $this->registerFunctions($obj);
  
    }
  
  }
  
  
  
  function getNavigationID($siteID)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $fp->fp;
  
  
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `site` = '$siteID' AND `dynamic` = '1';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
  
    return $row->ID;
  }
  
  
  function getSubpageID($naviID)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    $sql = "SELECT site FROM `$dbprefix"."navigation` WHERE `ID` = '$naviID' OR `name` = '$naviID';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
  
    $sql = "SELECT ID FROM `$dbprefix"."subpages` WHERE `ID` = '$row->site' OR `name` = '$row->site';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
  
    return $row->ID;
  }
  
  function getSiteTemplate($ID, $navigation = false)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $template = "";
  
    if ($navigation)
    {
      $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `ID` = '$ID' OR `name` = '$ID';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
  
      $ID = $row->name;
    }                
  
  
    $sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `ID` = '$ID' OR `name` = '$ID';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
  
    return $row->template;        
  }
  
  
  
  function getChilds($parent, $navigationID = true)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    if (!$navigationID)
    {
      $parent = $this->getNavigationID($parent);
  
    }
  
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `parent` = '$parent' ORDER BY `order` ASC;";
    $erg = $hp->mysqlquery($sql);
  
    $childs = array();
  
    while ($row = mysql_fetch_object($erg))
    {
      $childs[] = $row;
    }
  
    return $childs;
  
  }
  
  function getDepth($naviID, $visited = null)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    if ($visited == null)
    {
      $visited = array();
    }
    
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `ID` = '$naviID';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
    
    if (in_array($row->ID, $visited))
    {
      return 0;
    }
    $visited[] = $row->ID;
    
    if ("$row->parent" == "0")
    {
      return 1;
    } else
    {
      return 1 + $this->getDepth("$row->parent", $visited);
    }
    
  
  }
  
  
  function isVisible ($naviID, $visited = null)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    if ($visited === null)
    {
      $visited = array();
      
    } elseif (in_array($naviID, $visited))
    {
      return false;
      
    } else
    {
      
      $visited[] = $naviID;
    }
    
    if (isset($this->visible[$naviID]))
    {
      return $this->visible[$naviID];
    }
    
    
    // Ermitteln, welche Seite gerade geladen ist
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `site` = '$hp->site';";
    $erg = $hp->mysqlquery($sql);
    
    $depth = $this->getDepth($naviID);
    
    
    if (mysql_num_rows($erg) > 0)
    {
      // Die aktuelle Seite ist in der Navigation
      $row = mysql_fetch_object($erg);
      
      $parent = "$row->parent";
      $ID = $row->ID;
      
      // Ist die Aktuelle Seite Verwandt ..
      
      $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `ID` = '$naviID';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
      
      
      
      $ok1 = (("$row->parent" == $parent) || ("$row->parent" == $ID) || ($row->ID == $ID));
      if ($ok1)
      {
        $this->visible[$naviID] = true;
        return true;
      }
      

    
      $childs = $this->getChilds($naviID);
    
      
      foreach ($childs as $k => $row)
      {
        if ($row->ID == $ID)
        {
          $this->visible[$naviID] = true;
          return true;
        }
      
    
      }
      
      $depthA = $this->getDepth($ID);
  
           
      if (($depthA == 3) && ($depth == 2))
      {
         $childs = $this->getChilds("$row->parent");
         
           foreach ($childs as $k => $row2)
           {           
              if (($row->ID != $ID) && $this->isVisible($row2->ID, $visited))
              {
                $this->visible[$naviID] = true;
                return true;
              
              }
               
           }
         
    
         
      }
    
    }
    

    $this->visible[$naviID] = ($depth == 1); 
    return ($depth == 1);
    
    
  
  }
  
  
  
  function removeFromNavigation($SiteID, $visited = null)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    if ($visited == null) 
    {
      $visited = array();
    
    }
  
    if (in_array($SiteID, $visited))
    {
      return;
    }
  
    $visited[] = $SiteID;
  
  
    $childs = $this->getChilds($SiteID);
  
    
    foreach ($childs as $k=>$el)
    {
      $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `name` = '$el->name';";
      $erg = $hp->mysqlquery($sql);
      $row = mysql_fetch_object($erg);
    
      $this->removeFromNavigation($row->ID, $visited);
    
    }
   
    $sql = "DELETE FROM `$dbprefix"."navigation` WHERE `ID` = '$SiteID';";
    $erg = $hp->mysqlquery($sql);
  
  
  }
  
  
  
  function haveChilds($parent)
  {
   return (count($this->getChilds($parent) > 0));
  }
  
  
  function getSite($site)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    
    $sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `ID` = '$site' OR `name` = '$site';";
    $erg = $hp->mysqlquery($sql);
  
    $array = mysql_fetch_array($erg);
    
    return ((is_array($array)) ? $array : false);
    
  }
  
  function siteHaveChilds($parent)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    $sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `parent` = '$parent';";
    $erg = $hp->mysqlquery($sql);
    
    return (mysql_num_rows($erg)> 0);
  
  }
  
  function siteCanHaveChilds($site)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    $site = $this->getSite($site);
    
    return  $this->templateCanHaveChilds($site["template"]);

  }
  
  function templateCanHaveChilds($template)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    
    $config = $this->getTemplateConfig($template);
    
    if ($config === false)
    {
      return false;
    } else
    {
      return in_array("navigation", $config["dyncontent"]);
      
    }
    //re
  }
  
  
  function printNavigation($maxdepth = 5)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
    $result = "";
  
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `parent` = '0' ORDER BY `order` ASC;";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
      $result .=$this->printNavigation_r($row, $maxdepth, 0);
  
    }
  
    return $result;
  }
  
  
  
  private function printNavigation_r($element, $maxdepth, $depth)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
   if ($depth > $maxdepth)
   {
    return "";
   }
   //depth, titel, name, dynamic
   $out = $element->ID."<!>".$depth."<!>".$element->name."<!>".$element->site."<!>".$element->dynamic."</el>";
   $childs = $this->getChilds($element->ID, true);
  
   foreach ($childs as $key=>$value) 
   {
   
    $sql = "SELECT * FROM `$dbprefix"."navigation` WHERE `name` = '$value->name';";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
     $out .=$this->printNavigation_r($row, $maxdepth, $depth+1);
    } 	
   }
  
   return $out;
  
  }
  
  
  function getAllAvailableSites($dynamic = false)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
  
  
    $pages = array();
  
    if ($dynamic)
    {
    
      $sql = "SELECT site FROM `$dbprefix"."navigation` WHERE `dynamic` = '1';";
      $erg = $hp->mysqlquery($sql);
    
      $used = array();
      
      while ($row = mysql_fetch_object($erg))
      {
        $used[] = strtolower($row->site);
      }
      
      $sql = "SELECT name FROM `$dbprefix"."subpages` WHERE `parent` = '0';";
      $erg = $hp->mysqlquery($sql);
      
      while ($row = mysql_fetch_object($erg))
      {
        if (!in_array(strtolower($row->name), $used))
        {
        $pages[] = $row->name;
        }
      }
      
    
    
    } else
    {
  
      $sql = "SELECT site FROM `$dbprefix"."navigation` WHERE `dynamic` = '0';";
      $erg = $hp->mysqlquery($sql);
    
      $used = array();
    
      while ($row = mysql_fetch_object($erg))
      {
        $used[] = $row->site;
      }
     
      $handle = @opendir("./sites"); 
      while (false !== ($file = readdir($handle))) 
      {
        $n = explode(".", $file);
        $a = $n[0];
        $b = $n[1];
    
        if ($b == "php")
        {
            $name = $a;
            
            if (!$hp->isSiteRestricted($name) && !in_array($name, $used))
            {
            
            $pages[] = $name;
            
            }      
        }
      }
  
      foreach($this->display as $k=>$site)
      {
        if (!in_array($site, $pages) && !$hp->isSiteRestricted($site) && !in_array($site, $used))
        {
          $pages[] = $site;
        }
      }
    }
  

  
  
    return $pages;
  
  }
  
  
  
  function loadSite($site)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $config = $hp->getconfig();
    $right = $hp->right;
    
    $design = $config["design"];
    $page = $this->getSite($site);
  
    if ($site == false)
    {
      return false;
    }
  
    $template = $page['template'];
  
    $ok = false;
    foreach ($this->templatePath as $k => $path)
    {
      $path = str_replace("#!Design#", $design, $path);
      $tempPath = $path.$template.".html";
      
      if (is_file($tempPath) && file_exists($tempPath))
      {
        $ok = true;
      }
    }
    if (!$ok)
    {
      return false;
    }
  
    // Importieren der Konfiguration 
    $tempConfig = $this->getTemplateConfig($template);
  
    if ($tempConfig == false)
    {
      $error->error("Fehlerhafte SubPage-Config für das Template ".$template);
      return false;
  
    }
  
    // Einbinden des subpage Template Systems:
    $content = new subpageTemplate($hp);
    $content->load($template);
  
    // Binde Dynamische Inhalte ein
    $this->appendDynamicContent($site, $content);
  
  
    // Lade die Template Daten für diese Unterseite und ersetzte die statischen Inhalte
    $templateArray = $this->getTemplateData($site);
    
    $ok = true;
    if (isset($templateArray['MinLevel']))
    {
      
       $level = $templateArray['MinLevel'];
       $ok = $right->isAllowed($level);       
    }
    
    if (!$ok)
    {
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Sie haben nicht das nötige Recht, diese Seite zu betreten!");
      return $site->get();
    
    }
    
    $content->setArray($templateArray);
  
    //Liefere die so erstellte Seite zurück:
    return $content->get();
  
  }
  
  
  function getTemplateData($site)
  {
  
    $page = $this->getSite($site);
  
    if ($page == false)
    {
      return false;
    }
  
    $content = $page["content"];
    // Löscht alle überflüssigen Escape Zeichen:
    
    $content = preg_replace("/\\\\[\\\\]*\\\\/", "\\", $content);
  
    $template = array();
  
    $elementSplit = explode("<!--!>", $content);
  
    foreach ($elementSplit as $k=>$line) 
    {
    	
    	$data = explode("<!=!>", $line);
    	if (count($data) == 2)
    	{
    	 $template[$data[0]] = $data[1];
    	}
    	
    }
    return $template;
  }
  
  
  function appendDynamicContent($site, $content)
  {
  
  
   $template = $this->getSiteTemplate($site);
   $config = $this->getTemplateConfig($template);
    
    
    $dynContent = $config["dyncontent"];
    
    foreach ($dynContent as $pl=>$type) 
    {
       if (isset($this->dynContent[$type]) &&  is_array($this->dynContent[$type]) &&
        isset($this->dynContent[$type]["object"]) && ($this->dynContent[$type]["object"] != null))
        {
            $data = $this->dynContent[$type];
            $f = $data["function"];
            $o = $data["object"];
              
            $result = $o->$f($site, $config);
           // $content = str_replace("<!--$pl-->", $result, $content);
           $content->set($pl,$result);
          
        } else
        {
         $content->set($pl,"<img src=\"images/alert2.gif\"> Fehler: Dyanmisches Template exsistiert nicht <img src=\"images/alert2.gif\">");
        }
           	
    }
    
    
  }
    
  function getTemplateConfig($template)
  {
    $hp = $this->hp;
    $config = $hp->getconfig();
    
    $design = $config["design"];                       
    
    foreach ($this->templatePath as $k => $path)
    {
      $path = str_replace("#!Design#", $design, $path);
      $tempPath = $path.$template."_config.php";
      if (!is_file($tempPath) || !file_exists($tempPath))
      {
        continue;
      }
       
    // Importieren der gefundenen Datei:
       include $tempPath;
  
       if (is_array($subpageconfig))
       {
       return $subpageconfig;
       } else
       {
       return false;
       }
       
    }
    return false;
  
  }
  
  
  function getAllTemplates()
  {
    $templates = array();
    $hp = $this->hp;
    $config = $hp->getconfig();
    
    $design = $config["design"];
    
    foreach ($this->templatePath as $k => $path)
    {
      $path = str_replace("#!Design#", $design, $path);
      if (is_dir($path))
      {
        $handle = @opendir($path); 
        while (false !== ($file = readdir($handle))) 
        {
          $n = explode(".", $file);
          $a = $n[0];
          $b = $n[1];
      
          if ($b == "php")
          {
              $array = explode("_", $a);
              if ((count($array) > 1) and ($array[1] == "config"))
              {
      
                if (file_exists($path.$array[0].".html") && is_file($path.$array[0].".html"))
                {
                   // Einbinden der Config Datei, um Namen zu erhalten:
                   
                   include $path.$file;
                   
                   if (isset($subpageconfig["name"]))
                   {
                      $name = $subpageconfig["name"];
                                
                      if (!in_array($array[0], $templates))
                      {
                        $templates[$array[0]] =  $name;
                      }
                   }
      
                }
               
               
              }
          }
        }
      }
    }
    return $templates;
  }
  
  
  function getAllTemplatesWithDynContent($dynContent)
  {
    $pages = array();
    $hp = $this->hp;
    $config = $hp->getconfig();
    
    $design = $config["design"];
    
    foreach ($this->templatePath as $k => $path)
    {
     
      $path = str_replace("#!Design#", $design, $path);
      if (is_dir($path))
      {
        $handle = opendir($path); 
     
        while (false !== ($file = readdir($handle))) 
        {
          $n = explode(".", $file);
          $a = $n[0];
          $b = $n[1];
      
          if ($b == "php")
          {
            
              $array = explode("_", $a);
              if ((count($array) > 1) and ($array[1] == "config"))
              {
      
                $Fpath = $path.$file;
                if (is_file($Fpath) && file_exists($Fpath))
                {
                  include $Fpath;
                  if (in_array($dynContent, $subpageconfig["dyncontent"]))
                  {
                   
                    $pages[]  = $array[0];
                  }
            
            
                }
              }
          }
        }
      }
    }
  
    return $pages;
  
  }
 
  function getEvents($date, $level = "all")
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $right = $hp->right;
    
    if ($level == null)
    {
       $level = $_SESSION["level"];
    }
    
    $dateData = explode(".",$date);
    
    if (count($dateData) != 3)
    {
      throw new Exception("Ungültiges Datum");
    }
    
    $day = $dateData[0];
    $month = $dateData[1];
    $year = $dateData[2];
    
    $sql = "SELECT * FROM `$dbprefix"."events`;";
    $erg = $hp->mysqlquery($sql);
    $events = array();
    
    while ($row = mysql_fetch_object($erg))
    {
      $startData = explode(".", $row->date);
      $endData = explode(".", $row->enddate);
      
      $startDay = $startData[0];
      $startMonth = $startData[1];
      $startYear = $startData[2];
      
      $endDay = $endData[0];
      $endMonth = $endData[1];
      $endYear = $endData[2];

      $endstamp = (($endYear*12+$endMonth)*30+$endDay);
      $startstamp = (($startYear*12+$startMonth)*30+$startDay);
      $stamp = (($year*12+$month)*30+$day);
      
      if (($startstamp <= $stamp) &&  ($stamp <= $endstamp))
      {
          if (($level === "all") || ($right->isAllowed($row->level, $level)))
          {
            $events[] = $row;
          }
      }
      
    
    }
     
    return $events;
  
  }
  
  
  
  // ------------------------- Dynamische Inhalte ------------------------------
  
  //      Alle Funktionen müssen mit dy_ anfangen und die Argumente $site und $templateConfig haben
  //      und muss einen String, mit dem Inhalt haben
  
  
  function dy_test($site, $templateConfig)
  {
  return " :)";
  }
  
  
  function dy_navigation($site, $templateConfig)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    
    $sql = "SELECT ID FROM `$dbprefix"."subpages` WHERE `name` = '$site' OR `ID` = '$site';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
    
    $ID = $row->ID;
    
    
    $childs = array();
    $sql = "SELECT * FROM `$dbprefix"."subpages` WHERE `parent` = '$ID' ORDER BY `parent_kat` ASC, `created` DESC;";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_array($erg))
    {
      $childs[$row["parent_kat"]][] = $row;    
    }
    
    $site = new siteTemplate($hp);
    $site->load("subpage_navigation");
    
    $contentKAT = "";
    
    foreach ($childs as $kat => $els)
    {
      
      $content = "";
      foreach($els as $k => $el)
      {
       
       $content .= $site->getNode("Element", $el);      
      
      }
      
      $data = array(
        "titel" => $kat,
        "Elements" => $content
      );
      
      $contentKAT .= $site->getNode("Headline", $data);
    
    }
    
    $site->set("Content", $contentKAT);
    
    return $site->get();
    
    
  }
  
  function dy_calendar($site, $templateConfig)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $game = $hp->game;
    $info = $hp->info;
    $error = $hp->error;
    $fp = $hp->fp;
    $right = $hp->right;
    
    $arr_monate = array ('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');

    
    $sql = "SELECT ID FROM `$dbprefix"."subpages` WHERE `name` = '$site' OR `ID` = '$site';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);
    
    $ID = $row->ID;
    
    // Sekunden pro Tag
    $sPD = 86400;
    
    $childs = array();
    $sql = "SELECT * FROM `$dbprefix"."events`;";
    $erg = $hp->mysqlquery($sql);
    while ($row = mysql_fetch_object($erg))
    {
      if (!$right->isAllowed($row->level))
      {
        continue;
      }
    
      $display = explode(",", $row->display);
      if (in_array($ID, $display))
      {
        $startData = explode(".", $row->date);
        $endData = explode(".", $row->enddate);
        
        $startDay = $startData[0];
        $startMonth = $startData[1];
        $startYear = $startData[2];
        
        $endDay = $endData[0];
        $endMonth = $endData[1];
        $endYear = $endData[2];
        
        $start = gregoriantojd($startMonth, $startDay, $startYear);
        $end = gregoriantojd($endMonth, $endDay, $endYear);
        
        $countDays = ($end - $start);
        
        $startTime = mktime(0, 0, 0, $startMonth, $startDay, $startYear);
        
        
        for ($i = 0; $i < $countDays+1; $i++)
        {
          
          $myTime = $startTime + $i * $sPD;
          
          $monat = date("m", $myTime);
          $day = date("d", $myTime);         
          $year = date("Y", $myTime); 
          
          $tev = (($year * 12) + $monat);
          $tnw = ((date("Y") * 12) + date("n"));
          
          if ($tev < $tnw)
          {
            continue;
          }
      
          
     
                   
          $childs[$year][$monat][$day][] = $row;          
       
        }
      }         
    }

        
    $site = new siteTemplate($hp);
    $site->load("calendar");
    
    $contentYear = "";
    foreach ($childs as $year=>$MonthList)
    {
      ksort($MonthList);
      
      $contentMonth = "";
      foreach ($MonthList as $month=>$DayList)
      {
        ksort($DayList);
        
        $contentDay = "";
        foreach ($DayList as $day=>$list)
        {
          
          $content = "";
          foreach ($list as $k => $row)
          {
            $data = array(
              "ID" => $row->ID,
              "time" => $row->start,
              "endtime" => $row->end,
              "description" => $row->description,
              "date" => "$day.$month.$year",
              "startdate" => $row->date,
              "enddate" => $row->enddate,
              "name" => $row->name          
            );
          
            $content .= $site->getNode("List-Element", $data);
  
          }
          
          $data = array(
            "day" => $day,
            "year" => $year,
            "month" => $month,
            "Content" => $content        
          );
          
          $contentDay .= $site->getNode("List-Day", $data);
        
        }
      
        $data = array(
          "name" => $arr_monate[$month-1],
          "year" => $year,
          "Content" => $contentDay      
        );
        
        $contentMonth .= $site->getNode("List-Month", $data);
      
      }
    
      $data = array(
        "name" => $year,
        "Content" => $contentMonth      
      );
      
      $contentYear .= $site->getNode("List-Year", $data);
    
    }
    
    
    $site->set("Content", $contentYear);
    
        
    return $site->get("List");
    
    
  }  
  

}
?>