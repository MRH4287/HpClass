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


if (!$right[$level]["manage_subpage"])
{
  $error->error("Sie haben nicht die benötigte Berechtigung!", "1");

} elseif (isset($get["new"]))
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
  
  $site->set("site", "new");
  $site->set("subpage_name", "");
  $site->set("edit", "false");
  $site->set("ID", "");
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
  
} elseif (isset($get["edit"]))
{
  $siteData = $subpages->getSite($get["edit"]);
  $tempData = $subpages->getTemplateData($get["edit"]);
  if ($siteData != false)
  {
      $site = new siteTemplate($hp);
      $site->load("subpage");
      
      $site->set("TemplateSelector", $siteData["template"]);
      $site->set("site", "edit");
      $site->set("edit", "true");
      $site->set("subpage_name", $siteData['name']);
      $site->set("ID", $siteData["ID"]);
      
      
      // Seitenüberprüfung:
      $tpC = $subpages->getTemplateConfig($siteData["template"]);
        
      $content = "";
      foreach($tpC["template"] as $ID=>$type)
      {
       switch($type)
       {
        case "textbox":
        
          $data = array(
           "name" => $ID,
            "value" => $tempData[$ID],
            "ID" => "tp_".$ID       
          );
        
          $content .= $site->getNode("TextBox", $data);
        
         
        break;
      
      
        case "textarea":
       
          $data = array(
           "name" => $ID,
           "value" => $tempData[$ID],
           "ID" => "tp_".$ID       
          );
       
          $content .= $site->getNode("TextArea", $data);
       
        
        break;
      
      
        case "checkbox":
       
         $data = array(
            "name" => $ID,
            "checked" => $tempData[$ID],
            "ID" => "tp_".$ID       
          );
       
         $content .= $site->getNode("CheckBox", $data);
       
        
        break; 
      
        case "combobox":
       
          $options = "";
          if (isset($tpC["data"][$ID]) and is_array($tpC["data"][$ID]))
          {
           foreach ($tpC["data"][$ID] as $k=>$value)
           {
             $data = array(
             
             "ID" => $value,
             "value" => $value,
             "selected" => ($value == $tempData[$ID])? "selected" : ""            
             );
            
             $options .= $site->getNode("ComboBoxOption", $data);
            
            }
          }
          $data = array(
           "name" => $ID,
           "ID" => "tp_".$ID,
           "Options" => $options       
          );
       
          $content .= $site->getNode("ComboBox", $data);
       
        
        break;  
    
       }
     }   
     $site->set("Content", $content); 
        
     $site->display();
  
  } else
  {
      $site = new siteTemplate($hp);
      $site->load("info");
      $site->set("info", "Die gewünschte Unterseite ist nicht verfügbar!<br><a href=?site=subpage>zurück</a>");
      $site->display();
  }
  
} elseif (isset($post["edit"]))
{
  $site = new siteTemplate($hp);
  $site->load("info");
  
  $ID = $post["ID"];
  $templateData = $subpages->getSite($ID);
  if ($templateData != false)
  {
    $templateName = $templateData["template"];
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
     $sql = "UPDATE `$dbpräfix"."subpages` SET `content` = '$data' WHERE `ID` = '$ID';";
     $erg = $hp->mysqlquery($sql);
     
     $site->set("info", "Unterseite erfolgreich modifiziert!<br><a href=?site=subpage>zurück</a>");
     
     
  
    } else
    {
      $site->set("info", "Das gewählte Template ist nicht verfügbar!<br><a href=?site=subpage>zurück</a>");
    }
    
  } else
  {
    $site->set("info", "Diese Seite exsistiert nicht!<br><a href=?site=subpage>zurück</a>");
  }
  
  $site->display();
  

} elseif (isset($get["list"]))
{
  //List_Element
  $site = new siteTemplate($hp);
  $site->load("subpage");
  
  $sql = "SELECT * FROM `$dbpräfix"."subpages`;";
  $erg = $hp->mysqlquery($sql);
  
  $templates = $subpages->getAllTemplates();
  
  $elements = "";
  while ($row = mysql_fetch_object($erg))
  {
    $data = array(
      "name" => $row->name,
      "template" => $templates[$row->template],
      "ID" => $row->ID    
    );  
  
   $elements .= $site->getNode("List_Element", $data);
   
  }
  
  $site->set("Elements", $elements);
  
  
  $site->display("List"); 




} elseif (isset($get["del"]))
{
  // Ist die Bestätigung vorhanden?
  if (isset($get["ok"]))
  {
    $sql = "DELETE FROM `$dbpräfix"."subpages` WHERE `ID` = '".$get["del"]."';";
    $erg = $hp->mysqlquery($sql);
    
    $site = new siteTemplate($hp);
    $site->load("info");
    $site->set("info", "Unterseite \"".$get["del"]."\" erfolgreich gelöscht!<br><a href=?site=subpage&list>zurück</a>");
    $site->display();
  
  } else
  {
    $site = new siteTemplate($hp);
    $site->load("info");
    $site->set("info", "Möchten Sie die Unterseite \"".$get["del"]."\" wirklich löschen?<br><a href=?site=subpage&del=".$get["del"]."&ok>Ja</a><br><a href=?site=subpage&list>zurück</a>");
    $site->display();
  
  
  }


} else
{
  $site = new siteTemplate($hp);
  $site->load("subpage");
  $site->display("Menu");      

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