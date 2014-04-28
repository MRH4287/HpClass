var widget_config_text = "";


function widget_add_in(object)
{

var value = object.value;
var name = object.id;

var add = "";
if (widget_config_text != "")
{
add += "<!--!>";
}
add += name+"<!=!>"+value;

widget_config_text+=add;

}


function widget_save(temp)
{
xajax_savewidgetconfig(temp, widget_config_text);  
widget_config_text = "";
}