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
$right = $hp->right;


// try to load something:

$site = new pluginTemplate($this, 'forum');


if( isset($get['forums']))
{
  $site->load('forums');
  
} elseif (isset($get['fid']))
{
  
  $site->load('threads');
  $site->set('fid', intval($get['fid']));
  
} else
{ 
  $site->load('index');
}
$site->display();

?>