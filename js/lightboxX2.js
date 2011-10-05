(function($) {

  $.fn.lightBoxX2 = function(settings)
  {
    settings = jQuery.extend({
      // Configuration related to overlay
			overlayBgColor: 		'#000',		// (string) Background color to overlay; inform a hexadecimal value like: #RRGGBB. Where RR, GG, and BB are the hexadecimal values for the red, green, and blue values of the color.
			overlayOpacity:			0.8,		// (integer) Opacity value to overlay; inform: 0.X. Where X are number from 0 to 9
			containerBorderSize:	10,			// (integer) If you adjust the padding in the CSS for the container, #lightbox-container-image-box, you will need to update this value
			containerResizeSpeed:	400,		// (integer) Specify the resize duration of container image. These number are miliseconds. 400 is default.

	    
	    clicked:			      0,
	    
	    imageLoading:			'images/loading.gif',		// (string) Path and the name of the loading icon
			imageBtnClose:		'images/closelabel.gif'		// (string) Path and the name of the close btn

    
    }, settings);
    

    var jQueryMatchedObj = this; // This, in this context, refer to jQuery object

    function _initialize() {
      console.log('init'); 
                
			_start(this,jQueryMatchedObj); // This, in this context, refer to object (link) which the user have clicked
			return false; // Avoid the browser following the link
			
		}


 		/**
	 * Start the jQuery lightBox plugin
	 *
	 * @param object objClicked The object (link) whick the user have clicked
	 * @param object jQueryMatchedObj The jQuery object with all elements matched
	 */
	function _start(objClicked,jQueryMatchedObj) {
		// Hime some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
		$('embed, object, select').css({ 'visibility' : 'hidden' });
		// Call the function to create the markup structure; style some elements; assign events in some elements.
		
    console.log('start');
    console.log(objClicked);
    
    _set_interface();
		
		settings.clicked = objClicked;
	
		// Call the function that prepares image exibition
		_set_image_to_view();
	}



  /**
   * Prepares image exibition; doing a image�s preloader to calculate it�s size
   *
   */
  function _set_image_to_view() { // show the loading
  	// Show the loading
		$('#lightboxX2-loading').show();
  	

  	$('#lightboxX2-container-image-data-box').hide();
  	
  	var url = settings.clicked.getAttribute('href');
  	 	
  	// Load Content over AJAX
  	$.ajax({
      url: url,
      success: function(data) 
      {
        $('#lightboxX2-content').html(data);
       
        var loaded = $('#lightboxX2-content');
        
        console.log(loaded);
        console.log('W:'+loaded.width()+' H:'+loaded.height());
              
        _resize_container_image_box(loaded.width(),loaded.height());
        
        $('#lightboxX2-container-image-data-box').slideDown('fast');
        
        tinyMCE.init(
        {
      		mode : "textareas",
      		theme : "advanced",
      		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    		  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    		  theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,search,replace,bullist,numlist,outdent,indent,blockquote,undo,redo,link,unlink,anchor,image,cleanup,help,code,insertdate,inserttime,preview,forecolor,backcolor",
    		  theme_advanced_buttons3 : "tablecontrols,hr,removeformat,visualaid,sub,sup,charmap,emotions,iespell,media,advhr,ltr,rtl,fullscreen",
    		  theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,styleprops,cite,abbr,acronym,del,ins,attribs,visualchars,nonbreaking,template,pagebreak",
      		theme_advanced_toolbar_location : "top",
      		theme_advanced_toolbar_align : "left",
      		theme_advanced_statusbar_location : "bottom",
      		theme_advanced_resizing : true,
    	 });
        
        
      }
    });
  };


		function _set_interface() {
		// Apply the HTML markup into body tag
		$('body').append('<div id="jquery-overlay"></div><div id="jquery-lightboxX2"><div id="lightboxX2-container-image-box"><div id="lightboxX2-container-image"><div id="lightboxX2-content"></div><div id="lightboxX2-loading"><a href="#" id="lightboxX2-loading-link"><img src="' + settings.imageLoading + '" /></a></div></div></div><div id="lightboxX2-container-image-data-box"><div id="lightboxX2-container-image-data"><div id="lightboxX2-secNav"><a href="#" id="lightboxX2-secNav-btnClose"><img src="' + settings.imageBtnClose + '"></a></div></div></div></div>');	
		// Get page sizes
		var arrPageSizes = ___getPageSize();
		// Style overlay and show it
		$('#jquery-overlay').css({
			backgroundColor:	settings.overlayBgColor,
			opacity:			settings.overlayOpacity,
			width:				arrPageSizes[0],
			height:				arrPageSizes[1]
		}).fadeIn();
		// Get page scroll
		var arrPageScroll = ___getPageScroll();
		// Calculate top and left offset for the jquery-lightbox div object and show it
		$('#jquery-lightboxX2').css({
			top:	arrPageScroll[1] + (arrPageSizes[3] / 10),
			left:	arrPageScroll[0]
		}).show();
	
  	// Assigning click events in elements to close overlay
	//	$('#jquery-overlay,#jquery-lightboxX2').click(function() {
	//		_finish();									
	//	});
		// Assign the _finish function to lightbox-loading-link and lightbox-secNav-btnClose objects
		$('#lightboxX2-loading-link,#lightboxX2-secNav-btnClose').click(function() {
			_finish();
			return false;
		});
		// If window was resized, calculate the new overlay dimensions
		$(window).resize(function() {
			// Get page sizes
			var arrPageSizes = ___getPageSize();
			// Style overlay and show it
			$('#jquery-overlay').css({
				width:		arrPageSizes[0],
				height:		arrPageSizes[1]
			});
			// Get page scroll
			var arrPageScroll = ___getPageScroll();
			// Calculate top and left offset for the jquery-lightbox div object and show it
			$('#jquery-lightboxX2').css({
				top:	arrPageScroll[1] + (arrPageSizes[3] / 10),
				left:	arrPageScroll[0]
			});
		});
	}


			/**
		 * Perfomance an effect in the image container resizing it
		 *
		 * @param integer intImageWidth The image�s width that will be showed
		 * @param integer intImageHeight The image�s height that will be showed
		 */
		function _resize_container_image_box(intImageWidth,intImageHeight) {
		                                           
		  console.log('resize - ');
		  console.log(intImageWidth+' - '+intImageHeight);
		
			// Get current width and height
			var intCurrentWidth = $('#lightboxX2-container-image-box').width();
			var intCurrentHeight = $('#lightboxX2-container-image-box').height();
			
			console.log('current:');
			console.log(intCurrentWidth+':'+intCurrentHeight);
			
			// Get the width and height of the selected image plus the padding
			var intWidth = (intImageWidth + (settings.containerBorderSize * 2)); // Plus the image�s width and the left and right padding value
			var intHeight = (intImageHeight + (settings.containerBorderSize * 2)); // Plus the image�s height and the left and right padding value
			// Diferences
			var intDiffW = intCurrentWidth - intWidth;
			var intDiffH = intCurrentHeight - intHeight;
			// Perfomance the effect
			$('#lightboxX2-container-image-box').animate({ width: intWidth, height: intHeight },settings.containerResizeSpeed,function() { _show_image(); });
			if ( ( intDiffW == 0 ) && ( intDiffH == 0 ) ) {
				if ( $.browser.msie ) {
					___pause(250);
				} else {
					___pause(100);	
				}
			} 
			$('#lightboxX2-container-image-data-box').css({ width: intImageWidth });
			};
		
			function _show_image() {
			$('#lightboxX2-loading').hide();
			
			$('#lightboxX2-content').css('overflow','visible').css('position','block')
      .css('z-index', '0');
		
			
		};



    /**
		 * Remove jQuery lightBox plugin HTML markup
		 *
		 */
		function _finish() {
			$('#jquery-lightboxX2').remove();
			$('#jquery-overlay').fadeOut(function() { $('#jquery-overlay').remove(); });
			// Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
			$('embed, object, select').css({ 'visibility' : 'visible' });
		}


  		/**
		 / THIRD FUNCTION
		 * getPageSize() by quirksmode.com
		 *
		 * @return Array Return an array with page width, height and window width, height
		 */
		function ___getPageSize() {
			var xScroll, yScroll;
			if (window.innerHeight && window.scrollMaxY) {	
				xScroll = window.innerWidth + window.scrollMaxX;
				yScroll = window.innerHeight + window.scrollMaxY;
			} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
			} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
			}
			var windowWidth, windowHeight;
			if (self.innerHeight) {	// all except Explorer
				if(document.documentElement.clientWidth){
					windowWidth = document.documentElement.clientWidth; 
				} else {
					windowWidth = self.innerWidth;
				}
				windowHeight = self.innerHeight;
			} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			} else if (document.body) { // other Explorers
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}	
			// for small pages with total height less then height of the viewport
			if(yScroll < windowHeight){
				pageHeight = windowHeight;
			} else { 
				pageHeight = yScroll;
			}
			// for small pages with total width less then width of the viewport
			if(xScroll < windowWidth){	
				pageWidth = xScroll;		
			} else {
				pageWidth = windowWidth;
			}
			arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight);
			return arrayPageSize;
		};
		/**
		 / THIRD FUNCTION
		 * getPageScroll() by quirksmode.com
		 *
		 * @return Array Return an array with x,y page scroll values.
		 */
		function ___getPageScroll() {
			var xScroll, yScroll;
			if (self.pageYOffset) {
				yScroll = self.pageYOffset;
				xScroll = self.pageXOffset;
			} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
				yScroll = document.documentElement.scrollTop;
				xScroll = document.documentElement.scrollLeft;
			} else if (document.body) {// all other Explorers
				yScroll = document.body.scrollTop;
				xScroll = document.body.scrollLeft;	
			}
			arrayPageScroll = new Array(xScroll,yScroll);
			return arrayPageScroll;
		};
		 /**
		  * Stop the code execution from a escified time in milisecond
		  *
		  */
		 function ___pause(ms) {
			var date = new Date(); 
			curDate = null;
			do { var curDate = new Date(); }
			while ( curDate - date < ms);
		 };
		 
		// Return the jQuery object for chaining. The unbind method is used to avoid click conflict when the plugin is called more than once
		return this.unbind('click').click(_initialize);
	};
})(jQuery);

/*-----------------------------------------------------------------------------------------------*/

/*
// Onload, make all links that need to trigger a lightbox active
function initialize(){
	addLightboxMarkup();
	lbox = document.getElementsByClassName('lbOn');
	for(i = 0; i < lbox.length; i++) {
		valid = new lightboxX2(lbox[i]);
	}
}

var lb;
// Add in markup necessary to make this work. Basically two divs:
// Overlay holds the shadow
// Lightbox is the centered square that the content is put into.
function addLightboxMarkup() {
	bod 				= document.getElementsByTagName('body')[0];
	over 		  	=  document.createElement('div');
	

	
	over.id		= 'overlayX2';
	lb					= document.createElement('div');
	lb.id				= 'lightboxX2';
	lb.className 	= 'loading';
		//	lb.style.opacity = '0.9';
//lb.valign = "middle";
//lb.align= "center";
		lb.style.position = "absolute";
		lb.style.background = "white";  // #ffd
		lb.style.border = "1px solid #777";
		lb.style.padding = '10px';
		lb.style.cursor = 'pointer';
		lb.style.color = '#555';
		lb.style.top = '0px';
		lb.style.left = '0px';


    
		lb.style.fontSize = '11px';
	lb.innerHTML	= '<div id="lbLoadMessage">' +
						  '<p>Loading</p>' +
						  '</div>';
	bod.appendChild(over);
	bod.appendChild(lb);
	
resolution();
}



function resolution()
{

//var div = document.getElementsByTagName('body')[0].childNodes[document.getElementsByTagName('body')[0].childNodes.length-1];
// var  divs = document.getElementsByTagName('div');
// for(i = 0; i < divs.length; i++) {
// 
// document.write(divs[i].id);

//alert(lb);
		var width = lb.offsetWidth;
		var height = lb.offsetHeight;

		if (width >= document.body.clientWidth) 
			{
			lb.style.left = '200px';
			//document.getElementById('fade').style.width = width+'px';
			}
		else
			{
			var spacewidth = document.body.clientWidth - width;
			var leftwidth = spacewidth / 2;
			lb.style.left = leftwidth + 'px';
			}
  		if ((height >= document.body.clientHeight) ||  ((document.body.clientHeight) >= height*4))
  		{
  			lb.style.top = '500px';
  			
  		}	else
  		{
  			var spaceheight = document.body.clientHeight - height;
  			var topheight = spaceheight / 2;
  			topheight = topheight + 200;
  			lb.style.top = topheight+'px';
  		}	

}

*/

