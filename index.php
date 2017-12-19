<?php get_header();

global $paged;
$paged = $paged ? $paged : 1;

// Show the hero slider on the first page of the blog
if ( is_home() && $paged == 1 ) eames_hero_slider( 'blog' ); ?>

<main id="site-content">

	<div class="section-inner split">

		<div class="content">
			
			<?php if ( is_archive() ) : ?>
			
				<header class="archive-header">
					<div>
						<h6 class="subheading"><?php echo eames_get_archive_title_prefix(); ?></h6>
						<h3 class="archive-title"><?php the_archive_title(); ?></h3>
						<?php the_archive_description(); ?>
					</div>
				</header>
			
			<?php elseif ( is_search() && have_posts() ) : ?>
			
				<header class="archive-header">
					<div>
						<h6 class="subheading"><?php _e( 'Search', 'eames' ); ?></h6>
						<h3 class="archive-title"><?php printf( __( 'Search: %s', 'eames' ), '&ldquo;' . get_search_query() . '&rdquo;' ); ?></h3>
						<p><?php printf( _n( 'We found %s result matching your search.', 'We found %s results matching your search.', $wp_query->found_posts, 'eames' ), $wp_query->found_posts ); ?></p>
					</div>
				</header>
			
			<?php elseif ( is_search() ) : ?>

				<div class="section-inner">

					<header class="archive-header">
						<div>
							<h6 class="subheading"><?php _e( 'Search', 'eames' ); ?></h6>
							<h3 class="archive-title"><?php _e( 'No results found', 'eames' ); ?></h3>
							<p><?php global $found_posts; printf( __( 'We could not find any results for the search query "%s".', 'eames' ), get_search_query() ); ?></p>
						</div>
					</header>

				</div>

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

		<?php get_sidebar( 'blog' ); ?>
	
	</div><!-- .section-inner -->

</main>

<?php get_footer(); ?>