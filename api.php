<?php

if (!file_exists('include/api/key.php'))
{
	echo json_encode(array("error" => "No Shared Secret Definied"));
	exit();

}


require "include/class.php";
require_once "include/standalone.php";
require_once "include/api/types.inc.php";
require_once "include/class_api.php";
require_once 'include/class_widgets.php';
require_once 'include/class_subpages.php';
require_once 'include/class_pluginloader.php';
require_once "include/base/SiteTemplate.php";
require_once "include/base/subpageTemplate.php";
require_once "include/base/pluginTemplate.php";

require 'include/api/key.php';




//Standalone:
$hp             = new Standalone("./");
$pluginloader   = new PluginLoader;
$widgets        = new widgets;
$subpages       = new subpages;



$pluginloader->sethp($hp);
$hp->setpluginloader($pluginloader);
$widgets->sethp($hp);
$hp->setwidgets($widgets);
$subpages->sethp($hp);
$hp->setsubpages($subpages);


$pluginloader->Init();

$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$config = $hp->getconfig();

$pluginloader->Load();

if (!is_array($get))
{
	$get = array();
}

if (!is_array($post))
{
	$post = array();
}

$req = array_merge($get, $post);

if ($config["enable_ScriptAccess"])
{
	if (isset($req["req"]))
	{
		echo $pluginloader->request($req["data"]);

	} elseif (isset($req["com"]))
	{
		echo $pluginloader->command($req["data"]);

	} else
	{

		echo json_encode(array("error" => "Unknown Subset"));

	}

} else
{
	echo json_encode(array("error" => "Not Allowed"));
}


$pluginloader->Save();

?>