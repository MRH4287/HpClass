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
  $node = 'Main';
  
  
  if( isset($get['forums']))
  {
    $site->load('forums');
    
  } elseif (isset($get['fid']))
  {
    
    $site->load('threads');
    $site->set('fid', intval($get['fid']));
    
  } elseif (isset($get['tid']))
  {
    $site->load('threadView');
    $site->set('tid', intval($get['tid']));
    
  } elseif (isset($get['newForum']) && ($right->is('manage_forums')))
  {
  
    $site->load('forumEdit'); 
    $site->set('edit', false);
    $site->set('ID', -1);
    
    
  
  } else
  { 
    $site->load('index');
  }
  $site->display($node);

?>