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