<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>
		
		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
        
        <link rel="profile" href="http://gmpg.org/xfn/11">
		 
		<?php wp_head(); ?>
	
	</head>
	
	<body <?php body_class(); ?>>

		<div class="mobile-menu-wrapper">

			<div class="mobile-search">

				<form role="search" method="get" class="mobile-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'eames' ); ?></span>
					<label for="mobile-search-field"></label>
					<input type="search" id="mobile-search-field" class="ajax-search-field" placeholder="<?php _e( 'Search', 'eames' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
					<div class="cancel-search"></div>
				</form>

				<div class="compact-search-results ajax-search-results">

					<?php // Content is added to this element by the eames_ajax_search() function (by way of javascript) ?>

				</div><!-- .compact-search-results -->

			</div><!-- .mobile-search -->

			<ul class="mobile-menu">
				<?php 
				if ( has_nav_menu( 'primary-menu' ) ) {
					wp_nav_menu( array( 
						'container' 		=> '',
						'items_wrap' 		=> '%3$s',
						'theme_location' 	=> 'mobile-menu',
						'walker'			=> new Eames_Walker_with_Sub_Toggles()
					) ); 
				} else {
					wp_list_pages( array(
						'container' => '',
						'title_li' 	=> ''
					) );
				}
				?>
			</ul>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
					
				<ul class="social-menu mobile">
							
					<?php 

					$nav_social_args = array(
						'theme_location'	=>	'social',
						'container'			=>	'',
						'container_class'	=>	'menu-social',
						'items_wrap'		=>	'%3$s',
						'menu_id'			=>	'menu-social-items',
						'menu_class'		=>	'menu-items',
						'depth'				=>	1,
						'link_before'		=>	'<span class="screen-reader-text">',
						'link_after'		=>	'</span>',
						'fallback_cb'		=>	'',
					);

					wp_nav_menu( $nav_social_args );

					?>
					
				</ul><!-- .social-menu -->
			
			<?php endif; ?>

		</div><!-- .mobile-menu-wrapper -->

		<div class="body-inner">

			<div class="mobile-nav-content-overlay dark-overlay"></div>
    
			<header class="site-header">

				<div class="nav-toggle">

					<div class="bar"></div>
					<div class="bar"></div>
					<div class="bar"></div>

				</div>
				
				<div class="header-inner section-inner">

					<?php eames_header_search(); ?>

					<div class="header-titles">

						<?php if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) :

							$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
							$logo_url = $logo[0];
							?>
							
							<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>" class="custom-logo" style="background-image: url( <?php echo $logo_url; ?> );">
								<img src="<?php echo $logo_url; ?>" />
							</a>
							
						<?php elseif ( is_singular() ) : ?>

							<h1 class="site-title"><a href="<?php echo esc_url( home_url() ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></h1>
						
						<?php else : ?>
						
							<h2 class="site-title"><a href="<?php echo esc_url( home_url() ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></h2>
						
						<?php endif;
						
						if ( get_bloginfo( 'description' ) ) : ?>

							<p class="site-description"><?php bloginfo( 'description' ); ?></p>

						<?php endif;
						
						if ( eames_is_woocommerce_activated() ) {

							eames_account_modal();

							eames_cart_modal();

						} elseif ( isset( $nav_social_args ) ) {

							echo '<ul class="social-menu header">';

								wp_nav_menu( $nav_social_args );
								
							echo '</ul><!-- .social-menu -->';
							
						}

						?>

					</div><!-- .header-titles -->

				</div><!-- .header-inner -->

				<ul class="site-nav<?php if ( get_theme_mod( 'eames_sticky_nav' ) ) echo ' stick-me'; ?>">
					<?php 
					if ( has_nav_menu( 'primary-menu' ) ) {
						wp_nav_menu( array( 
							'container' 		=> '',
							'items_wrap' 		=> '%3$s',
							'theme_location' 	=> 'primary-menu',
						) ); 
					} else {
						wp_list_pages( array(
							'container' => '',
							'title_li' 	=> ''
						) );
					}
					?>
				</ul>

			</header><!-- .site-header -->