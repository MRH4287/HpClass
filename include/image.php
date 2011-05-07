<?php
class ImageControl
{
  var $hp;
  
  
  var $sourceList = array(
    
      "usr" => array(
        
        "table" => "user",
        "index" => "id",
        "dataset" => "bild",
        "widthSet" => "width",
        "heightSet" => "height",
        "wantedWidth" => 150,
        "wantedHeight" => null  
      ),
      
      "usedpic" => array(
        
        "table" => "usedpics",
        "index" => "ID",
        "dataset" => "data",
        "widthSet" => "width",
        "heightSet" => "height",
        "wantedWidth" => null,
        "wantedHeight" => null  
      )
    
    );
    
  
  
  function __construct($hp)
  {
    $this->hp = $hp;
    $this->addTemplate();
  }
  
  
  
  /*
  
   Liest alle Plugins aus einem bestimmtem Ordner aus
  
  */
  function addTemplate()
  {
    try
    { 
      $config = $this->hp->getconfig();
    
      $path = "../template/".$config["design"]."/imageData.php";
      
      if (file_exists($path) && is_file($path))
      {
        include $path;
        
        if (isset($sourceList) && is_array($sourceList))
        {
          $this->sourceList = array_merge($this->sourceList, $sourceList);
        } else
        {
          echo "Fehler: imageData Datei gefunden, jedoch im falschem Format!";
          exit;
        
        }
        
        
      } 
    } catch (Exception $e) 
    {
    }
  }
  
  
  function getSource($name)
  {
    
    $source = null;
    foreach ($this->sourceList as $key => $data)
    {
       if ($name == $key)
       {
        $source = $data;
        break;
       }
    
    } 
    return $source;
  }


}

session_start();




if (!isset($site) && isset($_GET['id']) && isset($_GET['source']))
{
  require "./class.php";
  require_once "./standalone.php";
  require_once "./base/picture.php";
  
  //Standalone:
  $hp = new Standalone(".");
  
  
  // Site Config:
  $right = $hp->getright();
  $level = $_SESSION['level'];
  $get = $hp->get();
  $post = $hp->post();
  $dbpräfix = $hp->getpräfix();



  $image = new ImageControl($hp);
  
  $config = $hp->getconfig();
  
  $design = $config["design"];
  
   
  $source = $image->getSource($get["source"]);  

  if ($source == null)
  {
    echo "Unknown Subset!";
    exit;
    
    
  }


   $info = array();
   
   $sql = "SELECT * FROM `$dbpräfix".$source["table"]."` WHERE `".$source["index"]."` = '".$get["id"]."';";
   $result= $hp->mysqlquery($sql);
    if(!$result)
    {
    		print "Fehler im SQL-Skript.<br/>\n";
    		print mysql_error()."<br/>\n";
    		exit;					
    }
   $datei=mysql_fetch_assoc($result);
   
   $info["image"] = $datei[$source["dataset"]];
   $info["width"] = $datei[$source["widthSet"]];
   $info["height"] = $datei[$source["heightSet"]];


  $bildok = true;
  
  
  if(!$info["image"] || ($info["width"] == 0))
  {
  		$bildok = false; 		    						
  }
  	
  if (isset($get["org"]))
  {
    $source["wantedWidth"] = $info["width"];
    $source["wantedHeight"] = $info["height"];
  } else
  {
    if (isset($get["ww"]))
    {
       $source["wantedWidth"] = $get["ww"];
    } 
    if (isset($get["wh"]))
    {
       $source["wantedHeight"] = $get["wh"];
    } 
  
  }
 
    
  try
  {
    
     set_error_handler(create_function('', "throw new Exception(); return true;"));
      

      
     $picture = new Picture();
        
        
     if ($bildok)
     {
        $picture->setAsString($info["image"], $info["width"], $info["height"]);
     } else
     {
       $picture->setJPG("../nopic.jpg");
     }
        
     $picture->display($source["wantedWidth"], $source["wantedHeight"]);
        
     exit;
      

      
  } catch (Exception $e)
  {
    header("Content-type: image/jpeg");
    print $datei['bild'];	
  }
    
	
} else
{
  echo "Diese Seite kann nur direkt aufgerufen werden!";
}
?>


