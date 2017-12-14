<aside class="sidebar">

    <?php 
    
    // If WC is activated and we're on a WC page, show shop sidebar
    if ( eames_is_woocommerce_activated() && is_woocommerce() ) {
        dynamic_sidebar( 'sidebar-shop' );

    // If not, show blog sidebar
    } else {
        dynamic_sidebar( 'sidebar' );
    }

    ?>

</aside>