<?php
class XajaxTemplate
{
var $hp;

function sethp($hp)
{
$this->hp = $hp;
}


function ax_test3()
{
$response = new xajaxResponse();

$response->assign("test", "innerHTML", "bla");
$response->script("testFunktion();");

return $response;
}




}
?>