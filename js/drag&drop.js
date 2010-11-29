function getinfo(element)
{
var info = "";

info += "id:="+element.id;
info += "<!--!>";
info += "innerHTML:="+element.innerHTML;
info += "<!--!>";
info += "className:="+element.className;

return info;

}


function createWidgetBox(holderT, key, value)
{
  var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.innerHTML = value;
  
  
  holder.appendChild(newNode);
  
  new Draggable(key,{revert: true});

}



function createDropBox(holderT, key)
{

 var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.setAttribute('class', "dropp");
  //newNode.innerHTML = value;
  
  
  holder.appendChild(newNode);
  

Droppables.add(key,{onDrop: function(drag, base) {

xajax_dragevent(base.id, drag.id, getinfo(drag), getinfo(base));

 }, hoverclass: 'hclass'});
 

}


function widgetDropEvent(dropper, drag, infon, info_droppable)
{
xajax_dragevent(dropper, drag, infon, info_droppable);
xajax_reloadWidgets();

}


function widgetDeletDropEvent(dropper, drag, infon, info_droppable)
{
xajax_widget_del(dropper, drag, infon, info_droppable);
xajax_reloadWidgets();

}


function killElement(key)
{
var element = document.getElementById(key);
 	 if (element != null)
 	 {
 	 var papa = element.parentNode;
   if (papa) papa.removeChild(element);
   }
}