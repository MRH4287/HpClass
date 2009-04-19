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
$kat = $post['kat'];


if (isset ($post['text']))
{
$sql = "INSERT INTO `".$dbpräfix."config` (

`name` ,
`ok` ,
`description`,
`typ`,
`kat`
)
VALUES (
'$name', 'Change Me', '$descript', 'string', '$kat'
);";
} else
{
$sql = "INSERT INTO `".$dbpräfix."config` (

`name` ,
`ok` ,
`description`,
`typ`,
`kat`
)
VALUES (
'$name', 'false', '$descript', 'bool', '$kat'
);";
}
$hp->mysqlquery($sql);
echo mysql_error();


}
// Ende auswertung Post
// Abfrage des aktuellen zustandes...

$cfg = array();
$abfrage = "SELECT * FROM `".$dbpräfix."config` ORDER BY `".$dbpräfix."config`.`name` ASC";
//$ergebnis = SQLexec($abfrage, "index");
$ergebnisss = $hp->mysqlquery($abfrage);
echo mysql_error();
while($row = mysql_fetch_array($ergebnisss))
   {
   $cfg[$row['kat']][] = $row;
   }

?>


  <center>
  <form method="POST" action="index.php?site=config">

  <table border="1" width="677" height="7" bordercolor="#4E6F81">
  <?
  foreach ($cfg as $key=>$row2) {
  	
  if ($key == "")
  {
  $key = "Standard";
  }
  ?>
    <tr>
      
      <td width="757" height="25" bgcolor="#5A8196"><?=$key?></td>
      <td width="80" height="21" bgcolor="#5A8196">
        <p align="center"></p>
      </td>
    </tr>
<?


foreach ($row2 as $key12=>$row) {
	

   echo '<tr>
      
      <td width="757" height="28">'.$row['description'].'</td>
      ';
      if ($row['typ'] == "string")
 {
 $value = $row['ok'];
 $value = str_replace("\"", "'", $value);
$value = str_replace("<", "&lt;", $value);
 
 echo '<td width="80" height="32" align="center"><input type="text" name="text['.$row['name'].']" value="'."$value".'" '; 
 }
 else

       {
      echo '
      <td width="80" height="32" align="center"><input type="checkbox" name="config[]" value="'.$row['name'].'" ';
}
 
      if ($row['ok'] == "true")
      {
      echo "checked=\"true\"";
      }
      
      echo "></td>
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
    <td>Katigorie:</td>
    <td><input type="text" name="kat" size="31"></td>
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
