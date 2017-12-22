<?php

/* 

   WOOCOMMERCE FUNCTIONS
   This file contains all WooCommerce specific hooks and custom functions
   --------------------------------------------------------------------------------------------- */



/* ---------------------------------------------------------------------------------------------
	CUSTOM WRAPPER ELEMENT
	--------------------------------------------------------------------------------------------- */


// Disable defaults
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Replace with our own
function eames_woo_theme_wrapper_start() { 
	?>
	<main id="site-content">
		<div class="section-inner">
	<?php
}
add_action( 'woocommerce_before_main_content', 'eames_woo_theme_wrapper_start', 10 );

// End wrapper
function eames_woo_theme_wrapper_end() { 
	?>
		</div><!-- .section-inner -->
	</main><!-- #site-content -->
	<?php
}
add_action( 'woocommerce_after_main_content', 'eames_woo_theme_wrapper_end', 10 );


/* ---------------------------------------------------------------------------------------------
	REMOVE STUFF
    --------------------------------------------------------------------------------------------- */
    

// Disable default Woocommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


// Conditional removals of actions
if ( ! function_exists( 'eames_woo_remove_actions' ) ) {

    function eames_woo_remove_actions() {

        global $paged;
        if ( ! $paged ) $paged = 1;

        // Remove breadcrumbs on the front page of the shop
        if ( is_shop() ) remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

        // Remove rating from loop items
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        
        // Remove add to cart button from loop items
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

        // Remove default output of sidebars
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

        // Remove output of categories on single products
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    }
    add_action( 'wp_head', 'eames_woo_remove_actions' );

}

// Remove title on the front page
if ( ! function_exists( 'eames_woo_remove_title_on_shop_home' ) ) {

    function eames_woo_remove_title_on_shop_home( $title ) {

        // Remove breadcrumbs on the front page of the shop
        return is_shop() ? '' : $title;

    }
    add_action( 'woocommerce_show_page_title', 'eames_woo_remove_title_on_shop_home' );

}



/* ---------------------------------------------------------------------------------------------
	ADD THE HERO SLIDER TO THE SHOP HOME PAGE
    --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_hero_slider' ) ) {

    function eames_woo_hero_slider() {

        global $paged;
        if ( ! $paged ) $paged = 1;

        if ( is_shop() && $paged == 1 ) {
            eames_hero_slider( 'shop' );
        }

    }
    add_action( 'woocommerce_before_main_content', 'eames_woo_hero_slider', 5 );

}


/* ---------------------------------------------------------------------------------------------
   ADJUST WOOCOMMERCE PAGINATION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_pagination_arguments' ) ) {

    function eames_woo_pagination_arguments( $args ) {

        $args['prev_text'] = __( 'Previous', 'eames' );
        $args['next_text'] = __( 'Next', 'eames' );

        return $args;

    }
    add_filter( 'woocommerce_pagination_args', 'eames_woo_pagination_arguments' );

}


/* ---------------------------------------------------------------------------------------------
   ADJUST WOOCOMMERCE BREADCRUMBS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_breadcrumbs_arguments' ) ) {

    function eames_woo_breadcrumbs_arguments( $args ) {

        $args['delimiter'] = '<span class="seperator"></span>';
        $args['wrap_before'] = '<nav class="breadcrumbs">';

        return $args;

    }
    add_filter( 'woocommerce_breadcrumb_defaults', 'eames_woo_breadcrumbs_arguments' );

}


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT UPPER AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_wrap_single_product_upper_opening' ) ) {
    function eames_woo_wrap_single_product_upper_opening() {
        echo '<section class="product-upper-wrapper">';
    }
    add_action( 'woocommerce_before_single_product_summary', 'eames_woo_wrap_single_product_upper_opening', 1 );
}

if ( ! function_exists( 'eames_woo_wrap_single_product_upper_closing' ) ) {
    function eames_woo_wrap_single_product_upper_closing() {
        echo '</section>';
    }
    add_action( 'woocommerce_after_single_product_summary', 'eames_woo_wrap_single_product_upper_closing', 1 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP PRODUCT RATING AND PRICE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_wrap_single_product_price_rating_opening' ) ) {
    function eames_woo_wrap_single_product_price_rating_opening() {
        echo '<div class="product-price-rating">';
    }
    add_action( 'woocommerce_single_product_summary', 'eames_woo_wrap_single_product_price_rating_opening', 9 );
}

if ( ! function_exists( 'eames_woo_wrap_single_product_price_rating_closing' ) ) {
    function eames_woo_wrap_single_product_price_rating_closing() {
        echo '</div><!-- .product-price-rating -->';
    }
    add_action( 'woocommerce_single_product_summary', 'eames_woo_wrap_single_product_price_rating_closing', 11 );
}


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT LOWER AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_wrap_single_product_lower_opening' ) ) {
    function eames_woo_wrap_single_product_lower_opening() {
        echo '<section class="product-lower-wrapper"><div class="section-inner">';
    }
    add_action( 'woocommerce_after_single_product_summary', 'eames_woo_wrap_single_product_lower_opening', 5 );
}

if ( ! function_exists( 'eames_woo_wrap_single_product_lower_closing' ) ) {
    function eames_woo_wrap_single_product_lower_closing() {
        echo '</div></section>';
    }
    add_action( 'woocommerce_after_single_product_summary', 'eames_woo_wrap_single_product_lower_closing', 50 );
}


/* ---------------------------------------------------------------------------------------------
   ADD SIDEBAR TO PRODUCT SINGLE
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_single_product_sidebar' ) ) {
    function eames_woo_single_product_sidebar() {
        get_template_part( 'sidebar' );
    }
    add_action( 'woocommerce_after_single_product_summary', 'eames_woo_single_product_sidebar', 10 );
}


/* ---------------------------------------------------------------------------------------------
	CUSTOM FALLBACK IMAGE FOR PRODUCTS
	--------------------------------------------------------------------------------------------- */


function eames_woo_custom_thumbnail() {

    function eames_woo_custom_thumbnail_src_replace( $src ) {

        // Get either the customizer set fallback or the theme default
        $src = eames_get_fallback_image_url();

        return $src;
    }
    add_filter( 'woocommerce_placeholder_img_src', 'eames_woo_custom_thumbnail_src_replace' );

}
add_action( 'init', 'eames_woo_custom_thumbnail' );


/* ---------------------------------------------------------------------------------------------
   ACCOUNT MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_account_modal' ) ) {

    function eames_account_modal() {

    }

}


/* ---------------------------------------------------------------------------------------------
   CART MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_cart_modal' ) ) {

    function eames_cart_modal() {

    }

}


?>