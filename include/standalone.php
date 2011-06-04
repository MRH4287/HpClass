<?php

class Standalone extends Hp
{

  function __construct($path)
  {
    
    // Einbinden der Resourcen;
    require_once $path.'/class_error.php';
    require_once $path.'/class_info.php';
    require_once $path.'/class_lang.php';
    require_once $path.'/class_right.php';
    require_once $path.'/class_config.php';
    require_once $path.'/FirePHP.class.php';
    
    
    $config = $path."/config.php";
    // Config einlesen
    if (is_file($config))
    {
      include $config;
      
      $this->host=$dbserver;
      $this->password=$dbpass;
      $this->user=$dbuser;
      $this->präfix=$dbpräfix;
      $this->db=$dbdatenbank;
      
      
    
    } else
    {
      echo "Config Datei nicht gefunden!";
      exit;
     }
     
    // MysqlVerbindung
    $this->connect();

    // Initialisieren der Resourcen:
    $this->config     = new config;
    if (file_exists($path."/config-default.php"))
    {
        include $path."/config-default.php";
        $this->config->registerArray($configData);
  
    }
    $this->config->sethp($this);
      
    $this->right      = new right;
    
    if (file_exists($path."/rights.php"))
    {
        include $path."/rights.php";
        $this->right->registerArray($registed);
        $this->right->registerLevel($levels);
      
    }
    
    
    $this->error      = new errorclass;
    $this->info       = new infoclass;
    $this->lang       = new lang;
    $this->setfirephp($firephp);
    $this->error->sethp($this);
    $this->lang->sethp($this);
    $this->info->init($this->lang, $this->error, $this);
    
    //Config
    $this->handelinput($_GET, $_POST);
    $this->handelconfig();


  }

  function outputdivs()
  {
  
    $this->error->outputdiv();
    $this->info->outputdiv();
  
  }
  
  function getmessages()
  {
  
    $this->info->getmessages();
    $this->error->showerrors();
  
  }


}


?>
