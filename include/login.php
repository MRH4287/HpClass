<?php
class login
{
var $login;
var $config;
var $hp;

function __construct()
{
// Standard Config:
$this->config = array(

"title_front" => "<font color=\"black\">",
"title_back" => "</font>",
"list_front" => "<font color=\"black\">",
"list_back" => "</font>",
"list_symbol" => "&raquo;&nbsp;",
"list_behind_link" => "",
"list_before_link" => ""

);


}

function set_hp($hp)
{
$this->hp = $hp;
}

function tf()
{
return $this->config["title_front"];
}

function tb()
{
return $this->config["title_back"];
}

function lf()
{
return $this->config["list_front"];
}
function lb()
{
return $this->config["list_back"];
}
function ls()
{
return $this->config["list_symbol"];
}

function lvl()
{
return $this->config["list_before_link"];
}

function lnl()
{
return $this->config["list_behind_link"];
}


function addstr($str)
{
$this->login = $this->login.$str;
}

function getlogin()
{
return $this->login;
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

    foreach ($array as $key=>$value) {
    	
    	if ($key == "!addlinks")
    	{
      $links = $this->getlinks();
      } else
      {
      $links = array($key => $value);
      
      }
    $this->addlinks($links);	
    	
    }

    

}


function addlinks($links)
{
 
 $l = $this;
 $hp = $this->hp;
 $right = $hp->getright();
 $level = $_SESSION['level'];

$superadmin = in_array($_SESSION['username'], $hp->getsuperadmin());
 

 foreach ($links as $key=>$value) {
 	
 	$data = explode("!", $key);
 	
 	if ((count($data) > 1) and ($data[1] != ""))
 	{
 	  
 	  if ($data[1] == "superadmin")
 	  {
 	      if ($superadmin)
 	      {
          $this->addstr($l->lvl().'<a href='.$data[0].'>'.$l->lf().$l->ls().$value.$l->lb().'</a>'.$l->lnl());
        }
     } else
      {
 	
        if ($right[$level][$data[1]])
        {
        	$this->addstr($l->lvl().'<a href='.$data[0].'>'.$l->lf().$l->ls().$value.$l->lb().'</a>'.$l->lnl());

        }
      }  
   } else
   { 	
 	  $this->addstr($l->lvl().'<a href='.$key.'>'.$l->lf().$l->ls().$value.$l->lb().'</a>'.$l->lnl());
 	 }
 	
 }


}

}

$login = new login;
$login->set_hp($hp);
$login->getconfig($temp, $design);
// Short Cut
$l = $login;

$dbpräfix = $hp->getpräfix();

// Hier kommt der Logintext
//$login->addstr('<p align="left">');

 if (!isset($_SESSION['username'])) { 
 
      $login->addstr('<form method="POST" action="sites/login.php">
      '.$l->tf().$lang->word('username').':'.$l->tb().'<br>
      <input type="text" name="user" size="15"><br>
      '.$l->tf().$lang->word('password').':'.$l->tb().'<br>
      <input type="password" name="passwort" size="15"><input type="submit" value="'.$lang->word('loginbutt').'" name="login">
      '.$l->lvl().'<a href=index.php?site=register>'.$l->lf().$l->ls().$lang->word('register').$l->lb().'</a>'.$l->lnl().'
      '.$l->lvl().'<a href=index.php?site=lostpw>'.$l->lf().$l->ls().'Passwort vergessen?'.$l->lb().'</a>'.$l->lnl().'
    </form>');
     } else { 

 
    
   $login->addstr($l->tf().$lang->word('loggedas').$l->tb().'<br><br>
   
   <b>'.$l->tf().$_SESSION['username']." (".$_SESSION['level'].')'.$l->lb().'</b><br>');
   
   $links = array(
   "sites/login.php?logout" => "Logout",
   "index.php?site=admin!adminsite" => "Administration",
   "index.php?site=pm" => "PM-Menu",
   "index.php?site=profil" =>  $lang->word('editprofile'),
   "index.php?site=vote!manage_vote" => "Umfragen",
   "!addlinks",
   "index.php?site=usedpics!usedpics" => "Benutzte Bilder",
   "index.php?site=rights!superadmin" => "Rechte",
   "index.php?site=config!superadmin" => "Konfiguration",
   "index.php?site=plugins!superadmin" => "Plugin System",
   "index.php?site=dragdrop!superadmin" => "Widget System"
   );
   
   $login->output($links);


}
//$login->addstr('</p>');

// Übergabe des Wertes
$template['login'] = $login->getlogin();
?>
