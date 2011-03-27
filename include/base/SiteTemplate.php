<?php

/*!

  Das Template einer Unterseite

*/
class siteTemplate
{
private $hp;

public $name;
public $autor;


private $blocks = array();
private $data = array();

private $DEFAULT_NODE = "Main";


function __construct($hp)
{
  $this->hp = $hp;
}


/*!

  Läd und parst die Template Daten
  @param path Pfad zu der lesenden Datei

*/
function load($name)
{

  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;
  $config = $hp->getconfig();
  
  $design = $config["design"];
  
  $path = "";
  if (is_dir("template/$design/sites") && is_file("template/$design/sites/$name.html"))
  {
  $path = "template/$design/sites/$name.html";
  } else
  {
  $path = "template/sites/$name.html";
  }
  
  

  // Laden der Datei
  $input =  file_get_contents($path);

 

  //Pasen der Datei:
  
  $lines = preg_split("/[\n|\r]/", $input);
  foreach ($lines as $lineNr=>$line) 
  {
  	
    if (preg_match("/\#\!\!NAME=(.*)/", $line, $m))
     {
  	   $this->name = $m[1];
     }
     
     if (preg_match("/\#\!\!AUTOR=(.*)/", $line, $m))
     {
  	   $this->autor = $m[1];
     }
  
  }
  //echo "Name: $this->name<br>";
 //echo "Autor: $this->autor<br>";


  
  
  $data = preg_split("/(\[\!=([^!]*)!])/", $input); 
  
  foreach ($data as $key=>$value) 
  {

    $blockname = "None";
    $lines = preg_split("/[\n|\r]/", $value);
    $content = "";
      foreach ($lines as $lineNr=>$line) 
      {     
         if (preg_match("/\[\!\/([^!]*)\!]/", $line, $m))
         { 
          $blockname = $m[1];
         } else
         {
          $content .= $line."\n\r";
         }
        
      
      }
    $this->blocks[$blockname] = $content;
   
   // echo  $this->blocks[$blockname];

  }
  


}


function getPlaceholder($content, $key = "!")
{
  $result = array();

    // Herausfiltern der Platzhalter:
  	if (preg_match_all("/\#\\".$key."[^\#|^!]*#/", $content, $m2))
  	{
      foreach ($m2 as $k=>$data)
      {
        foreach ($data as $key2 => $placeholder)
        {
          $result[] = str_replace("#", "", str_replace("#$key", "", $placeholder));
          
        } 
      }
    
    }
    
  return $result;
}


function replace($data)
{
  //Ersetze Inline Platzhalter:
  foreach($this->data as $key=>$value)
  {
    $data = str_replace("#!$key#", $value, $data);
  }
                                                  
  //Ersetze die Sprachblocks
  $data = $this->replaceLangBlocks($data);
  // Ersetze Bedingte Ausgaben
  $data = $this->replaceCondit($data);
  return $data;
}



function replaceLangBlocks($data)
{
  $hp = $this->hp;
  $lang = $hp->getlangclass();


  $langData = $this->getPlaceholder($data, "%");
  
  foreach ($langData as $k=> $word)
  {
    $data = str_replace("#%$word#", $lang->word($word), $data);
  }

  return $data;
}


function replaceCondit($data)
{
  $hp = $this->hp;
  $right = $hp->getright();
  
  $level = $_SESSION['level'];
  $conditData = $this->getPlaceholder($data, "?");

  foreach ($conditData as $k => $word)
  {
    $con = explode(" : ", $word);
    $rightN = $con[0];
    
    
    
    if (preg_match("/\"(.*)\"/", $con[1]))
    {
       $tmp = $this->replaceLangBlocks($con[1]);  
       $iftrue =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $con[1]))
    {
        $iftrue = $hp->getlangclass()->word(str_replace("%", "", $con[1]));
    } 
    elseif (isset($this->data[$con[1]]))
    {
      $iftrue = $this->data[$con[1]];
    } else
    {
      $iftrue = "[T]";
    }
   
   
    $iffalse = "";
    
    if (isset($con[2]))
    {
      if (preg_match("/\"(.*)\"/", $con[2]))
      {
       $tmp = $this->replaceLangBlocks($con[2]);  
       $iffalse =  str_replace("\"", "", $tmp);
      } 
      elseif (preg_match("/%(.*)/", $con[2]))
      {
        $iffalse = $hp->getlangclass()->word(str_replace("%", "", $con[2]));
      }      
      elseif (isset($this->data[$con[2]]))
      {
       $iffalse = $this->data[$con[2]];
      } else
      {
        $iffalse = "[F]";
      }
  
    }
  
    if (preg_match("/\:(.*)/", $rightN))
    {
      $name = str_replace(":", "", $rightN);
      if (isset($this->data[$name]))
      {
        $output = (($this->data[$name] == "true") ? $iftrue : $iffalse);
      } else
      {
        $output = "[name?]";
      }

    } elseif (isset($right[$level][$rightN]))
    {
      $output = ($right[$level][$rightN] ? $iftrue : $iffalse);
       
    } elseif (($rightN == "superadmin"))
    {
       $output = (isset($_SESSION['username']) && in_array($_SESSION['username'], $hp->getsuperadmin())) ? $iftrue : $iffalse; 
    } else
    {
      $output = "[right?]";
    }
    
    $data = str_replace("#?$word#", $output, $data);
  }

return $data;
}




function set($key, $value)
{
  $this->data[$key] = $value;
}

function setArray($data)
{
  foreach ($data as $key=>$value)
  {
  $this->set($key, $value);
  }

}


function getNode($name, $data = null)
{
  if (isset($this->blocks[$name]))
  {
    $tmp = $this->data;
    if ($data != null)
    {
     $this->data = $data;  
    }
    
    $result = $this->blocks[$name];
    $result = $this->replace($result);
  
    $this->data = $tmp;


    return $result;
  } else
  {
    return "[?]";
  }
}



function display($node = null)
{
  echo $this->get($node);
}

function get($node = null)
{
  if ($node == null)
  {
    $node = $this->DEFAULT_NODE;
  }

  if (isset($this->blocks[$node]))
  {

    return $this->replace($this->blocks[$node]);
  
  } else
  {
    return "<b>Node '$node' not found!</b>";
  
  }
}


}
?>