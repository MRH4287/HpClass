<?php

/*!

  Das Template einer Unterseite

*/
class siteTemplate
{
protected $hp;

public $name;
public $autor;


private $blocks = array();
protected $data = array();

private $DEFAULT_NODE = "Main";

private $neededRight = null;
protected $searchpath = "";
protected $searchpathT = "";



function __construct($hp)
{
  $this->hp = $hp;
  $this->searchpath = "template/sites/";
  $this->searchpathT = "template/#!Design#/sites/";
  
}


/*!

  Läd und parst die Template Daten
  @param path Pfad zu der lesenden Datei

*/
function load($name, $direct = false)
{

  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;
  $config = $hp->getconfig();
  
  if (!$direct)
  {
    $design = $config["design"];
    
    $searchpath = $this->searchpath;
    $searchpathT = str_replace("#!Design#", $design, $this->searchpathT);
    
    $path = "";
    if (is_dir($searchpathT) && is_file("$searchpathT/$name.html"))
    {
    $path = "$searchpathT/$name.html";
    } else
    {
    $path = "$searchpath/$name.html";
    }
  } else
  {
    $path = $name;
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
         } elseif ($line != "")
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
  	if (preg_match_all("/\#\\".$key."[^\#]*#/", $content, $m2))
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


public function replace($data)
{
  //Ersetze Inline Platzhalter:
  $data = $this->replaceDefault($data);
                                                  
  //Ersetze die Sprachblocks
  $data = $this->replaceLangBlocks($data);
  // Ersetze Bedingte Ausgaben
  $data = $this->replaceCondit($data);
  // Ersetzte die Gleichheitsprüfung
  $data = $this->replaceEquals($data);
  
  return $data;
}


function replaceDefault($data)
{
  foreach($this->data as $key=>$value)
  {
    $data = str_replace("#!$key#", $value, $data);
  }
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

function replaceEquals($data)
{
  $hp = $this->hp;
  $right = $hp->getright();
  
  $level = $_SESSION['level'];
  $Data = $this->getPlaceholder($data, "=");
  
  foreach ($Data as $k => $word)
  {
    // name == "test" : "a #%test#" : b
    // name == bla : abc : %lol
    
    $values = explode(" : ", $word);
    $con = explode(" == ", $values[0]);
    
    $compareA = "A";    
    $compareB = "B";
    
    if ((count($values) != 3) || (count($con) != 2))
    {
      continue;
    }
    
    // A
    if (preg_match("/\"(.*)\"/", $con[0]))
    {
       $tmp = $this->replaceLangBlocks($this->replaceDefault($con[0]));  
       $compareA =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $con[0]))
    {
        $comapreA = $hp->getlangclass()->word(str_replace("%", "", $con[0]));
    } 
    elseif (preg_match("/!(.*)/", $con[0]))
    {
        $comapreA = $this->replace($this->blocks[str_replace("!", "", $con[0])]);
    } 
    elseif (isset($this->data[$con[0]]))
    {
      $compareA = $this->data[$con[0]];
    } 
    
    
    // B
    if (preg_match("/\"(.*)\"/", $con[1]))
    {
       $tmp = $this->replaceLangBlocks($this->replaceDefault($con[1]));  
       $compareB =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $con[1]))
    {
        $comapreB = $hp->getlangclass()->word(str_replace("%", "", $con[1]));
    } 
    elseif (preg_match("/!(.*)/", $con[1]))
    {
        $comapreB = $this->replace($this->blocks[str_replace("!", "", $con[1])]);
    } 
    elseif (isset($this->data[$con[1]]))
    {
      $compareB = $this->data[$con[1]];
    } 
    
    
    // Values:
    
    if (preg_match("/\"(.*)\"/", $values[1]))
    {
       $tmp = $this->replaceLangBlocks($this->replaceDefault($values[1]));  
       $iftrue =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $values[1]))
    {
       $iftrue = $hp->getlangclass()->word(str_replace("%", "", $values[1]));
    } 
    elseif (preg_match("/!(.*)/", $values[1]))
    {
        $iftrue = $this->replace($this->blocks[str_replace("!", "", $values[1])]);
    } 
    elseif (isset($this->data[$values[1]]))
    {
       $iftrue = $this->data[$values[1]];
    } else
    {
      $iftrue = "[T]";
    }
    
    
    if (preg_match("/\"(.*)\"/", $values[2]))
    {
       $tmp = $this->replaceLangBlocks($this->replaceDefault($values[2]));  
       $iffalse =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $values[2]))
    {
       $iffalse = $hp->getlangclass()->word(str_replace("%", "", $values[2]));
    } 
    elseif (preg_match("/!(.*)/", $values[2]))
    {
        $iffalse = $this->replace($this->blocks[str_replace("!", "", $values[2])]);
    } 
    elseif (isset($this->data[$values[2]]))
    {
       $iffalse = $this->data[$values[2]];
    } else
    {
      $iffalse = "[F]";
    }
    
    $data = str_replace("#=$word#", ($compareA == $compareB)? $iftrue : $iffalse, $data);
  
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
       $tmp = $this->replaceLangBlocks($this->replaceDefault($con[1]));  
       $iftrue =  str_replace("\"", "", $tmp);
    } 
    elseif (preg_match("/%(.*)/", $con[1]))
    {
        $iftrue = $hp->getlangclass()->word(str_replace("%", "", $con[1]));
    } 
    elseif (preg_match("/!(.*)/", $con[1]))
    {
        $iftrue = $this->replace($this->blocks[str_replace("!", "", $con[1])]);
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
       $tmp = $this->replaceLangBlocks($this->replaceDefault($con[2]));  
       $iffalse =  str_replace("\"", "", $tmp);
      } 
      elseif (preg_match("/%(.*)/", $con[2]))
      {
        $iffalse = $hp->getlangclass()->word(str_replace("%", "", $con[2]));
      }   
      elseif (preg_match("/!(.*)/", $con[2]))
      {
        $iffalse = $this->replace($this->blocks[str_replace("!", "", $con[2])]);
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
       
    } elseif (($rightN == "login"))
    {
       $output = (isset($_SESSION['username'])) ? $iftrue : $iffalse;
       
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


function right($right = "login")
{
  $this->neededRight = $right;
}


function display($node = null)
{
  echo $this->get($node);
}

function get($node = null)
{
  $level = $_SESSION['level'];
  
  $ok = false;
  $nr = $this->neededRight;
  
  if ($nr == null)
  {
    $ok = true;
  } else
  {
    if ($nr == "login")
    {
      $ok = isset($_SESSION["username"]);
    } elseif ($nr == "superadmin")
    {
      $ok = (isset($_SESSION['username']) && in_array($_SESSION['username'], $this->hp->getsuperadmin()));
    } else
    {
       $ok = $this->hp->right->is($nr);
    }
    
  }
   
  
  if ($ok)
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
    
  } else
  {
    $hp = $this->hp;
    $lang = $hp->getlangclass();
  
    return $lang->word('noright2');
  }
}


}
?>