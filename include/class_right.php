<?php

class right
{

  public $hp;

  private $recht = array();
  private $registed = array();
  private $cats = array();
  private $desc  = array();
  private $levels = array();
  

  public function sethp($hp)
  {
    
    $this->hp = $hp;
    $this->load();
  
  }

  
  function load()
  {
    $hp = $this->hp;
    $dbpräfix = $hp->getpräfix();
  
      // Rechte
    $abfrage = "SELECT * FROM `".$dbpräfix."right` Order By `level` ASC";
    //$ergebnis = SQLexec($abfrage, "index");
    $ergebnisss = $hp->mysqlquery($abfrage);
    $right = array();
    while($row = mysql_fetch_object($ergebnisss))
    {
       $rlevel="$row->level";
       if ("$row->ok" == "true")
       {
        $value = true;
       } else
       {
        $value = false;
       }
       $rright = "$row->right";
       
       
       $this->cats[$rright] = $row->cat;
       $this->desc[$rright] = $row->description;
       $right[$rlevel][$rright] = $value;
    }
    
    $this->recht = $right;

  
  }
  
  function is($right, $level = null)
  {
    if ($level == null)
    {
      $level = $_SESSION['level'];
    }
    
  
    return (((isset($this->recht[$level][$right]))) && ($this->recht[$level][$right]) && (in_array($right, $this->registed)));
  
  
  }
  
  
  /*! 
    
    Wegen Rückwertskompatibilität
    
  */
  function getright()
  {
    $myright = array();
        
    foreach ($this->levels as $k => $level)
    {
    
      foreach ($this->registed as $k => $right)
      {
      
        $myright[$level][$right] = (isset($this->recht[$level][$right])) ? $this->recht[$level][$right] : false;
      
      }
      
      
    
    }
    
   return $myright; 
     
  }
  
  function getregisted()
  {
  
    return $this->registed;
    
  }

  function register($right, $desc, $cat)
  {

      if (!in_array($right, $this->registed))
      {
      
        $this->registed[] = $right;
        $this->cats[$right] = $cat;
        $this->desc[$right] = $desc;
      
      } else
      {
      
        throw new Exception("Key Allready exsists");
        
      }

  
  
  }
  
  
  function registerArray($rights)
  {
  
    foreach ($rights as $k => $data)
    {
      $this->register($data["name"], $data["desc"], $data["cat"]);    
         
    }
  
  
  }

  function registerLevel($level)
  {
  
    if (is_array($level))
    {
      foreach ($level as $k => $name)
      {
        $this->registerLevel($name);
      }
    } else
    {
       $this->levels[] = $level;      
    }
  
  }


  function getlevels()
  {
    return $this->levels;
  }

  function cat($right)
  {
  
    return $this->cats[$right];
  
  }
  
  function desc($right)
  {
    return $this->desc[$right];
  }



  function save($rights)
  {
    $hp = $this->hp;
    $dbpräfix = $hp->getpräfix();   
  
    if (is_array($rights))
    {
        $sql = "TRUNCATE `".$dbpräfix."right`;";
        $hp->mysqlquery($sql);
        
        foreach ($rights as $aktlevel => $right)
        {  
          foreach ($right as $name => $on)
          {
          
            $on = ($on) ? "true" : "false";
          
            $sql = "INSERT INTO `".$dbpräfix."right` (
            `ID` ,
            `level` ,
            `right`,
            `ok`,
            `description`,
            `cat`
            )
            VALUES (
            NULL , '$aktlevel', '$name', '$on', '".$this->desc($name)."', '".$this->cat($name)."'
            );";
            $hp->mysqlquery($sql);
          
          }
        
        }

    } else
    {
      throw new Exception();
    }
  
  
  }
  
  

}
?>