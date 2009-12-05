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

// $widgets = $hp->widgets->getwidgets();
$widgets = array("test" => "2");

?>
<center><h2>Widget Steuerung:</h2></center>
<br>


    <table width="500" height="50" border="1">
     <tr>
     <td>
    
      <?php
      
        foreach ($widgets as $key=>$value) {
        	
        	echo "<div id=$key>$value</div><br>";
        	
        }
      
      ?>  


        <table width="100%" height="50">

<tr>
<td>
<div id="t" class="dropp">1</div>

<script>

Droppables.add('t',{onDrop: function(drag, base) {

//xajax_test(drag.id);
//drag.hide();

xajax_dragevent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});
 

</script>

</td>
</tr>
</table>    

        
     </td>

     </tr>
     
     <tr>
     <td colspan="4">clear</td>
     </tr>
     
     
    </table>

     <br>
    

    <script>
    <?php
    
    foreach ($widgets as $key=>$value)
    {
     echo "new Draggable('$key',{revert: true});"; 
    }
   ?>
    
</script>



 



<div id="info">
</div>
