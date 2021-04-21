<li <?php post_class( 'content-search' ); ?> id="post-<?php the_ID(); ?>">

	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

		<?php

		if ( has_post_thumbnail() ) {
			$image_size = sullivan_is_woocommerce_activated() ? apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' ) : 'thumbnail';
			the_post_thumbnail( $image_size );
		} else {
			echo '<img src="' . esc_url( sullivan_get_fallback_image_url() ) . '" />';
		}

		the_title( '<h2>', '</h2>' );
		
		if ( get_post_type() === 'post' ) : ?>

			<p class="post-date"><?php the_date( get_option( 'date_format' ) ); ?></p>

		<?php endif; ?>

	</a>

</article>
