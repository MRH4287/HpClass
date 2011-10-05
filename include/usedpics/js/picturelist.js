var picturelist_data = new Array ();
var picturelist_offset = 0;
var picturelist_count = 3;
var picturelist_on = false;


function picturelist_setdata(e)
{
 picturelist_data = e;
}


function picturelist_goleft()
{

if (picturelist_on)
{
setTimeout("picturelist_goleft()", 500);
picturelist_left();

}

}

function picturelist_goright()
{

if (picturelist_on)
{
setTimeout("picturelist_goright()", 500);
picturelist_right();

}

}


function picturelist_left()
{
picturelist_offset--;
if (picturelist_offset < 0)
{
picturelist_offset = 0;
}

picturelist_print();

}

function picturelist_right()
{
picturelist_offset++;

var max = picturelist_data.length - picturelist_count;

if (picturelist_offset > max)
{
picturelist_offset = max;
}

picturelist_print();

}


function picturelist_print()
{
  
  var content = "";
  
  var to = picturelist_offset+picturelist_count;
  if (to > picturelist_data.length)
  {
    to = picturelist_data.length;
  }
  
  
  for (i = picturelist_offset; i < to; i++)
  {
  
    if (picturelist_data[i])
    {
      content += picturelist_data[i];
    }
  }
 $("#picturelist_holder").html(content);
}