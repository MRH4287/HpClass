/**
 * Modified Version by MRH
 * for the usage for the HPClass CMS
 * I dont't claim any rights for this code.
 *
 * jQuery lightBox plugin
 * This jQuery plugin was inspired and based on Lightbox 2 by Lokesh Dhakar (http://www.huddletogether.com/projects/lightbox2/)
 * and adapted to me for use like a plugin from jQuery.
 * @name jquery-lightbox-0.5.js
 * @author Leandro Vieira Pinho - http://leandrovieira.com
 * @version 0.5
 * @date April 11, 2008
 * @category jQuery plugin
 * @copyright (c) 2008 Leandro Vieira Pinho (leandrovieira.com)
 * @license CCAttribution-ShareAlike 2.5 Brazil - http://creativecommons.org/licenses/by-sa/2.5/br/deed.en_US
 * @example Visit http://leandrovieira.com/projects/jquery/lightbox/ for more informations about this jQuery plugin
 */

(function($) {

  $.fn.lightBoxX2 = function(settings)
  {
		settings = jQuery.extend({
				overlayBgColor: 		'#000',		// (string) Background color to overlay; inform a hexadecimal value like: #RRGGBB. Where RR, GG, and BB are the hexadecimal values for the red, green, and blue values of the color.
				overlayOpacity:			0.8,		// (integer) Opacity value to overlay; inform: 0.X. Where X are number from 0 to 9
				containerBorderSize:	10,			// (integer) If you adjust the padding in the CSS for the container, #lightbox-container-image-box, you will need to update this value
				containerResizeSpeed:	400,		// (integer) Specify the resize duration of container image. These number are miliseconds. 400 is default.
				keyToClose:				  'c',		// (string) (c = close) Letter to close the jQuery lightBox interface. Beyond this letter, the letter X and the SCAPE key is used to.	    
				clicked:			      0,
				imageLoading:			'images/loading.gif',		// (string) Path and the name of the loading icon
				imageBtnClose:		'images/closelabel.gif'		// (string) Path and the name of the close btn

    
    }, settings);
    

    var jQueryMatchedObj = this; // This, in this context, refer to jQuery object
		var present = false; 	// Is an older Window still open?


    function _initialize()
		{
       
			// Close old Windows:
			//		_finish(true);   
			
			_start(this,jQueryMatchedObj); // This, in this context, refer to object (link) which the user have clicked
			return false; // Avoid the browser following the link
		}


 		/**
  	 * Start the jQuery lightBox plugin
  	 *
  	 * @param object objClicked The object (link) whick the user have clicked
  	 * @param object jQueryMatchedObj The jQuery object with all elements matched
  	 */
  	function _start(objClicked,jQueryMatchedObj) 
    {
			
			// Check for older Windows
			this.present = ($("#jquery-lightboxX2").length != 0);

			// Hide some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
			$('embed, object, select').css({ 'visibility' : 'hidden' });



			if (!this.present)
			{
				// Clean up everything, just to be shure ;)
				_finish(true);
				
				// Call the function to create the markup structure; style some elements; assign events in some elements.
				_set_interface();
				
			} else
			{
				// Some Cleanup
				$('#lightboxX2-content').css('overflow','hidden').css('position','absolute')
				.css('z-index', '-1');
			
				$('#lightboxX2-container-image-data-box').slideUp('fast');

			}

  		settings.clicked = objClicked;
  	
  		// Call the function that prepares image exibition
  		_set_image_to_view();
  	}
  
  
  
    /**
     * Prepares image exibition; doing a image큦 preloader to calculate it큦 size
     *
     */
    function _set_image_to_view() 
    { // show the loading
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
                          
          _resize_container_image_box(loaded.width(),loaded.height());
          
					
					// Load tinyMCE here to support Text editing within the popups.
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
        		theme_advanced_resizing : true
      	 });
      	 
				 
				 // Enable Lightbox Popups within the Popup
      	 $('.lbOn[lbSet!="true"]').lightBoxX2().attr('lbSet', 'true');
         
        },
				error: function()
				{
					var data = '<div class="#lightboxX2-content"><img src="./images/alert2.gif" alt="Error" /> Error while loading document</div>';
					
					$('#lightboxX2-content').html(data);
         
          var loaded = $('#lightboxX2-content');

                
          _resize_container_image_box(loaded.width(),loaded.height());	
				}
      });
    };


		function _set_interface() 
    {
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
  		$('#jquery-overlay').click(function() {       // ,#jquery-lightboxX2
  			_finish();									
  		});
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
  		
  		// Deaktiviert, wegen den Eingaben ...
		  //_enable_keyboard_navigation();
	   }



    /**
		 * Enable a support to keyboard navigation
		 *
		 */
		function _enable_keyboard_navigation() {
			$(document).keydown(function(objEvent) {
				_keyboard_action(objEvent);
			});
		}
		/**
		 * Disable the support to keyboard navigation
		 *
		 */
		function _disable_keyboard_navigation() {
			$(document).unbind();
		}
		/**
		 * Perform the keyboard actions
		 *
		 */
		function _keyboard_action(objEvent) {
			// To ie
			if ( objEvent == null ) {
				keycode = event.keyCode;
				escapeKey = 27;
			// To Mozilla
			} else {
				keycode = objEvent.keyCode;
				escapeKey = objEvent.DOM_VK_ESCAPE;
			}
			// Get the key in lower case form
			key = String.fromCharCode(keycode).toLowerCase();
			// Verify the keys to close the ligthBox
			if ( ( key == settings.keyToClose ) || ( key == 'x' ) || ( keycode == escapeKey ) ) {
				_finish();
			}
			
		}



		/**
		 * Perfomance an effect in the image container resizing it
		 *
		 * @param integer intImageWidth The image큦 width that will be showed
		 * @param integer intImageHeight The image큦 height that will be showed
		 */
		function _resize_container_image_box(intImageWidth,intImageHeight)
		{
		
			// Get current width and height
			var intCurrentWidth = $('#lightboxX2-container-image-box').width();
			var intCurrentHeight = $('#lightboxX2-container-image-box').height();
			
			
			// Get the width and height of the selected image plus the padding
			var intWidth = (intImageWidth + (settings.containerBorderSize * 2)); // Plus the image큦 width and the left and right padding value
			var intHeight = (intImageHeight + (settings.containerBorderSize * 2)); // Plus the image큦 height and the left and right padding value
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
		
		
		
		
			/**
			 * Displays the Image after loading is complete
			 *
			 */
			function _show_image()
			{
				$('#lightboxX2-loading').hide();
				
				$('#lightboxX2-content').css('overflow','visible').css('position','block')
				.css('z-index', '0');
			
				$('#lightboxX2-container-image-data-box').slideDown('fast');
			
			};



    /**
		 * Remove jQuery lightBox plugin HTML markup
		 *
		 */
		function _finish(second)
		{
		  second = second || false;
		
			$('#jquery-lightboxX2').remove();
			if (!second)
			{    
  			$('#jquery-overlay').fadeOut(function() { $('#jquery-overlay').remove(); });
  			// Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
			} else
			{
        	$('#jquery-overlay').remove();
      }
      $('embed, object, select').css({ 'visibility' : 'visible' });
		}


  	/**
		 / THIRD FUNCTION
		 * getPageSize() by quirksmode.com
		 *
		 * @return Array Return an array with page width, height and window width, height
		 */
		function ___getPageSize()
		{
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
		function ___getPageScroll()
		{
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
		 function ___pause(ms)
		 {
			var date = new Date(); 
			curDate = null;
			do { var curDate = new Date(); }
			while ( curDate - date < ms);
		 };
		 
		// Return the jQuery object for chaining. The unbind method is used to avoid click conflict when the plugin is called more than once
		return this.unbind('click').click(_initialize);
	};
})(jQuery);