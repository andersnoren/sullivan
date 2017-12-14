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
   HERO SLIDER
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_hero_slider' ) ) {

	function eames_hero_slider() {

		$slides = array(
			array(
				'url' 			=> 'http://www.google.com',
				'title' 		=> 'Tinker & Ace Hotel',
				'subtitle' 		=> 'A limited edition collaboration.',
				'button_text' 	=> 'Read More',
				'image_url' 	=> get_template_directory_uri() . '/assets/images/dummy-hero.jpg',
			),
			array(
				'url' 			=> 'http://www.google.com',
				'title' 		=> 'Tinker & Ace Hotel with a long title',
				'subtitle' 		=> 'A limited edition collaboration.',
				'button_text' 	=> 'Read More',
				'image_url' 	=> get_template_directory_uri() . '/assets/images/dummy-hero.jpg',
			),
			array(
				'url' 			=> 'http://www.google.com',
				'title' 		=> 'Tinker & Ace Hotel',
				'subtitle' 		=> 'A limited edition collaboration.',
				'button_text' 	=> 'Read More',
				'image_url' 	=> get_template_directory_uri() . '/assets/images/dummy-hero.jpg',
			),
			array(
				'url' 			=> 'http://www.google.com',
				'title' 		=> 'Tinker & Ace Hotel',
				'subtitle' 		=> 'A limited edition collaboration.',
				'button_text' 	=> 'Read More',
				'image_url' 	=> get_template_directory_uri() . '/assets/images/dummy-hero.jpg',
			),
		);

		if ( $slides ) : ?>
		
			<div class="flexslider hero-slider loading bg-black">
			
				<ul class="slides">
		
					<?php foreach( $slides as $slide ) : ?>
						
						<li class="slide">
							<div class="bg-image dark-overlay" style="background-image: url( <?php echo $slide['image_url']; ?> );">
								<div class="section-inner">
									
									<header>

										<h1>
											<?php if ( isset( $slide['url'] ) ) echo '<a href="' . $slide['url'] . '">'; ?>
											<?php echo $slide['title']; ?>
											<?php if ( isset( $slide['url'] ) ) echo '</a>'; ?>
										</h1>

										<p class="sans-excerpt"><?php echo $slide['subtitle']; ?></p>

										<div class="button-wrapper">
											<a href="<?php echo $slide['url']; ?>" class="button white"><?php echo $slide['button_text']; ?></a>
										</div>

									</header>

								</div><!-- .section-inner -->
							</div><!-- .bg-image -->
						</li><!-- .slide -->
						
					<?php endforeach; ?>
			
				</ul>
				
			</div>
			
			<?php
			
		endif;

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
   CUSTOMIZER SETTINGS
   --------------------------------------------------------------------------------------------- */


class Eames_Customize {

	public static function eames_register( $wp_customize ) {

		// Add our Customizer section
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
		

		// Make built-in controls use live JS preview
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		
		
		// SANITATION

		// Sanitize boolean for checkbox
		function eames_sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}
		
	}

	// Initiate the live preview JS
	public static function eames_live_preview() {
		wp_enqueue_script( 'eames-themecustomizer', get_template_directory_uri() . '/assets/js/theme-customizer.js', array(  'jquery', 'customize-preview', 'masonry' ), '', true );
	}

}

// Setup the Theme Customizer settings and controls
add_action( 'customize_register', array( 'Eames_Customize', 'eames_register' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init', array( 'Eames_Customize' , 'eames_live_preview' ) );


?>