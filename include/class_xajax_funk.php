<?php
class Xajax_Funktions
{
var $hp;

var $ajaxFuncPrefix = 'ax_';

var $xajax;


function __construct()
{
         $this->xajax = new xajax();
         $this->registerFunctions();


}


function sethp($hp)
{
$this->hp = $hp;
}

 public function registerFunctions() {
    		$methods = get_class_methods($this);
    		
    		foreach ($methods as $m) {
			$p = $this->ajaxFuncPrefix;
    			if (preg_match("/^{$p}[a-z]/", $m)) {
    				$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);
    				$this->xajax->registerFunction(array($m2, &$this, $m));
    			}
    		}
    }


function printjs()
{

$this->xajax->printJavascript("include/");
}

function processRequest()
{
$this->xajax->processRequest();
}




}
?>
