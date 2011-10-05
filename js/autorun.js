function lightBoxCheck()
{
  $('.lbOn[lbSet!="true"]').lightBoxX2().attr('lbSet', 'true');
  
  //window.setTimeout('lightBoxCheck()', 2000); 
}


$(document).ready(function() {
  
  $('a[rel*=lightbox]').lightBox();
  lightBoxCheck();

  
});