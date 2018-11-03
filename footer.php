			<footer id="site-footer" class="bg-black">

				<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>

					<div class="section-inner footer-widgets">

						<div class="widgets-wrapper">

							<?php

							for ( $i = 1; $i <= 4; $i++ ) :

								if ( is_active_sidebar( 'footer-' . $i ) ) :

									if ( $i === 2 ) echo '<div class="secondary-widgets">'; ?>

									<div class="column column-<?php echo absint( $i ); ?>">

										<div class="widgets">

											<?php dynamic_sidebar( 'footer-' . $i ); ?>

										</div><!-- .widgets -->

									</div><!-- .column-<?php echo absint( $i ); ?> -->

									<?php

								endif;

							endfor;

							// If the secondary widgets div has been opened, close it
							if ( $i > 1 ) {
								echo '</div><!-- .secondary-widgets -->';
							}

							?>

						</div><!-- .widgets-wrapper -->

					</div><!-- .footer-widgets -->

				<?php endif; ?>

				<div class="section-inner credits">

					<p>
						<span>&copy; <?php echo date_i18n( __( 'Y', 'sullivan' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-name"><?php wp_kses_post( bloginfo( 'name' ) ); ?></a></span>
						<?php /* Translators: $s = name of the theme developer */ ?>
						<span><?php printf( _x( 'Theme by %s', 'Translators: $s = name of the theme developer', 'sullivan' ), '<a href="http://www.andersnoren.se">' . __( 'Anders Norén', 'sullivan' ) . '</a>' ); ?></span>
					</p>

					<?php if ( has_nav_menu( 'social' ) ) : ?>

						<ul class="social-menu social-icons">

							<?php
							wp_nav_menu( array(
								'theme_location'	=> 'social',
								'container'			=> '',
								'container_class'	=> 'menu-social',
								'items_wrap'		=> '%3$s',
								'menu_id'			=> 'menu-social-items',
								'menu_class'		=> 'menu-items',
								'depth'				=> 1,
								'link_before'		=> '<span>',
								'link_after'		=> '</span>',
								'fallback_cb'		=> '',
							) );
							?>

						</ul><!-- .social-menu -->

					<?php endif; ?>

				</div><!-- .section-inner.credits -->

			</footer><!-- .footer -->

		</div><!-- .body-inner -->
		
		<?php wp_footer(); ?>

	</body>
</html>
