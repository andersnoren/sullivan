/**
 * Custom JavaScript functions for the customizer controls.
 */

( function( $ ) {

    // Multiple checkboxes: Add the values of the checked checkboxes to the hidden input
    $( '.customize-control-checkbox-multiple input[type="checkbox"]' ).live( 'change', function() {

        // Get the values of all of the checkboxes into a comma seperated variable
        checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
            function() {
                return this.value;
            }
        ).get().join( ',' );

        // If there are no values, make that explicit in the variable so we know whether the default output is needed
        if ( ! checkbox_values ) {
            checkbox_values = 'empty';
        }

        // Update the hidden input with the variable
        $( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );

    } );


    // Check the slideshow number of slides and add slides button on load
    $( '.accordion-section[id*="_slider"]' ).live( 'expanded', function(){

        // Button data
        var $button = $( this ).find( '#button-add-slide' ),
            buttonSlideshowArea = $button.data( 'slideshow' );

        // Number of slides input data
        var $numberInput = $button.closest( '.customize-control' ).siblings( '#customize-control-eames_blog_slider_max_slides' ).find( 'input[type="number"]' ),
            maxNumberVal = parseInt( $numberInput.attr( 'max' ) ),
            currentNumberVal = parseInt( $numberInput.val() );

        // If we're at max, disable the button
        if ( currentNumberVal >= maxNumberVal ) {
            $button.addClass( 'button-primary-disabled' );
        }

    } );

    // Increment the slideshow number of slides input when the Add Slide button is clicked
    $( '#button-add-slide' ).live( 'click', function(){

        // Button data
        var buttonSlideshowArea = $( this ).data( 'slideshow' );

        // Number of slides input data
        var $numberInput = $( this ).closest( '.customize-control' ).siblings( '#customize-control-eames_blog_slider_max_slides' ).find( 'input[type="number"]' ),
            maxNumberVal = parseInt( $numberInput.attr( 'max' ) ),
            currentNumberVal = parseInt( $numberInput.val() ),
            newNumberVal = currentNumberVal + 1;

        // If the incrementation does not put us at max, increment value and update slideshow
        if ( newNumberVal <= maxNumberVal ) {
            $numberInput.val( newNumberVal ).trigger( 'change' );
            handleSlider( buttonSlideshowArea, newNumberVal );
            $( this ).removeClass( 'button-primary-disabled' );
        }

        // If we're at max, disable the button
        if ( newNumberVal >= maxNumberVal ) {
            $( this ).addClass( 'button-primary-disabled' );
        }

    } );


    // Make sure the Add Slide button is active when the number of slides input is changes
    $( '[id*="slider_max_slides"] input[type="number"]' ).live( 'change', function(){

        var thisMax = parseInt( $( this ).attr( 'max' ) ),
            thisVal = parseInt( $( this ).val() );

        if ( thisVal < thisMax ) {
            var $addSlideButton = $( this ).closest( '.customize-control' ).siblings( '[id*="add_slide"]' ).find( '#button-add-slide' );

            $addSlideButton.removeClass( 'button-primary-disabled' );
        }

    } );


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
		console.log( newval );

        // Get the last element in the group of elements for our current number of slides
        var $max_slidesControl = $( '#customize-control-eames_' + slideshowArea + '_slider_max_slides' ),
            $section = $max_slidesControl.parent(),
			$lastMatchingControl = $section.find( $( '.customize-control[id*="_' + newval + '_"]' ) );

        // If we're not showing any slides, hide all controls following the one setting number of slides
        if ( newval == 0 ) {

            $max_slidesControl.nextAll( ':not([id*="add_slide"])' ).hide();

        // Otherwise, show the specified number of slides
        } else {

            // Hide all slideshow elements following the last matching one
            $lastMatchingControl.nextAll( ':not([id*="add_slide"])' ).hide();

            // Show all slideshow elements preceding it
            $lastMatchingControl.add( $lastMatchingControl.prevAll() ).show();

        }

    }
	
	
} )( jQuery );