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


if (isset ($get['del']) and ($right[$level]['delvideo'] ))
{
$abfrage = "DELETE FROM ".$dbpr�fix."videos WHERE `ID` = ".$get['del'];
$ergebnis = $hp->mysqlquery($abfrage);
    
}

?>

<table align="center"><tbody><tr>
<?php
$abfrage = "SELECT * FROM ".$dbpr�fix."videos";
$ergebnis = $hp->mysqlquery($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());
while($row = mysql_fetch_object($ergebnis))
   {
   ?>
   <td class="BildRahmen2" align="center" bgcolor="#dae3e9"">
   <center><?php echo "$row->Titel"?> (<?php echo "$row->user"?>) <?php if ($right[$level]['delvideo']) { echo "<a href=index.php?site=videos&del=$row->ID>L�schen</a>"; } ?></center><br>
   <center><?php echo "$row->HTML"?>
   </td></tr><tr>
   <?php
  }

?>
</tr></table>
