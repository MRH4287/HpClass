<?php
// Config System 
// 4.1
if ($config['titel'] != "")
{
$titel = $config['titel'];
}




if ($config['design'] != "")
{
$design = $config['design'];
}

// Template


$template['titel']=$titel;
  if (isset($_SESSION['username']))
  {
   $template['username']=$_SESSION['username'];
  } else
  {
   $template['username'] = "NONE";
  }


// JS
$template['jsu']='<script src="js/scriptaculous/prototype.js"></script>
<script src="js/scriptaculous/scriptaculous.js"></script>
<script src="js/functions.js"></script> 
<script src="js/drag&drop.js"></script> 
<script src="js/widgetconfig.js"></script> 
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="include/usedpics/js/picturelist.js"></script>
';

$template['js']='
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/lightboxX2.js"></script>
<link rel="stylesheet" href="css/lightboxX2.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/votes.js"></script>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
<link rel="stylesheet" href="css/forum.css" type="text/css"/>
<link rel="stylesheet" href="css/widget.css" type="text/css"/>
<link rel="stylesheet" href="include/usedpics/css/usedpics.css" type="text/css" />
<link rel="stylesheet" href="css/news.css" type="text/css"/>
<link rel="stylesheet" href="css/plugins.css" type="text/css"/>
';

?>
