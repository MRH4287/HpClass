<?php
// Hier eine Liste aller im Script vorhandenen Variablen:
// $path = Pfad zum Modulverzeichnis (moduls/$path/ für eine korrekte verlinkung!)
// $name = Name des Scripts!
// $run  = Name des PHP Scripts, was ausgeführt wird!
// $template = Templatearray (siehe BEispiel Funktion)
// $hp = HPClass
// $error = Errorclass
// $info = Infoclass
// $lang = Langclass
// $temp = Templatescript (Bitte $template verwenden)

if (is_object($hp->xajaxF))
{

$hp->xajaxF->extend($config['design']);



}


?>
