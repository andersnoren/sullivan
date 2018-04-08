<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * Included in theme to wrap the text strings, allowing for a more compact display on smaller devices.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( ! woocommerce_products_will_display() ) {
	return;
}
?>
<p class="woocommerce-result-count">
	<?php
	$paged    = max( 1, $wp_query->get( 'paged' ) );
	$per_page = $wp_query->get( 'posts_per_page' );
	$total    = $wp_query->found_posts;
	$first    = ( $per_page * $paged ) - $per_page + 1;
	$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

	echo '<span class="prefix">' . __( 'Showing', 'sullivan' ) . ' </span>';

	echo '<span class="rest">';

	if ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'one result', '%d results', $total, 'sullivan' ), $total );
	} else {
		/* translators: 1: first result 2: last result 3: total results */
		printf( _n( 'one result', 'results %1$d-%2$d of %3$d', $total, 'sullivan' ), $first, $last, $total );
	}

	echo '</span>';
	?>
</p>
