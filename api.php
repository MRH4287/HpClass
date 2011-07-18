<?php

if (!file_exists('include/api/key.php'))
{
  echo json_encode(array("error" => "No Shared Secret Definied"));
  exit();

}


require "include/class.php";
require_once "include/standalone.php";
require_once "include/api/types.inc.php";
require_once "include/class_api.php";
require_once 'include/class_pluginloader.php';
require 'include/api/key.php';




//Standalone:
$hp             = new Standalone("include");
$pluginloader   = new PluginLoader;

$pluginloader->sethp($hp);
$hp->setpluginloader($pluginloader);

$pluginloader->Init();

$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$config = $hp->getconfig();

$pluginloader->Load();

if ($config["enable_ScriptAccess"])
{
  if (isset($post["req"]))
  {
    echo $pluginloader->request($post["data"]);
    
  } elseif (isset($post["com"]))
  {
    echo $pluginloader->command($post["data"]);
    
  } else
  {
  
    echo json_encode(array("error" => "Unknown Subset"));
    
  }

} else
{
  echo json_encode(array("error" => "Not Allowed"));
}

?>