<?php
require_once "include/base/SiteTemplate.php";

class template extends siteTemplate
{

  var $template = array();
  var $path;
  
  
  
  function __construct($hp)
  {
    parent::__construct($hp);
  }
  
  
  function seterror($error)
  {
    $this->error=$error;
  }
  
  function gettemp($part)
  {
    return $this->template[$part]; 
  }
  
  function settemplate($temp)
  {
    $this->setArray($temp);
  }
  
  
  function load($path, $v2 = "")
  {
    $this->path = $path;
    
    if (!file_exists("template/$path.html"))
    {
    
      $this->template=array();
      $this->hp->error->error("Template $path not found!", "2");
      if (file_exists("template/default.html"))
      {
        $path = "default";
      } else
      {
       $this->error->error("Standard Template wurde nicht gefunden!","3");
      }
    }
    
    parent::load("template/$path.html", true);
    
    //$temp = file_get_contents("template/$path.html");
    
    
    
    
    $temp = $this->loadtemplatefile($path);
    if (is_array($temp))
    {
      $this->setArray($temp);
    } 
     
    $this->addVote();
    
    //$this->data = $this->spezialsigs($data);
    
    $this->hp->subpages->loadTemplateFile($path);
    
    
    $data = explode("<!--next-->", $this->get());
   
    if (count($data) > 1)
    {
    
      $this->template['header'] = $data[0];
      $this->template['footer'] = $data[1];
      
    } else
    {
      $this->template['header'] = $data[0];
      $this->template['footer'] = "";
    }
  
  }
  
  function addtemp($temp, $wort)
  {
  
    $this->set($temp, $wort);
    
  }
  
  function spezialsigs($data)
  {
  
    foreach ($data as $key=>$value) 
    {
        
      $data[$key] = $this->replace($value);
    	
    }
    return $data;
  }
 
 function getloginconfig($path)
  {
  
    if (is_file("template/$path/login.php"))
    {
    
      include "template/$path/login.php";
      
      return $config;
    } else
    {
      return null;
    }
    
  }
  
  function loadtemplatefile($path)
  {
  
    if (file_exists("template/$path/template.php"))
    {
    
      include "template/$path/template.php";
    
    }
  
  return $template;
  
  }
  
  function getHeader()
  {
  
    return $this->data['header'];
    
  }
   
  
  function addVote()
  {
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;
  $right = $hp->getright();
  $lbs = $hp->lbsites;
  
  $level = $_SESSION["level"];
  
  
  
  $sql = "SELECT * FROM `$dbpräfix"."vote`";
  $erg = $hp->mysqlquery($sql);
  
   
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
        
       if (!in_array($_SESSION['ID'], $whov))
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
         
          $fp->log($data2);
         
          $content = $site->getNode("Vote-List", $data2);
          $fp->log($content);

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
            
      $this->set('vote-'.$row->ID, $site->get("Vote"));
     
     
    }
    
  }


}
?>
