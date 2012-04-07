<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    session_start();
	
	require "../include/class.php";
	require_once "../include/standalone.php";
	require_once '../include/class_ajax.php';
	require_once '../include/class_pluginloader.php';
	require_once "../include/base/SiteTemplate.php";
	require_once "../include/base/subpageTemplate.php";
	require_once "../include/base/pluginTemplate.php";

	
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
	
	PluginLoader::$PATH = '../';

	$pluginloader->sethp($hp);
	$hp->setpluginloader($pluginloader);

	$pluginloader->Init();

	$pluginloader->Load();
	
	if (isset($_POST['action']))
	{
		$result = AjaxFunctions::call($_POST['action'], $_POST);
		
		if ($result === null)
		{
			header("HTTP/1.0 422 Unprocessable Entity");
			echo "Function undefined";
		} else
		{
			echo json_encode($result);
		}
	
	} else
	{
	    header("HTTP/1.0 400 Bad Request");
		echo "Function var undefined";
	}
} else
{
    header("HTTP/1.0 400 Bad Request");
    echo "Only over AJAX call available";
}
?>