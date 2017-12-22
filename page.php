<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner">

        <?php

        if ( have_posts() )  : 

            while ( have_posts() ) : the_post();

                // If the page has WooCommerce shortcodes, make the inner sections wide
                $content = get_the_content();
                $page_has_woocommerce_shortcodes = eames_string_has_woo_shortcodes( $content );
                $section_inner_width = $page_has_woocommerce_shortcodes ? 'wide' : 'thin';

                // Show WooCommerce breadcrumbs if we're showing WooCommerce content
                if ( eames_is_woocommerce_activated() && $page_has_woocommerce_shortcodes ) {
                    woocommerce_breadcrumb();
                }

                ?>

                <article <?php post_class(); ?> id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    
                    <header class="section-inner <?php echo $section_inner_width; ?> max-percentage page-header text-center">
                    
                        <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                        <?php if ( has_excerpt() ) : ?>

                            <p class="sans-excerpt"><?php echo get_the_excerpt(); ?></p>

                        <?php endif; ?>
                    
                    </header><!-- .post-header -->

                    <?php if ( has_post_thumbnail() ) : ?>

                        <div class="featured-media section-inner max-percentage medium">

                            <?php the_post_thumbnail(); ?>

                        </div><!-- .featured-media -->

                    <?php endif; ?>

                    <div class="entry-content page-content section-inner <?php echo $section_inner_width; ?> max-percentage">

                        <?php the_content(); ?>
                        <?php wp_link_pages(); ?>

                    </div><!-- .entry-content -->

                    <?php if ( get_comments_number() || comments_open() ) : ?>
                    
                        <div class="section-inner section-inner thin  max-percentage">
                            <?php comments_template(); ?>
                        </div>
                    
                    <?php endif; ?>

                </article>
                
                <?php

            endwhile;

        endif; 

        ?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>