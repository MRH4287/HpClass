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
$xajaxF = $hp->xajaxF;
$fp = $hp->fp;






if (isset($get["new"]))
{
  $site = new siteTemplate($hp);
  $site->load("subpage");


  //Rufe eine Liste aller Templates auf:

  $templates = $subpages->getAllTemplates();
  
  $template = "";
  $f = true;
  $ft = "";
  foreach ($templates as $ID=>$name)
  {
    $data = array(
      "selected" => ($f)? "selected" : "",
      "value" => $name,
      "ID" => $ID
    
    );
    
    $template .= $site->getNode("ComboBoxOption", $data);
    
    // Ermitteln des ersten Templates:
    if ($f)
    {
      $ft = $ID;
    }
    
    $f = false;
  }
  
  $data = array(
    "Templates" =>$template 
  );
  
  $site->set("TemplateSelector", $site->getNode("TemplateSelector", $data));

  // Lade die Daten für das erste Template:
  $xajaxF->open("xajax_subpageTemplateChange('$ft');");
  
  $site->set("Content", "<img src=images/loading.gif alt=\"Loading\" />");  
  $site->display();

} elseif (isset($post["new"]))
{

  $site = new siteTemplate($hp);
  $site->load("info");
  
  $subpageName = $post["subpage_name"];
  if ($subpages->getSite($subpageName) == false)
  {
    $templateName = $post["template_name"];
    $tpC = $subpages->getTemplateConfig($templateName);
  
    if (($tpC != false) && (isset($tpC["template"])))
    {
     // Abfragen der Daten:
      
      $data = "";
     foreach ($tpC["template"] as $name=>$type)
     {
       if (isset($post["tp_$name"]))
       {
         $value = $post["tp_$name"];
         
         if ($data != "")
         {
           $data .= "<!--!>";
         }
         
         $data .= "$name<!=!>$value";
       }
     }
     
     //Speichern der Unterseite:
     $sql = "INSERT INTO `$dbpräfix"."subpages` (`name`, `content`, `template`, `created`) VALUES ('$subpageName', '$data', '$templateName', '".time()."');";
     $erg = $hp->mysqlquery($sql);
     
     $site->set("info", "Unterseite erfolgreich erstellt!<br><a href=?site=subpage>zurück</a>");
     
     
  
    } else
    {
      $site->set("info", "Das gewählte Template ist nicht verfügbar!<br><a href=?site=subpage>zurück</a>");
    }
    
  } else
  {
    $site->set("info", "Es exsistiert bereits eine Seite mit diesen Namen!<br><a href=?site=subpage>zurück</a>");
  }
  
  $site->display();
}







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