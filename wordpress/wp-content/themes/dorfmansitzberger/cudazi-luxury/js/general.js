

// FONT REPLACEMENT
// Replace all tags with cufon equivalant
// Cufon.replace('h1,h2,h3,h4,h5,h6,#commentform label');
//

// JQUERY

//jQuery.noConflict(); - not needed unless you are pulling from an external jquery library

jQuery(function($){
		   
	   // Clear searchform on click
		$("#searchform #s").click(function(){
			$(this).val("");   
		});
		
	
		// Dropdown Menu config
		$("ul.sf-menu").supersubs({ 
            minWidth:    12,   // minimum width of sub-menus in em units 
            maxWidth:    14,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish();  // call supersubs first, then superfish, so that subs are 
                         // not display:none when measuring. Call before initialising 
                         // containing tabs for same reason.
	
	
	// Softbutton = soft fade on hover, used for social media icons
	$(".softbutton a").stop().fadeTo("fast", .55);
	$(".softbutton a").hover(
		function(){
			$(this).stop().fadeTo("fast", 1);
		},
		function(){
			$(this).stop().fadeTo("fast", .55);
		}
	);
	
	
	// Promo slider setup, inserted on when promo is in use to save bandwidth
	
	$('#promos').cycle({ 
		fx:     'fade', 
		speed:  1000, 
		timeout: 3000,
		pager: '#promo-nav',
		pause: 1,
		height: 400,
		pauseOnPagerHover: 1,
		fastOnEvent: 350,
		pagerAnchorBuilder: function(idx, slide) { 
			return "#promo-nav li:eq("+ idx +") a"; 
		}
	});

});// end jquery