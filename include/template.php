<?php
$right = $hp->getright();
$config = $hp->getconfig();

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
$template['head']='
<script src="js/jQuery.js"></script>
<script src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.tipTip.js"></script>
<script type="text/javascript" src="js/jquery.filedrop.js"></script>
<script type="text/javascript" src="js/lightboxX2.js"></script>
<script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<script src="js/functions.js"></script>
<script src="js/drag&drop.js"></script>
<script src="js/widgetconfig.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/votes.js"></script>
<script src="js/l10n.js"></script>
<script src="js/templateSystem.js"></script>
<script src="js/autorun.js"></script>
<link rel="stylesheet" href="css/lightboxX2.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/tipTip.css" type="text/css"/>
<link rel="stylesheet" href="css/forum.css" type="text/css"/>
<link rel="stylesheet" href="css/widget.css" type="text/css"/>
<link rel="stylesheet" href="include/usedpics/css/usedpics.css" type="text/css" />
<link rel="stylesheet" href="css/news.css" type="text/css"/>
<link rel="stylesheet" href="css/plugins.css" type="text/css"/>
<link rel="stylesheet" href="css/popup_tagestermine.css" type="text/css"/>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.lightbox-0.5.css" type="text/css" media="screen" />
';


?>
