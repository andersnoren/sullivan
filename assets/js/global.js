// ======================================================================= Namespace
var WP = WP || {},
$ = jQuery;


// ======================================================================= Global variables
var doc = $( document ),
	win = $( window );


// =======================================================================  Mobile Menu
WP.mobileMenu = {
	
	init: function(){

		// Toggle mobile menu on nav-toggle click
		$( '.nav-toggle' ).on( 'click', function(){	
			WP.mobileMenu.toggleMenu();
		} );

		// Toggle the sub menus in the mobile menu
		$( '.sub-nav-toggle' ).on( 'click', function(){
			WP.mobileMenu.toggleSubMenu( $( this ) );
		} );

		// On load, check if we need to expand the sub menus
		win.on( 'load', function(){
			WP.mobileMenu.checkSubMenuActive();
		} );

		// Hide mobile menu on mobile menu overlay click
		$( '.mobile-nav-content-overlay' ).on( 'click', function(){
			WP.mobileMenu.hideMenu();
		} );

		// Hide mobile menu on resize
		win.on( 'resize', function(){
			if ( win.width() > 1000 ) {
				WP.mobileMenu.hideMenu( pauseFlex = false );
			}
		} );

	},

	// Toggle the menu to the state it currently doesn't have
	toggleMenu: function() {

		if ( $( 'body' ).hasClass( 'showing-mobile-menu' ) ) {
			WP.mobileMenu.hideMenu();
		} else {
			WP.mobileMenu.showMenu();
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
			WP.ajaxSearch.emptyResults();
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

} // WP.mobileMenu


// ==================================================================== Sticky Menu
WP.stickyMenu = {
	
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
			win.on( 'resize scroll load', function(){
				var stickyOffset = $( '#sticky-adjuster' ).offset().top;
				WP.stickyMenu.stickIt( stickyElement, stickyClass, stickyOffset );
			} );

			WP.stickyMenu.stickIt( stickyElement, stickyClass, stickyOffset );

		}

	},

	// Stick the search form
	stickIt: function ( stickyElement, stickyClass, stickyOffset ) {

		var winScroll = win.scrollTop();

		if ( stickyElement.css( 'display' ) != 'none' && winScroll > stickyOffset ) {
			
			// If a sticky edge element exists and we've scrolled past it, hide the filter bar
			if ( ! stickyElement.hasClass( stickyClass ) ) {
				stickyElement.addClass( stickyClass );
				$( '#sticky-adjuster' ).height( stickyElement.outerHeight() ).css( 'margin-bottom', parseInt( stickyElement.css( 'marginBottom' ) ) );
			}

		// If not, remove class and sticky-adjuster properties
		} else {
			WP.stickyMenu.unstickIt();
		}

	},

	unstickIt: function() {
		$( '.' + stickyClass ).removeClass( stickyClass );
		$( '#sticky-adjuster' ).height( 0 ).css( 'margin-bottom', '0' );
	}

} // Sticky Menu


// =======================================================================  Modals
WP.modals = {
	
	init: function(){

		// Search Modal
		$( '#header-search-field' ).blur( function(){

			if ( ! $( this ).val() ) {

				$( this ).parents( 'form' ).siblings( '.modal' ).removeClass( 'active' );

			}

		} )

	},

} // WP.modals


// =======================================================================  Cover Page

WP.coverPage = {

	init: function(){

		$( '.to-content' ).on( 'click', function(){

			$( 'html, body' ).animate({
				scrollTop: $( '#content-element' ).offset().top - 50
			}, 1000 );

		} );

	}

} // WP.coverPage


// =======================================================================  Hero Slider


WP.heroSlider = {
	
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

			win.bind( 'resize', function() {
				setTimeout( function(){ 
					var slider = $slider.data( 'flexslider' );
					slider.resize();
				}, 1000 );
			} );

		}

	}

} // WP.heroSlider


// =======================================================================  Post Slider


WP.postSlider = {
	
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

			win.bind( 'resize', function() {
				setTimeout( function(){ 
					var slider = $slider.data( 'flexslider' );
					slider.resize();
				}, 1000 );
			} );

		}

	}

} // WP.postSlider


// =======================================================================  Scroll Show
	
WP.scrollShow = {
	
	init: function(){

		// Add class to elements when they're in view
		if ( $( '.tracker' ).length ) {

			win.on( 'load', function () {
				WP.scrollShow.detectTrackers();
			} );

			win.scroll( function () {
				$( '.tracker' ).each( function () {
					if ( WP.scrollShow.isScrolledIntoView( this ) === true ) {
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
			if ( $( this ).offset().top < win.height() ) {
				$( this ).addClass( 'spotted' );
			}
		} );
	},

	// Check whether an element is within the viewport
	isScrolledIntoView: function( elem ) {

		var docViewTop = win.scrollTop(),
			docViewBottom = docViewTop + win.height();

		var elemTop = $( elem ).offset().top,
			elemBuffer = win.width > 600 ? 200 : 50,
			elemBottom = elemTop + elemBuffer;

		return ( elemBottom <= docViewBottom );

	}

} // WP.scrollShow


// =======================================================================  Fade Blocks
WP.fadeBlocks = {
	
	init: function(){

		// Parallax effect on the fade blocks
		var scroll = window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.msRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			// IE fallback
			function(callback){ window.setTimeout(callback, 1000/60) };
			
		function loop(){

			var windowOffset = window.pageYOffset;

			if ( windowOffset < win.outerHeight() ) {

				$( '.fade-block' ).css({ 
					'transform': 'translateY( ' + Math.ceil ( windowOffset * 0.2 ) + 'px )',
					'opacity': 1 - ( windowOffset * 0.002 )
				} );

			}

			scroll( loop );

		}

		loop();

	}

} // WP.fadeBlocks



// =======================================================================  Resize videos
WP.intrinsicRatioEmbeds = {
	
	init: function(){

		// Resize videos after their container
		var vidSelector = ".post iframe, .post object, .post video, .widget-content iframe, .widget-content object, .widget-content iframe";	
		var resizeVideo = function(sSel) {
			$( sSel ).each(function() {
				var $video = $(this),
					$container = $video.parent(),
					iTargetWidth = $container.width();

				if ( !$video.attr("data-origwidth") ) {
					$video.attr("data-origwidth", $video.attr("width"));
					$video.attr("data-origheight", $video.attr("height"));
				}

				var ratio = iTargetWidth / $video.attr("data-origwidth");

				$video.css("width", iTargetWidth + "px");
				$video.css("height", ( $video.attr("data-origheight") * ratio ) + "px");
			});
		};

		resizeVideo( vidSelector );

		win.resize( function() {
			resizeVideo( vidSelector );
		} );

	},

} // WP.intrinsicRatioEmbeds



// =======================================================================  Smooth Scroll
WP.smoothScroll = {
	
	init: function(){

		// Smooth scroll to anchor links
		$('a[href*="#"]')
		// Remove links that don't actually link to anything
		.not('[href="#"]')
		.not('[href="#0"]')
		.click(function(event) {
			// On-page links
			if ( location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname ) {
				// Figure out element to scroll to
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				// Does a scroll target exist?
				if (target.length) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$('html, body').animate({
						scrollTop: target.offset().top
					}, 1000 );
				}
			}
		})

	},

} // WP.smoothScroll


// ======================================================================= AJAX Search
WP.ajaxSearch = {
	
	init: function(){

		// Delay function
		delay = ( function(){
			var timer = 0;
			return function( callback, ms ) {
				clearTimeout (timer);
				timer = setTimeout(callback, ms);
			}
		})();

		// Update results on keyup, after delay
		$( '.ajax-search-field' ).on( 'keyup', function() {
			if ( this.value.length != 0 ) {
				$searchField = $( this );
				delay( function(){
					WP.ajaxSearch.loadPosts( $searchField );
				}, 200 );
			} else {
				WP.ajaxSearch.emptyResults();
			}
		} );

		// Check for empty on blur
		$( '.ajax-search-field' ).on( 'blur', function() {
			if ( $( this ).val().length == 0 ) {
				WP.ajaxSearch.emptyResults();
			}
		} );

		// Empty search on cancel search click
		$( '.cancel-search' ).on( 'click', function(){
			WP.ajaxSearch.emptyResults();
		} )

	},

	loadPosts: function( $searchField ){

		var $container = $( '.ajax-search-results' ),
			data = $searchField.val();

		search_string = JSON.stringify( data );

		$.ajax({
			url: ajax_search.ajaxurl,
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
		});

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

} // WP.ajaxSearch


// ======================================================================= Function calls
doc.ready( function( ) {

	WP.mobileMenu.init();							// Mobile menu

	WP.stickyMenu.init();							// Sticky menu

	WP.modals.init();								// Handle modals

	WP.coverPage.init();							// Cover Page specifics

	WP.heroSlider.init();							// Hero Slider

	WP.postSlider.init();							// Post Slider

	WP.intrinsicRatioEmbeds.init();					// Resize embeds

	WP.scrollShow.init();							// Show elements on scroll

	WP.smoothScroll.init();							// Smooth scrolls to anchor links

	WP.fadeBlocks.init();							// Fade blocks on scroll

	WP.ajaxSearch.init();							// AJAX search

} );