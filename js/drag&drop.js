function getinfo(element)
{
  var info = "";

  info += "id:="+element.attr('id');
  info += "<!--!>";
  info += "innerHTML:="+element.html();
  info += "<!--!>";
  info += "className:="+element.attr('class');

  return info;

}


function createWidgetBox(holderT, key, value)
{

  var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.setAttribute("class", "drag");
  newNode.innerHTML = value;
  
  
  holder.appendChild(newNode);
  
  
  $("#"+key).draggable({
			revert: true
		});


}



function createDropBox(holderT, key)
{

 var holder = document.getElementById(holderT);

  var newNode = document.createElement('div');
  newNode.setAttribute('id', key);
  newNode.setAttribute('class', "dropp");
  //newNode.innerHTML = value;
  
  holder.appendChild(newNode);
  
  $( "#"+key ).droppable(
  {
      hoverClass: "hclass",
			drop: function( event, ui ) {
      widgetDropEvent($(this).attr('id'), ui.draggable.attr('id'), getinfo(ui.draggable), getinfo($(this)));
			}
	});
 
}

function setAsDropBox(id)
{

  $( "#"+id ).droppable(
    {
        hoverClass: "hclass",
  			drop: function( event, ui ) {
  			 widgetDropEvent($(this).attr("id"), ui.draggable.attr("id"), getinfo(ui.draggable), getinfo($(this)));
  			}
  	});
}


function widgetDropEvent(dropper, drag, infon, info_droppable)
{
  killElement(drag);


  xajax_dragevent(dropper, drag, infon, info_droppable);
  //xajax_reloadWidgets();

}


function widgetDeletDropEvent(dropper, drag, infon, info_droppable)
{
  killElement(drag);
  xajax_widget_del(dropper, drag, infon, info_droppable);
  xajax_reloadWidgets();

}


function killElement(key)
{
  $('#'+key).remove();
}
