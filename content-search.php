<li <?php post_class( 'content-search' ); ?> id="post-<?php the_ID(); ?>">

	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

		<?php

		if ( sullivan_is_woocommerce_activated() ) {
			$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );
		} else {
			$image_size = 'post-thumbnail';
		}

		$image_url = get_the_post_thumbnail_url( $image_size ) ? get_the_post_thumbnail_url( $image_size ) : sullivan_get_fallback_image_url(); ?>

		<img src="<?php echo esc_url( $image_url ); ?>" />

		<?php the_title( '<h2>', '</h2>' ); ?>

		<?php if ( get_post_type() === 'post' ) : ?>

			<p class="post-date"><?php the_date( get_option( 'date_format' ) ); ?></p>

		<?php endif; ?>

	</a>

</article>
