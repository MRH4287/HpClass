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
     <td width="">
   
 <?php
 
 foreach ($widgets as $key=>$value) {
 	
 	echo "<div id=$key>$value</div>";
 	
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




 



<div id="info">
</div>
