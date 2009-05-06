<?php

class infoclass
{
var $lang;
var $error;
var $hp;
var $infoarray = array();
var $okmarray = array();

var $firephp;

function init($lang, $error, $hp)
{
$this->lang = $lang;
$this->error = $error;
$this->hp = $hp;
$this->firephp = $hp->getfirephp();
}

function info($info)
{
 if (!in_array($info, $this->infoarray))
  {
  array_push($this->infoarray, $info);
  $this->firephp->info($info);
  }
}

function okm($okm)
{
 if (!in_array($okm, $this->okmarray))
  {
  array_push($this->okmarray, $okm);
  $this->firephp->log($okm);
  }
}

function okn($okm)
{
// Löst das Problem, dass ich mich beim Programmieren verschreib :-D
$this->okm($okm);
}


function outputdiv()
{

echo '<div id="infodiv" style="color:black; background-color:yellow; display:none;"><p align="center"></p></div>'."\n";

echo '<div id="okdiv" style="color:white; background-color:green; display:none;"><p align="center"></p></div>'."\n";

}

function getmessages()
{
$string="";
$infoarray = $this->infoarray;



foreach ($infoarray as $key=>$value) {
if ($string <> "")
{
$string = $string . ", ". $value;	
} else
{
$string = $value;
}
}
if ($string <> "")
{
$string = str_replace("'", '\\"', $string);
?>

<script type="text/javascript">

var errordiv = document.getElementById('infodiv');
errordiv.innerHTML = '<p align="center"><?=$string?></p>';
errordiv.style.display = '';

</script>

<?

}


$string="";
$okmarray = $this->okmarray;



foreach ($okmarray as $key=>$value) {
if ($string <> "")
{
$string = $string . ", ". $value;	
} else
{
$string = $value;
}
}
if ($string <> "")
{
$string = str_replace("'", '\"', $string);
?>

<script type="text/javascript">

var errordiv = document.getElementById('okdiv');
errordiv.innerHTML = '<p align="center"><?=$string?></p>';
errordiv.style.display = '';

</script>

<?

}





}

}


?>
