<?php
// Site Config:
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$subpages = $hp->subpages;
$fp = $hp->fp;



$site = new siteTemplate($hp);
$site->load("subpage");


//Rufe eine Liste aller Templates auf:

$templates = $subpages->getAllTemplates();
print_r($templates);










// Navigation - Tmp:
/*

?>

<script>


function createNaviDragBox(holderT, key, value)
{
  var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.setAttribute("class", "drag");
  newNode.innerHTML = value;
  
  
  holder.appendChild(newNode);
  
  new Draggable(key,{revert: true});

}



function createNaviDropBox(holderT, key)
{

 var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.setAttribute('class', "dropp");
  //newNode.innerHTML = value;
  
  holder.appendChild(newNode);
  
Droppables.add(key,{onDrop: function(drag, base) {

NaviDropEvent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});
 
}


function NaviDropEvent(dropper, drag, infon, info_droppable)
{
//killElement(drag);
//xajax_dragevent(dropper, drag, infon, info_droppable);
//xajax_reloadWidgets();

}



</script>


<?php


 if (isset($get['page']))
 {
 
  $subpage = $subpages->loadSite($get['page']);
  
  if ($subpage == false)
  {
  echo "Die gewünschte Seite existiert nicht!";
  } else
   {
   
   echo $subpage;
   
   }
  
 } else
 
 {







$edit = isset($get['edit']);




$text = $subpages->printNavigation();
$elements = explode("</el>", $text);

$scripts = array();

foreach ($elements as $key=>$value) {
	
	 $infos = explode("<!>", $value);
	 
	 $depth = $infos[0];
	 $name = $infos[1];
	 $site = $infos[2];
	 $dynamic = $infos[3];
	 
	 
	 $output = ""; 
	 for ($i = 0; $i < $depth; $i++) {
	 
    $output .= "-";  	
  }
  
  if ($edit)
  {
  $output .= "<div id=\"holder_navi_$name\"></div>";
  $scripts[] = "createNaviDragBox('holder_navi_$name', 'navi_$name', '$name');";
  
  
  } else
  {
  
    $output .= "<a href=\"?site=$site\">$name</a><br>";

  
  }
	 
	echo $output; 
	 
}

 echo "<br><br><br>";
 echo "<div id=\"holder_navi_drop_box\"></div>";

 echo "<script>";
 echo implode("\n", $scripts);
 echo "createNaviDropBox('holder_navi_drop_box', 'navi_drop')";
 echo "</script>";

 }



 */
?>