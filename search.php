<?php get_header();

global $paged;
$paged = $paged ? $paged : 1;

// Show the hero slider on the first page of the blog
if ( is_home() && $paged == 1 ) sullivan_hero_slider( 'blog' ); ?>

<main id="site-content">

	<div class="section-inner">
			
        <?php if ( is_search() && have_posts() ) : ?>

            <header class="section-inner thin max-percentage page-header text-center">
                            
                <h1 class="page-title"><?php printf( __( 'Search: %s', 'sullivan' ), '&ldquo;' . wp_kses_post( get_search_query() ) . '&rdquo;' ); ?></h1>

                <p class="sans-excerpt"><?php printf( _n( 'We found %s result matching your search.', 'We found %s results matching your search.', $wp_query->found_posts, 'sullivan' ), $wp_query->found_posts ); ?></p>
            
            </header><!-- .page-header -->
        
        <?php elseif ( is_search() ) : ?>

            <header class="section-inner thin max-percentage page-header text-center">
                
                <h1 class="page-title"><?php _e( 'No results found', 'sullivan' ); ?></h1>

                <p class="sans-excerpt"><?php global $found_posts; printf( __( 'We could not find any results for the search query "%s", but you can try a different one through the form below.', 'sullivan' ), wp_kses_post( get_search_query() ) ); ?></p>
            
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