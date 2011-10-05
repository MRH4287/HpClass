$(document).ready(function() {
  
  $('a[rel*=lightbox]').lightBox();
  $('.lbOn[lbSet!="true"]').lightBoxX2().attr('lbSet', 'true');

  
});