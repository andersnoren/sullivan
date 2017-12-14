<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner">

        <?php

        if ( have_posts() )  : 

            while ( have_posts() ) : the_post();

                ?>

                <article <?php post_class(); ?> id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    
                    <header class="section-inner thin page-header text-center">
                    
                        <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

                        <?php if ( has_excerpt() ) : ?>

                            <p class="sans-excerpt"><?php echo get_the_excerpt(); ?></p>

                        <?php endif; ?>
                    
                    </header><!-- .post-header -->

                    <?php if ( has_post_thumbnail() ) : ?>

                        <div class="featured-media section-inner medium">

                            <?php the_post_thumbnail(); ?>

                        </div><!-- .featured-media -->

                    <?php endif; ?>

                    <div class="entry-content page-content section-inner thin">

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

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>