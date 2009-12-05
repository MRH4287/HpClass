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


?>







    <table width="500" height="50" border="1">
     <tr>
     <td width="">
               
       
       
       
 <?php
 
 
 ?>
    </td>
    </tr>
    </table>

    <script>
   new Draggable('bewegmich',{revert: true}); 
   new Draggable('bewegmich2',{revert: true}); 
   new Draggable('bewegmich3',{revert: true}); 
    

</script>



 



<div id="info">
</div>
