<?php
class pluginTemplate extends siteTemplate
{

  public function __construct($hp, $folder)
  {
    parent::__construct($hp);
    
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
    

    
  }


}





?>