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
  protected $vars = array();
  
  private $aktArray = null;
  
  private $DEFAULT_NODE = "Main";
  
  private $neededRight = null;
  protected $searchpath = "";
  protected $searchpathT = "";
  
  
  public static $functions = array();
  
  
  public function __construct($hp, $copy = null)
  {
    $this->hp = $hp;
    $this->searchpath = "template/sites/";
    $this->searchpathT = "template/#!Design#/sites/";
    
    self::extend($this);
    
    if (($copy != null) && (is_a($copy, "siteTemplate")))
    {
      $this->data = array_merge($this->data, $copy->data);
      $this->vars = array_merge($this->vars, $copy->vars);
    }
    
    
  }
  

  
  
  
  /*!
  
    L�d und parst die Template Daten
    @param path Pfad zu der lesenden Datei
  
  */
  public function load($name, $direct = false)
  {
  
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
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
         } elseif (($line != "") && ($blockname == "None"))
         {
          $content .= $line."\n\r";
         }
        
      
      }
      $this->blocks[$blockname] = $content;
     
     // echo  $this->blocks[$blockname];
  
    }
    
  
  
  }
  
  
  private function getPlaceholder($content, $key = "!", $trust = false)
  {
    $result = array();
  
      // Herausfiltern der Platzhalter:
    	if (preg_match_all("/#".((!$trust) ? "\\" : "").$key."[^\#]*#/", $content, $m2))
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
    // Ersetze Inline Platzhalter:
    $data = $this->replaceDefault($data);
    // Ersetzt die Variablen
    $data = $this->replaceVars($data);
    // Die Kommentare in den Templates werden ersetzt
    $data = $this->replaceComment($data);                                                
    // Ersetze die Sprachblocks
    $data = $this->replaceLangBlocks($data);
    // Ersetze Bedingte Ausgaben
    $data = $this->replaceCondit($data);
    // Ersetzte die Gleichheitspr�fung
    $data = $this->replaceEquals($data);
    // Ersetzte die LbSites
    $data = $this->replaceLbSite($data);
    // Ersetzt die Funktionen
    $data = $this->replaceFunctions($data);
    //Ersetzte die Loops
    $data = $this->replaceLoop($data);
    
    
    return $data;
  }
  
  
  private function replaceDefault($data)
  {
    foreach($this->data as $key=>$value)
    {
      if (!is_array($value) && !is_object($value))
      {
        $data = str_replace("#!$key#", $value, $data);
      }
    }
    foreach($this->vars as $key=>$value)
    {
      if (!is_array($value) && !is_object($value))
      {
        $data = str_replace("#!$key#", $value, $data);
      }
    }
    
    return $data;
  }
  
  
  private function replaceComment($data)
  {
    $langData = $this->getPlaceholder($data, "/");   
    foreach ($langData as $k=> $word)
    {
      $data = str_replace("#/$word#", "", $data);
    }
  
    return $data;  
  
  }
  
  private function replaceVars($data)
  {
    $PData = $this->getPlaceholder($data, "V\\:", true);
    foreach ($PData as $k=> $word)
    {
      $word = str_replace("V:", "", $word);
      
      $split = explode(" : ", $word);
      $content = "";
      
      if (count($split) == 2)
      {   
        $this->vars[$split[0]] =  $this->replaceExtendedInput($split[1]);       
      } else
      {
        $content = "[Var?]";
      }

      $data = str_replace("#V:$word#", $content, $data);
    }
  
    return $data;  
  
  }
  
  private function replaceLangBlocks($data)
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
  
  private function replaceFunctions($data)
  {
    $hp = $this->hp;
  
  
    $tempData = $this->getPlaceholder($data, "@");
    
    foreach ($tempData as $k=> $word)
    {
    
      $split = explode(" : ", $word);
      
      $out = "";
      if (isset(self::$functions[$split[0]]))
      {
          $sys = self::$functions[$split[0]];
          
          $obj = $sys["obj"];
          $func = $sys["func"];
          
          $args = array();
          
          for ($i=1; $i < count($split); $i++)
          {
              $el = $split[$i];
              
              $content = $this->replaceExtendedInput($el);  
          
              $args[] = $content;
          
          }
          
          
          $out = $obj->$func($args); 
        
      
      } else
      {
        $out = "[@-Func?]";
      }
      
      $data = str_replace("#@$word#", $out, $data);
    }
  
    return $data;
  }
  
  private function replaceLbSite($data)
  {
    $hp = $this->hp;
    
    
    $Data = $this->getPlaceholder($data, "+");
    
    foreach ($Data as $k => $word)
    {
    
      $values = explode(" = ", $word);
      
      
      $content = "";
      
      
      $content = $this->replaceExtendedInput($values[1]);
      
      
      $vars = "";
      if (count($values) > 2)
      {
        
        $vars = $this->replaceExtendedInput($values[2]);        
          
      }
      
      $type = "lbOn";
      if (count($values) > 3)
      { 
        $type = $this->replaceExtendedInput($values[3]);      
          
      }
           
      $data = str_replace("#+$word#", $hp->lbsites->link($values[0], $content, $vars, $type), $data);
      
      
    
    }
    
    
    return $data;
  
  
  }
  
  
  private function replaceEquals($data)
  {
    $hp = $this->hp;
    $right = $hp->getright();
    
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
      
      $compareA = $this->replaceExtendedInput($con[0]);
      
      
      // B
      $compareB = $this->replaceExtendedInput($con[1]);
      
      
      // Values:
      
      $iftrue = $this->replaceExtendedInput($values[1], "[T]");
     
      $iffalse = $this->replaceExtendedInput($values[2], "[F]");
     
      
      $data = str_replace("#=$word#", ($compareA == $compareB)? $iftrue : $iffalse, $data);
    
    }
  
    return $data;
  }
  
  
  private function replaceCondit($data)
  {
    $hp = $this->hp;
    $right = $hp->getright();
    $config = $hp->getconfig();
    
    if (isset($_SESSION['level']))
    {
      $level = $_SESSION['level'];
    } else
    {
      $level = 0;
    }
    $conditData = $this->getPlaceholder($data, "?");
  
    foreach ($conditData as $k => $word)
    {
      $con = explode(" : ", $word);
      $rightN = $con[0];
       
      $iftrue = $this->replaceExtendedInput($con[1], "[T]");
     
              
      $iffalse = "";
      
      if (isset($con[2]))
      {
        $iffalse = $this->replaceExtendedInput($con[2], "[F]");
      }
      
    
      if (preg_match("/\:(.*)/", $rightN))
      {
        $name = str_replace(":", "", $rightN);
    
        $in = $this->replaceExtendedInput($name, "false");
        
        $output = ((($in === "true") || ($in === true)) ? $iftrue : $iffalse);

  
      } elseif (preg_match("/\=(.*)/", $rightN))
      {
        $name = str_replace("=", "", $rightN);
        if (isset($config[$name]))
        {
          $output = (($config[$name]) ? $iftrue : $iffalse);
        } else
        {
          $output = "[config?]";
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
  
  
  private function replaceLoop($data)
  {
    $hp = $this->hp;
    
    $PData = $this->getPlaceholder($data, "L\\:", true);
        
    foreach ($PData as $k=> $word)
    {
      $word = str_replace("L:", "", $word);
      $content = "";
    
      $split = explode(" : ", $word); 
        
      if (count($split) >= 2)
      {
      
        $array = $this->replaceExtendedInput($split[0]);
        if (is_array($array))
        {
          $text = "";
          $myBase =  $this->replaceExtendedInput($split[1]);
          
          foreach ($array as $key => $value)
          {
             $text .= $this->replace($this->replaceLoopContent($myBase, $key, $value));           
          }
          
          $content = $text;         
      
        } else
        {
          $content = "[Array?]";
        }
      } else
      {
        $content = "[Output?]";
      }
      $data = str_replace("#L:$word#", $content, $data);
    }
      
    return $data;
  }
  
  
  private function replaceLoopContent($data, $key, $value)
  {
    $hp = $this->hp;
    
    
    if (is_array($value))
    {
      $this->aktArray = $value;
      
      $PData = $this->getPlaceholder($data, "l:", true);
      
      foreach ($PData as $k=> $word)
      {
        $word = str_replace("l:", "", $word);
        $content = "";
      
        $split = explode(".", $word);
        
        if ((count($split) == 1) && ($split[0] !== "K"))
        {
          if (isset($value[$split[0]]))
          {
             $content = $this->replace($value[$split[0]]);          
          
          } else
          {
            $content = "[Unknown Array Index!]";
          } 
        
        } 
        elseif ((count($split) == 2) && ($split[0] === "V"))
        {
          if (isset($value[$split[1]]))
          {
             $content = $this->replace($value[$split[1]]);          
          
          } else
          {
            $content = "[Unknown Array Index!]";
          } 
        } 
        elseif ((count($split) == 1) && ($split[0] === "K"))
        {
          $content = $key;                           
        }
        
        $data = str_replace("#l:$word#", $content, $data);
      }
      
    } else
    {
      $data = "[Second Array?]";
    }
  
    $this->aktArray = null;
  
    return $data;
  }
  
  
  private function replaceExtendedInput($input, $fallback = "")
  {
      $hp = $this->hp;
  
      $content = "";
      
      
      if (preg_match("/@([^\"]*)\(([^\)]*)\)/", $input, $m))
      {                 
         if (isset(self::$functions[$m[1]]))
         {
             $sys = self::$functions[$m[1]];
             
             $obj = $sys["obj"];
             $func = $sys["func"];
             
             $args = array();
             
             $split = explode(", ", $m[2]);
              
             foreach ($split as $i => $el)     
             {   
                 $content = $this->replaceExtendedInput($el);                         
                 $args[] = $content;     
             }        
             
             $content = $obj->$func($args); 
                    
         } else
         {
           $content = "[#-Func?]";
         }
        
      } elseif (preg_match("/\"(.*)\"/", $input))
      {
          $tmp = $this->replaceLangBlocks($this->replaceDefault($input));  
          $content =  str_replace("\"", "", $tmp);
          
      }      
      elseif (preg_match("/%(.*)/", $input))
      {               
          $content = $hp->getlangclass()->word(str_replace("%", "", $input));
          
      } 
      elseif (preg_match("/!(.*)/", $input))
      {       
          $name = str_replace("!", "", $input);
          
          if (isset($this->blocks[$name]))
          {
            $content = $this->replace($this->blocks[str_replace("!", "", $input)]);
            
          } else
          {
            $content = "[Block?]";
          }
          
          
      }
      elseif (preg_match("/l\:(.*)/", $input))
      {          
        $input = str_replace("l:", "", $input);
        
        if ($this->aktArray != null)
        {
        
          if (isset($this->aktArray[$input]))
          {
               $content = $this->aktArray[$input];          
            
          } else
          {
              $content = "[Unknown Array Index!]";
          } 
        
        } else
        {
          $content = "[l: Array?]";
        }
      
        
      
      } elseif (isset($this->data[$input]))
      {               
        $content = $this->data[$input];

      } elseif (isset($this->vars[$input]))
      {          
        $content = $this->vars[$input];
      } 
      else
      {          
        $content = $fallback;
      } 
   
      return $content;
  
  }
  
    
  
  
  public function set($key, $value)
  {
    $this->data[$key] = $value;
  }
  
  public function append($key, $value)
  {
    if (!isset($this->data[$key]))
    {
      $this->set($key, $value);
    } else
    {
      $this->data[$key] = $this->data[$key] . $value;
    }
  }
  
  public function setArray($data)
  {
    foreach ($data as $key=>$value)
    {
    $this->set($key, $value);
    }
  
  }
  
  
  public function getNode($name, $data = null)
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
  
  public function foreachNode($nodeName, $data)
  {
    if (!is_string($nodeName) || !is_array($data))
    {
      throw new Exception();  
    } else
    {
      $content = "";
      
      foreach ($data as $k=>$value)
      {
        $content .= $this->getNode($nodeName, $value);
      }
      
      return $content;
    
    
    }
    
  
  }
  
  
  public function right($right = "login")
  {
    $this->neededRight = $right;
  }
  
  
  public function display($node = null)
  {
    echo $this->get($node);
  }
  
  public function get($node = null)
  {
    
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
  
  public function getVars()
  {
    return $this->vars;
  }


  // ------------------------ Static Functions ---------------------------------
  public static function extend($ext)
  {
    
    if (is_object($ext))
    {
      //$ext->sethp($this->hp);
      
      $funktions = get_class_methods($ext);
      
      foreach ($funktions as $key=>$value) 
      {
        $split = explode("_", $value);
        if ((count($split) > 1) && ($split[0] == "temp") && ($split[1] != "") && !in_array($split[1],  self::$functions))
        {
          $name = $split[1];
        
        	$array = array(
           "name" => $name,
           "func" => $value,
           "obj" => $ext          
          );
                    
          self::$functions[$name] = $array;   
          
        }
      }
    
    }
  
  }


 // ----------------------------- Template Functions ---------------------------
 
 public function temp_echo($args)
 {  
    $value = "";

    foreach($args as $k=>$v)
    {
      $value .= (is_string($v)) ? $v : (is_array($v) ? "[Array]" : (is_bool($v) ? $v : (is_object($v) ? "[Object]" : "[Unknown]")));
    }
    return $value;  
 }
 
 
 public function temp_inArray($args)
 {
    $argCount = count($args);
    
    if ($argCount != 2)
    {
      return "[InArray: Need 2 Arguments]";
    } else
    {
      return  in_array($args[0], $args[1]);
    }
 
 }
 
 /*
 
  Includes Content from another template File
  Parameter:
    - template File
    - Block (Optional)
 
 */
 public function temp_include($args)
 {
    $argCount = count($args);
    
    $site = new siteTemplate($this->hp, $this);
    
    if ($argCount < 1)
    {
      return "[Args?]";
    } elseif ($argCount >= 1)
    {
      $site->load($args[0]);      
      
      $content = '';
      
      switch ($argCount)
      {
        case 2:
          $content = $site->get($args[1]);
          break;
        
        case 1:
        default:
          $content = $site->get();
          break;
      }     
      $this->vars = array_merge($this->vars, $site->getVars());
      
      return $content;
    }         
 }



}
?>