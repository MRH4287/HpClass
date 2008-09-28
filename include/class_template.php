<?php
class template
{

var $template = array();
var $temp;
var $error;
var $hp;
var $lang;


function seterror($error)
{
$this->error=$error;
}

function gettemp($part)
{

return $this->template[$part];

}

function settemplate($temp)
{
$this->temp=$temp;
}


function load($path)
{


if (!file_exists("template/$path.html"))
{

$this->template=array();
$this->error->error("Template $path not found!", "2");
if (file_exists("template/default.html"))
{
$path = "default";
} else
{
$error->error("Standad Template wurde nicht gefunden!","3");
}
}

$temp = "";

$userdatei = fopen ("template/$path.html","r");
while (!feof($userdatei))
   {
$zeile = fgets($userdatei,1000000);
 $temp=$temp.$zeile;
}

   $data = explode("<!--next-->", $temp);

  $temp = $this->loadtemplatefile($path);
 if (is_array($temp))
 {
 $this->temp = array_merge($this->temp, $temp); 
 } 
	$data = $this->spezialsigs($data);
 

   
$this->template['header']=$this->template['header'].$data[0];

$this->template['footer']=$this->template['footer'].$data[1];


}

function spezialsigs($data)
{

foreach ($data as $key=>$value) {

foreach ($this->temp as $key2=>$value2) {
$value = str_replace("<!--$key2-->", $value2, $value);	
}


$data[$key] = $value;
	
}
return $data;
}

function loadtemplatefile($path)
{
if (file_exists("template/$path/template.php"))
{

include "template/$path/template.php";

}

return $template;

}

function sethp($hp)
{
$this->hp = $hp;
}

function setlang($lang)
{
$this->lang=$lang;
}




}
?>
