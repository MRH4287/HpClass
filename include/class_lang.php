<?php
class lang
{
var $lang = array();

var $clang;
var $error;

function init($lang2)
{


if (!isset($_SESSION['language']))
{
$this->setlang($lang2);
} else
{
$this->setlang($_SESSION['language']);
}
$this->lang = array();
$this->incfiles();


}

function lang1()
{
return $this->lang;
}

function getlang()
{
return $this->lang[$this->clang];
}

function getlang2($key1)
{
$key1 = (string) $key1;
return $this->lang[$key1];
}

function setlang($lang2)
{
if (!is_object($lang2)) 
{
$this->clang = $lang2;

$_SESSION['language'] = $lang2;
} else
{

echo "ERRORRROROOROROROROROR!!!!!";
}
}

function word($word)
{
$clang = $this->clang;

if ($this->lang[$clang][$word] <> "") {
return $this->lang[$clang][$word];
} else
{
$this->error->error("Language File not Found!", "2");
if ($clang == "dev")
 {
 return "<-$word->";
 } else
  {
  return "<-!->";
  }
 }
}


function word_exsists($word)
{
if ($this->lang[$this->clang][$word] <> "")
{
return true;
} else
{
return false;
}
}

function currentlang()
{
return $this->clang;
}

function incfiles ()
{

// Include von Sprachdaten aus Datei:

$x=-2;
$handle = @opendir("./include/lang/$file"); 
while (false !== ($file = @readdir($handle))) {
	$attrib=@fileperms("./include/lang/$file");
	$filesize=@filesize("./include/lang/$file");
	$file_size_now = @round($filesize / 1024 * 100) / 100 . "Kb";
	$n= @explode(".",$file);
$art = @strtolower($n[1]);


if ($art == "php")
{
if (file_exists("./include/lang/$file"))
include ("./include/lang/$file");

$this->addlang ($lang);

}
} 



}

function addlang ($lang)
{
$mrharray = array();

foreach ($lang as $key=>$value) {
$key = (string) $key;
$mrharray = $this->lang[$key];

if (!is_array($mrharray))
{
$mrharray = array();
}
$temparray2 = array ();

$temparray2 = $lang[$key];


$mrharray = array_merge($mrharray, $temparray2);
$this->lang[$key] = $mrharray;
br;

}

}

function seterror($error)
{
$this->error = $error;
}


}
?>
