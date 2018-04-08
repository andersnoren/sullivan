<?php get_header();

global $paged;
$paged_val = $paged ? $paged : 1;

// Show the hero slider on the first page of the blog
if ( is_home() && $paged_val == 1 ) sullivan_hero_slider( 'blog' ); ?>

<main id="site-content">

	<div class="section-inner">

		<?php if ( is_search() && have_posts() ) : ?>

			<header class="section-inner thin max-percentage page-header text-center">

				<?php /* Translators: %s = the search query */ ?>
				<h1 class="page-title"><?php printf( _x( 'Search: %s', 'Translators: %s = the search query', 'sullivan' ), '&ldquo;' . get_search_query() . '&rdquo;' ); ?></h1>

				<?php /* Translators: %s = the number of search results */ ?>
				<p class="sans-excerpt"><?php printf( _nx( 'We found %s result matching your search.', 'We found %s results matching your search.', absint( $wp_query->found_posts ), 'Translators: %s = the number of search results', 'sullivan' ), absint( $wp_query->found_posts ) ); ?></p>

			</header><!-- .page-header -->

		<?php elseif ( is_search() ) : ?>

			<header class="section-inner thin max-percentage page-header text-center">

				<h1 class="page-title"><?php _e( 'No results found', 'sullivan' ); ?></h1>

				<?php /* Translators: %s = the search query */ ?>
				<p class="sans-excerpt"><?php printf( _x( 'We could not find any results for the search query "%s", but you can try a different one through the form below.', 'Translators: %s = the search query', 'sullivan' ), get_search_query() ); ?></p>

			</header><!-- .page-header -->

			<div class="section-inner thin max-percentage">

				<?php get_search_form(); ?>

			</div>

		<?php endif;

		if ( have_posts() ) : ?>

			<ul class="item-grid section-inner max-percentage">

				<?php while ( have_posts() ) : the_post();

					if ( sullivan_is_woocommerce_activated() && $post->post_type == 'product' ) {
						wc_get_template_part( 'content', 'product' );
					} else {
						get_template_part( 'content-search' );
					}

				endwhile; ?>

			</ul><!-- .item-grid -->

			<?php get_template_part( 'pagination' ); ?>

		<?php endif; ?>

	</div><!-- .section-inner -->

</main>

<?php get_footer(); ?>
