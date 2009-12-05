<?php
// Config System 
// 4.1
if ($config['titel'] != "")
{
$titel = $config['titel'];
}
if ($config['mainheadline'] != "")
{
$mainheadline = $config['mainheadline'];
$mainheadline = str_replace("&lt;", "<", $mainheadline);
$mainheadline = str_replace("'", "\"", $mainheadline);
}
if ($config['design'] != "")
{
$design = $config['design'];
}
$version = $hp->getversion();
$version = $version['version'];
// Template
$template['headline']=$headline;
$template['titel']=$titel;
$template['mainheadline']=$mainheadline;
$template['username']=$_SESSION['username'];
$template['info']="MRH HPClass CMS V$version";

if (file_exists(".svn/entries"))
{
$content = file(".svn/entries");
$VERSION = trim($content[3]);
$template['svn']=$VERSION;

$template['svnrev']="Aktuelle SVN Version: $VERSION";
} else
{
$template['svnrev']="Kein SVN!";
}

// JS
$template['jsu']='<script src="js/scriptaculous/prototype.js"></script>
<script src="js/scriptaculous/scriptaculous.js"></script>
<script src="js/drag&drop.js"></script> 
';

$template['js']='



<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/lightboxX2.js"></script>
<link rel="stylesheet" href="css/lightboxX2.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/fade_effekt.js"></script>
<script type="text/javascript" src="js/votes.js"></script>
<link rel="stylesheet" href="css/forum.css" type="text/css"/>
<link rel="stylesheet" href="css/widget.css" type="text/css"/>

';

?>
