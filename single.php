<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner split">

		<div class="content">

			<?php

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					get_template_part( 'content', get_post_type() );

				endwhile;

			endif;

			?>

		</div><!-- .content -->

		<?php get_sidebar(); ?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>
