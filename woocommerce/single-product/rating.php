<?php
/**
 * Single Product Rating
 *
 * Included in theme to slim down length of the WooCommerce review link wording.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>

	<div class="woocommerce-product-rating">
		<?php
		echo wc_get_rating_html( $average, $rating_count );

		/* Translators: %s = the number of reviews */
		if ( comments_open() ) : ?>
			<a href="#reviews" class="woocommerce-review-link" rel="nofollow"><?php printf( _nx( '%s review', '%s reviews', $review_count, 'Translators: %s = the number of reviews', 'sullivan' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
		<?php endif ?>
	</div>

<?php endif; ?>
