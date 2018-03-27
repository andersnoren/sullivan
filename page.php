<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner">

        <?php

        if ( have_posts() )  : 

            while ( have_posts() ) : the_post();

                // If the page has WooCommerce shortcodes, make the inner sections wide
                $content = get_the_content();
                $page_has_woocommerce_shortcodes = sullivan_string_has_woo_shortcodes( $content );
                $section_inner_width = $page_has_woocommerce_shortcodes ? 'wide' : 'thin';

                // Thin section-inner on the login page
                $showing_login_form = ( sullivan_is_woocommerce_activated() && is_account_page() && ! is_user_logged_in() );
                if ( $showing_login_form ) {
                    $section_inner_width = 'thin';
                }

                // Show WooCommerce breadcrumbs if we're showing WooCommerce content
                if ( sullivan_is_woocommerce_activated() && $page_has_woocommerce_shortcodes && ! $showing_login_form ) {
                    woocommerce_breadcrumb();
                }

                ?>

                <article <?php post_class(); ?> id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    
                    <?php 
                    // Don't show the header on the login form page
                    if ( ! $showing_login_form ) : ?>

                        <header class="section-inner <?php echo esc_attr( $section_inner_width ); ?> max-percentage page-header text-center">
                        
                            <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                            <?php if ( has_excerpt() ) : ?>

                                <p class="sans-excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>

                            <?php endif; ?>
                        
                        </header><!-- .post-header -->

                    <?php endif; ?>

                    <?php if ( has_post_thumbnail() ) : ?>

                        <div class="featured-media section-inner max-percentage medium">

                            <?php the_post_thumbnail(); ?>

                        </div><!-- .featured-media -->

                    <?php endif;
                    
                    // Conditional content classes, depending on whether we're on an active WooCommerce page
                    if ( $page_has_woocommerce_shortcodes ) {
                        $content_classes = 'section-inner ' . $section_inner_width . ' max-percentage';
                        // Append class if we're on a my account page (while logged in)
                        if ( is_account_page() && is_user_logged_in() ) $content_classes .= ' account-wrapper';
                    } else {
                        $content_classes = 'entry-content page-content section-inner ' . $section_inner_width . ' max-percentage';
                    }

                    ?>

                    <div class="<?php echo $content_classes; ?>">

                        <?php 
                        the_content();
                        wp_link_pages();

                        // Show the shop sidebar on my account pages
                        if ( sullivan_is_woocommerce_activated() && is_account_page() && is_user_logged_in() ) get_sidebar(); 
                        ?>

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