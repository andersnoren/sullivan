<aside class="sidebar">

	<?php

	// If WC is activated and we're on a WC page, show shop sidebar
	if ( sullivan_is_woocommerce_activated() && ( is_woocommerce() || is_account_page() ) && is_active_sidebar( 'sidebar-shop' ) ) {
		dynamic_sidebar( 'sidebar-shop' );

	// If not, show blog sidebar
	} else {
		dynamic_sidebar( 'sidebar' );
	}

	?>

</aside>
