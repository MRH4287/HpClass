/*
Created By: Chris Campbell
Website: http://particletree.com
Date: 2/1/2006

Inspired by the lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
*/

/*-------------------------------GLOBAL VARIABLES------------------------------------*/

var detect = navigator.userAgent.toLowerCase();
var OS,browser,version,total,thestring;
/*-----------------------------------------------------------------------------------------------*/

//Browser detect script origionally created by Peter Paul Koch at http://www.quirksmode.org/

function getBrowserInfo() {
	if (checkIt('konqueror')) {
		browser = "Konqueror";
		OS = "Linux";
	}
	else if (checkIt('safari')) browser 	= "Safari"
	else if (checkIt('omniweb')) browser 	= "OmniWeb"
	else if (checkIt('opera')) browser 		= "Opera"
	else if (checkIt('webtv')) browser 		= "WebTV";
	else if (checkIt('icab')) browser 		= "iCab"
	else if (checkIt('msie')) browser 		= "Internet Explorer"
	else if (!checkIt('compatible')) {
		browser = "Netscape Navigator"
		version = detect.charAt(8);
	}
	else browser = "An unknown browser";

	if (!version) version = detect.charAt(place + thestring.length);

	if (!OS) {
		if (checkIt('linux')) OS 		= "Linux";
		else if (checkIt('x11')) OS 	= "Unix";
		else if (checkIt('mac')) OS 	= "Mac"
		else if (checkIt('win')) OS 	= "Windows"
		else OS 								= "an unknown operating system";
	}
}

function checkIt(string) {
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}

/*-----------------------------------------------------------------------------------------------*/

$(document).ready(function() {
  initialize();
  getBrowserInfo();
}
//Event.observe(window, 'unload', Event.unloadCache, false);

var lightboxX2 = Class.create();

lightboxX2.prototype = {

	yPos : 0,
	xPos : 0,

	initialize: function(ctrl) {
		this.content = ctrl.href;
		Event.observe(ctrl, 'click', this.activate.bindAsEventListener(this), false);
		ctrl.onclick = function(){return false;};
		
		        if (LightboxOptions.resizeSpeed > 10) LightboxOptions.resizeSpeed = 10;
        if (LightboxOptions.resizeSpeed < 1)  LightboxOptions.resizeSpeed = 1;

	    this.resizeDuration = LightboxOptions.animate ? ((11 - LightboxOptions.resizeSpeed) * 0.15) : 0;
	    this.overlayDuration = LightboxOptions.animate ? 0.2 : 0;  // shadow fade in/out duration
		
	},
	
	// Turn everything on - mainly the IE fixes
	activate: function(){
		if (browser == 'Internet Explorer'){
			this.getScroll();
			this.prepareIE('100%', 'hidden');
			this.setScroll(0,0);
			this.hideSelects('hidden');
		}
		this.displayLightbox("block");
	},
	
	// Ie requires height to 100% and overflow hidden or else you can scroll down past the lightbox
	prepareIE: function(height, overflow){
		bod = document.getElementsByTagName('body')[0];
		bod.style.height = height;
		bod.style.overflow = overflow;
  
		htm = document.getElementsByTagName('html')[0];
		htm.style.height = height;
		htm.style.overflow = overflow; 
	},
	
	// In IE, select elements hover on top of the lightbox
	hideSelects: function(visibility){
		selects = document.getElementsByTagName('select');
		for(i = 0; i < selects.length; i++) {
			selects[i].style.visibility = visibility;
		}
	},
	
	// Taken from lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
	getScroll: function(){
		if (self.pageYOffset) {
			this.yPos = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			this.yPos = document.documentElement.scrollTop; 
		} else if (document.body) {
			this.yPos = document.body.scrollTop;
		}
	},
	
	setScroll: function(x, y){
		window.scrollTo(x, y); 
	},
	
	displayLightbox: function(display){
		$('overlayX2').style.display = display;
		$('lightboxX2').style.display = display;
		if(display != 'none') this.loadInfo();
	},
	
	// Begin Ajax request based off of the href of the clicked linked
	loadInfo: function() {
	
	  $('lightboxX2').innerHTML = "<img src='images/loading.gif' alt='Wird geladen...' />";
		var myAjax = new Ajax.Request(
        this.content,
        {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
		);
		
	},
	
	// Display Ajax response
	processInfo: function(response){
		info = "<div id='lbContent'>" + response.responseText + "</div>";
		//new Insertion.Before($('lbLoadMessage'), info)
		$('lightboxX2').innerHTML=info;
		$('lightboxX2').className = "done";	
		this.actions();			
	},
	
	// Search through new links within the lightbox, and attach click event
	actions: function(){
		lbActions = document.getElementsByClassName('lbAction');

		for(i = 0; i < lbActions.length; i++) {
			Event.observe(lbActions[i], 'click', this[lbActions[i].rel].bindAsEventListener(this), false);
			lbActions[i].onclick = function(){return false;};
		}

    lbox = document.getElementsByClassName('lbOn3');
    for(i = 0; i < lbox.length; i++) {
    		valid = new lightboxX2(lbox[i]);
    }

      tinyMCE.init({
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

    resolution();
	},
	
	// Example of creating your own functionality once lightbox is initiated
	insert: function(e){
	   link = Event.element(e).parentNode;
	   Element.remove($('lbContent'));
	 
	   var myAjax = new Ajax.Request(
			  link.href,
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
	   );
	 
	},
	
	// Example of creating your own functionality once lightbox is initiated
	deactivate: function(){
		Element.remove($('lbContent'));
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		this.displayLightbox("none");
	}
	

}

/*-----------------------------------------------------------------------------------------------*/

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



