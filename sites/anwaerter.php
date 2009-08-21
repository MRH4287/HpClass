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


?>

<p align="center"><font size="4"><?php echo $lang->word('bewerbungen')?>:</font></p>
<p align="center">&nbsp;</p>
<?php
if (!isset ($_SESSION['username'])) {
$error->error($lang->word('noright2'),"3");
exit;
}

if ((!$right[$level]['useragree']) and ((!$right[$level]['userdisagree'])))
{
$error->error($lang->word('noright2'),"2");
exit;
}

if (isset($get['register']))
if (!$right[$level]['useragree'])
{
$error->error($lang->word('kberechacanwae'),"2");

} else

{
$abfrage = "SELECT * FROM ".$dbpräfix."anwaerter WHERE `user`= '".$get['register']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
   $user="$row->user";
   $passwort123="$row->pass";
   $name="$row->name";
   $nachname="$row->nachname";

   $datum="$row->datum";
   }

   
$eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['register']."'";
$eintragen1 = $hp->mysqlquery($eintrag);
echo mysql_error()."<br>";


$eintrag = "INSERT INTO `".$dbpräfix."user`
(user, pass, name, nachname, datum, level)
VALUES
('$user', '$passwort123', '$name', '$nachname', '$datum', '1')";
$eintragen2 = $hp->mysqlquery($eintrag);
if ($eintragen1 == true and $eintragen2 == true)
{
echo"<br>".$lang->word('postok')."<br>";
} else { $error->error("Fehler: ".mysql_error(),"2"); }
}

if (isset($get['delet']))
if (!$right[$level]['userdisagree'])
{
$error->error($lang->word('kberechdeanwae'),"2");

} else

{
$eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['delet']."'";
$eintragen = $hp->mysqlquery($eintrag);
if ($eintragen == true){
echo"<br>".$lang->word('delok')."<br>";
} else { $error->error("Fehler: ".mysql_error(),"2"); }
}


$abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
?>

<table border="1" width="671">
  <tr>
    <td width="176"><?php echo $lang->word('username')?></td>
    <td width="198"><?php echo $lang->word('name')?>:</td>
    <td width="186"><?php echo $lang->word('nachname')?></td>
    <td width="373"><?php echo $lang->word('datum')?>:</td>
    <td width="967"><?php echo $lang->word('bewebungstext')?>:</td>
    <td width="152">&nbsp;</td>
  </tr>
  <tr>
    <td width="176"><?php echo "$row->user"?></td>
    <td width="198"><?php echo "$row->name"?></td>
    <td width="186"><?php echo "$row->nachname"?></td>
    <td width="373"><?php echo "$row->datum"?></td>
    <td width="967"><?php echo "$row->text"?></td>
    <td width="152"><a href=index.php?site=anwaerter&register=<?php echo "$row->user"?>><?php echo $lang->word('yes')?></a>/<a href=index.php?site=anwaerter&delet=<?php echo "$row->user"?>><?php echo $lang->word('no')?></a></td>
  </tr>
</table>
<?php
} ?>
