( function( $ ) {

    // Update the blog slider when the accordion is expanded
    $( '#accordion-section-eames_blog_slider' ).live( 'expanded', function(){
        var originalValue = wp.customize( 'eames_blog_slider_max_slides' ).get();
        handleSlider( 'blog', originalValue );
    } );

	// Slideshow blog: Hide controls depending on the number of slides
	wp.customize( 'eames_blog_slider_max_slides', function( value ) {

        // Update on change
        value.bind( function( newval ) {
            handleSlider( 'blog', newval );
        } );

    } );

    // Update the shop slider when the accordion is expanded
    $( '#accordion-section-eames_shop_slider' ).live( 'expanded', function(){
        var originalValue = wp.customize( 'eames_blog_slider_max_slides' ).get();
        handleSlider( 'shop', originalValue );
    } );

    // Slideshow shop: Hide controls depending on the number of slides
	wp.customize( 'eames_shop_slider_max_slides', function( value ) {

        // Update on change
        value.bind( function( newval ) {
            handleSlider( 'shop', newval );
        } );

    } );
    
    // Update the specified slideshowArea
    function handleSlider( slideshowArea, newval ){

        // newval = the number of slides to show

        // Get the last element in the group of elements for our current number of slides
        var $max_slidesControl = $( '#customize-control-eames_' + slideshowArea + '_slider_max_slides' ),
            $section = $max_slidesControl.parent(),
            $lastMatchingControl = $section.find( $( '.customize-control[id*="_' + newval + '_"]:last ' ) );

        // If we're not showing any slides, hide all controls following the one setting number of slides
        if ( newval == 0 ) {

            $max_slidesControl.nextAll().hide();

        // Otherwise, show the specified number of slides
        } else {

            // Hide all slideshow elements following the last matching one
            $lastMatchingControl.nextAll().hide();

            // Show all slideshow elements preceding it
            $lastMatchingControl.add( $lastMatchingControl.prevAll() ).show();

        }

    }
	
	
} )( jQuery );