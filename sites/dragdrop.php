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
  <script src="js/scriptaculous/prototype.js"></script>
<script src=".js/scriptaculous/scriptaculous.js"></script>  
<script src="js/drag&drop.js"></script> 


<style>
 .drag
 {
 border: 1px outset;
 width: 100px;
/* background-color: red;  */
 }
 

 
  .dropp
 {

  height: 100%;
  width: 100%;
 
 
 border: 2px rgb(0,0,0) dashed;
 
background-color: rgb(255,255,204);
 }
    .hclass
 {
/* width: 150px;
 height: 150px;  */
 background-color: orange;
 }
 
 

</style>





    <table width="500" height="50" border="1">
     <tr>
     <td width="25%">
               
        
        <div id="bewegmich2" class="drag">Beweg mich2 :) </div>
        
        <div id="bewegmich3" class="drag">
             <table width="20" height="30" border="1">
             <tr>
              <td width="50%">
              Hallo
              </td>

               <td>
     
               Ihr
               </td>
     
              </tr>
              </table>
        
        </div>
        
     </td>

     <td width="25%">
     
     <div id="dropp1" class="dropp"><div id="bewegmich" class="drag">MoveMe</div></div>
     </td>
             <td width="25%">
     
     <div id="dropp2" class="dropp"></div>
     </td>
           <td width="25%">
     
     <div id="dropp3" class="dropp"></div>
     </td>
     
     </tr>
     
     <tr>
     <td colspan="4">clear</td>
     </tr>
     
     
    </table>
 

       <br>
 
  <div id="dropp4" class="dropp"></div>
 <?php
 
 
 ?>
    

    <script>
   new Draggable('bewegmich',{revert: true}); 
   new Draggable('bewegmich2',{revert: true}); 
   new Draggable('bewegmich3',{revert: true}); 
    
Droppables.add('dropp1',{onDrop: function(dragged, dropped) {

xajax_dragevent(dropped.id, dragged.id, getinfo(dragged), getinfo($(dropped)));

 }, hoverclass: 'hclass'});
 
 
 
 Droppables.add('dropp2',{onDrop: function(dragged, dropped) {


xajax_dragevent(dropped.id, dragged.id, getinfo(dragged), getinfo($(dropped)));

 }, hoverclass: 'hclass'});
 
 
 Droppables.add('dropp3',{onDrop: function(dragged, dropped) {

xajax_dragevent(dropped.id, dragged.id, getinfo(dragged), getinfo($(dropped)));

 }, hoverclass: 'hclass'}); 
 
  Droppables.add('dropp4',{onDrop: function(dragged, dropped) {

xajax_dragevent(dropped.id, dragged.id, getinfo(dragged), getinfo($(dropped)));

 }, hoverclass: 'hclass'}); 

</script>



 



<div id="info">
</div>
