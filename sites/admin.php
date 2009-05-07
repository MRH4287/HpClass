<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();

$ok = true;
// Prüfen der Zugriffsberechtigung
if (!isset($_SESSION['username']) and !isset($post))
{
echo $lang->word('noright')."<br>".$lang->word("login")."<br>";
echo $lang->word('wrotenews')."<br>";
echo $lang->word('wrotenews2');
$ok = false;
}

if (!$right[$level]['adminsite'])
{

echo $lang->word('noright2');
$ok = false;
}

if ($ok)
{


//Beginn der Haupseite



if (!$right[$level]['newsedit'])
{


} else
{


 ?>
<p align="left">&nbsp;</p>
<p align="left"><b><a href="index.php?site=news&delet=true"><?=$lang->word('editnews')?></a></b></p>
<?
} // Wegen Rechte



if ($right[$level]['useragree'])
{

?>
<p align="left"><a href=index.php?site=anwaerter><?=$lang->word('anwerter')?><? $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());
    $number=0;
while($row = mysql_fetch_object($ergebnis))
   { $number=$number+1; }
   echo " ($number)";
    ?></a></p> <br>
    <?  }
    
    if ($right[$level]['upload'])
{ ?>
    
    <p align="left"><a href=index.php?site=upload><?=$lang->word('upload')?></a></p>
    
    <?
    }
 if ($right[$level]['moduladmin'])
{ ?>
    
    <p align="left"><a href=index.php?site=modulmanager><?=$lang->word('modulman')?></a></p>
    
    <?
    }   
    
    
 } // OK     
     ?>
