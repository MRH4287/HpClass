<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$config = $hp->config;



//Abfrage des POST ergebnisses
if (isset($post['sub']))
{
    
  $hp->config->apply($post['config']);
  $config->load();

}
// Ende auswertung Post
// Abfrage des aktuellen zustandes...

$site = new siteTemplate($hp);
$site->load("config");

$Tdata = array();


$registed = $config->getregisted();
foreach ($registed as $k => $name)
{
  // right, level, description, ok, cat
  $Tdata[$config->cat($name)][] = $name;     
} 


$designs = array();
if (is_dir("./template"))
{
  $handle = opendir("./template"); 
  while (false != ($file = readdir($handle))) 
  {
    $exp = explode(".",$file);
    if ((count($exp) >= 2) && ($exp[1] == "html"))
    {
      $designs[]=$exp[0];
    }
  }
}

$currentConfig = $config->getconfig();

$content = "";

foreach ($Tdata as $cat => $Cdata)
{
  $contentCat = "";
  foreach ($Cdata as $k => $conf)
  {
    $type = $config->type($conf);
    $input = "";
    
    switch ($type)
    {
      case "bool":
       
       $data = array(
          "name" => $conf,
          "checked" => ($currentConfig[$conf]) ? "true" : "false"       
       ); 
       
       $input = $site->getNode("Input-Checkbox", $data);
        
      
      break;
      
      
      case "design":
      
        $cont = "";
        
        foreach ($designs as $k => $name)
        {
          $data = array(
            "value" => $name,
            "ID" => $name,
            "selected" => ($currentConfig["design"] == $name) ? "true" : "false"
          );
          $cont .= $site->getNode("Input-Option", $data);
        
        }
        
        $data  = array(
          "name" => $conf,
          "Options" => $cont       
        );
        
        $input = $site->getNode("Input-Combobox", $data);
        
      
      
      break;
      
      default:
      
        $data = array(
          "name" => $conf,
          "value" => $currentConfig[$conf]        
        );
        
        $input = $site->getNode("Input-Textbox", $data);
        
      
      break;
    
    }
    
    $data = array(
      "name" => $conf,
      "description" => $config->desc($conf),
      "input" => $input
    );
    
    $contentCat .= $site->getNode("Config", $data);
    
    
  }

  $data = array(
    "name" => $cat,
    "Config" => $contentCat
  );
  
  $content .= $site->getNode("Categorie", $data);

}

$data = array(
  "Config" => $content

);

$site->setArray($data);

$site->display();



?>
