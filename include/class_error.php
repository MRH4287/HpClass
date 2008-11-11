<?php
class errorclass
{
var $errorm;
var $errorarray = array();
var $hp;
var $firephp;


function error($string, $l)
{
$config = $this->hp->getconfig();

$l = (string) $l;
if ($l == "1")
{
if (!$config['hideerrors'])
{
echo $string;
}
$this->firephp->info($string);
} elseif ($l == "2")
{

$this->errorm = $string;
$this->firephp->warn($string);
if (!$config['hideerrors'])
if (!in_array($string, $this->errorarray))
{

array_push($this->errorarray, $string);
}



} elseif ($l == "3")
{
$datum = date('j').".".date('n').".".date('y');
$dbpräfix = $this->hp->getpräfix();
$this->firephp->error($string);
if (!$config['hideerrors'])
{
echo "<font color=red>$string</font>";
}
if (isset($dbpräfix))
{
$this->hp->pm('mrh', 'System', 'ERROR der Stufe 3!', $string, $datum);
}

exit;
}



}

function geterror()
{
return $this->errorm;
}

function errorclass()
{

echo '<div id="errordiv" style="color:white; background-color:red; display:none;"><p align="center"></p></div>'."\n";

}

function showerrors()
{
$string="";
$errorarray = $this->errorarray;



foreach ($errorarray as $key=>$value) {
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
$string =str_replace("'", '"', $string);
?>

<script type="text/javascript">

var errordiv = document.getElementById('errordiv');
errordiv.innerHTML = '<p align="center">ERROR: <?=$string?></p>';
errordiv.style.display = '';

</script>

<?

}

}

function sethp($hp)
{
$this->hp=$hp;
}

}

?>

