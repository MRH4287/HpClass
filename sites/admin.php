<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();
$lang = $hp->getlangclass();
$error = $hp->geterror();

$ok = true;
// Pr�fen der Zugriffsberechtigung
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
<p align="left"><b><a href="index.php?site=news&delet=true"><?php echo $lang->word('editnews')?></a></b></p>
<?php
} // Wegen Rechte



if ($right[$level]['useragree'])
{

?>
<p align="left"><a href=index.php?site=anwaerter><?php echo $lang->word('anwerter')?><?php $abfrage = "SELECT * FROM ".$dbpr�fix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());
    $number=0;
while($row = mysql_fetch_object($ergebnis))
   { $number=$number+1; }
   echo " ($number)";
    ?></a></p> <br>
    <?php  }
    
    if ($right[$level]['upload'])
{ ?>
    
    <p align="left"><a href=index.php?site=upload><?php echo $lang->word('upload')?></a></p>
    
    <?php
    }
 if ($right[$level]['moduladmin'])
{ ?>
    
    <p align="left"><a href=index.php?site=modulmanager><?php echo $lang->word('modulman')?></a></p>
    
    <?php
    }   
    
    
 } // OK     
     ?>
