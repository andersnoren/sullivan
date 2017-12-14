<?php 

class eames_recent_products extends WP_Widget {

	function __construct() {
		$widget_ops = array( 
			'classname' => 'widget_eames_recent_products', 
			'description' => __( 'Displays recently added products.', 'eames' ) 
		);
		parent::__construct( 'widget_eames_recent_products', __( 'Recent Products', 'eames' ), $widget_ops );
	}
	
	function widget( $args, $instance ) {
	
		// Outputs the content of the widget
		extract( $args ); // Make before_widget, etc available.
		
		$widget_title = null; 
		$number_of_products = null; 
		
		$widget_title = esc_attr( apply_filters( 'widget_title', $instance['widget_title'] ) );
		$number_of_products = esc_attr( $instance['number_of_products'] );
		
		echo $before_widget;

		if ( ! empty( $widget_title ) ) {
		
			echo $before_title . $widget_title . $after_title;
			
		}

			global $post;
		
		if ( $number_of_products == 0 ) $number_of_products = 5;

		$recent_products = get_posts( array(
			'posts_per_page' 	=> $number_of_products,
			'post_status'    	=> 'publish',
			'post_type'			=> 'product',
		) );
		
		if ( $recent_products ) : ?>

			<ul class="eames-widget-list">
				
				<?php foreach ( $recent_products as $post ) : 

					setup_postdata( $post );

					$product = wc_get_product( $post->ID );

					?>
			
					<li>
					
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								
							<div class="post-icon">
							
								<?php 

								$post_format = get_post_format() ? get_post_format() : 'standard'; 

								if ( has_post_thumbnail() ) {
									
									the_post_thumbnail( 'thumbnail' );
									
								} else { ?>
								
									<div class="genericon genericon-<?php echo $post_format; ?>"></div>
								
								<?php } ?>
								
							</div>
							
							<div class="inner">
											
								<p class="title"><?php the_title(); ?></p>
								<p class="meta"><?php echo $product->get_price(); ?></p>
							
							</div>
												
						</a>
						
					</li>
				
				<?php endforeach; ?>
		
			</ul>
			
		<?php endif;
		
		echo $after_widget; 
	}
	
	
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );

		// Make sure we are getting a number
		$instance['number_of_products'] = is_int( intval( $new_instance['number_of_products'] ) ) ? intval( $new_instance['number_of_products']): 5;
	
		// Update and save the widget
		return $instance;
		
	}
	
	function form($instance) {
		
		// Set defaults
		if ( ! isset( $instance['widget_title'] ) ) $instance['widget_title'] = '';
		if ( ! isset( $instance['number_of_products'] ) ) $instance['number_of_products'] = 5;
	
		// Get the options into variables, escaping html characters on the way
		$widget_title = esc_attr( $instance['widget_title'] );
		$number_of_products = esc_attr( $instance['number_of_products'] );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php  _e( 'Title', 'eames' ); ?>:
			<input id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" class="widefat" value="<?php echo esc_attr( $widget_title ); ?>" /></label>
		</p>
						
		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_products' ); ?>"><?php _e( 'Number of products to display', 'eames' ); ?>:
			<input id="<?php echo $this->get_field_id( 'number_of_products' ); ?>" name="<?php echo $this->get_field_name( 'number_of_products' ); ?>" type="text" class="widefat" value="<?php echo esc_attr( $number_of_products ); ?>" /></label>
			<small>(<?php _e( 'Defaults to 5 if empty', 'eames' ); ?>)</small>
		</p>
		
		<?php
	}
}
?>