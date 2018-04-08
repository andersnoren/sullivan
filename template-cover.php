<?php

/* Template Name: Cover Page */

get_header(); ?>

<main id="site-content">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			?>

			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

				<?php

				$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sullivan_fullscreen' );
				$featured_image_url = isset( $featured_image[0] ) ? esc_url( $featured_image[0] ) : '';

				?>

				<div class="page-hero with-content dark-overlay bg-image bg-attach"<?php if ( $featured_image_url ) echo ' style="background-image: url( ' . esc_url( $featured_image_url ) . ' )"'; ?>>

					<header class="section-inner thin page-header text-center fade-block">

						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

						<?php if ( has_excerpt() ) : ?>

							<p class="sans-excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>

						<?php endif; ?>

					</header><!-- .post-header -->

					<div class="to-content"></div>

				</div><!-- .page-hero -->

				<div id="content-element" class="entry-content page-content section-inner thin">

					<?php the_content(); ?>
					<?php wp_link_pages(); ?>

				</div><!-- .entry-content -->

				<?php if ( get_comments_number() || comments_open() ) : ?>

					<div class="section-inner section-inner thin">
						<?php comments_template(); ?>
					</div>

				<?php endif; ?>

			</article>

			<?php

		endwhile;

	endif;

	?>

</main><!-- #site-content -->

<?php get_footer(); ?>
