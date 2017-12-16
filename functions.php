<?php

/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'eames_setup' ) ) {

	function eames_setup() {
		
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
		
		// Set content-width
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 600;
		
		// Post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size
		set_post_thumbnail_size( 870, 9999 );
		
		// Custom Image Sizes
		add_image_size( 'eames_fullscreen', 1860, 9999 );
		
		// Background color
		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
		) );
		
		// Custom logo
		add_theme_support( 'custom-logo', array(
			'height'      => 400,
			'width'       => 600,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );
		
		// Title tag
		add_theme_support( 'title-tag' );
		
		// Add nav menu
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'eames' ) );
		register_nav_menu( 'mobile-menu', __( 'Mobile Menu', 'eames' ) );
		register_nav_menu( 'social', __( 'Social Menu', 'eames' ) );
		
		// Add excerpts to pages
		add_post_type_support( 'page', array( 'excerpt' ) );
		
		// HTML5 semantic markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'eames', get_template_directory() . '/languages' );
		
		$locale_file = get_template_directory() . "/languages/" . get_locale();
		
		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
		
	}
	add_action( 'after_setup_theme', 'eames_setup' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_load_style' ) ) {

	function eames_load_style() {
		if ( ! is_admin() ) {
			wp_register_style( 'eames-google-fonts', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,400i,500,700,700i&amp;subset=latin-ext', array(), null );
			wp_register_style( 'eames_fontawesome', get_stylesheet_directory_uri() . '/assets/font-awesome/css/font-awesome.css' );

			wp_enqueue_style( 'eames-style', get_stylesheet_uri(), array( 'eames-google-fonts', 'eames_fontawesome' ) );
		} 
	}
	add_action( 'wp_enqueue_scripts', 'eames_load_style' );

}


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_add_editor_styles' ) ) {

	function eames_add_editor_styles() {
		add_editor_style( array( 
			'eames-editor-styles.css', 
			'https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,400i,500,700,700i&amp;subset=latin-ext' 
		) );
	}
	add_action( 'init', 'eames_add_editor_styles' );

}


/* ---------------------------------------------------------------------------------------------
   DEACTIVATE DEFAULT WP GALLERY STYLES
   --------------------------------------------------------------------------------------------- */


add_filter( 'use_default_gallery_style', '__return_false' );


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_enqueue_scripts' ) ) {

	function eames_enqueue_scripts() {

		wp_register_script( 'eames_flexslider', get_template_directory_uri() . '/assets/js/flexslider.min.js', '', true );
		wp_enqueue_script( 'eames_global', get_template_directory_uri() . '/assets/js/global.js', array( 'jquery', 'eames_flexslider' ), '', true );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		global $wp_query;

		// AJAX PAGINATION
		wp_localize_script( 'eames_global', 'ajax_search', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'query_vars'	=> json_encode( $wp_query->query )
		) );

	}
	add_action( 'wp_enqueue_scripts', 'eames_enqueue_scripts' );

}


/* ---------------------------------------------------------------------------------------------
   POST CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_post_classes' ) ) {

	function eames_post_classes( $classes ) {

		// Class indicating presence/lack of post thumbnail
		$classes[] = ( has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail' );
		
		return $classes;
	}
	add_action( 'post_class', 'eames_post_classes' );

}


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_body_classes' ) ) {

	function eames_body_classes( $classes ) {

		global $post;

		// Check for post thumbnail
		if ( is_singular() && has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail';
		} elseif ( is_singular() ) {
			$classes[] = 'missing-post-thumbnail';
		}

		// Check for manually entered excerpt
		if ( is_singular() && has_excerpt() ) {
			$classes[] = 'has-manual-excerpt';
		} elseif ( is_singular() ) {
			$classes[] = 'missing-manual-excerpt';
		}

		// Check whether we're in the customizer preview
		if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}

		// Slim page template class names (class = name - file suffix)
		if ( is_page_template() ) {
			$classes[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', get_page_template_slug( $post->ID ) );
		}
		
		return $classes;
	}
	add_action( 'body_class', 'eames_body_classes' );

}


/* ---------------------------------------------------------------------------------------------
   ADD HTML CLASS IF THERE'S JAVASCRIOPT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_has_js' ) ) {

	function eames_has_js() { 
		?>
		<script>jQuery( 'html' ).removeClass( 'no-js' ).addClass( 'js' );</script>
		<?php
	}
	add_action( 'wp_head', 'eames_has_js' );

}


/* ---------------------------------------------------------------------------------------------
   REGISTER SIDEBAR
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_sidebar_registration' ) ) {
	
	function eames_sidebar_registration() {

		// Arguments used in all register_sidebar() calls
		$shared_args = array(
			'before_title' 	=> '<h3 class="widget-title subheading">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div></div>'
		);

		// Footer #1
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #1', 'eames' ),
			'id' 			=> 'footer-1',
			'description' 	=> __( 'Widgets in this area will be shown in the first column in the footer. The "Contact Information" widget gets special styling treatment on mobile if it is placed here.', 'eames' ),
		), $shared_args ) );

		// Footer #2
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #2', 'eames' ),
			'id' 			=> 'footer-2',
			'description' 	=> __( 'Widgets in this area will be shown in the second column in the footer.', 'eames' ),
		), $shared_args ) );

		// Footer #3
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #3', 'eames' ),
			'id' 			=> 'footer-3',
			'description' 	=> __( 'Widgets in this area will be shown in the third column in the footer.', 'eames' ),
		), $shared_args ) );

		// Footer #4
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #4', 'eames' ),
			'id' 			=> 'footer-4',
			'description' 	=> __( 'Widgets in this area will be shown in the fourth column in the footer.', 'eames' ),
		), $shared_args ) );

		// If WooCommerce is activated, call the blog sidebar "Sidebar Blog"
		if ( eames_is_woocommerce_activated() ) {
			$sidebar_blog_name = __( 'Sidebar Blog', 'eames' );
		
		// If not, it's the only sidebar and we can just call it "Sidebar"
		} else {
			$sidebar_blog_name = __( 'Sidebar', 'eames' );
		}

		// Sidebar Blog
		register_sidebar( array_merge( array(
			'name' 			=> $sidebar_blog_name,
			'id'			=> 'sidebar',
			'description' 	=> __( 'Widgets in this area will be shown in the sidebar.', 'eames' ),
		), $shared_args ) );

		// Sidebar Shop (only if WC is activated)
		if ( eames_is_woocommerce_activated() ) {

			register_sidebar( array_merge( array(
				'name' 			=> __( 'Sidebar Shop', 'eames' ),
				'id'			=> 'sidebar-shop',
				'description' 	=> __( 'Widgets in this area will be shown in the sidebar.', 'eames' ),
			), $shared_args ) );

		}

	}
	add_action( 'widgets_init', 'eames_sidebar_registration' ); 

}


/* ---------------------------------------------------------------------------------------------
   INCLUDE THEME WIDGETS
   --------------------------------------------------------------------------------------------- */


require_once( get_template_directory() . '/widgets/contact-information.php' );
require_once( get_template_directory() . '/widgets/recent-comments.php' );
require_once( get_template_directory() . '/widgets/recent-posts.php' );
require_once( get_template_directory() . '/widgets/recent-products.php' );


/* ---------------------------------------------------------------------------------------------
   REGISTER THEME WIDGETS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_register_widgets' ) ) {

	function eames_register_widgets() {

		// Default widgets
		register_widget( 'eames_contact_information' );
		register_widget( 'eames_recent_comments' );
		register_widget( 'eames_recent_posts' );

		// Widgets that require Woocommerce
		if ( eames_is_woocommerce_activated() ) {
			register_widget( 'eames_recent_products' );
		}

	}
	add_action( 'widgets_init', 'eames_register_widgets' );

}
   
   
/* ---------------------------------------------------------------------------------------------
	DELIST DEFAULT WIDGETS REPLACE BY THEME ONES
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_unregister_default_widgets' ) ) {

	function eames_unregister_default_widgets() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
	}
	add_action( 'widgets_init', 'eames_unregister_default_widgets', 11 );

}


/* ---------------------------------------------------------------------------------------------
   MODIFY MORE LINK
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_modify_read_more_link' ) ) {

	function eames_modify_read_more_link() {
		return '<a class="more-link button" href="' . get_permalink() . '">' . __( 'Read More', 'eames' ) . '</a>';
	}
	add_filter( 'the_content_more_link', 'eames_modify_read_more_link' );

}


/* ---------------------------------------------------------------------------------------------
   GET COMMENT EXCERPT LENGTH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_get_comment_excerpt' ) ) {

	function eames_get_comment_excerpt( $comment_ID = 0, $num_words = 20 ) {

		// Get our comment text
		$comment = get_comment( $comment_ID );
		$comment_text = strip_tags( $comment->comment_content );

		// Separate it into words
		$word_array = explode( ' ', $comment_text );

		// If we have more words than the limit, we need to excerpt
		if ( count( $word_array ) > $num_words ) {

			$excerpt = '';

			for ( $i = 0; $i < $num_words; $i++ ) {
				$excerpt .= $word_array[$i] . ' ';
			}

			// Remove surrounding whitespace and trailing dot
			$excerpt = trim( $excerpt );
			$excerpt = rtrim( $excerpt, '.' );

			// Append the ellipsis
			$excerpt .= '...';

		// Fewer words than the limit? Return the full comment text
		} else {
			$excerpt = $comment_text;
		}

		// Return our excerpt
		return apply_filters( 'get_comment_excerpt', $excerpt );
	}

}


/* ---------------------------------------------------------------------------------------------
   CHECK WHETHER WOOCOMMERCE IS ACTIVE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_is_woocommerce_activated' ) ) {

	function eames_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { 
			return true; 
		} else { 
			return false; 
		}
	}

}


/* ---------------------------------------------------------------------------------------------
   INCLUDE WOOCOMMERCE FUNCTIONS
   --------------------------------------------------------------------------------------------- */


if ( eames_is_woocommerce_activated() ) {

	// All functions that require Woocommerce functionality to work are contained within this file
	include( locate_template( 'functions-woocommerce.php' ) );

	/* 
		* EXCEPTION:
		* eames_sidebar_registration() and eames_register_widgets() both have  
		* conditional registration of shop specific sidebar areas and widgets.
		* */

}


/* ---------------------------------------------------------------------------------------------
   REMOVE ARCHIVE PREFIXES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_remove_archive_title_prefix' ) ) {

	function eames_remove_archive_title_prefix( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date( get_option( 'date_format' ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'eames' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'eames' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} else {
			$title = __( 'Archives', 'eames' );
		}
		return $title;
	}
	add_filter( 'get_the_archive_title', 'eames_remove_archive_title_prefix' );

}


/* ---------------------------------------------------------------------------------------------
   GET ARCHIVE PREFIX
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_get_archive_title_prefix' ) ) {

	function eames_get_archive_title_prefix() {
		if ( is_category() ) {
			$title_prefix = __( 'Category', 'eames' );
		} elseif ( is_tag() ) {
			$title_prefix = __( 'Tag', 'eames' );
		} elseif ( is_author() ) {
			$title_prefix = __( 'Author', 'eames' );
		} elseif ( is_year() ) {
			$title_prefix = __( 'Year', 'eames' );
		} elseif ( is_month() ) {
			$title_prefix = __( 'Month', 'eames' );
		} elseif ( is_day() ) {
			$title_prefix = __( 'Day', 'eames' );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title_prefix = $tax->labels->singular_name;
		} else {
			$title_prefix = __( 'Archives', 'eames' );
		}
		return $title_prefix;
	}

}


/* ---------------------------------------------------------------------------------------------
   HEADER SEARCH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_header_search' ) ) {

	function eames_header_search() { ?>

		<div class="header-search">

			<form role="search" method="get" class="header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'eames' ); ?></span>
				<label for="header-search-field"></label>
				<input type="search" id="header-search-field" class="ajax-search-field" placeholder="<?php _e( 'Search', 'eames' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
			</form>

			<div class="compact-search-results ajax-search-results modal arrow-left">

				<?php // Content is added to this element by the eames_ajax_search() function (by way of javascript) ?>

			</div>

		</div><!-- .header-search -->


		<?php
	}
}


/* ---------------------------------------------------------------------------------------------
   AJAX SEARCH
   This function is called when the ajax search fields are updated
   --------------------------------------------------------------------------------------------- */


function eames_ajax_search() {

	$string = json_decode( stripslashes( $_POST['query_data'] ), true );

	if ( $string ) :

		$args = array(
			's'					=> $string,
			'posts_per_page'	=> 5,
			'post_status'		=> 'publish',
		);

		$ajax_query = new WP_Query( $args );

		if ( $ajax_query->have_posts() ) {
			
			?>

			<ul class="eames-widget-list">
				
				<?php

				// Custom loop
				while ( $ajax_query->have_posts() ) : $ajax_query->the_post(); 

					$post_format = get_post_format() ? get_post_format() : 'standard';
					$post_type = get_post_type();
				
					?>

					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					
							<div class="post-icon">
							
								<?php 

								if ( has_post_thumbnail() ) {
									
									the_post_thumbnail( 'thumbnail' );
									
								} else { ?>
								
									<div class="genericon genericon-<?php echo $post_format; ?>"></div>
								
								<?php } ?>
								
							</div>
							
							<div class="inner">
											
								<p class="title"><?php the_title(); ?></p>

								<?php if ( $post_type == 'post' ) : ?>

									<p class="meta"><?php the_time( get_option( 'date_format' ) ); ?></p>

								<?php elseif( $post_type == 'product' ) : ?>

									<p class="meta">PRICE HERE</p>
									
								<?php endif; ?>
							
							</div>
					
						</a>
					</li>					

					<?php
				// End the loop
				endwhile;

				?>

			</ul>

			<?php if ( $ajax_query->max_num_pages > 1 ) : ?>

				<a class="show-all" href="<?php echo home_url( '?s=' . $string ); ?>"><span><?php printf( _n( 'Show %s result', 'Show all %s results', $ajax_query->found_posts, 'eames' ), $ajax_query->found_posts ); ?></span></a>

			<?php endif; ?>

			<?php

		} else {

			echo '<p class="no-results-message">' . __( 'We could not find anything that matches your search query. Please try again.', 'eames' ) . '</p>';

		}

	endif; // if string

	die();
}
add_action( 'wp_ajax_nopriv_ajax_search_results', 'eames_ajax_search' );
add_action( 'wp_ajax_ajax_search_results', 'eames_ajax_search' );


/* ---------------------------------------------------------------------------------------------
   MENU WALKER WITH SUB NAV TOGGLE
   --------------------------------------------------------------------------------------------- */


class Eames_Walker_with_Sub_Toggles extends Walker_Nav_Menu {
	

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		// Add a sub-nav-toggle if there are children and close the wrapper
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$item_output .= '<div class="menu-toggle-wrapper">';
		}
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		// Add a sub-nav-toggle if there are children and close the wrapper
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$item_output .= '<div class="sub-nav-toggle"></div></div>';
		}

		/**
		 * Filters a menu item's starting output.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}


/* ---------------------------------------------------------------------------------------------
	GET SLIDESHOW AREAS
	Ensure we get the right values for the slideshow areas by keeping that data in a single place.

	Child theme devs: This function can be plugged, and the $slideshow_areas can be extended to
	create another slideshow section with corresponding settings and controls in the Customizer. 
	That slideshow will then be available for output by calling eames_hero_slider() with your 
	area name as the function argument.
   --------------------------------------------------------------------------------------------- */


   if ( ! function_exists( 'eames_get_slideshow_area' ) ) {

	function eames_get_slideshow_area( $area = '' ) {

		// Blog slideshow area
		$slideshow_areas = array(
			'blog' => array(
				'name'			=> 'blog',
				'title' 		=> __( 'Slideshow (blog)', 'eames' ),
				'description' 	=> __( 'Add information to be shown in the slideshow on the blog start page.', 'eames' ),
				'priority'		=> 40,
				'max_slides'	=> 10,
			),
		);

		// Shop slideshow area (provided WC is installed and active)
		if ( eames_is_woocommerce_activated() ) {
			$slideshow_areas['shop'] = array(
				'name'			=> 'shop',
				'title' 		=> __( 'Slideshow (shop)', 'eames' ),
				'description' 	=> __( 'Add information to be shown in the slideshow on the shop start page.', 'eames' ),
				'priority'		=> 40,
				'max_slides'	=> 10,
			);
		}

		// If a specific area is requested and exists, return that
		if ( $area && isset( $slideshow_areas[$area] ) ) {
			return $slideshow_areas[$area];

		// If it's requested but doesn't exist, go fish
		} elseif ( $area && ! isset( $slideshow_areas[$area] ) ) {
			return false;
		}

		// If no argument is provided, return all areas
		return $slideshow_areas;

	}

}


/* ---------------------------------------------------------------------------------------------
   HERO SLIDER
   --------------------------------------------------------------------------------------------- */


   if ( ! function_exists( 'eames_hero_slider' ) ) {

	function eames_hero_slider( $area = 'blog', $return = false ) {

		$number_of_slides = get_theme_mod( 'eames_' . $area . '_slider_max_slides' );

		$area_data = eames_get_slideshow_area( $area );

		$slides = false;

		if ( $number_of_slides != 0 && $area_data ) : 

			if ( $return == true ) {

				// Start the output buffer
				ob_start();

			}
		
			?>
		
			<div class="flexslider hero-slider loading bg-black" id="heroslider_<?php echo $area; ?>">
			
				<ul class="slides">
		
					<?php for( $i = 1; $i <= $number_of_slides; $i++ ) : 
						
						// Get the customizer values for the current slideshow area and slide count
						$slide = array(
							'image' 	=> get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_image' ) ? get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_image' ) : '',
							'title' 	=> get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_title' ) ? get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_title' ) : '',
							'subtitle' 	=> get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_subtitle' ) ? get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_subtitle' ) : '',
							'button_text' 	=> get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_button_text' ) ? get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_button_text' ) : '',
							'url' 	=> get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_url' ) ? get_theme_mod( 'eames_' . $area . '_slider_' . $i . '_url' ) : '',
						);

						// Make sure our required fields have values
						if ( $slide['image'] && ( $slide['title'] || $slide['subtitle'] ) ) : ?>
							
							<li class="slide">
								<div class="bg-image dark-overlay" style="background-image: url( <?php echo esc_url( $slide['image'] ); ?> );">
									<div class="section-inner">
										
										<header>

											<h1>
												<?php
												if ( isset( $slide['url'] ) ) echo '<a href="' . esc_url( $slide['url'] ) . '">';
												echo $slide['title'];
												if ( isset( $slide['url'] ) ) echo '</a>'; 
												?>
											</h1>

											<p class="sans-excerpt"><?php echo $slide['subtitle']; ?></p>

											<?php if ( $slide['url'] && $slide['button_text'] ) : ?>

												<div class="button-wrapper">
													<a href="<?php echo esc_url( $slide['url'] ); ?>" class="button white"><?php echo $slide['button_text']; ?></a>
												</div>

											<?php endif; ?>

										</header>

									</div><!-- .section-inner -->
								</div><!-- .bg-image -->
							</li><!-- .slide -->
							
							<?php

						endif;

						// Make sure we reset the $slide variable
						unset( $slide );
				
					endfor; ?>
			
				</ul>
				
			</div>
			
			<?php

			if ( $return == true ) {

				$hero_slider_output = ob_get_contents();

				ob_end_clean();

				return $hero_slider_output;

			}
			
		endif;

	}

}


/* ---------------------------------------------------------------------------------------------
   	CUSTOM CUSTOMIZER CONTROLS
   --------------------------------------------------------------------------------------------- */


if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'EamesSeperator' ) ) :

	// Custom Customizer control that outputs an HR to seperate other controls
	class EamesSeperator extends WP_Customize_Control {
	
		public function render_content() {
			echo '<hr class="eames-customizer-seperator" />';
		}
	}

endif;

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'EamesCustomizerTitle' ) ) :

	// Custom Customizer control that outputs an HR to seperate other controls
	class EamesCustomizerTitle extends WP_Customize_Control {

		// Whitelist content parameter
		public $content = '';

		public function render_content() {
			if ( isset( $this->content ) ) {
				echo '<h2 style="margin: 0 0 5px;">' . $this->content . '</h2>';
			}
		}
	}

endif;


/* ---------------------------------------------------------------------------------------------
   CUSTOMIZER SETTINGS
   --------------------------------------------------------------------------------------------- */


class Eames_Customize {

	public static function eames_register( $wp_customize ) {


		/* Theme Options section ----------------------------- */

		$wp_customize->add_section( 'eames_options', array(
			'title' 		=> __( 'Theme Options', 'eames' ),
			'priority' 		=> 35,
			'capability' 	=> 'edit_theme_options',
			'description' 	=> __( 'Customize the theme settings for Eames.', 'eames' ),
		) );

		// Sticky the site navigation
		$wp_customize->add_setting( 'eames_sticky_nav', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'eames_sanitize_checkbox'
		) );

		$wp_customize->add_control( 'eames_sticky_nav', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'eames_options',
			'label' 		=> __( 'Sticky navigation', 'eames' ),
			'description' 	=> __( 'Keep the site navigation stuck to the top of the window when the visitor has scrolled past it.', 'eames' ),
		) );


		/* Slideshow sections ----------------------------- */

		// Get the slideshow areas

		// Loop through the slideshow areas and create a section with corresponding settings and controls for each
		foreach( $slideshow_areas as $area ) {

			// Add the section
			$wp_customize->add_section( 'eames_' . $area['name'] . '_slider', array(
				'title' 		=> $area['title'],
				'priority' 		=> $area['priority'],
				'capability' 	=> 'edit_theme_options',
				'description' 	=> $area['description']
			) );

			// Add a setting for number of slides
			$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_maxsliders', array(
				'default'			=> 1,
				'sanitize_callback' => 'absint',
				'transport'			=> 'postMessage'
			) );

			$wp_customize->add_control( 'eames_' . $area['name'] . '_slider_maxsliders', array(
				'type' 			=> 'number',
				'section' 		=> 'eames_' . $area['name'] . '_slider',
				'label' 		=> __( 'Number of slides', 'eames' ),
				'description'	=> __( 'Empty slides will be skipped automatically.', 'eames' ),
				'input_attrs'	=> array(
					'min' 			=> 0,
					'max' 			=> 10,
				),
			) );

			// Loop through the number of slides, and add a set of settings for each slide
			for ( $i = 1; $i <= $area['max_slides']; $i++ ) {

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_hr', array() );

				$wp_customize->add_control( new EamesSeperator( $wp_customize, 'eames_' . $area['name'] . '_slider_' . $i . '_hr', array(
					'content' 	=> '',
					'section' 	=> 'eames_' . $area['name'] . '_slider',
				) ) );

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_section_title', array() );

				$wp_customize->add_control( new EamesCustomizerTitle( $wp_customize, 'eames_' . $area['name'] . '_slider_' . $i . '_section_title', array(
					'content' 	=> sprintf( __( 'Slide %s', 'eames' ), $i ),
					'section' 	=> 'eames_' . $area['name'] . '_slider',
				) ) );

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_title', array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'eames_' . $area['name'] . '_slider_' . $i . '_title', array(
					'type' 			=> 'text',
					'section' 		=> 'eames_' . $area['name'] . '_slider',
					'label' 		=> __( 'Title', 'eames' ),
				) );

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_subtitle', array(
					'sanitize_callback' => 'sanitize_textarea_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'eames_' . $area['name'] . '_slider_' . $i . '_subtitle', array(
					'type' 			=> 'textarea',
					'section' 		=> 'eames_' . $area['name'] . '_slider',
					'label' 		=> __( 'Subtitle', 'eames' ),
				) );

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_button_text', array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'eames_' . $area['name'] . '_slider_' . $i . '_button_text', array(
					'default'		=> __( 'Read More', 'eames' ),
					'type' 			=> 'text',
					'section' 		=> 'eames_' . $area['name'] . '_slider',
					'label' 		=> __( 'Button text', 'eames' ),
				) );

				$wp_customize->add_setting( 'eames_' . $area['name'] . '_slider_' . $i . '_url', array(
					'sanitize_callback' => 'sanitize_url',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'eames_' . $area['name'] . '_slider_' . $i . '_url', array(
					'type' 			=> 'url',
					'section' 		=> 'eames_' . $area['name'] . '_slider',
					'label' 		=> __( 'URL', 'eames' ),
				) );

			} // for

		} // foreach $slideshow_areas


		/* Slideshow (shop) section ----------------------------- */


		$wp_customize->add_section( 'eames_shop_slider', array(
			'title' 		=> __( 'Slideshow (shop)', 'eames' ),
			'priority' 		=> 45,
			'capability' 	=> 'edit_theme_options',
			'description' 	=> __( 'Add information to be shown in the slideshow on the shop start page.', 'eames' ),
		) );


		/* Built-in controls ----------------------------- */


		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		
		
		/* Sanitation functions ----------------------------- */

		// Sanitize boolean for checkbox
		function eames_sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}
		
	}

	// Initiate the live preview JS
	public static function eames_customize_preview() {
		wp_enqueue_script( 'eames-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array(  'jquery', 'customize-preview' ), '', true );
	}

	// Initiate the live preview JS
	public static function eames_customize_controls() {
		wp_enqueue_script( 'eames-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array(  'jquery', 'customize-controls' ), '', true );
	}

}

// Setup the Theme Customizer settings and controls
add_action( 'customize_register', array( 'Eames_Customize', 'eames_register' ) );

// Enqueue customize preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init', array( 'Eames_Customize' , 'eames_customize_preview' ) );

// Enqueue customize controls javascript in Theme Customizer admin screen
add_action( 'customize_controls_init', array( 'Eames_Customize' , 'eames_customize_controls' ) );


?>