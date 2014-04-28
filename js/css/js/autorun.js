var l10n = new Localization();

$(document).ready(function() {
  
  // Lightbox
  $('a[rel*=lightbox]').lightBox();
  
  // Lightbox X2
  $('.lbOn[lbSet!="true"]').lightBoxX2().attr('lbSet', 'true');

  //Tooltip
  $('*[tip]').each(function()
  {
     var t = $(this); 
     var c = t.attr('tip');
     t.tipTip({content:c,
     keepAlive: t.attr('tipSticky')
     });
  
  });

  
});