/**
 * aankha-woo-assitive-menu.js
 *
 * Woocommerce assitive menu
 * 
 */
jQuery(document).ready( function($) {
		
		$('body').on('click', '#minicartt-icon', function(){		
			

			$('#minicartt').toggleClass( "active" );
		
				$(".cart_list").mCustomScrollbar( { theme:"minimal-dark" } );
				
		});

		// visibile on js load
		//$('#minicartt').css({'visibility':'visible', 'opacity':'1'});
    
});


