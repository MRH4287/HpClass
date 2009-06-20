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


//Variablen



//Abfrage des POST ergebnisses
if (isset($post['sub']))
{
foreach ($right as $key=>$value) {
foreach ($value as $key2=>$value2)
{
$right[$key][$key2] = "false";
}	
}

$abfrage = "SELECT * FROM `".$dbpräfix."right`";
//$ergebnis = SQLexec($abfrage, "index");
$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
   $descriptions["$row->right"] = "$row->description";
   }
   
$levels = $post['levelcount'];
$levels = explode("&-&", $levels);
$fp = $hp->fp;
foreach ($levels as $keyh=>$valueh) {
	



$temp = $post['right'.$valueh];


$temp['lol']= "1337";

foreach ($temp as $key=>$value) {
if ($value != "1337")
{
//echo "set r1 $value to true!!<br>";
$right[$valueh][$value]="true";
}	
}	
	
}
/*
$right1['lol']= "1337";
$right2['lol']= "1337";
$right3['lol']= "1337";
$right1 = $post['right1'];
$right2 = $post['right2'];
$right3 = $post['right3'];
$right1['lol']= "1337";
$right2['lol']= "1337";
$right3['lol']= "1337";
*/




//Saverights

$sql = "TRUNCATE `".$dbpräfix."right`;";
$hp->mysqlquery($sql);
echo mysql_error();


foreach ($right as $aklevel=>$temp) {
	



foreach ($temp as $key=>$value) {

if ($value == "true")
{
$value = "true";
} else
{
$value = "false";
}
//echo "r1 $key -> $value => $descriptions[$key]<br>";
	$sql = "INSERT INTO `".$dbpräfix."right` (
`ID` ,
`level` ,
`right`,
`ok`,
`description`
)
VALUES (
NULL , '$aklevel', '$key', '$value', '$descriptions[$key]'
);";
$hp->mysqlquery($sql);
echo mysql_error();
}

}


//Endsaverights


//echo $right1;
} elseif (isset($post['sub2'])) 
{
$rightname=$post['right'];
$descript=$post['descript'];

$levels = array();
$sql = "SELECT * FROM `$dbpräfix"."right`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
if (!in_array($row->level, $levels))
{
$levels[] = $row->level;
}
}

foreach ($levels as $key=>$value) {

$sql = "INSERT INTO `".$dbpräfix."right` (

`level` ,
`right` ,
`ok` ,
`description`
)
VALUES (
'$value', '$rightname', 'false', '$descript'
);";
$hp->mysqlquery($sql);
echo mysql_error();

	
}


} elseif (isset($post['sub3']))
{
$newl=$post['newl'];
$oldl=$post['oldl'];

$sql = "SELECT * FROM `$dbpräfix"."right` WHERE `level` = '$oldl'";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{



/*
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `level` int(2) NOT NULL,
  `right` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `ok` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(200) COLLATE latin1_general_ci NOT NULL,
*/

$sql = "INSERT INTO `".$dbpräfix."right` (

`level` ,
`right` ,
`ok` ,
`description`
)
VALUES (
'$newl', '$row->right', '$row->ok', '$row->description'
);";
$hp->mysqlquery($sql);


}

}
// Ende auswertung Post
// Abfrage des aktuellen zustandes...


?>


  <center>
  <form method="POST" action="index.php?site=rights">

  <table border="1" width="677" height="7" bordercolor="#4E6F81">
    <tr>
      <td width="40" height="22" bgcolor="#5A8196">Level</td>
      <td width="757" height="25" bgcolor="#5A8196">Name</td>
      <td width="58" height="21" bgcolor="#5A8196">
        <p align="center">Ok?</p>
      </td>
    </tr>
<?


$sql = "SELECT * FROM `".$dbpräfix."right`";
$erg = $hp->mysqlquery($sql);
$levels = array();
while ($row = mysql_fetch_object($erg))
{
if (!in_array($row->level, $levels))
{
$levels[] = $row->level;
}
}
$fp = $hp->fp;

foreach ($levels as $egal=>$aktlevel) {
	


$abfrage = "SELECT * FROM `".$dbpräfix."right` WHERE `level` = '$aktlevel'";
//$ergebnis = SQLexec($abfrage, "index");
$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {
if ("$row->right" != "userdelet")
      {
          
   echo '<tr>
      <td width="40" height="31" align="center" bgcolor="#5A8196">'."$row->level".'</td>
      <td width="757" height="28">'."$row->description".'</td>
      ';
   echo '
      <td width="58" height="32" align="center"><input type="checkbox" name="right'."$row->level".'[]" value="'."$row->right".'" ';
      
      if ("$row->ok" == "true")
      {
      echo "checked=\"true\"";
      }
      
      echo"></td>
    </tr>
   ";
   }
  } 
  // Abtrennung
  echo '        <tr>
      <td width="40" height="1" align="center" bgcolor="#5A8196">&nbsp;</td>
      <td width="757" height="1" bgcolor="#5A8196"></td>
      <td width="58" height="1" align="center" bgcolor="#5A8196"></td>
    </tr>
    '; 
}   


    

  ?>  
  <input type="hidden" name="levelcount" value="<?php
  echo implode("&-&", $levels);
   ?>">
        <tr>
      <td width="855" height="31" align="center" bgcolor="#5A8196" colspan="3">
                  <input type="submit" value="Abschicken" name="sub">
        
      </td>
    </tr>   


  </table>
  </center>
  <? if ($_SESSION['username'] == "mrh") { ?>
</form>
  <form method="POST" action="index.php?site=rights">
<table border="1" width="160">
  <tr>
    <td>right:</td>
    <td><input type="text" name="right" size="31"></td>
  </tr>
  <tr>
    <td>description:</td>
    <td><input type="text" name="descript" size="31"></td>
  </tr>
</table>

<input type="submit" value="Abschicken" name="sub2">

</form>
</form>
  <form method="POST" action="index.php?site=rights">
<table border="1" width="160">
  <tr>
    <td>New Level:</td>
    <td><input type="text" name="newl" size="31"></td>
  </tr>
  <tr>
    <td>Copy from:</td>
    <td><input type="text" name="oldl" size="31"></td>
  </tr>
</table>

<input type="submit" value="Abschicken" name="sub3">

</form>

<? 
}



?>
