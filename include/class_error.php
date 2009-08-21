<?php
class errorclass
{
var $errorm;
var $errorarray = array();
var $hp;
var $firephp;

function error($string, $l = "2", $fu = "")
{

if (!is_object($this->firephp))
{
$this->firephp = $this->hp->getfirephp();
}

$l = (string) $l;
if ($l == "1")
{
$this->firephp->warn($string, $fu);

echo $string;
} elseif ($l == "2")
{

$this->errorm = $string;

if (!in_array($string, $this->errorarray))
{
$this->firephp->warn($string, $fu);

array_push($this->errorarray, $string);
}



} elseif ($l == "3")
{
$datum = date('j').".".date('n').".".date('y');
$this->firephp->error($string, $fu);

echo "<font color=red>$string</font>";

$this->hp->PM('mrh', 'System', 'ERROR der Stufe 3!', $string, $datum);


exit;
}
}

function geterror()
{
return $this->errorm;
}

function outputdiv()
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
$string = str_replace("'", '"', $string);
?>

<script type="text/javascript">

var errordiv = document.getElementById('errordiv');
errordiv.innerHTML = '<p align="center">ERROR: <?php echo $string?></p>';
errordiv.style.display = '';

</script>

<?php

}

}

function sethp($hp)
{
$this->hp=$hp;
}

}

?>
