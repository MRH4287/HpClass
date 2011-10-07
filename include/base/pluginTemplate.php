<?php
class pluginTemplate extends siteTemplate
{

  private $folder = "";
  

  public function __construct($hp, $folder, $copy = null)
  {
      parent::__construct($hp, $copy);
    
      $this->searchpath = "template/sites/plugins/$folder/";
      $this->searchpathT = "template/#!Design#/sites/plugins/$folder/";
    
      if (!is_dir("./template/sites/plugins/"))
      {
        $hp->error->error("Plugin Template Direcory does not exist! (./template/sites/plugins/)");
      }
      
      if (!is_dir($this->searchpath) && ($this->searchpathT))
      {
        $hp->error->error("Template Plugin Folder does not exist! (".$this->searchpath.", ".$this->searchpathT.")" );
      }
    
      $this->folder = $folder;
    
      self::extend($this);
    
  }



 public function temp_this($args)
 {
    $argCount = count($args);
    
    $site = new pluginTemplate($this->hp, $this->folder, $this);
        
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