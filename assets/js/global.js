// ======================================================================= Namespace
var sullivan = sullivan || {},
$ = jQuery;


// =======================================================================  Mobile Menu
sullivan.mobileMenu = {

	init: function(){

		// Toggle mobile menu on nav-toggle click
		$( '.nav-toggle' ).on( 'click', function(){
			sullivan.mobileMenu.toggleMenu();
		} );

		// Toggle the sub menus in the mobile menu
		$( '.sub-nav-toggle' ).on( 'click', function(){
			sullivan.mobileMenu.toggleSubMenu( $( this ) );
		} );

		// On load, check if we need to expand the sub menus
		$( window ).on( 'load', function(){
			sullivan.mobileMenu.checkSubMenuActive();
		} );

		// Hide mobile menu on mobile menu overlay click
		$( '.mobile-nav-content-overlay' ).on( 'click', function(){
			sullivan.mobileMenu.hideMenu();
		} );

		// Hide mobile menu on resize
		$( window ).on( 'resize', function(){
			if ( $( window ).width() > 1000 ) {
				sullivan.mobileMenu.hideMenu( pauseFlex = false );
			}
		} );

	},

	// Toggle the menu to the state it currently doesn't have
	toggleMenu: function() {

		if ( $( 'body' ).hasClass( 'showing-mobile-menu' ) ) {
			sullivan.mobileMenu.hideMenu();
		} else {
			sullivan.mobileMenu.showMenu();
		}

	},

	// Show the mobile menu
	showMenu: function() {

		$( '.nav-toggle' ).addClass( 'active' );
		$( '.mobile-menu-wrapper' ).addClass( 'visible' );
		$( 'body' ).addClass( 'showing-mobile-menu lock-scroll' );

		// Pause sliders when we hide the menu
		if ( $( '.hero-slider' ).length ) {
			var slider = $( '.hero-slider' ).data( 'flexslider' );
			slider.pause();
		}

	},

	// Hide the mobile menu
	hideMenu: function( pauseFlex = true ) {
		$( '.nav-toggle' ).removeClass( 'active' );
		$( '.mobile-menu-wrapper' ).removeClass( 'visible' );
		$( 'body' ).removeClass( 'showing-mobile-menu lock-scroll' );

		// Empty the mobile search results
		setTimeout( function(){
			sullivan.ajaxSearch.emptyResults();
		}, 1000 )

		// Play sliders again when we show the menu
		if ( pauseFlex == true && $( '.hero-slider' ).length ) {
			var slider = $( '.hero-slider' ).data( 'flexslider' );
			slider.play();
		}
	},

	// Toggle sub menus
	toggleSubMenu: function( $subNavToggle ) {
		var $subMenu = $subNavToggle.parent( '.menu-toggle-wrapper' ).next( '.sub-menu' );

		$subNavToggle.toggleClass( 'active' );
		$subMenu.slideToggle( 400 );
	},

	// On load, check whether we need to expand sub menus to show the current item
	checkSubMenuActive: function() {

		var $currentMenuItem = $( '.mobile-menu ul li.current-menu-item' );

		// Find the current menu item, provided it is a sub item
		if ( $currentMenuItem.length ) {

			// Loop through each ancestor of the item and show/activate the sub nav elements
			$currentMenuItem.parents( 'li.current_page_ancestor' ).each( function(){
				$( this ).children( '.menu-toggle-wrapper' ).children( '.sub-nav-toggle' ).addClass( 'active' );
				$( this ).children( '.sub-menu' ).show();
			} );
		}

	}

} // sullivan.mobileMenu


// ======================================================================= Toggles
sullivan.toggles = {

	init: function () {

		$( '.toggle' ).on( 'click', function( e ){

			console.log( e );

			// Get our targets
			var targetString = $( this ).data( 'toggle-target' );
			var target = $( targetString );

			// Untoggle any currently toggled toggles
			$( '.toggle' ).not( this ).removeClass( 'active' );

			// The diva toggleable elements steal the stage for themselves
			$( '.diva' ).not( target ).each( function() {
				if ( $( this ).hasClass( 'active' ) ) {
					$( this ).removeClass( 'active' );
				}
			} );

			// Toggle the target of the clicked toggle
			if ( $( this ).data( 'toggle-type' ) == 'slidetoggle' ) {
				$( this ).toggleClass( 'active' );
				var duration = $( this ).data( 'toggle-duration' ) ? $( this ).data( 'toggle-duration' ) : 400;
				target.slideToggle( duration );
			} else {
				$( this ).add( target ).toggleClass( 'active' );
			}

			return false;

		} );

	}

} // sullivan.toggles


// ==================================================================== Sticky Menu
sullivan.stickyMenu = {

	init: function() {

		var stickyElement = $( '.stick-me' );

		if ( $( stickyElement ).length ) {

			stickyClass = 'make-sticky';

			var stickyOffset = stickyElement.scrollTop();

			// Our stand-in element for stickyElement while stickyElement is off on a scroll
			if ( ! $( '#sticky-adjuster' ).length ) {
				stickyElement.before( '<div id="sticky-adjuster"></div>' );
			}

			// Stick it on resize, scroll and load
			$( window ).on( 'resize scroll load', function(){
				var stickyOffset = $( '#sticky-adjuster' ).offset().top;
				sullivan.stickyMenu.stickIt( stickyElement, stickyClass, stickyOffset );
			} );

			sullivan.stickyMenu.stickIt( stickyElement, stickyClass, stickyOffset );

		}

	},

	// Stick the search form
	stickIt: function ( stickyElement, stickyClass, stickyOffset ) {

		var winScroll = $( window ).scrollTop();

		if ( stickyElement.css( 'display' ) != 'none' && winScroll > stickyOffset ) {

			// If a sticky edge element exists and we've scrolled past it, hide the filter bar
			if ( ! stickyElement.hasClass( stickyClass ) ) {
				stickyElement.addClass( stickyClass );
				$( '#sticky-adjuster' ).height( stickyElement.outerHeight() ).css( 'margin-bottom', parseInt( stickyElement.css( 'marginBottom' ) ) );
			}

		// If not, remove class and sticky-adjuster properties
		} else {
			sullivan.stickyMenu.unstickIt();
		}

	},

	unstickIt: function() {
		$( '.' + stickyClass ).removeClass( stickyClass );
		$( '#sticky-adjuster' ).height( 0 ).css( 'margin-bottom', '0' );
	}

} // Sticky Menu


// =======================================================================  Modals
sullivan.modals = {

	init: function(){

		// Search Modal
		$( '#header-search-field' ).blur( function(){

			if ( ! $( this ).val() ) {

				$( this ).parents( 'form' ).siblings( '.modal' ).removeClass( 'active' );

			}

		} )

	},

} // sullivan.modals


// =======================================================================  Cover Page

sullivan.coverPage = {

	init: function(){

		$( '.to-content' ).on( 'click', function(){

			$( 'html, body' ).animate({
				scrollTop: $( '#content-element' ).offset().top - 50
			}, 1000 );

		} );

	}

} // sullivan.coverPage


// =======================================================================  Fancy Number Inputs

sullivan.fancyNumberInputs = {

	init: function(){

		// Add the extra markup
		sullivan.fancyNumberInputs.addQuantityMarkup();

		// Loop through all quantity elements and set up our variables
		sullivan.fancyNumberInputs.handleQuantityElements();

		// Add the quantity markup back and rerun the handling when WC fragments have been refreshed
		$( 'body' ).on( 'wc_fragments_refreshed', function(){
			sullivan.fancyNumberInputs.addQuantityMarkup();
			sullivan.fancyNumberInputs.handleQuantityElements();
		} );

	},

	// Add the extra markup
	addQuantityMarkup: function() {
		$( '<div class="quantity-nav"><div class="quantity-button quantity-up"></div><div class="quantity-button quantity-down"></div></div>' ).insertAfter( '.quantity input' );
	},

	// Loop through all quantity elements and set up our variables
	handleQuantityElements: function() {

		$( '.quantity' ).each( function() {
			var $spinner = $( this ),
				$input = $spinner.find( 'input[type="number"]' ),
				inputVal = parseFloat( $input.val() ),
				$btnUp = $spinner.find( '.quantity-up' ),
				$btnDown = $spinner.find( '.quantity-down' ),
				min = $input.attr( 'min' ) ? $input.attr( 'min' ) : 1,
				max = $input.attr( 'max' ) ? $input.attr( 'max' ) : 99999;

			if ( inputVal >= max ) { $btnUp.addClass( 'disabled' ); }
			if ( inputVal <= min ) { $btnDown.addClass( 'disabled' ); }

			$input.on( 'change blur', function(){

				var newVal = parseFloat( $( this ).val() );

				if ( ! $( 'body' ).hasClass( 'woocommerce-cart' ) && ( ! newVal || newVal <= 0 ) ) {
					$( this ).val( 1 );
					newVal = 1;
				}

				if ( newVal >= max ) {
					$btnUp.addClass( 'disabled' );
					$( this ).val( max );
				} else if ( newVal <= min ) {
					$btnDown.addClass( 'disabled' );
				} else {
					$btnUp.add( $btnDown ).removeClass( 'disabled' );
				}

			} );

			// Update quantity on increase click
			$btnUp.on( 'click', function() {
				var oldValue = parseFloat( $input.val() );
				if ( oldValue >= max ) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue + 1;
					$btnDown.removeClass( 'disabled' );
				}
				$spinner.find( 'input' ).val( newVal );
				$spinner.find( 'input' ).trigger( 'change' );
			} );

			// Update quantity on decrease click
			$btnDown.on( 'click', function() {
				var oldValue = parseFloat( $input.val() );
				if ( oldValue <= min ) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue - 1;
					$btnUp.removeClass( 'disabled' );
				}
				$spinner.find( 'input' ).val( newVal );
				$spinner.find( 'input' ).trigger( 'change' );
			} );

		} );

	}

}


// =======================================================================  Hero Slider


sullivan.heroSlider = {

	init: function() {

		var $slider = $( '.hero-slider' );

		if ( $slider.length ) {

			var animSpeed = 1000,
				slideshowSpeed = $slider.attr( 'data-slideshow-speed' ) ? $slider.attr( 'data-slideshow-speed' ) : 7000;

			// Load Flexslider
			$slider.flexslider({
				animation: "slide",
				animationSpeed: animSpeed,
				controlNav: true,
				directionNav: false,
				keyboard: false,
				pauseOnHover: true,
				slideshowSpeed: slideshowSpeed,
				smoothHeight: false,
				start: function( $slider ) {
					$slider.removeClass( 'loading' ).addClass( 'loaded' );
					$slider.update();
				},
				after: function( $slider ) {

					$currentSlide = $slider.find( '.flex-active-slide' );

					// Add a class to the pagination if the current element only has an image
					if ( $currentSlide.hasClass( 'only-image' ) ) {
						$( '.flex-control-nav' ).addClass( 'has-background' );
					} else {
						$( '.flex-control-nav' ).removeClass( 'has-background' );
					}
				},
			} );

			$( window ).bind( 'resize', function() {
				setTimeout( function(){
					var slider = $slider.data( 'flexslider' );
					slider.resize();
				}, 1000 );
			} );

		} // End if().

	}

} // sullivan.heroSlider


// =======================================================================  Post Slider


sullivan.postSlider = {

	init: function() {

		var $slider = $( '.post-slider' );

		if ( $slider.length ) {

			// Load Flexslider
			$slider.flexslider({
				animation: "slide",
				animationSpeed: 1000,
				controlNav: false,
				directionNav: true,
				keyboard: false,
				pauseOnHover: true,
				start: function( $slider ) {
					$slider.removeClass( 'loading' ).addClass( 'loaded' );
					$slider.update();
				},
			} );

			$( window ).bind( 'resize', function() {
				setTimeout( function(){
					var slider = $slider.data( 'flexslider' );
					slider.resize();
				}, 1000 );
			} );

		}

	}

} // sullivan.postSlider


// =======================================================================  Scroll Show

sullivan.scrollShow = {

	init: function(){

		// Add class to elements when they're in view
		if ( $( '.tracker' ).length ) {

			$( window ).on( 'load', function () {
				sullivan.scrollShow.detectTrackers();
			} );

			$( window ).scroll( function () {
				$( '.tracker' ).each( function () {
					if ( sullivan.scrollShow.isScrolledIntoView( this ) === true ) {
						$( this ).addClass( 'spotted' );
					}
				} );
			} );

		}

	},

	// Go through the trackers and see whether we've shown them
	detectTrackers: function() {
		$( '.tracker' ).each( function () {
			$( this ).addClass( 'will-spot' );
			if ( $( this ).offset().top < $( window ).height() ) {
				$( this ).addClass( 'spotted' );
			}
		} );
	},

	// Check whether an element is within the viewport
	isScrolledIntoView: function( elem ) {

		var docViewTop = $( window ).scrollTop(),
			docViewBottom = docViewTop + $( window ).height();

		var elemTop = $( elem ).offset().top,
			elemBuffer = $( window ).width > 600 ? 200 : 50,
			elemBottom = elemTop + elemBuffer;

		return ( elemBottom <= docViewBottom );

	}

} // sullivan.scrollShow


// =======================================================================  Fade Blocks
sullivan.fadeBlocks = {

	init: function(){

		// Parallax effect on the fade blocks
		var scroll = window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.msRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			// IE fallback
			function( callback ) { window.setTimeout( callback, 1000 / 60 ) };

		function loop(){

			var windowOffset = window.pageYOffset;

			if ( windowOffset < $( window ).outerHeight() ) {

				$( '.fade-block' ).css({
					'transform': 'translateY( ' + Math.ceil( windowOffset * 0.2 ) + 'px )',
					'opacity': 1 - ( windowOffset * 0.002 )
				} );

			}

			scroll( loop );

		}

		loop();

	}

} // sullivan.fadeBlocks



// =======================================================================  Resize videos
sullivan.intrinsicRatioEmbeds = {

	init: function(){

		// Resize videos after their container
		var vidSelector = ".post iframe, .post object, .post video, .widget-content iframe, .widget-content object, .widget-content iframe";
		var resizeVideo = function(sSel) {
			$( sSel ).each( function() {
				var $video = $( this ),
					$container = $video.parent(),
					iTargetWidth = $container.width();

				if ( ! $video.attr( "data-origwidth" ) ) {
					$video.attr( "data-origwidth", $video.attr( "width" ) );
					$video.attr( "data-origheight", $video.attr( "height" ) );
				}

				var ratio = iTargetWidth / $video.attr( "data-origwidth" );

				$video.css( "width", iTargetWidth + "px" );
				$video.css( "height", ( $video.attr( "data-origheight" ) * ratio ) + "px" );
			});
		};

		resizeVideo( vidSelector );

		$( window ).resize( function() {
			resizeVideo( vidSelector );
		} );

	},

} // sullivan.intrinsicRatioEmbeds



// =======================================================================  Smooth Scroll
sullivan.smoothScroll = {

	init: function(){

		// Smooth scroll to anchor links
		$( 'a[href*="#"]' )
		// Remove links that don't actually link to anything
		.not( '[href="#"]' )
		.not( '[href="#0"]' )
		.click( function( event ) {
			// On-page links
			if ( location.pathname.replace( /^\//, '' ) == this.pathname.replace( /^\//, '' ) && location.hostname == this.hostname ) {
				// Figure out element to scroll to
				var target = $( this.hash );
				target = target.length ? target : $( '[name=' + this.hash.slice( 1 ) + ']' );
				// Does a scroll target exist?
				if ( target.length ) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$( 'html, body' ).animate({
						scrollTop: target.offset().top
					}, 1000 );
				}
			}
		})

	},

} // sullivan.smoothScroll


// ======================================================================= AJAX Search
sullivan.ajaxSearch = {

	init: function(){

		// Delay function
		delay = ( function(){
			var timer = 0;
			return function( callback, ms ) {
				clearTimeout( timer );
				timer = setTimeout( callback, ms );
			}
		} )();

		// Update results on keyup and focus, after delay
		$( '.ajax-search-field' ).on( 'keyup focus', function() {
			if ( this.value.length != 0 ) {
				$searchField = $( this );
				delay( function(){
					sullivan.ajaxSearch.loadPosts( $searchField );
				}, 200 );
			} else {
				sullivan.ajaxSearch.emptyResults();
			}
		} );

		// Check for empty on blur
		$( '.ajax-search-field' ).on( 'blur', function() {
			if ( $( this ).val().length == 0 ) {
				sullivan.ajaxSearch.emptyResults();
			}
		} );

		// Empty search on cancel search click
		$( '.cancel-search' ).on( 'click', function(){
			sullivan.ajaxSearch.emptyResults();
		} );

	},

	loadPosts: function( $searchField ){

		var $container 	= $( '.ajax-search-results' ),
			data 		= $searchField.val();

		search_string = JSON.stringify( data );

		$.ajax( {
			url: sullivan_ajax_search.ajaxurl,
			type: 'post',
			data: {
				action: 'ajax_search_results',
				query_data: search_string
			},

			beforeSend: function() {
				$container.addClass( 'loading' );
			},

			success: function( result ) {

				$container.empty().append( $( result ) );

				if ( data ) {
					$container.addClass( 'active' );
					$container.closest( '.mobile-search, .header-search' ).addClass( 'search-active' );
				}

			},

			complete: function() {
				// We're no longer loading
				$container.removeClass( 'loading' );
			},

			error: function(jqXHR, textStatus, errorThrown) {
				console.log( 'AJAX error: ' + errorThrown );
			}
		} );

	},

	emptyResults: function(){
		$( '.ajax-search-results' ).each( function() {

			// Remove active class, empty element
			if ( $( this ).hasClass( 'active' ) ) {
				$( this ).parents( '.mobile-search, .header-search' ).removeClass( 'search-active' );
				$( this ).removeClass( 'active', function(){
					$( this ).empty();
				} );
			} else {
				$( this ).empty();
			}

			// Reset the search field value
			$( this ).parent().find( '.ajax-search-field' ).val( '' );

		} );
	}

} // sullivan.ajaxSearch


// ======================================================================= Function calls
$( document ).ready( function( ) {

	sullivan.mobileMenu.init();							// Mobile menu

	sullivan.toggles.init();							// Toggles

	sullivan.stickyMenu.init();							// Sticky menu

	sullivan.modals.init();								// Handle modals

	sullivan.coverPage.init();							// Cover Page specifics

	sullivan.fancyNumberInputs.init();					// Make the number inputs fancy

	sullivan.heroSlider.init();							// Hero Slider

	sullivan.postSlider.init();							// Post Slider

	sullivan.intrinsicRatioEmbeds.init();				// Resize embeds

	sullivan.scrollShow.init();							// Show elements on scroll

	sullivan.smoothScroll.init();						// Smooth scrolls to anchor links

	sullivan.fadeBlocks.init();							// Fade blocks on scroll

	sullivan.ajaxSearch.init();							// AJAX search

} );
