function getinfo(element)
{
info = "";

info += "id:="+element.id;
info += "<!--!>";
info += "innerHTML:="+element.innerHTML;
info += "<!--!>";
info += "className:="+element.className;

return info;

}