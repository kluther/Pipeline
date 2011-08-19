/*
 * Feedback - jQuery plugin 1.2.0
 * Copyright (c) 2009 - 2010 Duncan J. Kenzie
 *  This version published 2010-09-24
 *  Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 */
 
(function() { 

 jQuery.fn.feedback = function(msgtext, options) {
     // define defaults and override with options, if available
     // by extending the default settings, we don't modify the argument
	 var opts  = jQuery.extend({
	     type: "info",  					   
	     infoIcon: "ui-icon-info",
	     infoClass: "ui-state-highlight ui-corner-all",
	     errorIcon:   "ui-icon-alert", 
	     errorClass:  "ui-state-error ui-corner-all", 
	     duration: 2000,
	     offsetX : 0, 
	     offsetY : 0, 
             feedbackClass: "ui-feedback"
	  }, options);

 var divclass= opts.feedbackClass;  // Class for container div - error or info . 
 var iconclass="";  // Icon class- alert or info. 
 
   if (!msgtext) var msgtext = "ERROR: No message to display."; 
	
   return this.each(function(){
   		// handle to the element(s):  
  		var me = $(this); 
  	  	if (opts.type == "error") 
		{ 
			divclass= divclass + " " + opts.errorClass ;
			iconclass=opts.errorIcon; 
		}
		else 
		{
			divclass= divclass + " " + opts.infoClass; 
			iconclass=opts.infoIcon;
		}
		
		// if the icon class starts with "ui-" assume it's a valid Jquery UI class:  
		if (iconclass.substr(0,3) == "ui-")  iconclass = "ui-icon " + iconclass; 
		
  		// Create DOM elements of div, para (for text) and span (for image) and insert  after current DOM object: 
  		var msg = $('<div></div>').css({ display : "none", position : "fixed", padding: "5px", width: "180px", fontSize: "80%", zIndex: "1010"}).addClass(divclass);
  		msg.append('<span style="float:left" class="'+ iconclass+'"></span>'+'<p style="margin-left:20px">'+msgtext+'</p>');
		
		// Insert after this DOM element: 
		me.after(msg);
        
		var x = 20 - opts.offsetX;
		var y = 40 - opts.offsetY;
		
        // After fadeout remove obsolete object (in a callback -ensures done after the fade): 
  		msg.fadeIn("slow")
  			.css({ left: x+'px', bottom: y+'px' })
  			.animate({opacity: 1.0}, opts.duration)
  			.fadeOut("slow", function(){
  					$(this).remove();
  			});
 	 });
 };  
})(jQuery);