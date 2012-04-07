<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    session_start();
    
    require "../include/class.php";
    require_once "../include/standalone.php";
	require_once '../include/class_pluginloader.php';

    class ErrorStandaloneMod
    {

      function error($text, $level = "2", $function = "")
      {
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode(array('error' => $text));
        exit;
      }
    }
    	
    //Standalone:
	$hp             = new Standalone("../");
	$pluginloader   = new PluginLoader;
	$lang = $hp->langclass;

	$hp->error = new ErrorStandaloneMod();
    $lang->seterror($hp->error);
	
	$pluginloader->sethp($hp);
	$hp->setpluginloader($pluginloader);

	$pluginloader->Init();

	$pluginloader->Load();

    require "../include/base/siteTemplate.php";
    require "../include/base/javascriptTemplate.php";

    $temp = new javascriptTemplate($hp, '../');

    $file = (isset($_GET['file'])) ? $_GET['file'] : null;
    $node = (isset($_GET['node'])) ? $_GET['node'] : "Main";
    $all = (isset($_GET['all'])) ? $_GET['all'] : "true";

    $all = ($all === "true") ? true : false;

    if ($file == null)
    {
        echo json_encode("No Data!");
        exit;
    }

    $temp->load($file);

    echo json_encode($temp->serialize($node, $all));

} else
{
    header("HTTP/1.0 400 Bad Request");
    echo "Only over AJAX call available";
}
?>