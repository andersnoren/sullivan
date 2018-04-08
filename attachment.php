<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner">

		<?php

		if ( have_posts() ) :

			while ( have_posts() ) : the_post();

				?>

				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

					<header class="section-inner thin page-header text-center">

						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

						<?php if ( $post->post_excerpt ) : ?>

							<p class="sans-excerpt"><?php echo wp_kses_post( $post->post_excerpt ); ?></p>

						<?php endif; ?>

					</header><!-- .post-header -->

					<?php

					$image = wp_get_attachment_image_src( get_the_ID(), 'post-thumbnail' );

					if ( $image ) :  ?>

						<div class="featured-media section-inner medium">

							<img src="<?php echo esc_url( $image[0] ); ?>" />

						</div><!-- .featured-media -->

					<?php endif; ?>

					<div class="entry-content page-content section-inner thin">

						<?php the_content(); ?>

					</div><!-- .entry-content -->

				</article>

				<?php

			endwhile;

		endif;

		?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>
