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
$config = $hp->getconfig();


//Variablen



//Abfrage des POST ergebnisses
if (isset($post['sub']))
{

foreach ($config as $key=>$value)
{

$config[$key] = "false";

}	


$config2= $post['config'];
$config3 = $post['text'];

if (is_array($config2))
{
foreach ($config2 as $key=>$value) {
//echo "set r1 $value to true!!<br>";
$config[$value]="true";
}
}

if (is_array($config3))
{
foreach ($config3 as $key=>$value) {
//echo "set r1 $value to true!!<br>";
$config[$key]=$value;
}
}


// Class Regelung 
// 4.2b
$hp->setconfig_array($config);
$hp->applyconfig();



} elseif (isset($post['sub2'])) 
{
$name=$post['name'];
$descript=$post['descript'];


if (isset ($post['text']))
{
$sql = "INSERT INTO `".$dbpräfix."config` (

`name` ,
`ok` ,
`description`,
`typ`
)
VALUES (
'$name', 'Change Me', '$descript', 'string'
);";
} else
{
$sql = "INSERT INTO `".$dbpräfix."config` (

`name` ,
`ok` ,
`description`,
`typ`
)
VALUES (
'$name', 'false', '$descript', 'bool'
);";
}
$hp->mysqlquery($sql);
echo mysql_error();


}
// Ende auswertung Post
// Abfrage des aktuellen zustandes...


?>


  <center>
  <form method="POST" action="index.php?site=config">

  <table border="1" width="677" height="7" bordercolor="#4E6F81">
    <tr>
      
      <td width="757" height="25" bgcolor="#5A8196">Name</td>
      <td width="80" height="21" bgcolor="#5A8196">
        <p align="center">Ok?</p>
      </td>
    </tr>
<?
// Level 1

$abfrage = "SELECT * FROM `".$dbpräfix."config` ORDER BY `".$dbpräfix."config`.`ID` ASC";
//$ergebnis = SQLexec($abfrage, "index");
$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_object($ergebnisss))
   {

   echo '<tr>
      
      <td width="757" height="28">'."$row->description".'</td>
      ';
      if ($row->typ == "string")
 {
 $value = $row->ok;
 $value = str_replace("\"", "'", $value);
$value = str_replace("<", "&lt;", $value);
 
 echo '<td width="80" height="32" align="center"><input type="text" name="text['."$row->name".']" value="'."$value".'" '; 
 }
 else

       {
      echo '
      <td width="80" height="32" align="center"><input type="checkbox" name="config[]" value="'."$row->name".'" ';
}
 
      if ("$row->ok" == "true")
      {
      echo "checked=\"true\"";
      }
      
      echo "></td>
    </tr>
   ";
   
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
  <form method="POST" action="index.php?site=config">
<table border="1" width="160">
  <tr>
    <td>Config:</td>
    <td><input type="text" name="name" size="31"></td>
  </tr>
  <tr>
    <td>description:</td>
    <td><input type="text" name="descript" size="31"></td>
  </tr>
    <tr>
    <td>String?:</td>
    <td><input type="checkbox" name="text"></td>
  </tr>
</table>

<input type="submit" value="Abschicken" name="sub2">

</form>

<? 
}



?>
