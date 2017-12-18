			<footer id="site-footer" class="bg-black">

				<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>

					<div class="section-inner footer-widgets">

						<div class="widgets-wrapper">

							<?php 
							
							for ( $i = 1; $i <= 4; $i++ ) :

								if ( is_active_sidebar( 'footer-' . $i ) ) : 
								
									if ( $i == 2 ) echo '<div class="secondary-widgets">'; ?>
						
									<div class="column column-<?php echo $i; ?>">
									
										<div class="widgets">
								
											<?php dynamic_sidebar( 'footer-' . $i ); ?>
																
										</div><!-- .widgets -->
										
									</div><!-- .column-<?php echo $i; ?> -->
									
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
						<span>&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url() ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></span>
						<span><?php _e( 'Theme by', 'eames' ); ?> <a href="http://www.andersnoren.se">Anders Nor&eacute;n</a></span>
					</p>

					<?php if ( has_nav_menu( 'social' ) ) : ?>
					
						<ul class="social-menu">
									
							<?php 
							wp_nav_menu( array(
								'theme_location'	=>	'social',
								'container'			=>	'',
								'container_class'	=>	'menu-social',
								'items_wrap'		=>	'%3$s',
								'menu_id'			=>	'menu-social-items',
								'menu_class'		=>	'menu-items',
								'depth'				=>	1,
								'link_before'		=>	'<span>',
								'link_after'		=>	'</span>',
								'fallback_cb'		=>	'',
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