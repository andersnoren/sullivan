<?php

/*

   WOOCOMMERCE FUNCTIONS
   This file contains all WooCommerce specific hooks and custom functions
   --------------------------------------------------------------------------------------------- */



/* ---------------------------------------------------------------------------------------------
	CUSTOM WRAPPER ELEMENT
	--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'sullivan_woo_theme_wrapper_start' ) ) {

	// Disable defaults
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );

	// Replace with our own
	function sullivan_woo_theme_wrapper_start() {
		?>
		<main id="site-content">
			<div class="section-inner">
		<?php
	}
	add_action( 'woocommerce_before_main_content', 'sullivan_woo_theme_wrapper_start', 10 );

}

if ( ! function_exists( 'sullivan_woo_theme_wrapper_end' ) ) {

	// Disable defaults
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	// End wrapper
	function sullivan_woo_theme_wrapper_end() {
		?>
			</div><!-- .section-inner -->
		</main><!-- #site-content -->
		<?php
	}
	add_action( 'woocommerce_after_main_content', 'sullivan_woo_theme_wrapper_end', 10 );

}


/* ---------------------------------------------------------------------------------------------
	REMOVE STUFF
	--------------------------------------------------------------------------------------------- */


// Disable default Woocommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Conditional removals of actions
if ( ! function_exists( 'sullivan_woo_remove_actions' ) ) {

	function sullivan_woo_remove_actions() {

		// Remove breadcrumbs on the front page of the shop
		if ( is_shop() ) remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Remove rating from loop items
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

		// Remove add to cart button from loop items
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		// Remove default output of sidebars
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// Remove output of categories on single products from summary (added to the_content via filter)
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

		// Move cross_sell from cart_collaterals to after_cart
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

	}
	add_action( 'wp_head', 'sullivan_woo_remove_actions' );

}


/* ---------------------------------------------------------------------------------------------
	REMOVE TITLE ON THE FRONT PAGE
	--------------------------------------------------------------------------------------------- */

// Remove title on the front page
if ( ! function_exists( 'sullivan_woo_remove_title_on_shop_home' ) ) {

	function sullivan_woo_remove_title_on_shop_home( $title ) {

		// Remove title on the front page of the shop
		if ( is_shop() && ! get_search_query() ) {
			$title = '';
		}

		return $title;

	}
	add_action( 'woocommerce_show_page_title', 'sullivan_woo_remove_title_on_shop_home' );

}


/* ---------------------------------------------------------------------------------------------
	WOOCOMMERCE SPECIFIC BODY CLASSES
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_body_classes' ) ) {

	function sullivan_woo_body_classes( $classes ) {

		$queried_object = get_queried_object();

		// Add class to the front page of the shop
		if ( is_shop() && ! get_search_query() ) {
			$classes[] = 'shop-start';
		}

		// Check if a Woocommerce term has a thumbnail image set
		if ( is_woocommerce() && is_archive() && $queried_object && isset( $queried_object->term_id ) ) {
			if ( get_woocommerce_term_meta( $queried_object->term_id, 'thumbnail_id', true ) ) {
				$classes[] = 'term-has-image';
			} else {
				$classes[] = 'term-missing-image';
			}
		}

		// Add class if we're on an empty cart
		if ( is_cart() && WC()->cart->get_cart_contents_count() == 0 ) {
			$classes[] = 'viewing-empty-cart';
		}

		// Add class if we're on the account page and not logged in
		if ( ( is_account_page() && ! is_user_logged_in() ) || is_wc_endpoint_url( 'lost-password' ) ) {

			$classes[] = 'account-form';

			// Lost password = single form
			if ( is_wc_endpoint_url( 'lost-password' ) ) {

				$classes[] = 'single-account-form';

			// If the form query argument is set, add class indicating which form is visible
			} elseif ( isset( $_GET['form'] ) && get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {

				$classes[] = 'single-account-form';

				if ( $_GET['form'] == 'registration' ) {
					$classes[] = 'showing-registration-form';
				} else {
					$classes[] = 'showing-login-form';
				}

			// If not, we're either showing one or both forms, depending on the WooCommerce registration setting
			} else {
				if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
					$classes[] = 'both-account-forms';
				} else {
					$classes[] = 'single-account-form';
				}
			}
		}

		return $classes;

	}
	add_action( 'body_class', 'sullivan_woo_body_classes', 1 );

} // End if().


/* ---------------------------------------------------------------------------------------------
	ADD FORGOTTEN PASSWORD AND REGISTRATION LINKS TO LOGIN FORM
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_add_login_footer' ) ) {

	function sullivan_woo_add_login_footer() {
		?>

		<div class="login-registration-form-links">

			<p class="lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost password', 'sullivan' ); ?></a>
			</p>

			<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

				<p class="register_link">
					<span class="sep">&bull;</span><a href="<?php echo esc_url( add_query_arg( 'form', 'registration', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); ?>"><?php _e( 'Create account', 'sullivan' ); ?></a>
				</p>

			<?php endif; ?>

		</div>

		<?php

	}
	add_action( 'woocommerce_login_form_end', 'sullivan_woo_add_login_footer', 5 );

}


/* ---------------------------------------------------------------------------------------------
	ADD THE HERO SLIDER TO THE SHOP HOME PAGE
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_hero_slider' ) ) {

	function sullivan_woo_hero_slider() {

		global $paged;
		$paged_val = $paged ? $paged : 1;

		if ( is_shop() && ! get_search_query() && $paged_val == 1 ) {
			sullivan_hero_slider( 'shop' );
		}

	}
	add_action( 'woocommerce_before_main_content', 'sullivan_woo_hero_slider', 5 );

}


/* ---------------------------------------------------------------------------------------------
	ADD PRODUCT SINGLE META TO BOTTOM OF CONTENT
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_product_meta_in_content' ) ) {

	function sullivan_woo_product_meta_in_content( $content ) {

		// On products, get the single meta and append it to the content
		if ( is_singular( 'product' ) ) {

			ob_start();

			woocommerce_template_single_meta();

			$content .= ob_get_clean();

		}

		return $content;

	}
	add_filter( 'the_content', 'sullivan_woo_product_meta_in_content' );

}


/* ---------------------------------------------------------------------------------------------
   ADJUST WOOCOMMERCE PAGINATION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_pagination_arguments' ) ) {

	function sullivan_woo_pagination_arguments( $args ) {

		$args['prev_text'] = __( 'Previous', 'sullivan' );
		$args['next_text'] = __( 'Next', 'sullivan' );

		return $args;

	}
	add_filter( 'woocommerce_pagination_args', 'sullivan_woo_pagination_arguments' );

}


/* ---------------------------------------------------------------------------------------------
   EXCLUDE WOOCOMMERCE PAGES FROM SEARCH
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_exclude_wc_pages_in_search' ) ) {

	function sullivan_woo_exclude_wc_pages_in_search( $query ) {

		if ( $query->is_search && ! is_admin() ) {

			$woocommerce_pages = sullivan_woo_get_woocommerce_pages();

			$query->set( 'post__not_in', $woocommerce_pages );

		}

		return $query;

	}
	add_filter( 'pre_get_posts', 'sullivan_woo_exclude_wc_pages_in_search' );

}


/* ---------------------------------------------------------------------------------------------
   GET WOOCOMMERCE PAGES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_get_woocommerce_pages' ) ) {

	function sullivan_woo_get_woocommerce_pages() {

		$woocommerce_pages = array(
			get_option( 'woocommerce_shop_page_id' ),
			get_option( 'woocommerce_cart_page_id' ),
			get_option( 'woocommerce_checkout_page_id' ),
			get_option( 'woocommerce_myaccount_page_id' ),
			get_option( 'woocommerce_edit_address_page_id' ),
			get_option( 'woocommerce_view_order_page_id' ),
			get_option( 'woocommerce_change_password_page_id' ),
			get_option( 'woocommerce_logout_page_id' ),
		);

		return $woocommerce_pages;

	}
	add_filter( 'pre_get_posts', 'sullivan_woo_get_woocommerce_pages' );

}


/* ---------------------------------------------------------------------------------------------
   ADJUST WOOCOMMERCE BREADCRUMBS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_breadcrumbs_arguments' ) ) {

	function sullivan_woo_breadcrumbs_arguments( $args ) {

		$args['delimiter'] = '<span class="seperator"></span>';
		$args['wrap_before'] = '<nav class="breadcrumbs">';

		return $args;

	}
	add_filter( 'woocommerce_breadcrumb_defaults', 'sullivan_woo_breadcrumbs_arguments' );

}


/* ---------------------------------------------------------------------------------------------
   ADJUST WOOCOMMERCE SORTING STRINGS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_catalog_orderby_arguments' ) ) {

	function sullivan_woo_catalog_orderby_arguments( $args ) {

		$args['menu_order'] = __( 'Default sorting', 'sullivan' );
		$args['popularity'] = __( 'By popularity', 'sullivan' );
		$args['rating']     = __( 'By average rating', 'sullivan' );
		$args['date']       = __( 'By newness', 'sullivan' );
		$args['price']      = __( 'Price: low to high', 'sullivan' );
		$args['price-desc'] = __( 'Price: high to low', 'sullivan' );

		return $args;

	}
	add_filter( 'woocommerce_catalog_orderby', 'sullivan_woo_catalog_orderby_arguments' );

}

$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array() );


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT UPPER AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_archive_header_tools_opening' ) ) {
	function sullivan_woo_wrap_archive_header_tools_opening() {
		echo '<div class="archive-header-tools">';
	}
	add_action( 'woocommerce_before_shop_loop', 'sullivan_woo_wrap_archive_header_tools_opening', 15 );
}

if ( ! function_exists( 'sullivan_woo_wrap_archive_header_tools_closing' ) ) {
	function sullivan_woo_wrap_archive_header_tools_closing() {
		echo '</div>';
	}
	add_action( 'woocommerce_before_shop_loop', 'sullivan_woo_wrap_archive_header_tools_closing', 35 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT UPPER AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_single_product_upper_opening' ) ) {
	function sullivan_woo_wrap_single_product_upper_opening() {
		echo '<section class="product-upper-wrapper">';
	}
	add_action( 'woocommerce_before_single_product_summary', 'sullivan_woo_wrap_single_product_upper_opening', 1 );
}

if ( ! function_exists( 'sullivan_woo_wrap_single_product_upper_closing' ) ) {
	function sullivan_woo_wrap_single_product_upper_closing() {
		echo '</section>';
	}
	add_action( 'woocommerce_after_single_product_summary', 'sullivan_woo_wrap_single_product_upper_closing', 1 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP PRODUCT RATING AND PRICE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_single_product_price_rating_opening' ) ) {
	function sullivan_woo_wrap_single_product_price_rating_opening() {
		echo '<div class="product-price-rating">';
	}
	add_action( 'woocommerce_single_product_summary', 'sullivan_woo_wrap_single_product_price_rating_opening', 9 );
}

if ( ! function_exists( 'sullivan_woo_wrap_single_product_price_rating_closing' ) ) {
	function sullivan_woo_wrap_single_product_price_rating_closing() {
		echo '</div><!-- .product-price-rating -->';
	}
	add_action( 'woocommerce_single_product_summary', 'sullivan_woo_wrap_single_product_price_rating_closing', 11 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT LOWER AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_single_product_lower_opening' ) ) {
	function sullivan_woo_wrap_single_product_lower_opening() {
		echo '<section class="product-lower-wrapper"><div class="section-inner">';
	}
	add_action( 'woocommerce_after_single_product_summary', 'sullivan_woo_wrap_single_product_lower_opening', 5 );
}

if ( ! function_exists( 'sullivan_woo_wrap_single_product_lower_closing' ) ) {
	function sullivan_woo_wrap_single_product_lower_closing() {
		echo '</div></section>';
	}
	add_action( 'woocommerce_after_single_product_summary', 'sullivan_woo_wrap_single_product_lower_closing', 50 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP CART TOTALS ON CART
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_cart_totals_opening' ) ) {
	function sullivan_woo_wrap_cart_totals_opening() {
		?>

		<div class="woo-gray-box">

		<?php
	}
	add_action( 'woocommerce_before_cart_totals', 'sullivan_woo_wrap_cart_totals_opening', 1 );
}

if ( ! function_exists( 'sullivan_woo_wrap_cart_totals_closing' ) ) {
	function sullivan_woo_wrap_cart_totals_closing() {
		?>

		</div><!-- .woo-gray-box -->

		<?php
	}
	add_action( 'woocommerce_after_cart_totals', 'sullivan_woo_wrap_cart_totals_closing', 999 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP ORDER REVIEW ON CHECKOUT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_order_review_opening' ) ) {
	function sullivan_woo_wrap_order_review_opening() {
		?>

		<h3 id="order_review_heading"><?php _e( 'Your order', 'sullivan' ); ?></h3>

		<div class="order-review-wrapper woo-gray-box">

		<?php
	}
	add_action( 'woocommerce_checkout_order_review', 'sullivan_woo_wrap_order_review_opening', 1 );
}

if ( ! function_exists( 'sullivan_woo_wrap_order_review_closing' ) ) {
	function sullivan_woo_wrap_order_review_closing() {
		?>

		</div><!-- .order-review-wrapper -->

		<?php
	}
	add_action( 'woocommerce_checkout_order_review', 'sullivan_woo_wrap_order_review_closing', 100 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP ACCOUNT NAVIGATION AND ADD TOGGLE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_wrap_account_nav_opening' ) ) {
	function sullivan_woo_wrap_account_nav_opening() {
		?>

		<div class="account-nav-wrapper">

			<a href="#" class="toggle toggle-account-nav" data-toggle-target="nav.woocommerce-MyAccount-navigation" data-toggle-type="slidetoggle">
				<span class="show"><?php _e( 'Show account pages', 'sullivan' ); ?></span>
				<span class="hide"><?php _e( 'Hide account pages', 'sullivan' ); ?></span>
			</a>

		<?php
	}
	add_action( 'woocommerce_before_account_navigation', 'sullivan_woo_wrap_account_nav_opening', 1 );
}

if ( ! function_exists( 'sullivan_woo_wrap_account_nav_closing' ) ) {
	function sullivan_woo_wrap_account_nav_closing() {
		?>

		</div><!-- .account-nav-wrapper -->

		<?php
	}
	add_action( 'woocommerce_after_account_navigation', 'sullivan_woo_wrap_account_nav_closing', 100 );
}


/* ---------------------------------------------------------------------------------------------
   ADD SIDEBAR TO PRODUCT SINGLE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_single_product_sidebar' ) ) {
	function sullivan_woo_single_product_sidebar() {
		get_template_part( 'sidebar' );
	}
	add_action( 'woocommerce_after_single_product_summary', 'sullivan_woo_single_product_sidebar', 10 );
}


/* ---------------------------------------------------------------------------------------------
	CUSTOM FALLBACK IMAGE FOR PRODUCTS
	--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'sullivan_woo_custom_thumbnail' ) ) {

	function sullivan_woo_custom_thumbnail() {

		function sullivan_woo_custom_thumbnail_src_replace( $src ) {

			// Get either the customizer set fallback or the theme default
			$src = sullivan_get_fallback_image_url();

			return $src;
		}
		add_filter( 'woocommerce_placeholder_img_src', 'sullivan_woo_custom_thumbnail_src_replace' );

	}
	add_action( 'init', 'sullivan_woo_custom_thumbnail' );

}


/* ---------------------------------------------------------------------------------------------
	SHOW PRODUCT CATEGORY IMAGE ON THE START OF PRODUCT ARCHIVE
	--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'sullivan_woo_product_archive_image' ) ) {

	function sullivan_woo_product_archive_image() {
		if ( is_product_category() && get_woocommerce_term_meta( get_queried_object()->term_id, 'thumbnail_id', true ) ) {

			$image_id = get_woocommerce_term_meta( get_queried_object()->term_id, 'thumbnail_id', true );
			$image_obj = wp_get_attachment_image_src( $image_id, 'fullscreen' );
			$image_url = esc_url( $image_obj[0] );

			?>

			<figure class="page-hero bg-image bg-attach" style="background-image: url( <?php echo esc_url( $image_url ); ?> );"></figure><!-- .page-hero -->

			<?php
		}
	}
	add_action( 'woocommerce_before_main_content', 'sullivan_woo_product_archive_image', 1 );

}


/* ---------------------------------------------------------------------------------------------
   UPDATE NUMBER OF ITEMS IN CART-COUNT ON CHANGE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_update_cart_count_on_change' ) ) {

	function sullivan_woo_update_cart_count_on_change( $fragments ) {

		global $woocommerce;

		ob_start();

		if ( $woocommerce->cart->cart_contents_count ) : ?>

			<div class="cart-count">
				<?php echo absint( $woocommerce->cart->cart_contents_count ); ?>
			</div>

		<?php endif;

		$fragments['div.cart-count'] = ob_get_clean();

		// Return our fragments
		return $fragments;

	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'sullivan_woo_update_cart_count_on_change' );

}


/* ---------------------------------------------------------------------------------------------
   ACCOUNT MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_account_modal' ) ) {

	function sullivan_woo_account_modal() {

		$account_url = esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
		$account_title = is_user_logged_in() ? __( 'My account', 'sullivan' ) : __( 'Sign in', 'sullivan' );

		$logged_in = is_user_logged_in();
		$logged_in_class = $logged_in ? 'logged-in' : 'not-logged-in';

		?>

		<div class="header-account">

			<a href="<?php echo $account_url?>" class="account-toggle toggle" data-toggle-target=".account-modal">
				<p><?php echo $account_title; ?></p>
			</a>

			<div class="account-modal modal arrow-right diva <?php echo $logged_in_class; ?>">

				<?php

				if ( ! $logged_in ) :

					woocommerce_login_form();

				else :

					$user 			= wp_get_current_user();
					$user_name 		= ( $user->user_firstname && $user->user_lastname ) ? $user->user_firstname . ' ' . $user->user_lastname :
					$user->user_login;
					$user_firstname = $user->user_firstname ? $user->user_firstname : $user->user_login;

					?>

					<header>
						<strong class="user-name"><?php echo wp_kses_post( $user_name ); ?></strong>
						<span class="user-email"><?php echo wp_kses_post( $user->user_email ); ?></span>
					</header>

					<?php
					// Array with the labels and endpoints of the My account links
					$quicklinks = array(
						array(
							'label'     => __( 'Account details', 'sullivan' ),
							'endpoint'  => 'edit-account',
						),
						array(
							'label'     => __( 'Addresses', 'sullivan' ),
							'endpoint'  => 'edit-address',
						),
					);

					$orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
						'meta_key'          => '_customer_user',
						'meta_value'        => get_current_user_id(),
						'post_status'       => array_keys( wc_get_order_statuses() ),
						'post_type'         => wc_get_order_types( 'view-orders' ),
						'posts_per_page'    => 1,
					) ) );

					// If the user has orders, add a link to the order page
					if ( $orders ) {
						array_unshift( $quicklinks, array(
							'label'  	=> __( 'Orders', 'sullivan' ),
							'endpoint'  => 'orders',
						) );
					}
					?>

					<nav class="user-quicklinks">
						<?php foreach ( $quicklinks as $link ) :
							// Check if we're currently viewing this endpoint
							$classes = is_wc_endpoint_url( $link['endpoint'] ) ? 'active ' : '';
							$classes .= $link['endpoint'];
							?>
							<a<?php if ( $classes ) echo ' class="' . $classes . '"'; ?> href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . $link['endpoint'] . '/'; ?>"><?php echo wp_kses_post( $link['label'] ); ?></a>
						<?php endforeach; ?>
					</nav>

					<footer class="log-out-wrapper">
						<a class="log-out" href="<?php echo esc_url( wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); ?>"><?php _e( 'Sign out', 'sullivan' ); ?></a>
					</footer>

				<?php endif; ?>

			</div><!-- .account-modal -->

		</div><!-- .header-account -->

		<?php

	}

} // End if().


/* ---------------------------------------------------------------------------------------------
   CART MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'sullivan_woo_cart_modal' ) ) {

	function sullivan_woo_cart_modal() {

		global $woocommerce;

		?>

		<div class="header-cart">

			<div class="cart-toggle toggle" data-toggle-target=".cart-modal">

				<p><?php _e( 'Basket', 'sullivan' ); ?></p>

				<?php if ( $woocommerce->cart->cart_contents_count ) : ?>

					<div class="cart-count">
						<?php echo absint( $woocommerce->cart->cart_contents_count ); ?>
					</div>

				<?php endif; ?>

			</div>

			<div class="cart-modal modal arrow-right diva">

				<div class="widget_shopping_cart_content"></div>

			</div><!-- .cart-modal -->

		</div><!-- .header-cart -->

		<?php

	}

} // End if().

?>
