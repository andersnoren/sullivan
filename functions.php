<?php

/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'wright_setup' ) ) {

	function wright_setup() {
		
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
		add_image_size( 'wright_fullscreen', 1860, 9999 );
		
		// Custom logo
		add_theme_support( 'custom-logo', array(
			'height'      => 300,
			'width'       => 600,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Post formats
		add_theme_support( 'post-formats', array( 'gallery' ) );

		// Declare WooCommerce support
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
		// Title tag
		add_theme_support( 'title-tag' );
		
		// Add nav menu
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'wright' ) );
		register_nav_menu( 'mobile-menu', __( 'Mobile Menu', 'wright' ) );
		register_nav_menu( 'social', __( 'Social Menu', 'wright' ) );
		
		// Add excerpts to pages
		add_post_type_support( 'page', array( 'excerpt' ) );
		
		// HTML5 semantic markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'wright', get_template_directory() . '/languages' );

	}
	add_action( 'after_setup_theme', 'wright_setup' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_load_style' ) ) {

	function wright_load_style() {
		if ( ! is_admin() ) {
			wp_register_style( 'wright-google-fonts', 'https://fonts.googleapis.com/css?family=Archivo:400,400i,500,500i,700,700i&amp;subset=latin-ext', array(), null );
			wp_register_style( 'wright-fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.css' );

			$dependencies = array( 'wright-google-fonts', 'wright-fontawesome' );

			// Add WooCommerce styles, if WC is activated
			if ( wright_is_woocommerce_activated() ) {
				wp_register_style( 'wright-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce-style.css' );
				$dependencies[] = 'wright-woocommerce';
			}

			wp_enqueue_style( 'wright-style', get_template_directory_uri() . '/style.css', $dependencies );
		} 
	}
	add_action( 'wp_enqueue_scripts', 'wright_load_style' );

}


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_add_editor_styles' ) ) {

	function wright_add_editor_styles() {
		add_editor_style( array( 
			'wright-editor-styles.css', 
			'https://fonts.googleapis.com/css?family=Archivo:400,400i,500,500i,700,700i&amp;subset=latin-ext' 
		) );
	}
	add_action( 'init', 'wright_add_editor_styles' );

}


/* ---------------------------------------------------------------------------------------------
   DEACTIVATE DEFAULT WP GALLERY STYLES
   --------------------------------------------------------------------------------------------- */


add_filter( 'use_default_gallery_style', '__return_false' );


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_enqueue_scripts' ) ) {

	function wright_enqueue_scripts() {

		wp_register_script( 'wright_flexslider', get_template_directory_uri() . '/assets/js/flexslider.min.js', '', true );
		wp_enqueue_script( 'wright_global', get_template_directory_uri() . '/assets/js/global.js', array( 'jquery', 'wright_flexslider' ), '', true );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		global $wp_query;

		// AJAX PAGINATION
		wp_localize_script( 'wright_global', 'ajax_search', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'query_vars'	=> json_encode( $wp_query->query )
		) );

	}
	add_action( 'wp_enqueue_scripts', 'wright_enqueue_scripts' );

}


/* ---------------------------------------------------------------------------------------------
   POST CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_post_classes' ) ) {

	function wright_post_classes( $classes ) {

		global $post;

		// Class indicating presence/lack of post thumbnail
		$classes[] = ( has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail' );
		
		return $classes;
	}
	add_action( 'post_class', 'wright_post_classes' );

}


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_body_classes' ) ) {

	function wright_body_classes( $classes ) {

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
	add_action( 'body_class', 'wright_body_classes' );

}


/* ---------------------------------------------------------------------------------------------
   ADD HTML CLASS IF THERE'S JAVASCRIOPT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_has_js' ) ) {

	function wright_has_js() { 
		?>
		<script>jQuery( 'html' ).removeClass( 'no-js' ).addClass( 'js' );</script>
		<?php
	}
	add_action( 'wp_head', 'wright_has_js' );

}


/* ---------------------------------------------------------------------------------------------
   REGISTER SIDEBAR
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_sidebar_registration' ) ) {
	
	function wright_sidebar_registration() {

		// Arguments used in all register_sidebar() calls
		$shared_args = array(
			'before_title' 	=> '<h3 class="widget-title subheading">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div></div>'
		);

		// Footer #1
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #1', 'wright' ),
			'id' 			=> 'footer-1',
			'description' 	=> __( 'Widgets in this area will be shown in the first column in the footer. The "Contact Information" widget gets special styling treatment on mobile if it is placed here.', 'wright' ),
		), $shared_args ) );

		// Footer #2
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #2', 'wright' ),
			'id' 			=> 'footer-2',
			'description' 	=> __( 'Widgets in this area will be shown in the second column in the footer.', 'wright' ),
		), $shared_args ) );

		// Footer #3
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #3', 'wright' ),
			'id' 			=> 'footer-3',
			'description' 	=> __( 'Widgets in this area will be shown in the third column in the footer.', 'wright' ),
		), $shared_args ) );

		// Footer #4
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #4', 'wright' ),
			'id' 			=> 'footer-4',
			'description' 	=> __( 'Widgets in this area will be shown in the fourth column in the footer.', 'wright' ),
		), $shared_args ) );

		// If WooCommerce is activated, call the blog sidebar "Sidebar Blog"
		if ( wright_is_woocommerce_activated() ) {
			$sidebar_blog_name = __( 'Sidebar Blog', 'wright' );
			$sidebar_description = __( 'Widgets in this area will be shown in the sidebar on regular posts and pages.', 'wright' );
		
		// If not, it's the only sidebar and we can just call it "Sidebar"
		} else {
			$sidebar_blog_name = __( 'Sidebar', 'wright' );
			$sidebar_description = __( 'Widgets in this area will be shown in the sidebar.', 'wright' );
		}

		// Sidebar Blog
		register_sidebar( array_merge( array(
			'name' 			=> $sidebar_blog_name,
			'id'			=> 'sidebar',
			'description' 	=> $sidebar_description,
		), $shared_args ) );

		// Sidebar Shop (only if WC is activated)
		if ( wright_is_woocommerce_activated() ) {

			register_sidebar( array_merge( array(
				'name' 			=> __( 'Sidebar Shop', 'wright' ),
				'id'			=> 'sidebar-shop',
				'description' 	=> __( 'Widgets in this area will be shown in the sidebar on shop pages.', 'wright' ),
			), $shared_args ) );

		}

	}
	add_action( 'widgets_init', 'wright_sidebar_registration' ); 

}


/* ---------------------------------------------------------------------------------------------
   INCLUDE THEME WIDGETS
   --------------------------------------------------------------------------------------------- */


require_once( get_template_directory() . '/widgets/contact-information.php' );
require_once( get_template_directory() . '/widgets/recent-comments.php' );
require_once( get_template_directory() . '/widgets/recent-posts.php' );


/* ---------------------------------------------------------------------------------------------
   REGISTER THEME WIDGETS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_register_widgets' ) ) {

	function wright_register_widgets() {

		// Default widgets
		register_widget( 'wright_contact_information' );
		register_widget( 'wright_recent_comments' );
		register_widget( 'wright_recent_posts' );

	}
	add_action( 'widgets_init', 'wright_register_widgets' );

}


/* ---------------------------------------------------------------------------------------------
   CHECK WHETHER TO OUTPUT POST GALLERY

   @arg		$post_id int	Post ID for which to check whether there's a format gallery gallery
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_has_format_gallery_gallery' ) ) {

	function wright_has_post_gallery( $post_id = '' ) {

		if ( ! $post_id )
			return false;

		if ( get_post_format( $post_id ) == 'gallery' ) {

			$content = get_the_content( $post_id );

			// Check if the post content starts with a gallery shortcode
			if ( substr( $content, 0, 8 ) === "[gallery" ) {
				return true;
			}

		}

		return false;

	}

}


/* ---------------------------------------------------------------------------------------------
   OUTPUT POST SLIDER GALLERY

   @arg		$post_id int	Post ID for which to output the post gallery
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_post_gallery' ) ) {

	function wright_post_gallery( $post_id = '' ) {

		if ( ! $post_id )
			return false;

		$content = get_the_content( $post_id );

		// Check if the post content starts with a gallery shortcode
		if ( substr( $content, 0, 8 ) === "[gallery" ) {

			// Get the IDs of the shortcode
			preg_match( '/\[gallery.*ids=.(.*).\]/', $content, $ids );

			// Build an array from them
			$image_ids = explode( ",", $ids[1] );

			if ( $image_ids ) : ?>
			
				<div class="flexslider post-slider bg-black loading">
				
					<ul class="slides">
			
						<?php foreach( $image_ids as $image_id ) : 
							
							$image = wp_get_attachment_image_src( $image_id, 'post-thumbnail' );

							if ( $image ) :

								$image_url = esc_url( $image[0] );
								$image_caption = esc_attr( wp_get_attachment_caption( $image_id ) );
							
								?>
									
								<li class="slide">

									<img src="<?php echo $image_url; ?>">

									<?php if ( $image_caption ) : ?>

										<p class="slider-caption"><?php echo $image_caption; ?></p>

									<?php endif; ?>
									
								</li><!-- .slide -->

							<?php endif; ?>
								
						<?php endforeach; ?>
				
					</ul>
					
				</div>
				
				<?php
				
			endif; // if $images_id

		}

	}

}


/* ---------------------------------------------------------------------------------------------
   REMOVE GALLERY SHORTCODE IF WE'RE SHOWING THE FORMAT GALLERY POST GALLERY
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'wright_strip_out_post_gallery' ) ) {

	function wright_strip_out_post_gallery( $content ) {

		if ( wright_has_post_gallery( get_the_ID() ) ) {
			
			// Check if the post content starts with a gallery shortcode
			if ( substr( $content, 0, 8 ) === '[gallery' ) {

				$pattern = get_shortcode_regex();

				// Find the gallery shortcode in question
				if ( preg_match( '/'. $pattern .'/s', $content, $matches ) ) {
					$gallery_shortcode = $matches[0];

					// Get the position of the first occurrence of the gallery shortcode (prevents removing multiples, in case they exist)
					$position = strpos( $content, $gallery_shortcode );

					// If we have a position, remove the shortcode from the content
					if ( $position !== false ) {
						$content = substr_replace( $content, '', $position, strlen( $gallery_shortcode ) );
					}
				}

			}

		}

		return $content;

	}
	add_filter( 'the_content', 'wright_strip_out_post_gallery' );

}
   
   
/* ---------------------------------------------------------------------------------------------
	DELIST DEFAULT WIDGETS REPLACE BY THEME ONES
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_unregister_default_widgets' ) ) {

	function wright_unregister_default_widgets() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
	}
	add_action( 'widgets_init', 'wright_unregister_default_widgets', 11 );

}


/* ---------------------------------------------------------------------------------------------
   MODIFY MORE LINK
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_modify_read_more_link' ) ) {

	function wright_modify_read_more_link() {
		return '<a class="more-link button" href="' . get_permalink() . '">' . __( 'Read More', 'wright' ) . '</a>';
	}
	add_filter( 'the_content_more_link', 'wright_modify_read_more_link' );

}


/* ---------------------------------------------------------------------------------------------
   GET COMMENT EXCERPT LENGTH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_get_comment_excerpt' ) ) {

	function wright_get_comment_excerpt( $comment_ID = 0, $num_words = 20 ) {

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


if ( ! function_exists( 'wright_is_woocommerce_activated' ) ) {

	function wright_is_woocommerce_activated() {
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


if ( wright_is_woocommerce_activated() ) {

	// All functions that require Woocommerce functionality to work are contained within this file
	locate_template( 'functions-woocommerce.php', true );

   /* 
	* EXCEPTION:
	* wright_sidebar_registration() and wright_register_widgets() both have  
	* conditional registration of shop specific sidebar areas and widgets.
	* */

}


/* ---------------------------------------------------------------------------------------------
   wright CUSTOM LOGO OUTPUT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_custom_logo' ) ) {

	function wright_custom_logo() {

		// Get the logo
		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		
		if ( $logo ) {

			// For clarity
			$logo_url = esc_url( $logo[0] );
			$logo_width = $logo[1];
			$logo_height = $logo[2];

			// If the retina logo setting is active, reduce the width/height by half
			if ( get_theme_mod( 'wright_retina_logo' ) ) {
				$logo_width = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );
			}

			?>
			
			<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>" class="custom-logo-link">
				<img src="<?php echo $logo_url; ?>" width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" />
			</a>

			<?php
		}

	}

}


/* ---------------------------------------------------------------------------------------------
   REMOVE ARCHIVE PREFIXES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_remove_archive_title_prefix' ) ) {

	function wright_remove_archive_title_prefix( $title ) {
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
				$title = _x( 'Asides', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'wright' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'wright' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} else {
			$title = __( 'Archives', 'wright' );
		}
		return $title;
	}
	add_filter( 'get_the_archive_title', 'wright_remove_archive_title_prefix' );

}


/* ---------------------------------------------------------------------------------------------
   GET ARCHIVE PREFIX
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_get_archive_title_prefix' ) ) {

	function wright_get_archive_title_prefix() {
		if ( is_category() ) {
			$title_prefix = __( 'Category', 'wright' );
		} elseif ( is_tag() ) {
			$title_prefix = __( 'Tag', 'wright' );
		} elseif ( is_author() ) {
			$title_prefix = __( 'Author', 'wright' );
		} elseif ( is_year() ) {
			$title_prefix = __( 'Year', 'wright' );
		} elseif ( is_month() ) {
			$title_prefix = __( 'Month', 'wright' );
		} elseif ( is_day() ) {
			$title_prefix = __( 'Day', 'wright' );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title_prefix = $tax->labels->singular_name;
		} else {
			$title_prefix = __( 'Archives', 'wright' );
		}
		return $title_prefix;
	}

}


/* ---------------------------------------------------------------------------------------------
   HEADER SEARCH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_header_search' ) ) {

	function wright_header_search() { ?>

		<div class="header-search">

			<form role="search" method="get" class="header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'wright' ); ?></span>
				<label for="header-search-field"></label>
				<input type="search" id="header-search-field" class="ajax-search-field" placeholder="<?php _e( 'Search', 'wright' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" autocomplete="off" />
				
				<?php

				$defaults = array( 'post', 'page' );

				if ( wright_is_woocommerce_activated() ) {
					$defaults[] = 'product';
				}

				$post_types_in_search = get_theme_mod( 'wright_filter_search_post_types', $defaults );

				foreach( $post_types_in_search as $post_type ) {
					echo '<input type="hidden" name="post_type" value="' . esc_attr( $post_type ) . '">';
				}

				?>

			</form>

			<div class="compact-search-results ajax-search-results modal arrow-left">

				<?php // Content is added to this element by the wright_ajax_search() function ?>

			</div>

		</div><!-- .header-search -->


		<?php
	}
}


/* ---------------------------------------------------------------------------------------------
   AJAX SEARCH
   This function is called when the ajax search fields are updated
   --------------------------------------------------------------------------------------------- */


function wright_ajax_search() {

	$string = json_decode( stripslashes( $_POST['query_data'] ), true );

	if ( $string ) :

		$args = array(
			's'					=> $string,
			'posts_per_page'	=> 5,
			'post_status'		=> 'publish',
		);

		// Exclude WooCommerce account/cart pages, if WC is activated
		if ( wright_is_woocommerce_activated() ) {
			$args['post__not_in'] = wright_woo_get_woocommerce_pages();
		}

		// Limit post types to the search post typ setting, if it has been set
		if ( get_theme_mod( 'wright_filter_search_post_types' ) ) {
			$args['post_type'] = get_theme_mod( 'wright_filter_search_post_types' );
		}

		$ajax_query = new WP_Query( $args );

		if ( $ajax_query->have_posts() ) {
			
			?>

			<ul class="wright-widget-list">
				
				<?php

				// Custom loop
				while ( $ajax_query->have_posts() ) : $ajax_query->the_post(); 

					$post_format = get_post_format() ? esc_attr( get_post_format() ) : 'standard';
					$post_type = esc_attr( get_post_type() );
				
					?>

					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					
							<?php
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
							$image_url = $image ? esc_url( $image[0] ) : wright_get_fallback_image_url();
							?>
								
							<div class="post-image" style="background-image: url( <?php echo $image_url; ?> );"></div>
							
							<div class="inner">
											
								<p class="title"><?php the_title(); ?></p>

								<?php if ( $post_type == 'post' ) : ?>

									<p class="meta"><?php the_time( get_option( 'date_format' ) ); ?></p>

								<?php elseif( $post_type == 'product' ) : 

									global $product; ?>

									<p class="meta"><?php echo $product->get_price_html(); ?></p>
									
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

				<a class="show-all" href="<?php echo esc_url( add_query_arg( 's', $string, home_url() ) ); ?>"><span><?php printf( _n( 'Show %s result', 'Show all %s results', $ajax_query->found_posts, 'wright' ), $ajax_query->found_posts ); ?></span></a>

			<?php endif; ?>

			<?php

		} else {

			echo '<p class="no-results-message">' . __( 'We could not find anything that matches your search query. Please try again.', 'wright' ) . '</p>';

		}

	endif; // if string

	die();
}
add_action( 'wp_ajax_nopriv_ajax_search_results', 'wright_ajax_search' );
add_action( 'wp_ajax_ajax_search_results', 'wright_ajax_search' );


/* ---------------------------------------------------------------------------------------------
   MENU WALKER WITH SUB NAV TOGGLE
   --------------------------------------------------------------------------------------------- */


class Wright_Walker_with_Sub_Toggles extends Walker_Nav_Menu {
	

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
	That slideshow will then be available for output by calling wright_hero_slider() with your 
	area name as the function argument.
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_get_slideshow_area' ) ) {

	function wright_get_slideshow_area( $area = '' ) {

		// Blog slideshow area
		$slideshow_areas = array(
			'blog' => array(
				'name'			=> 'blog',
				'title' 		=> __( 'Slideshow (blog)', 'wright' ),
				'description' 	=> __( 'Add information to be shown in the slideshow on the blog start page.', 'wright' ),
				'priority'		=> 40,
				'max_slides'	=> 10,
			),
		);

		// Shop slideshow area (provided WC is installed and active)
		if ( wright_is_woocommerce_activated() ) {
			$slideshow_areas['shop'] = array(
				'name'			=> 'shop',
				'title' 		=> __( 'Slideshow (shop)', 'wright' ),
				'description' 	=> __( 'Add information to be shown in the slideshow on the shop start page.', 'wright' ),
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
   GET FALLBACK IMAGE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_get_fallback_image_url' ) ) {

	function wright_get_fallback_image_url() {

		$fallback_image_id = get_theme_mod( 'wright_fallback_image' );

		if ( $fallback_image_id ) {
			$fallback_image = wp_get_attachment_image_src( $fallback_image_id, 'full' );
		}

		$fallback_image_url = isset( $fallback_image ) ? esc_url( $fallback_image[0] ) : get_template_directory_uri() . '/assets/images/default-fallback-image.png';

		return $fallback_image_url;
 
	}

}


/* ---------------------------------------------------------------------------------------------
   CHECK WHETHER STRING HAS WOOCOMMERCE SHORTCODES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_string_has_woo_shortcodes' ) ) {

	function wright_string_has_woo_shortcodes( $content ) {

		// Check whether we have WooCommerce shortcode on this page
		if ( has_shortcode( $content, 'woocommerce_my_account' )
		|| has_shortcode( $content, 'woocommerce_checkout' )
		|| has_shortcode( $content, 'woocommerce_cart' ) ) {
			return true;
		}

		return false;

	}

}


/* ---------------------------------------------------------------------------------------------
   HERO SLIDER
   --------------------------------------------------------------------------------------------- */


   if ( ! function_exists( 'wright_hero_slider' ) ) {

	function wright_hero_slider( $area = 'blog', $return = false ) {

		// Get the number of slides to output
		$number_of_slides = absint( get_theme_mod( 'wright_' . $area . '_slider_max_slides' ) );

		// Get the arguments for the area in question
		$area_data = wright_get_slideshow_area( $area );

		if ( $number_of_slides != 0 && $area_data ) : 

			// If we're returning the slider...
			if ( $return == true ) {

				// ...start the output buffer
				ob_start();

			}

			$slideshow_speed = get_theme_mod( 'wright_' . $area . '_slider_speed' ) ? absint( get_theme_mod( 'wright_' . $area . '_slider_speed' ) ) : 7000;
		
			?>
		
			<div class="flexslider hero-slider loading bg-black" data-slideshow-speed="<?php echo $slideshow_speed; ?>" id="heroslider_<?php echo $area; ?>">
			
				<ul class="slides">
		
					<?php for( $i = 1; $i <= $number_of_slides; $i++ ) : 
						
						// Get the customizer values for the current slideshow area and slide count
						$slide = array(
							'image' 	=> get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_image' ) ? get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_image' ) : '',
							'title' 	=> get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_title' ) ? get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_title' ) : '',
							'subtitle' 	=> get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_subtitle' ) ? get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_subtitle' ) : '',
							'button_text' 	=> get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_button_text' ) ? get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_button_text' ) : '',
							'url' 	=> get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_url' ) ? get_theme_mod( 'wright_' . $area . '_slider_' . $i . '_url' ) : '',
						);

						$slide_image_url = '';

						// Check if the id in the image customizer setting has a file to go along with it
						if ( $slide['image'] ) {
							$slide_image = wp_get_attachment_image_src( $slide['image'], 'wright_fullscreen' );
							if ( $slide_image ) {
								$slide_image_url = $slide_image[0];
							}
						}

						// If we're in the customizer, always show the empty slides – if not, only show the ones with values
						// Kudos Johanna for the UX input <3
						if ( is_customize_preview() || ( $slide['image'] || $slide['title'] || $slide['subtitle'] ) ) : 

							$extra_slide_classes = '';

							$only_image = false;

							if ( $slide['image'] && ( ! $slide['title'] && ! $slide['subtitle'] ) ) {
								$only_image = true;
								$extra_slide_classes .= ' only-image';
							}
						
							?>
							
							<li class="slide<?php echo esc_attr( $extra_slide_classes ); ?>">

								<?php 
								// If the only content is an image and a URL is set, make the wrapper a link pointing to the URL
								if ( $only_image && $slide['url'] ) {
									$opening_element = 'a href="' . $slide['url'] . '"';
									$closing_element = 'a';
								} else {
									$opening_element = 'div';
									$closing_element = 'div';
								}
								?>
								<<?php echo $opening_element; ?> class="bg-image dark-overlay"<?php if ( $slide_image_url ) echo ' style="background-image: url( ' . esc_url( $slide_image_url ) . ' );"'; ?>>
									<div class="section-inner">
										
										<header>

											<?php if ( $slide['title'] ) : ?>

												<h1>
													<?php
													if ( $slide['url'] ) echo '<a href="' . esc_url( $slide['url'] ) . '">';
													echo esc_attr( $slide['title'] );
													if ( $slide['url'] ) echo '</a>'; 
													?>
												</h1>

											<?php endif; ?>

											<?php if ( $slide['subtitle'] ) : ?>

												<p class="sans-excerpt"><?php echo esc_attr( $slide['subtitle'] ); ?></p>

											<?php endif; ?>

											<?php if ( $slide['url'] && $slide['button_text'] ) : ?>

												<div class="button-wrapper">
													<?php 
													
													// If we're wrapping the slide in a link, we need to output the "button" as a div to prevent element breakage
													if ( $opening_element == 'div' ) : ?>
														<a href="<?php echo esc_url( $slide['url'] ); ?>" class="button white"><?php echo esc_attr( $slide['button_text'] ); ?></a>
													<?php else : ?>
														<div class="button white"><?php echo esc_attr( $slide['button_text'] ); ?></div>
													<?php endif; ?>
												</div>

											<?php endif; ?>

										</header>

									</div><!-- .section-inner -->
								</<?php echo $closing_element; ?>><!-- .bg-image -->
							</li><!-- .slide -->
							
							<?php

						endif;

						// Make sure we reset the $slide variable
						unset( $slide );
				
					endfor; ?>
			
				</ul>
				
			</div>
			
			<?php

			// If we're returning, get the output buffer contents and return them
			if ( $return == true ) {

				$hero_slider_output = ob_get_contents();

				ob_end_clean();

				return $hero_slider_output;

			}
			
		endif;

	}

}


/* ---------------------------------------------------------------------------------------------
   	LIMIT SEARCH RESULTS BY POST TYPE	
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'wright_search_results_filter' ) ) {

	function wright_search_results_filter( $query ) {
	
		if ( $query->is_search && ! is_admin() ) {

			// Get the value of the customizer setting (second arg: default value)
			$post_types_in_search = get_theme_mod( 'wright_filter_search_post_types', array( 'post', 'page', 'product' ) );

			// Set the query to the specific post types
			$query->set( 'post_type', $post_types_in_search );
		}
	
		return $query;
	} 
	add_filter( 'pre_get_posts', 'wright_search_results_filter' );

}


/* ---------------------------------------------------------------------------------------------
   	CUSTOM CUSTOMIZER CONTROLS
   --------------------------------------------------------------------------------------------- */


if ( class_exists( 'WP_Customize_Control' ) ) :

	if ( ! class_exists( 'wright_Customize_Control_Seperator' ) ) :

		// Custom Customizer control that outputs an HR to seperate other controls
		class Wright_Customize_Control_Seperator extends WP_Customize_Control {
		
			public function render_content() {
				echo '<hr class="wright-customizer-seperator" />';
			}

		}

	endif;

	if ( ! class_exists( 'wright_Customize_Control_Group_Title' ) ) :

		// Custom Customizer control that outputs an HR to seperate other controls
		class Wright_Customize_Control_Group_Title extends WP_Customize_Control {

			// Whitelist content parameter
			public $content = '';

			public function render_content() {
				if ( isset( $this->content ) ) {
					echo '<h2 style="margin: 0 0 5px;">' . esc_attr( $this->content ) . '</h2>';
				}
			}

		}

	endif;

	if ( ! class_exists( 'wright_Customize_Control_Add_Slide' ) ) :

		// Custom Customizer control that outputs a button that increments the max_slides number input
		class Wright_Customize_Control_Add_Slide extends WP_Customize_Control {

			// Whitelist content parameter
			public $content = '';

			public function render_content() {
				if ( isset( $this->content ) ) {
					echo '<a href="#" class="button button-primary" id="button-add-slide" data-slideshow="' . esc_attr( $this->content ) . '">' . __( 'Add slide', 'wright' ) . '</a>';
				}
			}

		}

	endif;

	if ( ! class_exists( 'wright_Customize_Control_Checkbox_Multiple' ) ) :

		// Custom Customizer control that outputs a specified number of checkboxes
		// Based on a solution by Justin Tadlock: http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
		class Wright_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

			public $type = 'checkbox-multiple';

			public function render_content() {

				if ( empty( $this->choices ) )
					return;
					
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>
				<?php endif;
				
				$multi_values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
		
				<ul>
					<?php foreach ( $this->choices as $value => $label ) : ?>
		
						<li>
							<label>
								<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
								<?php echo esc_html( $label ); ?>
							</label>
						</li>
		
					<?php endforeach; ?>
				</ul>
		
				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
				<?php 
			}
		}

	endif;

endif;


/* ---------------------------------------------------------------------------------------------
   CUSTOMIZER SETTINGS
   --------------------------------------------------------------------------------------------- */


class Wright_Customize {

	public static function wright_register( $wp_customize ) {


		/* Theme Options section ----------------------------- */


		$wp_customize->add_section( 'wright_options', array(
			'title' 		=> __( 'Theme Options', 'wright' ),
			'priority' 		=> 35,
			'capability' 	=> 'edit_theme_options',
			'description' 	=> __( 'Customize the theme settings for Wright.', 'wright' ),
		) );

		
		/* Sticky the site navigation ----------------------------- */


		$wp_customize->add_setting( 'wright_sticky_nav', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'wright_sanitize_checkbox'
		) );

		$wp_customize->add_control( 'wright_sticky_nav', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'wright_options',
			'label' 		=> __( 'Sticky navigation', 'wright' ),
			'description' 	=> __( 'Keep the site navigation stuck to the top of the window when the visitor has scrolled past it.', 'wright' ),
		) );


		/* 2X Header Logo ----------------------------- */


		$wp_customize->add_setting( 'wright_retina_logo', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'wright_sanitize_checkbox',
			'transport'			=> 'postMessage'
		) );

		$wp_customize->add_control( 'wright_retina_logo', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'title_tagline',
			'priority'		=> 10,
			'label' 		=> __( 'Retina logo', 'wright' ),
			'description' 	=> __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'wright' ),
		) );

		// Update logo retina setting with selective refresh
		$wp_customize->selective_refresh->add_partial( 'wright_retina_logo', array(
			'selector' 			=> '.header-titles .custom-logo-link',
			'settings' 			=> array( 'wright_retina_logo' ),
			'render_callback' 	=> function(){
				wright_custom_logo();
			},
		) );


		/* Fallback image setting ----------------------------- */


		// Seperator before fallback image
		$wp_customize->add_setting( 'wright_fallback_image_hr', array(
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Seperator( $wp_customize, 'wright_fallback_image_hr', array(
			'section' 	=> 'wright_options',
		) ) );


		// Fallback image setting
		$wp_customize->add_setting( 'wright_fallback_image', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'			=> 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'wright_fallback_image', array(
			'label'			=> __( 'Fallback image', 'wright' ),
			'description'	=> __( 'The selected image will be used when a post or product is missing a featured image. A default fallback image included in the theme will be used if no image is set.', 'wright' ),
			'mime_type'		=> 'image',
			'section' 		=> 'wright_options',
		) ) );


		/* Search Post Type Filter ----------------------------- */


		// Get post types that are public, and visible in search
		$post_types = get_post_types( array(
			'public'				=> true,
			'exclude_from_search'	=> false,
		) );

		$post_types_customizer_values = array();

		// Build an array of post types, with key: name and value: label
		foreach( $post_types as $post_type ) {
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_singular_label = $post_type_obj->labels->singular_name;
			$post_types_customizer_values[$post_type] = $post_type_singular_label;
		}

		// Seperator before post types
		$wp_customize->add_setting( 'wright_post_types_hr', array(
			'sanitize_callback'	=> 'esc_attr',
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Seperator( $wp_customize, 'wright_post_types_hr', array(
			'section' 	=> 'wright_options',
		) ) );

		// Default post types
		$defaults = array( 'post', 'page' );

		// Add products, if WooCommerce is active
		if ( wright_is_woocommerce_activated() ) {
			$defaults[] = 'product';
		}

		// Add multiple checkbox setting for post types
		$wp_customize->add_setting( 'wright_filter_search_post_types', array(
			'default'           => $defaults,
			'sanitize_callback' => 'wright_sanitize_multiple_checkboxes'
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Checkbox_Multiple( $wp_customize, 'wright_filter_search_post_types', array(
			'section' 		=> 'wright_options',
			'label'   		=> __( 'Post types to include in search:', 'wright' ),
			'description'	=> __( 'If you do not select any post types, search results will always display as "No results found".', 'wright' ),
			'choices' 		=> $post_types_customizer_values 
		) ) );


		/* Post Meta Setting ----------------------------- */


		// Seperator before post meta
		$wp_customize->add_setting( 'wright_fallback_image_hr', array(
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Seperator( $wp_customize, 'wright_post_meta_hr', array(
			'section' 	=> 'wright_options',
		) ) );


		// Post Meta Top Setting
		$wp_customize->add_setting( 'wright_post_meta_top', array(
			'default'           => array( 'post-date', 'sticky', 'edit-link' ),
			'sanitize_callback' => 'wright_sanitize_multiple_checkboxes'
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Checkbox_Multiple( $wp_customize, 'wright_post_meta_top', array(
			'section' 		=> 'wright_options',
			'label'   		=> __( 'Post meta top displays:', 'wright' ),
			'description'	=> __( 'Shown above the post titles in the blog.', 'wright' ),
 			'choices' 		=> array(
				'author'		=> __( 'Author', 'wright' ),
				'comments'		=> __( 'Comments', 'wright' ),
				'edit-link'		=> __( 'Edit Link (for logged in users)', 'wright' ),
				'post-date'		=> __( 'Post date', 'wright' ),
				'sticky'		=> __( 'Sticky status', 'wright' ),
			) 
		) ) );


		// Post Meta Bottom Setting
		$wp_customize->add_setting( 'wright_post_meta_bottom', array(
			'default'           => array( 'author', 'categories', 'comments' ),
			'sanitize_callback' => 'wright_sanitize_multiple_checkboxes'
		) );

		$wp_customize->add_control( new Wright_Customize_Control_Checkbox_Multiple( $wp_customize, 'wright_post_meta_bottom', array(
			'section' 		=> 'wright_options',
			'label'   		=> __( 'Post meta bottom displays:', 'wright' ),
			'description'	=> __( 'Shown next to the post content in the blog.', 'wright' ),
			'choices' 		=> array(
				'author'		=> __( 'Author', 'wright' ),
				'categories'	=> __( 'Categories', 'wright' ),
				'comments'		=> __( 'Comments', 'wright' ),
				'edit-link'		=> __( 'Edit Link (for logged in users)', 'wright' ),
				'post-date'		=> __( 'Post date', 'wright' ),
				'sticky'		=> __( 'Sticky status', 'wright' ),
				'tags'			=> __( 'Tags', 'wright' ),
			) 
		) ) );


		/* Slideshow sections ----------------------------- */

		// Get the slideshow areas
		$slideshow_areas = wright_get_slideshow_area();

		// Loop through the slideshow areas and create a section with corresponding settings and controls for each one
		foreach( $slideshow_areas as $area ) {

			// Add the section
			$wp_customize->add_section( 'wright_' . $area['name'] . '_slider', array(
				'title' 		=> $area['title'],
				'priority' 		=> $area['priority'],
				'capability' 	=> 'edit_theme_options',
				'description' 	=> $area['description']
			) );

			// Number of slides setting
			$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_max_slides', array(
				'default'			=> 1,
				'sanitize_callback' => 'absint',
				'transport'			=> 'postMessage'
			) );

			$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_max_slides', array(
				'type' 			=> 'number',
				'section' 		=> 'wright_' . $area['name'] . '_slider',
				'label' 		=> __( 'Number of slides', 'wright' ),
				'description'	=> __( 'Empty slides will be skipped automatically.', 'wright' ),
				'input_attrs'	=> array(
					'min' 			=> 0,
					'max' 			=> $area['max_slides'],
				),
			) );

			// Slideshow speed setting
			$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_speed', array(
				'default'			=> 7000,
				'sanitize_callback' => 'absint',
				'transport'			=> 'postMessage'
			) );

			$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_speed', array(
				'type' 			=> 'number',
				'section' 		=> 'wright_' . $area['name'] . '_slider',
				'label' 		=> __( 'Slideshow duration', 'wright' ),
				'description'	=> __( 'How long each slide should be shown, in milliseconds.', 'wright' ),
				'input_attrs'	=> array(
					'min' 			=> 1000,
					'step'			=> 100,			
				),
			) );

			// Loop through the number of slides, and add a set of settings for each slide
			for ( $i = 1; $i <= $area['max_slides']; $i++ ) {

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_hr', array(
					'sanitize_callback' => 'esc_attr',
				) );

				$wp_customize->add_control( new Wright_Customize_Control_Seperator( $wp_customize, 'wright_' . $area['name'] . '_slider_' . $i . '_hr', array(
					'content' 	=> '',
					'section' 	=> 'wright_' . $area['name'] . '_slider',
				) ) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_section_title', array(
					'sanitize_callback' => 'sanitize_text_field',
				) );

				$wp_customize->add_control( new Wright_Customize_Control_Group_Title( $wp_customize, 'wright_' . $area['name'] . '_slider_' . $i . '_section_title', array(
					'content' 	=> sprintf( __( 'Slide %s', 'wright' ), $i ),
					'section' 	=> 'wright_' . $area['name'] . '_slider',
				) ) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_image', array(
					'sanitize_callback' => 'absint',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'wright_' . $area['name'] . '_slider_' . $i . '_image', array(
					'label'		=> __( 'Background image', 'wright' ),
					'mime_type'	=> 'image',
					'section' 	=> 'wright_' . $area['name'] . '_slider',
				) ) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_title', array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_' . $i . '_title', array(
					'type' 			=> 'text',
					'section' 		=> 'wright_' . $area['name'] . '_slider',
					'label' 		=> __( 'Title', 'wright' ),
				) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_subtitle', array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_' . $i . '_subtitle', array(
					'type' 			=> 'text',
					'section' 		=> 'wright_' . $area['name'] . '_slider',
					'label' 		=> __( 'Subtitle', 'wright' ),
				) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_button_text', array(
					'default'			=> __( 'Read More', 'wright' ),
					'sanitize_callback' => 'sanitize_text_field',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_' . $i . '_button_text', array(
					'type' 			=> 'text',
					'section' 		=> 'wright_' . $area['name'] . '_slider',
					'label' 		=> __( 'Button text', 'wright' ),
				) );

				$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_' . $i . '_url', array(
					'sanitize_callback' => 'esc_url_raw',
					'transport'			=> 'postMessage'
				) );

				$wp_customize->add_control( 'wright_' . $area['name'] . '_slider_' . $i . '_url', array(
					'type' 			=> 'url',
					'section' 		=> 'wright_' . $area['name'] . '_slider',
					'label' 		=> __( 'URL', 'wright' ),
					'input_attrs'	=> array(
						'placeholder' 	=> 'http://'
					),
				) );

				// Update the hero slider using partial refresh
				$wp_customize->selective_refresh->add_partial( 'wright_' . $area['name'] . '_slider_' . $i . '_partial_refresh', [
					'selector'            => "#heroslider_" . $area['name'],
					'settings'            => [
						'wright_' . $area['name'] . '_slider_max_slides',
						'wright_' . $area['name'] . '_slider_speed',
						'wright_' . $area['name'] . '_slider_' . $i . '_image',
						'wright_' . $area['name'] . '_slider_' . $i . '_title',
						'wright_' . $area['name'] . '_slider_' . $i . '_subtitle',
						'wright_' . $area['name'] . '_slider_' . $i . '_button_text',
						'wright_' . $area['name'] . '_slider_' . $i . '_url',
					],
					'render_callback'     => function( $area ) { 
						// Arguments: slideshow area to output, whether to return
						return wright_hero_slider( 'blog', true );
					},
				] );

			} // for

			$wp_customize->add_setting( 'wright_' . $area['name'] . '_slider_add_slide', array(
				'sanitize_callback'	=> 'esc_attr',
			) );

			$wp_customize->add_control( new Wright_Customize_Control_Add_Slide( $wp_customize, 'wright_' . $area['name'] . '_slider_add_slide', array(
				'content' 	=> $area['name'],
				'section' 	=> 'wright_' . $area['name'] . '_slider',
			) ) );

		} // foreach $slideshow_areas


		/* Built-in controls ----------------------------- */


		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		// Update blogname with selective refresh
		$wp_customize->selective_refresh->add_partial( 'wright_header_site_title', array(
			'selector' => '.header-titles .site-title .site-name',
			'settings' => array( 'blogname' ),
			'render_callback' => function() {
				return get_bloginfo( 'name', 'display' );
			},
		) );

		// Update blogdescription with selective refresh
		$wp_customize->selective_refresh->add_partial( 'wright_header_site_description', array(
			'selector' => '.header-titles .site-description',
			'settings' => array( 'blogdescription' ),
			'render_callback' => function() {
				return get_bloginfo( 'description', 'display' );
			},
		) );
		
		
		/* Sanitation functions ----------------------------- */

		// Sanitize boolean for checkbox
		function wright_sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}

		// Sanitize booleans for multiple checkboxes
		function wright_sanitize_multiple_checkboxes( $values ) {
			$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;
			return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
		}
		
	}

	// Initiate the customize controls js
	public static function wright_customize_controls() {
		wp_enqueue_script( 'wright-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array(  'jquery', 'customize-controls' ), '', true );
	}

	// Initiate the customize preview js
	public static function wright_customize_preview() {
		wp_enqueue_script( 'wright-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array(  'jquery', 'customize-preview' ), '', true );
	}

}

// Setup the Theme Customizer settings and controls
add_action( 'customize_register', array( 'wright_Customize', 'wright_register' ) );

// Enqueue customize controls javascript in Theme Customizer admin screen
add_action( 'customize_controls_init', array( 'wright_Customize' , 'wright_customize_controls' ) );

// Enqueue customize preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init', array( 'wright_Customize' , 'wright_customize_preview' ) );



?>