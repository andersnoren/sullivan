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
					<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'sullivan' ); ?></span>
					<label for="mobile-search-field"></label>
					<input type="search" id="mobile-search-field" class="ajax-search-field" placeholder="<?php esc_attr_e( 'Search', 'sullivan' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
					<div class="cancel-search"></div>
				</form>

				<div class="compact-search-results ajax-search-results"></div><!-- .compact-search-results -->

			</div><!-- .mobile-search -->

			<ul class="mobile-menu">
				<?php
				if ( has_nav_menu( 'mobile-menu' ) ) {
					wp_nav_menu( array(
						'container' 		=> '',
						'items_wrap' 		=> '%3$s',
						'theme_location' 	=> 'mobile-menu',
						'walker'			=> new Sullivan_Walker_With_Sub_Toggles(),
					) );
				} else {
					wp_list_pages( array(
						'container' => '',
						'title_li' 	=> '',
					) );
				}
				?>
			</ul>

			<?php if ( has_nav_menu( 'social' ) ) : ?>

				<ul class="social-menu mobile">

					<?php

					$nav_social_args = array(
						'theme_location'	=> 'social',
						'container'			=> '',
						'container_class'	=> 'menu-social',
						'items_wrap'		=> '%3$s',
						'menu_id'			=> 'menu-social-items',
						'menu_class'		=> 'menu-items',
						'depth'				=> 1,
						'link_before'		=> '<span class="screen-reader-text">',
						'link_after'		=> '</span>',
						'fallback_cb'		=> '',
					);

					wp_nav_menu( $nav_social_args );

					?>

				</ul><!-- .social-menu -->

			<?php endif;

			if ( sullivan_is_woocommerce_activated() ) :

				$logged_in = is_user_logged_in(); ?>

				<div class="mobile-account">

					<?php if ( ! $logged_in ) : ?>

						<a class="sign-in" href="<?php echo add_query_arg( 'form', 'sign-in', esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); ?>"><?php _e( 'Sign in', 'sullivan' ); ?></a>

						<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
							<a class="register" href="<?php echo add_query_arg( 'form', 'registration', esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); ?>"><?php _e( 'Register', 'sullivan' ); ?></a>
						<?php endif; ?>

					<?php else :

						$account_url = esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>

						<a class="my-account" href="<?php echo $account_url; ?>"><?php _e( 'My account', 'sullivan' ); ?></a>
						<a class="sign-out" href="<?php echo esc_url( wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); ?>"><?php _e( 'Sign out', 'sullivan' ); ?></a>

					<?php endif; ?>

				</div><!-- .mobile-account -->

			<?php endif; ?>

		</div><!-- .mobile-menu-wrapper -->

		<div class="body-inner">

			<div class="mobile-nav-content-overlay dark-overlay"></div>

			<?php if ( sullivan_is_woocommerce_activated() ) woocommerce_demo_store(); ?>

			<header class="site-header">

				<div class="nav-toggle">

					<div class="bar"></div>
					<div class="bar"></div>
					<div class="bar"></div>

				</div>

				<div class="header-inner section-inner">

					<?php sullivan_header_search(); ?>

					<div class="header-titles">

						<?php if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) :

							sullivan_custom_logo();

						elseif ( is_front_page() || is_home() ) : ?>

							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></h1>

						<?php else : ?>

							<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></h2>

						<?php endif;

						if ( get_bloginfo( 'description' ) ) : ?>

							<p class="site-description"><?php echo wp_kses_post( get_bloginfo( 'description' ) ); ?></p>

						<?php endif;

						if ( sullivan_is_woocommerce_activated() ) {

							sullivan_woo_account_modal();

							sullivan_woo_cart_modal();

						} elseif ( isset( $nav_social_args ) ) {

							echo '<ul class="social-menu header">';

								wp_nav_menu( $nav_social_args );

							echo '</ul><!-- .social-menu -->';

						}

						?>

					</div><!-- .header-titles -->

				</div><!-- .header-inner -->

				<ul class="site-nav<?php if ( get_theme_mod( 'sullivan_sticky_nav' ) ) echo ' stick-me'; ?>">
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
							'title_li' 	=> '',
						) );
					}
					?>
				</ul>

			</header><!-- .site-header -->
