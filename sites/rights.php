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
$o_right = $hp->right;


//Variablen



//Abfrage des POST ergebnisses
if (isset($post['sub']))
{
  foreach ($right as $key=>$value) 
  {
    foreach ($value as $key2=>$value2)
    {
      $right[$key][$key2] = false;
    }	
  }
  $fp = $hp->fp;
  

    
  $levels = $post['levelcount'];
  $levels = explode("&-&", $levels);

  
  foreach ($levels as $keyh=>$valueh) 
  {
  	
     
  
    if (isset($post['right'.$valueh]))
    {
      $temp = $post['right'.$valueh];
    } else
    {
      $temp = array();
    }
           
    
    foreach ($temp as $key=>$value) 
    {

        //echo "set r1 $value to true!!<br>";
        $right[$valueh][$value]=true;
      	
    }	
  	
  }


  $o_right->save($right); 
  $o_right->load();
  //Endsaverights
  
  
}
// Ende auswertung Post
// Abfrage des aktuellen zustandes...


$site = new siteTemplate($hp);
$site->load("rights");




$levels = $o_right->getlevels();


$fp = $hp->fp;


$data = array();


$registedRights = $o_right->getregisted();
foreach ($registedRights as $k => $name)
{
  // right, level, description, ok, cat
  $data[$o_right->cat($name)][] = $name;     
} 
  

$content = "";
foreach ($levels as $egal=>$aktlevel) 
{
   
  $content_c = "";
  foreach ($data as $cat=>$array)
  {
  
    $content_r = "";
    foreach ($array as $k=>$name)
    {
      $tdata = array (
        "name" => $name,
        "level" => $aktlevel,
        "description" => $o_right->desc($name),
        "checked" => $o_right->is($name, $aktlevel) ? "true" : "false"      
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
   "level" => $aktlevel,
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
    
?>