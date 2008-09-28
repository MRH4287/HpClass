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


// Template
$template['headline']=$headline;
$template['titel']=$titel;
$template['mainheadline']=$mainheadline;
$template['username']=$_SESSION['username'];
$template['info']="MRH Website System v4";

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
$template['js']='<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />

';
?>
