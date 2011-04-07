<?php
class subpages
{
var $hp;

var $templatePath;
var $dynContent = array();

var $dynContentPrefix = "dy_";

function __construct()
{
  // Konfiguration:

  // Der Pfad zu dem Templates der Unterseiten:
  $this->templatePath = "./subpages/";


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
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $fp->fp;


  $sql = "SELECT * FROM `$dbpräfix"."navigation` WHERE `site` = '$siteID' AND `dynamic` = '1';";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);

  return $row->ID;
}


function getSubpageID($naviID)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $fp->fp;


  $sql = "SELECT site FROM `$dbpräfix"."navigation` WHERE `ID` = '$naviID' OR `name` = '$naviID';";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);

  $sql = "SELECT ID FROM `$dbpräfix"."subpages` WHERE `ID` = '$row->site' OR `name` = '$row->site';";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);

  return $row->ID;
}

function getSiteTemplate($ID, $navigation = false)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;
  $template = "";

  if ($navigation)
  {
    $sql = "SELECT * FROM `$dbpräfix"."navigation` WHERE `ID` = '$ID' OR `name` = '$ID';";
    $erg = $hp->mysqlquery($sql);
    $row = mysql_fetch_object($erg);

    $ID = $row->name;
  }                


  $sql = "SELECT * FROM `$dbpräfix"."subpages` WHERE `ID` = '$ID' OR `name` = '$ID';";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);

  return $row->template;        
}



function getChilds($parent, $navigationID = true)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $game = $hp->game;
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;


  if (!$navigationID)
  {
    $parent = $this->getNavigationID($parent);

  }

  $sql = "SELECT name FROM `$dbpräfix"."navigation` WHERE `parent` = '$parent' ORDER BY `order` ASC;";
  $erg = $hp->mysqlquery($sql);

  $childs = array();

  while ($row = mysql_fetch_object($erg))
  {
    $childs[] = $row->name;
  }

  return $childs;

}


function removeFromNavigation($SiteID, $visited = null)
{

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
  
  foreach ($childs as $k=>$name)
  {
    $this->removeFromNavigation($SiteID, $visited);
  
  }
 
  $sql = "DELETE FROM `$dbpräfix"."navigation` WHERE `ID` = '$siteID';";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);
  


}



function haveChilds($parent)
{
 return (count($this->getChilds($parent) > 0));
}


function getSite($site)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $game = $hp->game;
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;


  
  $sql = "SELECT * FROM `$dbpräfix"."subpages` WHERE `ID` = '$site' OR `name` = '$site';";
  $erg = $hp->mysqlquery($sql);

  $array = mysql_fetch_array($erg);
  
  return ((is_array($array)) ? $array : false);
  
}


function printNavigation($maxdepth = 5)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;

  $result = "";

  $sql = "SELECT * FROM `$dbpräfix"."navigation` WHERE `parent` = '0' ORDER BY `order` ASC;";
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
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;


 if ($depth > $maxdepth)
 {
  return "";
 }
 //depth, titel, name, dynamic
 $out = $element->ID."<!>".$depth."<!>".$element->name."<!>".$element->site."<!>".$element->dynamic."</el>";
 $fp->log($out);
 $childs = $this->getChilds($element->ID, true);

 foreach ($childs as $key=>$value) 
 {
 
  $sql = "SELECT * FROM `$dbpräfix"."navigation` WHERE `name` = '$value';";
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
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;


  $pages = array();

  if ($dynamic)
  {
  
    $sql = "SELECT site FROM `$dbpräfix"."navigation` WHERE `dynamic` = '1';";
    $erg = $hp->mysqlquery($sql);
  
    $used = array();
    
    while ($row = mysql_fetch_object($erg))
    {
      $used[] = $row->site;
    }
    
    $sql = "SELECT name FROM `$dbpräfix"."subpages`;";
    $erg = $hp->mysqlquery($sql);
    
    while ($row = mysql_fetch_object($erg))
    {
      if (!in_array($row-name, $used))
      {
      $pages[] = $row->name;
      }
    }
    
  
  
  } else
  {

    $sql = "SELECT site FROM `$dbpräfix"."navigation` WHERE `dynamic` = '0';";
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

  }



  return $pages;

}



function loadSite($site)
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;

  $page = $this->getSite($site);

  if ($site == false)
  {
    return false;
  }

  $template = $page['template'];

  $tempPath = $this->templatePath.$template.".html";
  $fp->log("Lade Template vom Pfad: $tempPath");

  if (!is_file($tempPath) || !file_exists($tempPath))
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

  // Lade die Datei in das System
  $content = file_get_contents($tempPath);

  // Binde Dynamische Inhalte ein
  $content = $this->appendDynamicContent($site, $content);


  // Lade die Template Daten für diese Unterseite und ersetzte die statischen Inhalte
  $templateArray = $this->getTemplateData($site);

  foreach ($templateArray as $key=>$value) 
  {
    $content = str_replace("<!--$key-->", $value, $content);	
  }


  //Liefere die so erstellte Seite zurück:
  return $content;

}


function getTemplateData($site)
{

  $page = $this->getSite($site);

  if ($page == false)
  {
    return false;
  }


  $content = $page["content"];

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
          $content = str_replace("<!--$pl-->", $result, $content);
        
      } else
      {
        $content = str_replace("<!--$pl-->", "<img src=\"images/alert2.gif\"> Fehler: Dyanmisches Template exsistiert nicht <img src=\"images/alert2.gif\">", $content);
      }
         	
  }
  
  return $content;
}


function getTemplateConfig($template)
{
  $tempPath = $this->templatePath.$template."_config.php";
    if (!is_file($tempPath) || !file_exists($tempPath))
    {
      return false;
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


function getAllTemplates()
{
  $templates = array();
 
  $handle = @opendir($this->templatePath); 
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

          if (file_exists($this->templatePath.$array[0].".html") && is_file($this->templatePath.$array[0].".html"))
          {
             // Einbinden der Config Datei, um Namen zu erhalten:
             
             include $this->templatePath.$file;
             
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
  return $templates;
}


function getAllTemplatesWithDynContent($dynContent)
{
  $pages = array();
 
  $handle = @opendir($this->templatePath); 
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

          $path = $this->templatePath.$file;
          if (is_file($path) && file_exists($path))
          {
            include $this->templatePath.$file;
      
            if (in_array($dynContent, $subpageconfig["dyncontent"]))
            {
              $pages[]  = $array[0];
            }
      
      
          }
        }
    }
  }

  return $pages;

}


// ------------------------- Dynamische Inhalte ------------------------------

//      Alle Funktionen müssen mit dy_ anfangen und die Argumente $site und $templateConfig haben
//      und muss einen String, mit dem Inhalt haben


function dy_test($site, $templateConfig)
{
return " :)";
}





}
?>