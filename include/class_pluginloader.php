<?php
class PluginLoader
{

private $hp;
public $plugins = array();

// ------------------------------


function sethp($hp)
{
  $this->hp = $hp;
}


/*
Startet den Plugin Loader

*/
function Init()
{
   
  // Bindet die BasisKlasse der Plugins ein
  include_once './include/base/plugin.php';
  $this->updatePluginList();
  $this->enablePlugins();

}

/*

Läd die Plugins

*/
function Load()
{

  foreach ($this->plugins as $name=>$data)
  {
    if ($data["enabled"] == true)
    {
      $data["o"]->OnLoad(); 
    }
  }


}


/*

Aktualisiert die Plugin Liste, in dem er die Daten aus dem plugins Unterordner liest
und diese in ein Array speichert.
Anschließend wird die Datenbank durchsucht und alle dort gelisteten Plugins auf enabled 
gesetzt, was dazu führt, dass bei diesen Objekten, die OnEnable Funktion aufgerufen wird.

*/
function updatePluginList()
{
  $hp = $this->hp;
  $dbpräfix = $hp->getpräfix();
  $info = $hp->info;
  $error = $hp->error;
  $fp = $hp->fp;


  
  // Alle vorhandenen Plugins laden.
 try
 { 
  
  if (is_dir("./plugins/"))
  {
    $handle = @opendir("./plugins/"); 
    while (false !== ($file = readdir($handle))) 
    {

      $n = explode(".", $file);
      if (count($n) >= 2)
      {
      $a = $n[0];
      $b = $n[count($n)-1];

        if ($b == "php")
        {

         try 
         {
           include "./plugins/$file"; 
           $myClass = new $a($this->hp, $this);
           
                      
           $this->plugins[$a] = array("o" => $myClass, "enabled" => $myClass->isEnabled);
                
          }
          catch (Exception $e) 
          {
             $this->hp->error->error($e->getMessage());
          }   


        }
       }
      }
    } else
    {
    }
  } catch (Exception $e) 
  {
  }   
  // Lade die Datenbank um alle Plugins zu suchen, die Aktiviert sind
  
  $sql = "SELECT * FROM `$dbpräfix"."plugins`";
  $erg = $hp->mysqlquery($sql);
  while ($row = mysql_fetch_object($erg))
  {
    
    $name = $row->name;
    
    if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
    {
        $this->plugins[$name]["enabled"] = true;    
    
    } else
    {
      
      $this->hp->info->info("Ungültiger Eintrag in Plugin Tabelle entfernt");
      $sql2 = "DELETE FROM `$dbpräfix"."plugins` WHERE `name` = '$name';";
      $hp->mysqlquery($sql2);
    
    
    }  
  
  
  }
  

}


/*

Aktiviert alle Plugins, bei denen das Flag enabled auf true steht

*/
function enablePlugins()
{

  foreach ($this->plugins as $name=>$data)
  {
    if ($data["enabled"] == true)
    {
      $data["o"]->OnEnable(); 
    }
  }

}


/*

  Liefert die Objektinstanz des Plugins mit dem angegebenen Namen zurück

*/
function getPlugin($name)
{
  if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
  {
    return $this->plugins[$name]["o"];    
  } else
  {
    return null;
  }

}


/*

  Liefert zurück, on ein Plugin mit einem angegebenen Namen aktiviert ist

*/
function isEnabled($name)
{
  if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
  {
    return $this->plugins[$name]["enabled"];    
  } else
  {
    return null;
  }

}


/*

 Fügt ein Plugin zu der Liste der erlaubten Plugins hinzu

*/
function enablePlugin($name)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;

  if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
  {
    $plugin = $this->plugins[$name];
    
    if (!$plugin["o"]->lock)
    {
      // Eintragen in die Datenbank;
      
      $sql = "REPLACE INTO `$dbpräfix"."plugins` (`name`) VALUES ('$name');";
      $erg = $hp->mysqlquery($sql);
    
      return true;
    
    } else
    {
      return -1;
    }
      
  } else
  {
    return false;
  }              

}


/*

 Entfernt ein Plugin von der Liste der erlaubten Plugins

*/
function disablePlugin($name)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$info = $hp->info;
$error = $hp->error;
$fp = $hp->fp;


  if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
  {
    $plugin = $this->plugins[$name];
    
    if (!$plugin["o"]->lock)
    {
      // Eintragen in die Datenbank;
      
      $sql = "DELETE FROM `$dbpräfix"."plugins` WHERE `name` = '$name';";
      $erg = $hp->mysqlquery($sql);
      
      return true;
    
    } else
    {
      return -1;
    }
      
  } else
  {
    return false;
  }              

}


/*

  Überprüft ob ein Plugin Zusätzliche Informationen (Autor, Homepage, Notizen) enthält

*/
function containsInfo($name)
{
  $plugin = $this->getPlugin($name);
  if ($plugin == null)
  {
    return null;
  } else
  {
      
    return (($plugin->autor != "") || ($plugin->homepage != "") || ($plugin->notes != "")); 
    
  }
  
}

}
?>