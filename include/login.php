<?php
class login
{
var $config;
var $hp;

function __construct($hp)
{
// Standard Config:
$this->config = array();
$this->hp = $hp;
}

function getconfig($template, $design)
{
 if (is_object($template))
 {
  $config = $template->getloginconfig($design);
  
   if (is_array($config))
   {
    $this->config = $config;
   }


 }
}

function getlinks()
{
 if (isset($this->config['links']) and is_array($this->config['links']))
 {
 return $this->config['links'];
 } else
 {
 return array();
 }

}

function output($array)
{

  $content = "";
  foreach ($array as $key=>$value) 
  {
    	
    if ($key == "!addlinks")
    {
      $links = $this->getlinks();
    } else
    {
      $links = array($key => $value);
      
    }
    $content .= $this->addlinks($links);	
    	
  }

  return $content;  

}


function addlinks($links)
{
 $hp = $this->hp;
 $l = $this;
 $right = $hp->getright();
 $level = $_SESSION['level'];

 $superadmin = in_array($_SESSION['username'], $hp->getsuperadmin());
 
 $site = new siteTemplate($hp);
 $site->load("login");

 $content = "";
 foreach ($links as $key=>$value) {
 	
 	$data = explode("!", $key);
 	
 	if ((count($data) > 1) and ($data[1] != ""))
 	{
 	  
 	  if ($data[1] == "superadmin")
 	  {
 	      if ($superadmin)
 	      {
 	        $dataA = array(
             "site" => $data[0],
             "name" => $value
           );
          $content .= $site->getNode("Link", $dataA);
        }
     } else
      {
 	
        if (isset($right[$level][$data[1]]) && $right[$level][$data[1]])
        {
        	 	 $dataA = array(
             "site" => $data[0],
             "name" => $value
           );
          $content .= $site->getNode("Link", $dataA);

        }
      }  
   } else
   { 	
     $dataA = array(
       "site" => $key,
       "name" => $value
      );
     $content .= $site->getNode("Link", $dataA); 	
 	 }
 	
 }

 return $content;
}

}

$login = new login($hp);
$login->getconfig($temp, $design);
// Short Cut
$l = $login;

$dbpräfix = $hp->getpräfix();

// Hier kommt der Logintext
//$login->addstr('<p align="left">');

 if (!isset($_SESSION['username'])) 
 { 
    $site = new siteTemplate($hp);
    $site->load("login");
    $template['login'] = $site->get("Login");
    
 } else { 

    $site = new siteTemplate($hp);
    $site->load("login");
    
    
   $links = array(
   "sites/login.php?logout" => "Logout",
   "index.php?site=admin!adminsite" => "Administration",
   "index.php?site=pm" => "PM-Menu",
   "index.php?site=profil" =>  $lang->word('editprofile'),
   "index.php?site=vote!manage_vote" => "Umfragen",
   "!addlinks",
   "index.php?site=usedpics!usedpics" => "Benutzte Bilder",
   "index.php?site=subpage!manage_subpage" => "Unterseiten Verwalten",
   "index.php?site=rights!superadmin" => "Rechte",
   "index.php?site=config!superadmin" => "Konfiguration",
   "index.php?site=plugins!superadmin" => "Plugin System",
   "index.php?site=dragdrop!superadmin" => "Widget System"
   );
   
  $data = array(
      "username" => $_SESSION['username'],
      "level" => $_SESSION['level'],
      "Links" => $login->output($links) 
  );
   
    
   $template['login'] = $site->getNode("List", $data);


}
//$login->addstr('</p>');

// Übergabe des Wertes

?>
