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
   


$right1['lol']= "1337";
$right2['lol']= "1337";
$right3['lol']= "1337";
$right1 = $post['right1'];
$right2 = $post['right2'];
$right3 = $post['right3'];
$right1['lol']= "1337";
$right2['lol']= "1337";
$right3['lol']= "1337";


foreach ($right1 as $key=>$value) {
if ($value != "1337")
{
//echo "set r1 $value to true!!<br>";
$right['1'][$value]="true";
}	
}
foreach ($right2 as $key=>$value) {
if ($value != "1337")
{
//echo "set r2 $value to true!!<br>";
$right['2'][$value]="true";
}		
}
foreach ($right3 as $key=>$value) {
if ($value != "1337")
{
//echo "set r3 $value to true!!<br>";
$right['3'][$value]="true";
}		
}


//Saverights

$sql = "TRUNCATE `".$dbpräfix."right`;";
$hp->mysqlquery($sql);
echo mysql_error();


$temp = $right['1'];

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
NULL , '1', '$key', '$value', '$descriptions[$key]'
);";
$hp->mysqlquery($sql);
echo mysql_error();
}


$temp = $right['2'];

foreach ($temp as $key=>$value) {

if ($value == "true")
{
$value = "true";
} else
{
$value = "false";
}
//echo "r2 $key -> $value => $descriptions[$key]<br>";
	$sql = "INSERT INTO `".$dbpräfix."right` (
`ID` ,
`level` ,
`right`,
`ok`,
`description`
)
VALUES (
NULL , '2', '$key', '$value', '$descriptions[$key]'
);";
$hp->mysqlquery($sql);
echo mysql_error();
}

$temp = $right['3'];

foreach ($temp as $key=>$value) {
if ($value == "true")
{
$value = "true";
} else
{
$value = "false";
}
//echo "r3 $key -> $value => $descriptions[$key]<br>";
	$sql = "INSERT INTO `".$dbpräfix."right` (
`ID` ,
`level` ,
`right`,
`ok`,
`description`
)
VALUES (
NULL , '3', '$key', '$value', '$descriptions[$key]'
);";
$hp->mysqlquery($sql);
echo mysql_error();
}

//Endsaverights


//echo $right1;
} elseif (isset($post['sub2'])) 
{
$rightname=$post['right'];
$descript=$post['descript'];

$sql = "INSERT INTO `".$dbpräfix."right` (

`level` ,
`right` ,
`ok` ,
`description`
)
VALUES (
'1', '$rightname', 'false', '$descript'
);";
$hp->mysqlquery($sql);
echo mysql_error();

$sql = "INSERT INTO `".$dbpräfix."right` (

`level` ,
`right` ,
`ok` ,
`description`
)
VALUES (
'2', '$rightname', 'false', '$descript'
);";
$hp->mysqlquery($sql);
echo mysql_error();

$sql = "INSERT INTO `".$dbpräfix."right` (

`level` ,
`right` ,
`ok` ,
`description`
)
VALUES (
'3', '$rightname', 'false', '$descript'
);";
$hp->mysqlquery($sql);
echo mysql_error();

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
// Level 1

$abfrage = "SELECT * FROM `".$dbpräfix."right` WHERE `level` = '1'";
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
      if ("$row->right" != "userdelet")
      { echo '
      <td width="58" height="32" align="center"><input type="checkbox" name="right'."$row->level".'[]" value="'."$row->right".'" ';
      } else
      {
      echo '<td width="58" height="32" align="center" bgcolor="#FF0000">X</td>';
      }
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
   
   
 // Level 2  
 
 $abfrage = "SELECT * FROM `".$dbpräfix."right` WHERE `level` = '2'";
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
      if ("$row->right" != "userdelet")
      { echo '
      <td width="58" height="32" align="center"><input type="checkbox" name="right'."$row->level".'[]" value="'."$row->right".'" ';
      } else
      {
      echo '<td width="58" height="32" align="center" bgcolor="#FF0000">X</td>';
      }
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
  // Level 3  
   $abfrage = "SELECT * FROM `".$dbpräfix."right` WHERE `level` = '3'";
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
      if ("$row->right" != "userdelet")
      { echo '
      <td width="58" height="32" align="center"><input type="checkbox" name="right'."$row->level".'[]" value="'."$row->right".'" ';
      } else
      {
      echo '<td width="58" height="32" align="center" bgcolor="#FF0000">X</td>';
      }
      if ("$row->ok" == "true")
      {
      echo "checked=\"true\"";
      }
      
      echo"></td>
    </tr>
   ";
   
   }
   }
    

  ?>  
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

<? 
}



?>
