<?php
class lang
{
var $lang = array();

var $clang;
var $error;
var $hp;
var $temppath;



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
$this->loadfromdb();


$this->inctempfiles();
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

function savetodb($file = true)
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();
$fp = $hp->fp;

if ($file)
{
$lang = array();
$this->incfiles();
}


$sql = "TRUNCATE `$dbpräfix"."lang`";
$erg = $hp->mysqlquery($sql);


foreach ($this->lang as $lang=>$langarray) {

foreach ($langarray as $key=>$value) {
	
	$sql = "INSERT INTO `$dbpräfix"."lang` (`lang`, `word`, `wort`) VALUES ('$lang', '$key', '$value');";
	$erg = $hp->mysqlquery($sql);
	
}
	
	
	
}

if ("$this->use" == "file")
{
$this->incfiles();
} elseif ("$this->use"  == "db")
{
$this->loadfromdb();
}

}

function loadfromdb()
{
$hp = $this->hp;
$dbpräfix = $hp->getpräfix();

$fp = $hp->fp;

$sql = "SHOW TABLES LIKE '$dbpräfix"."lang';";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_array($erg);
if ((count($row) >= 1) and ($row!=false))
{

$sql = "SELECT * FROM `$dbpräfix"."lang`";
$erg = $hp->mysqlquery($sql);

while ($row = mysql_fetch_object($erg))
{
$this->addword($row->lang, $row->word, $row->wort);
}
}
}

function addword($lang, $word, $wort)
{
$this->lang[$lang][$word] = $wort;
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

function inctempfiles()
{
$fp = $this->hp->firephp;

if ($this->temppath == "")
{
$config = $this->hp->getconfig();
if ($config['design'] != "")
{
$this->temppath = $config['design'];
}

}


if (is_dir("template/".$this->temppath."/lang/"))
{

$x=-2;
$handle = @opendir("./template/".$this->temppath."/lang/"); 
while (false !== ($file = @readdir($handle))) {
	$attrib=@fileperms("./template/".$this->temppath."/lang/$file");
	$filesize=@filesize("./template/".$this->temppath."/lang/$file");
	$file_size_now = @round($filesize / 1024 * 100) / 100 . "Kb";
	$n= @explode(".",$file);
$art = @strtolower($n[1]);


if ($art == "php")
{
if (file_exists("./template/".$this->temppath."/lang/$file"))
include ("./template/".$this->temppath."/lang/$file");

$this->addlang($lang);

}
} 


}

foreach ($this->lang[$this->clang] as $key=>$value) {
$array = explode("tp_", $key);


	if (count($array) == 2)
	{
  $this->hp->template->addtemp($array[1], $value);
  
  }
}

}

function settempfolder($design)
{
$config = $this->hp->getconfig();
if ($config['design'] != "")
{
$design = $config['design'];
}


$this->temppath=$desgin;
}

function sethp($hp)
{
$this->hp = $hp;
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


}

}

function seterror($error)
{
$this->error = $error;
}


}
?>
