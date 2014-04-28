var isupdate = false;

function setvote(to, id)
{
  var input = document.getElementById('vote'+id);
  input.value=to;
}

function postvote(id)
{
  var input = document.getElementById('vote'+id);
  var vote= input.value;

  xajax_vote(id, vote);
}

function setdate(day, month, year)
{
  var dayo       = document.getElementById("day");
  var montho     = document.getElementById("month");
  var yearo      = document.getElementById("year");
  var houro      = document.getElementById("hour");
  var mino       = document.getElementById("min");
  var aktdate    = document.getElementById("aktdate");

  dayo.value = day;
  montho.value = month;
  yearo.value = year;
  houro.value = '0';
  mino.value = '0';

  aktdate.innerHTML = day+'.'+month+'.'+year;

  checkvote(isupdate);

}

function checkvote(update)
{
  var titel      = document.getElementById("titel");
  var answer1    = document.getElementById("antwort1");
  var answer2    = document.getElementById("antwort2");
  var day        = document.getElementById("day");
  var month      = document.getElementById("month");
  var year       = document.getElementById("year");
  var hour       = document.getElementById("hour");
  var min        = document.getElementById("min");

  var titelt     = titel.value;
  var answer1t   = answer1.value;
  var answer2t   = answer2.value;
  var dayt       = day.value;
  var montht     = month.value;
  var yeart      = year.value;
  var hourt      = hour.value;
  var mint       = min.value;

  isupdate=update;

  xajax_checkvote(titelt, answer1t, answer2t, dayt, montht, yeart, hourt, mint, update);

}

// Forum
function vote_effect(voteid, star)
{
  var count = document.getElementById("vote"+voteid+"_count").value;


  var i = 1;
  for (i=1;i<star+1;i++)
  {
    var sternchen = document.getElementById("vote"+voteid+"_"+i);

    var stars = "";
    if (star != 0)
    {
      stars = star;
    }

    sternchen.className="forum_star"+stars;



  }
  for (i=star+1;i<6;i++)
  {
    var sternchen = document.getElementById("vote"+voteid+"_"+i);
    if (i > count)
    {
      sternchen.className="forum_star";
    } else
    {
      stars = count;
      sternchen.className="forum_star"+stars;
    }

  }
}


function addanswer()
{

    var el    = $("#answers tbody").children().last();
    var count    = $('#answers tr').length + 1;
    
    el.after('<tr id="voteAnswNr'+count+'"><td><input type="text" name="antwort[]" size="50" /></td></tr>');
    $('#voteAnswNr'+count).css('opacity', '0').css('background-color', 'yellow').animate({'opacity': '1', 'background-color': 'transparent'});
}