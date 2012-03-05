<?php
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && isset($_REQUEST['request']))
{
    session_start();
    
    require "../include/class.php";
    require_once "../include/standalone.php";

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
    $hp   = new Standalone("../include");
    $lang = $hp->langclass;
    
    $hp->error = new ErrorStandaloneMod();
    $lang->seterror($hp->error);
    
    $req = $_REQUEST['request'];

    if (!is_array($req) && !is_string($req))
    {
        header("HTTP/1.0 400 Bad Request");
        exit();
    }

    if (!is_array($req))
    {
        $req = array( $req );
    }

    $result = array();
    foreach($req as $key => $name)
    {
        $result[$name] = $lang->word($name);
    }

    echo json_encode($result);

} else
{
    header("HTTP/1.0 400 Bad Request");
    exit();
}
?>