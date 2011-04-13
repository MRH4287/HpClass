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
  foreach ($right as $key=>$value) 
  {
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
  foreach ($levels as $keyh=>$valueh) 
  {
  	
  
  
    if (isset($post['right'.$valueh]))
    {
      $temp = $post['right'.$valueh];
    } else
    {
      $temp = array();
    }
    
    $temp['lol']= "1337";
    
    foreach ($temp as $key=>$value) 
    {
      if ($value != "1337")
      {
        //echo "set r1 $value to true!!<br>";
        $right[$valueh][$value]="true";
      }	
    }	
  	
  }

  //Saverights
  
  $sql = "TRUNCATE `".$dbpräfix."right`;";
  $hp->mysqlquery($sql);
  echo mysql_error();
  
  
  foreach ($right as $aklevel=>$temp) 
  {  	
    foreach ($temp as $key=>$value) 
    {
    
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
  
  foreach ($levels as $key=>$value) 
  {
  
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
    
    
    echo "$sql<br>";
  	
  }
  echo "<hr>";

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


$site = new siteTemplate($hp);
$site->load("rights");



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


$content = "";
foreach ($levels as $egal=>$aktlevel) 
{
  if ($aktlevel == "0")
  {
    continue;
  }
 
  $data = array();

  $abfrage = "SELECT * FROM `".$dbpräfix."right` WHERE `level` = '$aktlevel' ORDER BY `cat` DESC;";
  //$ergebnis = SQLexec($abfrage, "index");
  $ergebnisss = $hp->mysqlquery($abfrage);
  while($row = mysql_fetch_object($ergebnisss))
  {
    // right, level, description, ok, cat
    $data[$row->cat][] = $row;     
  } 
      
  $content_c = "";
  foreach ($data as $cat=>$array)
  {
  
    $content_r = "";
    foreach ($array as $k=>$row)
    {
      $tdata = array (
        "name" => $row->right,
        "description" => $row->description,
        "checked" => $row->ok      
      );
    
      $content_r .= $site->getNode("Right", $tdata);
    }

    
     $tdata = array(
      "name" => $cat,
      "Rights" => $content_r    
    );    
    
    $content_c .= $site->getNode("Categorie", $tdata);
  
  }
  
  $tdata = array(
   "level" => $row->level,
   "Categories" => $content_c
  );
  

  
  $content .= $site->getNode("LevelBox", $tdata);


}   

$data = array(
  "Levels" => $content,
  "levelcount" => implode("&-&", $levels)
);


$site->setArray($data);


$site->display();
    


if ($_SESSION['username'] == "mrh") { ?>

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

<?php 
}



?>