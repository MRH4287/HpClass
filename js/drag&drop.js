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
  //var content = document.createTextNode(value);
  //newNode.appendChild(content)
  newNode.innerHTML = value;
  
  
  holder.appendChild(newNode);
  
  new Draggable(key,{revert: true});

}