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
$info = $hp->getinfo();

$pluginloader = $hp->pluginloader;

  $site = new siteTemplate($hp);
  $site->load("plugins");

  // Diese Seite sollte nicht ohne SuperAdmin Rechte zu öffnen sein,
  // Jedoch prüfe ich das lieber schnell nach

  if (isset($_SESSION['username']) and in_array($_SESSION['username'], $hp->getsuperadmin()))
  {
  
    $plugins = $pluginloader->plugins;
    $content = "";
    foreach ($plugins as $name=>$data)
    {
      $tempData = array(   
      "name" => $data["o"]->name,
      "extern" => ($data["extern"]) ? " (E)" : "",
      "PluginInfo" => "",
      "version" => $data["o"]->version,
      "ID" => $name
      );
        
      if ($data["o"]->lock)
      {
        $tempData["PluginData"] = ' <img src="./images/lock.gif" alt="Gesperrt"></div> '; 
             
      } elseif ($data["enabled"]) 
      {
        $tempData["PluginData"] = '<img src="./images/on.gif" alt="ON" onclick="xajax_pluginDisable(\''.$name.'\')"></div>';
          
      } else 
      {       
        $tempData["PluginData"] = '<img src="./images/off.gif" alt="OFF" onclick="xajax_pluginEnable(\''.$name.'\')"></div>';
      }
      if ($pluginloader->containsInfo($name))
      {
        $infoA = array(
        "autor" => $data["o"]->autor,
        "homepage" => $data["o"]->homepage,
        "notes" => $data["o"]->notes
        );
    
        $tempData["PluginInfo"] = $site->getNode("PluginInfo", $infoA);
  
      }
      $content .= $site->getNode("Plugins", $tempData);

    } 
    
    $site->set("Plugins", $content);
  
  } else
  {
    // Hier scheint wohl jemand die class.php geändert zu haben ...
    // Da diese Seite jedoch Kritisch ist, kann das nicht erlaubt werden!
    $site->load("info");
    
    $site->set("info","<b>Fehler:<b /> Um diese Seite zu betreten, müssen Sie SuperAdmin sein!");
  }


$site->display();

?>