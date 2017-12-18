/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
 
( function( $ ) {

	document.addEventListener( 'DOMContentLoaded', function() {

        hasSelectiveRefresh = (
            'undefined' !== typeof wp &&
            wp.customize &&
            wp.customize.selectiveRefresh &&
            wp.customize.widgetsPreview &&
            wp.customize.widgetsPreview.WidgetPartial
		);
		
        if ( hasSelectiveRefresh ) {
            wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
                
                // Slideshow blog: Hide controls depending on the number of slides
                wp.customize( 'eames_blog_slider_speed', function( value ) {

                    // Update on change
                    value.bind( function( newval ) {
                        
                        $( '.hero-slider' ).attr( 'data-slideshow-speed', newval );

                    } );

                } );
                
				WP.heroSlider.init();

            } );
        }
    } );
	
	
} )( jQuery );