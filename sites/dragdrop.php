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
   
 
    </td>
    </tr>
    </table>


 <table width="150" height="50">

<tr>
<td>
<div id="widget_DelWidgets" class="droppDel" valign="middle">Löschen</div>

</td>
</tr>
</table>

<script>

Droppables.add('widget_DelWidgets',{onDrop: function(drag, base) {

widgetDeletDropEvent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});
 

</script>


<div id="info">
</div>
