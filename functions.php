<?php

/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'sullivan_setup' ) ) {

	function sullivan_setup() {
		
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
		add_image_size( 'sullivan_fullscreen', 1860, 9999 );
		
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
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'sullivan' ) );
		register_nav_menu( 'mobile-menu', __( 'Mobile Menu', 'sullivan' ) );
		register_nav_menu( 'social', __( 'Social Menu', 'sullivan' ) );
		
		// Add excerpts to pages
		add_post_type_support( 'page', array( 'excerpt' ) );
		
		// HTML5 semantic markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'sullivan', get_template_directory() . '/languages' );

	}
	add_action( 'after_setup_theme', 'sullivan_setup' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_load_style' ) ) {

	function sullivan_load_style() {
		if ( ! is_admin() ) {

			$dependencies = array();

			/**
			 * Translators: If there are characters in your language that are not
			 * supported by Archivo, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$archivo = _x( 'on', 'Archivo font: on or off', 'sullivan' );

			if ( 'off' !== $archivo ) {
				$font_families = array();

				$font_families[] = 'Archivo:400,400i,500,500i,700,700i';

				$query_args = array(
					'family' => urlencode( implode( '|', $font_families ) ),
					'subset' => urlencode( 'latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

				wp_register_style( 'sullivan-google-fonts', $fonts_url, array(), null );
				$dependencies[] = 'sullivan-google-fonts';
			}

			wp_register_style( 'sullivan-fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.css' );
			$dependencies[] = 'sullivan-fontawesome';

			// Add WooCommerce styles, if WC is activated
			if ( sullivan_is_woocommerce_activated() ) {
				wp_register_style( 'sullivan-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce-style.css' );
				$dependencies[] = 'sullivan-woocommerce';
			}

			wp_enqueue_style( 'sullivan-style', get_template_directory_uri() . '/style.css', $dependencies );
		} 
	}
	add_action( 'wp_enqueue_scripts', 'sullivan_load_style' );

}


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_add_editor_styles' ) ) {

	function sullivan_add_editor_styles() {
		add_editor_style( array( 
			'sullivan-editor-styles.css', 
			'https://fonts.googleapis.com/css?family=Archivo:400,400i,500,500i,700,700i&amp;subset=latin-ext' 
		) );
	}
	add_action( 'init', 'sullivan_add_editor_styles' );

}


/* ---------------------------------------------------------------------------------------------
   DEACTIVATE DEFAULT WP GALLERY STYLES
   --------------------------------------------------------------------------------------------- */


add_filter( 'use_default_gallery_style', '__return_false' );


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_enqueue_scripts' ) ) {

	function sullivan_enqueue_scripts() {

		wp_register_script( 'sullivan_flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider.js', '', true );
		wp_enqueue_script( 'sullivan_global', get_template_directory_uri() . '/assets/js/global.js', array( 'jquery', 'sullivan_flexslider' ), '', true );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		global $wp_query;

		// AJAX PAGINATION
		wp_localize_script( 'sullivan_global', 'ajax_search', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'query_vars'	=> json_encode( $wp_query->query )
		) );

	}
	add_action( 'wp_enqueue_scripts', 'sullivan_enqueue_scripts' );

}


/* ---------------------------------------------------------------------------------------------
   SHOW NOTICE ON THEME ACTIVATION
   When the theme is activated, display a notice informing the user about the compatibility 
   plugin (if it isn't active)
   --------------------------------------------------------------------------------------------- */

function sullivan_theme_activation_notice() {

	// If Sullivan compat is already active, don't show the notice
	if ( is_plugin_active( 'sullivan-compat/sullivan-compat.php' ) ) {
		return;
	}

	// Add our notice function to the admin notices
	add_action( 'admin_notices', 'sullivan_theme_activation_notice_output' );

}
add_action( 'after_switch_theme', 'sullivan_theme_activation_notice' );

function sullivan_theme_activation_notice_output() {

	?>

	<div class="updated notice is-dismissible">
        <p><?php printf( __( 'Thanks for installing Sullivan! In order to activate the slideshow functionality, you need to install the Sullivan Compatibility Plugin from the %s.', 'sullivan' ), '<a href="' . admin_url( 'plugin-install.php?s=sullivan&tab=search' ) . '">' . __( 'WordPress.org plugin directory', 'sullivan' ) . '</a>' ); ?></p>
    </div>

	<?php

}


/* ---------------------------------------------------------------------------------------------
   POST CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_post_classes' ) ) {

	function sullivan_post_classes( $classes ) {

		global $post;

		// Class indicating presence/lack of post thumbnail
		$classes[] = ( has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail' );
		
		return $classes;
	}
	add_action( 'post_class', 'sullivan_post_classes' );

}


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_body_classes' ) ) {

	function sullivan_body_classes( $classes ) {

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
	add_action( 'body_class', 'sullivan_body_classes' );

}


/* ---------------------------------------------------------------------------------------------
   ADD HTML CLASS IF THERE'S JAVASCRIOPT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_has_js' ) ) {

	function sullivan_has_js() { 
		?>
		<script>jQuery( 'html' ).removeClass( 'no-js' ).addClass( 'js' );</script>
		<?php
	}
	add_action( 'wp_head', 'sullivan_has_js' );

}


/* ---------------------------------------------------------------------------------------------
   REGISTER SIDEBAR
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_sidebar_registration' ) ) {
	
	function sullivan_sidebar_registration() {

		// Arguments used in all register_sidebar() calls
		$shared_args = array(
			'before_title' 	=> '<h3 class="widget-title subheading">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div></div>'
		);

		// Footer #1
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #1', 'sullivan' ),
			'id' 			=> 'footer-1',
			'description' 	=> __( 'Widgets in this area will be shown in the first column in the footer. The "Contact Information" widget gets special styling treatment on mobile if it is placed here.', 'sullivan' ),
		), $shared_args ) );

		// Footer #2
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #2', 'sullivan' ),
			'id' 			=> 'footer-2',
			'description' 	=> __( 'Widgets in this area will be shown in the second column in the footer.', 'sullivan' ),
		), $shared_args ) );

		// Footer #3
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #3', 'sullivan' ),
			'id' 			=> 'footer-3',
			'description' 	=> __( 'Widgets in this area will be shown in the third column in the footer.', 'sullivan' ),
		), $shared_args ) );

		// Footer #4
		register_sidebar( array_merge( array(
			'name' 			=> __( 'Footer #4', 'sullivan' ),
			'id' 			=> 'footer-4',
			'description' 	=> __( 'Widgets in this area will be shown in the fourth column in the footer.', 'sullivan' ),
		), $shared_args ) );

		// If WooCommerce is activated, call the blog sidebar "Sidebar Blog"
		if ( sullivan_is_woocommerce_activated() ) {
			$sidebar_blog_name = __( 'Sidebar Blog', 'sullivan' );
			$sidebar_description = __( 'Widgets in this area will be shown in the sidebar on regular posts and pages.', 'sullivan' );
		
		// If not, it's the only sidebar and we can just call it "Sidebar"
		} else {
			$sidebar_blog_name = __( 'Sidebar', 'sullivan' );
			$sidebar_description = __( 'Widgets in this area will be shown in the sidebar.', 'sullivan' );
		}

		// Sidebar Blog
		register_sidebar( array_merge( array(
			'name' 			=> $sidebar_blog_name,
			'id'			=> 'sidebar',
			'description' 	=> $sidebar_description,
		), $shared_args ) );

		// Sidebar Shop (only if WC is activated)
		if ( sullivan_is_woocommerce_activated() ) {

			register_sidebar( array_merge( array(
				'name' 			=> __( 'Sidebar Shop', 'sullivan' ),
				'id'			=> 'sidebar-shop',
				'description' 	=> __( 'Widgets in this area will be shown in the sidebar on shop pages.', 'sullivan' ),
			), $shared_args ) );

		}

	}
	add_action( 'widgets_init', 'sullivan_sidebar_registration' ); 

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


if ( ! function_exists( 'sullivan_register_widgets' ) ) {

	function sullivan_register_widgets() {

		// Default widgets
		register_widget( 'sullivan_contact_information' );
		register_widget( 'sullivan_recent_comments' );
		register_widget( 'sullivan_recent_posts' );

	}
	add_action( 'widgets_init', 'sullivan_register_widgets' );

}


/* ---------------------------------------------------------------------------------------------
   CHECK WHETHER TO OUTPUT POST GALLERY

   @arg		$post_id int	Post ID for which to check whether there's a format gallery gallery
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_has_format_gallery_gallery' ) ) {

	function sullivan_has_post_gallery( $post_id = '' ) {

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


if ( ! function_exists( 'sullivan_post_gallery' ) ) {

	function sullivan_post_gallery( $post_id = '' ) {

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
								$image_caption = wp_kses_post( wp_get_attachment_caption( $image_id ) );
							
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

if ( ! function_exists( 'sullivan_strip_out_post_gallery' ) ) {

	function sullivan_strip_out_post_gallery( $content ) {

		if ( sullivan_has_post_gallery( get_the_ID() ) ) {
			
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
	add_filter( 'the_content', 'sullivan_strip_out_post_gallery' );

}
   
   
/* ---------------------------------------------------------------------------------------------
	DELIST DEFAULT WIDGETS REPLACE BY THEME ONES
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_unregister_default_widgets' ) ) {

	function sullivan_unregister_default_widgets() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
	}
	add_action( 'widgets_init', 'sullivan_unregister_default_widgets', 11 );

}


/* ---------------------------------------------------------------------------------------------
   MODIFY MORE LINK
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_modify_read_more_link' ) ) {

	function sullivan_modify_read_more_link() {
		return '<a class="more-link button" href="' . get_permalink() . '">' . __( 'Read More', 'sullivan' ) . '</a>';
	}
	add_filter( 'the_content_more_link', 'sullivan_modify_read_more_link' );

}


/* ---------------------------------------------------------------------------------------------
   GET COMMENT EXCERPT LENGTH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_get_comment_excerpt' ) ) {

	function sullivan_get_comment_excerpt( $comment_ID = 0, $num_words = 20 ) {

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


if ( ! function_exists( 'sullivan_is_woocommerce_activated' ) ) {

	function sullivan_is_woocommerce_activated() {
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


if ( sullivan_is_woocommerce_activated() ) {

	// All functions that require Woocommerce functionality to work are contained within this file
	locate_template( 'functions-woocommerce.php', true );

   /* 
	* EXCEPTION:
	* sullivan_sidebar_registration() and sullivan_register_widgets() both have  
	* conditional registration of shop specific sidebar areas and widgets.
	* */

}


/* ---------------------------------------------------------------------------------------------
   sullivan CUSTOM LOGO OUTPUT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_custom_logo' ) ) {

	function sullivan_custom_logo() {

		// Get the logo
		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		
		if ( $logo ) {

			// For clarity
			$logo_url = esc_url( $logo[0] );
			$logo_width = esc_attr( $logo[1] );
			$logo_height = esc_attr( $logo[2] );

			// If the retina logo setting is active, reduce the width/height by half
			if ( get_theme_mod( 'sullivan_retina_logo' ) ) {
				$logo_width = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );
			}

			?>
			
			<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>" class="custom-logo-link">
				<img src="<?php echo esc_url( $logo_url ); ?>" width="<?php echo esc_attr( $logo_width ); ?>" height="<?php echo esc_attr( $logo_height ); ?>" />
			</a>

			<?php
		}

	}

}


/* ---------------------------------------------------------------------------------------------
   REMOVE ARCHIVE PREFIXES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_remove_archive_title_prefix' ) ) {

	function sullivan_remove_archive_title_prefix( $title ) {
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
				$title = _x( 'Asides', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'sullivan' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'sullivan' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} else {
			$title = __( 'Archives', 'sullivan' );
		}
		return $title;
	}
	add_filter( 'get_the_archive_title', 'sullivan_remove_archive_title_prefix' );

}


/* ---------------------------------------------------------------------------------------------
   GET ARCHIVE PREFIX
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_get_archive_title_prefix' ) ) {

	function sullivan_get_archive_title_prefix() {
		if ( is_category() ) {
			$title_prefix = __( 'Category', 'sullivan' );
		} elseif ( is_tag() ) {
			$title_prefix = __( 'Tag', 'sullivan' );
		} elseif ( is_author() ) {
			$title_prefix = __( 'Author', 'sullivan' );
		} elseif ( is_year() ) {
			$title_prefix = __( 'Year', 'sullivan' );
		} elseif ( is_month() ) {
			$title_prefix = __( 'Month', 'sullivan' );
		} elseif ( is_day() ) {
			$title_prefix = __( 'Day', 'sullivan' );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title_prefix = $tax->labels->singular_name;
		} else {
			$title_prefix = __( 'Archives', 'sullivan' );
		}
		return $title_prefix;
	}

}


/* ---------------------------------------------------------------------------------------------
   HEADER SEARCH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_header_search' ) ) {

	function sullivan_header_search() { ?>

		<div class="header-search">

			<form role="search" method="get" class="header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'sullivan' ); ?></span>
				<label for="header-search-field"></label>
				<input type="search" id="header-search-field" class="ajax-search-field" placeholder="<?php _e( 'Search', 'sullivan' ); ?>" value="<?php echo wp_kses_post( get_search_query() ); ?>" name="s" autocomplete="off" />
				
				<?php

				$defaults = array( 'post', 'page' );

				if ( sullivan_is_woocommerce_activated() ) {
					$defaults[] = 'product';
				}

				$post_types_in_search = get_theme_mod( 'sullivan_filter_search_post_types', $defaults );

				foreach( $post_types_in_search as $post_type ) {
					echo '<input type="hidden" name="post_type" value="' . esc_attr( $post_type ) . '">';
				}

				?>

			</form>

			<div class="compact-search-results ajax-search-results modal arrow-left"></div>

		</div><!-- .header-search -->


		<?php
	}
}


/* ---------------------------------------------------------------------------------------------
   AJAX SEARCH
   This function is called when the ajax search fields are updated
   --------------------------------------------------------------------------------------------- */


function sullivan_ajax_search() {

	$string = json_decode( stripslashes( $_POST['query_data'] ), true );

	if ( $string ) :

		$args = array(
			's'					=> $string,
			'posts_per_page'	=> 5,
			'post_status'		=> 'publish',
		);

		// Exclude WooCommerce account/cart pages, if WC is activated
		if ( sullivan_is_woocommerce_activated() ) {
			$args['post__not_in'] = sullivan_woo_get_woocommerce_pages();
		}

		// Limit post types to the search post typ setting, if it has been set
		if ( get_theme_mod( 'sullivan_filter_search_post_types' ) ) {
			$args['post_type'] = get_theme_mod( 'sullivan_filter_search_post_types' );
		}

		$ajax_query = new WP_Query( $args );

		if ( $ajax_query->have_posts() ) {
			
			?>

			<ul class="sullivan-widget-list">
				
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
							$image_url = $image ? esc_url( $image[0] ) : sullivan_get_fallback_image_url();
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

				<a class="show-all" href="<?php echo esc_url( add_query_arg( 's', $string, home_url() ) ); ?>"><span><?php printf( _n( 'Show %s result', 'Show all %s results', $ajax_query->found_posts, 'sullivan' ), $ajax_query->found_posts ); ?></span></a>

			<?php endif; ?>

			<?php

		} else {

			echo '<p class="no-results-message">' . __( 'We could not find anything that matches your search query. Please try again.', 'sullivan' ) . '</p>';

		}

	endif; // if string

	die();
}
add_action( 'wp_ajax_nopriv_ajax_search_results', 'sullivan_ajax_search' );
add_action( 'wp_ajax_ajax_search_results', 'sullivan_ajax_search' );


/* ---------------------------------------------------------------------------------------------
   MENU WALKER WITH SUB NAV TOGGLE
   --------------------------------------------------------------------------------------------- */


class Sullivan_Walker_with_Sub_Toggles extends Walker_Nav_Menu {
	

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
   GET FALLBACK IMAGE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_get_fallback_image_url' ) ) {

	function sullivan_get_fallback_image_url() {

		$fallback_image_id = get_theme_mod( 'sullivan_fallback_image' );

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


if ( ! function_exists( 'sullivan_string_has_woo_shortcodes' ) ) {

	function sullivan_string_has_woo_shortcodes( $content ) {

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


if ( ! function_exists( 'sullivan_hero_slider' ) ) {

	function sullivan_hero_slider( $area = 'blog', $return = false ) {

		// Get the slides for the area in question
		$slideshow_location = get_term_by( 'slug', $area, 'sullivan_slideshow_location' );

		// No matching slideshow location = no output
		if ( ! $slideshow_location ) {
			return;
		}

		$slides = get_posts( array(
			'post_status'		=> 'publish',
			'post_type'			=> 'sullivan_slideshow',
			'posts_per_page'	=> -1,
			'tax_query'			=> array(
				array(
					'taxonomy'		=> 'sullivan_slideshow_location',
					'terms'			=> $slideshow_location->term_id,
				),
			),
		) );

		// No slides = no output
		if ( ! $slides ) {
			return;
		}

		// If we're returning the slider, start the output buffer
		if ( $return == true ) {
			ob_start();
		}
		
		?>
	
		<div class="flexslider hero-slider loading bg-black" data-slideshow-speed="7000" id="heroslider_<?php echo $area; ?>">
		
			<ul class="slides">
	
				<?php foreach( $slides as $slide ) : 

					// Check if the id in the image customizer setting has a file to go along with it
					if ( has_post_thumbnail( $slide->ID ) ) {
						$slide_image_url = get_the_post_thumbnail_url( $slide->ID, 'sullivan_fullscreen' );
					}
					
					?>
					
					<li class="slide">

						<div class="bg-image dark-overlay"<?php if ( $slide_image_url ) echo ' style="background-image: url( ' . esc_url( $slide_image_url ) . ' );"'; ?>>
							<div class="section-inner">
								
								<header>

									<?php 

									$slide_title = get_post_meta( $slide->ID, 'sullivan_slide_title', true );
									$slide_subtitle = get_post_meta( $slide->ID, 'sullivan_slide_subtitle', true );
									$slide_button_text = get_post_meta( $slide->ID, 'sullivan_slide_button_text', true );
									$slide_button_url = get_post_meta( $slide->ID, 'sullivan_slide_button_url', true );
									
									if ( $slide_title ) : ?>
										<h1><?php echo wp_kses_post( $slide_title ); ?></h1>
									<?php endif;
									
									if ( $slide_subtitle ) : ?>
										<p class="sans-excerpt"><?php echo wp_kses_post( $slide_subtitle ); ?></p>
									<?php endif;

									if ( $slide_button_text && $slide_button_url ) : ?>

										<div class="button-wrapper">
											<a href="<?php echo esc_url( $slide_button_url ); ?>" class="button white"><?php echo wp_kses_post( $slide_button_text ); ?></a>
										</div>

									<?php endif; ?>

								</header>

							</div><!-- .section-inner -->
						</div><!-- .bg-image -->
					</li><!-- .slide -->
						
				<?php endforeach; ?>
		
			</ul>
			
		</div>
			
		<?php

		// If we're returning, get the output buffer contents and return them
		if ( $return == true ) {

			$hero_slider_output = ob_get_contents();

			ob_end_clean();

			return $hero_slider_output;

		}

	}

}


/* ---------------------------------------------------------------------------------------------
   	LIMIT SEARCH RESULTS BY POST TYPE	
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_search_results_filter' ) ) {

	function sullivan_search_results_filter( $query ) {
	
		if ( $query->is_search && ! is_admin() ) {

			// Get the value of the customizer setting (second arg: default value)
			$post_types_in_search = get_theme_mod( 'sullivan_filter_search_post_types', array( 'post', 'page', 'product' ) );

			// Set the query to the specific post types
			$query->set( 'post_type', $post_types_in_search );
		}
	
		return $query;
	} 
	add_filter( 'pre_get_posts', 'sullivan_search_results_filter' );

}


?>