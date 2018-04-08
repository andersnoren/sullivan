<?php get_header();

global $paged;
$paged_val = $paged ? $paged : 1;

// Show the hero slider on the first page of the blog
if ( is_home() && $paged_val == 1 ) sullivan_hero_slider( 'blog' ); ?>

<main id="site-content">

	<div class="section-inner split">

		<div class="content">

			<?php if ( is_archive() ) : ?>

				<header class="archive-header">
					<div>
						<h6 class="subheading"><?php echo sullivan_get_archive_title_prefix(); ?></h6>
						<h3 class="archive-title"><?php the_archive_title(); ?></h3>
						<?php the_archive_description(); ?>
					</div>
				</header>

			<?php endif;

			if ( have_posts() ) : ?>

				<div class="posts" id="posts">

					<?php while ( have_posts() ) : the_post();

						get_template_part( 'content-post' );

					endwhile; ?>

				</div><!-- .posts -->

				<?php get_template_part( 'pagination' ); ?>

			<?php endif; ?>

		</div><!-- .content -->

		<?php get_sidebar(); ?>

	</div><!-- .section-inner -->

</main>

<?php get_footer(); ?>
