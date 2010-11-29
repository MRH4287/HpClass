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


$widgets = $hp->widgets->getwidgets();

?>







    <table width="500" height="50" border="1">
     <tr>
     <td width="" id="widgetContainer">
   
 <?php
 
 foreach ($widgets as $key=>$value) {
 	
 	echo "<div id=$key>$value</div>";
 	echo "<br>";
 }
 
 
 ?>
    </td>
    </tr>
    </table>
 <?php
 echo '<script>';
 foreach ($widgets as $key=>$value) {
 	
 	echo "new Draggable('$key',{revert: true}); ";
 	
 }

  echo '</script>';
 
 ?>

  <script>
  
  function reloadWidgets()
  {
  
  var holder = document.getElementById("widgetContainer");
  
  <?php
  foreach ($widgets as $key=>$value) {
 	?>
 	
 	 //Löschen der Elemente
 	 var element = document.getElementById('<?php echo $key; ?>');
 	 if (element != null)
 	 {
 	 var papa = element.parentNode;
   if (papa) papa.removeChild(element);
   }

   /*
  //Elemente neu erstellen
  var newNode = document.createElement('div');
  newNode.setAttribute('id', "<?php echo $key; ?>");
  var content = document.createTextNode("");
  newNode.appendChild(content)
  
  
  holder.appendChild(newNode);
  
  new Draggable('<?php echo $key; ?>',{revert: true});
    */
 	<?php
 } 
 ?>
  

  

  
  
  }
  
  
  </script>


 



<div id="info">
</div>
