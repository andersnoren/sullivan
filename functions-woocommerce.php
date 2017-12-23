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

        // Remove output of categories on single products from summary (added to the_content via filter)
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
	WOOCOMMERCE SPECIFIC BODY CLASSES
	--------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_body_classes' ) ) {

    function eames_woo_body_classes( $classes ) {

        $queried_object = get_queried_object();

        // Check if a Woocommerce term has a thumbnail image set
        if ( is_woocommerce() && is_archive() && $queried_object ) {
            if ( get_woocommerce_term_meta( $queried_object->term_id, 'thumbnail_id', true ) ) {
                $classes[] = 'term-has-image';
            } else {
                $classes[] = 'term-missing-image';
            }
        }

        return $classes;

    }
    add_action( 'body_class', 'eames_woo_body_classes', 1 );

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
	ADD PRODUCT SINGLE META TO BOTTOM OF CONTENT
    --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_woo_product_meta_in_content' ) ) {

    function eames_woo_product_meta_in_content( $content ) {

        // On products, get the single meta and append it to the content
        if ( is_singular( 'product' ) ) {

            ob_start();

            woocommerce_template_single_meta();

            $content .= ob_get_clean();

        }

        return $content;

    }
    add_filter( 'the_content', 'eames_woo_product_meta_in_content' );

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
   ADJUST WOOCOMMERCE SORTING STRINGS
   --------------------------------------------------------------------------------------------- */


   if ( ! function_exists( 'eames_woo_catalog_orderby_arguments' ) ) {

    function eames_woo_catalog_orderby_arguments( $args ) {

        $args['menu_order'] = __( 'Default sorting', 'eames' );
        $args['popularity'] = __( 'By popularity', 'eames' );
        $args['rating']     = __( 'By average rating', 'eames' );
        $args['date']       = __( 'By newness', 'eames' );
        $args['price']      = __( 'Price: low to high', 'eames' );
        $args['price-desc'] = __( 'Price: high to low', 'eames' );

        return $args;

    }
    add_filter( 'woocommerce_catalog_orderby', 'eames_woo_catalog_orderby_arguments' );

}


$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
    
) );


/* ---------------------------------------------------------------------------------------------
   WRAP SINGLE PRODUCT UPPER AREA
   --------------------------------------------------------------------------------------------- */


   if ( ! function_exists( 'eames_woo_wrap_archive_header_tools_opening' ) ) {
    function eames_woo_wrap_archive_header_tools_opening() {
        echo '<div class="archive-header-tools">';
    }
    add_action( 'woocommerce_before_shop_loop', 'eames_woo_wrap_archive_header_tools_opening', 15 );
}

if ( ! function_exists( 'eames_woo_wrap_archive_header_tools_closing' ) ) {
    function eames_woo_wrap_archive_header_tools_closing() {
        echo '</div>';
    }
    add_action( 'woocommerce_before_shop_loop', 'eames_woo_wrap_archive_header_tools_closing', 35 );
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
	SHOW PRODUCT CATEGORY IMAGE ON THE START OF PRODUCT ARCHIVE
	--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'eames_woo_product_archive_image' ) ) {

    function eames_woo_product_archive_image() {
        if ( is_product_category() && get_woocommerce_term_meta( get_queried_object()->term_id, 'thumbnail_id', true ) ) {
            
            $image_id = get_woocommerce_term_meta( get_queried_object()->term_id, 'thumbnail_id', true );
            $image_obj = wp_get_attachment_image_src( $image_id, 'fullscreen' );
            $image_url = $image_obj[0];

            ?>

            <figure class="page-hero bg-image bg-attach" style="background-image: url( <?php echo $image_url; ?> );"></figure><!-- .page-hero -->

            <?php
        }
    }
    add_action( 'woocommerce_before_main_content', 'eames_woo_product_archive_image', 1 );

}
    

/* ---------------------------------------------------------------------------------------------
   UPDATE NUMBER OF ITEMS IN CART-COUNT ON CHANGE
   --------------------------------------------------------------------------------------------- */


function eames_woo_update_cart_count_on_change( $fragments ) {

	global $woocommerce;

	ob_start();

	if ( $woocommerce->cart->cart_contents_count ) : ?>

        <div class="cart-count">
            <?php echo $woocommerce->cart->cart_contents_count; ?>
        </div>

    <?php endif;

	$fragments['div.cart-count'] = ob_get_clean();

	// Return our fragments
	return $fragments;

}
add_filter( 'woocommerce_add_to_cart_fragments', 'eames_woo_update_cart_count_on_change' );


/* ---------------------------------------------------------------------------------------------
   ACCOUNT MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_account_modal' ) ) {

    function eames_account_modal() {

        $account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
        $account_title = is_user_logged_in() ? __( 'My account', 'eames' ) : __( 'Sign in', 'eames' );

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

                    $user = wp_get_current_user();
                    $user_name = ( $user->user_firstname && $user->user_lastname ) ? $user->user_firstname . ' ' . $user->user_lastname :
                    $user->user_login;
                    $user_firstname = $user->user_firstname ? $user->user_firstname : $user->user_login;
                
                    ?>

                    <header>
                        <strong class="user-name"><?php echo $user_name; ?></strong>
                        <span class="user-email"><?php echo $user->user_email; ?></span>
                    </header>

                    <?php
                    // Array with the labels and endpoints of the My account links
                    $quicklinks = array(
                        array(
                            'label'     => __( 'Account details', 'eames' ),
                            'endpoint'  => 'edit-account',
                        ),
                        array(
                            'label'     => __( 'Adresses', 'eames' ),
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
                            'label'  => __( 'Orders', 'eames' ),
                            'endpoint'  =>  'orders',
                        ) );
                    }
                    ?>

                    <nav class="user-quicklinks">
                        <?php foreach( $quicklinks as $link ) :
                            // Check if we're currently viewing this endpoint
                            $classes = is_wc_endpoint_url( $link['endpoint'] ) ? 'active ' : '';
                            $classes .= $link['endpoint'];
                            ?>
                            <a<?php if ( $classes ) echo ' class="' . $classes . '"'; ?> href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . $link['endpoint'] . '/'; ?>"><?php echo $link['label']; ?></a>
                        <?php endforeach; ?>
                    </nav>

                    <footer class="log-out-wrapper">
                        <a class="log-out" href="<?php echo wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php _e( 'Sign out', 'eames' ); ?></a>
                    </footer>

                <?php endif; ?>

            </div><!-- .account-modal -->

        </div><!-- .header-account -->

        <?php

    }

}


/* ---------------------------------------------------------------------------------------------
   CART MODAL
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'eames_cart_modal' ) ) {

    function eames_cart_modal() {

        global $woocommerce;

        ?>

        <div class="header-cart">

            <div class="cart-toggle toggle" data-toggle-target=".cart-modal">

                <p><?php _e( 'Basket', 'eames' ); ?></p>

                <?php if ( $woocommerce->cart->cart_contents_count ) : ?>

                    <div class="cart-count">
                        <?php echo $woocommerce->cart->cart_contents_count; ?>
                    </div>

                <?php endif; ?>

            </div>

            <div class="cart-modal modal arrow-right diva">

                <div class="widget_shopping_cart_content"></div>

            </div><!-- .cart-modal -->

        </div><!-- .header-cart -->
        
        <?php

    }

}


?>