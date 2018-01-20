<?php 

class wright_contact_information extends WP_Widget {

	function __construct() {
        $widget_ops = array( 
			'classname'     => 'widget_wright_contact', 
			'description'   => __( 'Allows you to display contact information. Built with the "Footer #1" widget area in mind.', 'wright' ) 
		);
        parent::__construct( 'widget_wright_contact', __( 'Contact Information', 'wright' ), $widget_ops );
    }
	
	function widget( $args, $instance ) {
	
		// Outputs the content of the widget
		extract( $args ); // Make before_widget, etc available.
		
		$widget_title = ''; 
		$widget_address = ''; 
		
		$widget_title = esc_attr( apply_filters( 'widget_title', $instance['widget_title'] ) );
		$widget_address = esc_attr( apply_filters( 'widget_address', $instance['widget_address'] ) );
		$widget_phone = esc_attr( apply_filters( 'widget_phone', $instance['widget_phone'] ) );
		
		echo $before_widget;

		if ( ! empty( $widget_title ) ) {

			echo '<h3 class="contact-title">' . $widget_title . '</h3>';	
			
		}

		if ( ! empty( $widget_address ) ) {
			
			echo '<div class="contact-address">' . wpautop( $widget_address ) . '</div>';

		}

		if ( ! empty( $widget_phone ) ) {

			echo '<div class="contact-phone">' . wpautop( $widget_phone ) . '</div>';

		}
		
		echo $after_widget; 
	}
	
	
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		$instance['widget_address'] = strip_tags( $new_instance['widget_address'] );
		$instance['widget_phone'] = strip_tags( $new_instance['widget_phone'] );
	
		// Update and save the widget
		return $instance;
		
	}
	
	function form( $instance ) {
		
		// Set defaults
		if ( ! isset( $instance['widget_title'] ) ) $instance['widget_title'] = '';
		if ( ! isset( $instance['widget_address'] ) ) $instance['widget_address'] = '';
		if ( ! isset( $instance['widget_phone'] ) ) $instance['widget_phone'] = '';
	
		// Get the options into variables, escaping html characters on the way
		$widget_title = esc_attr( $instance['widget_title'] );
		$widget_address = esc_attr( $instance['widget_address'] );
		$widget_phone = esc_attr( $instance['widget_phone'] );
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php  _e( 'Title', 'wright' ); ?>:
			<input id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" class="widefat" value="<?php echo esc_attr( $widget_title ); ?>" /></label>
        </p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'widget_address' ); ?>"><?php  _e( 'Address', 'wright' ); ?>:
			<textarea id="<?php echo $this->get_field_id( 'widget_address' ); ?>" name="<?php echo $this->get_field_name( 'widget_address' ); ?>" class="widefat"><?php echo esc_attr( $widget_address ); ?></textarea></label>
        </p>
        
        <p>
			<label for="<?php echo $this->get_field_id( 'widget_phone' ); ?>"><?php  _e( 'Phone number', 'wright' ); ?>:
			<input id="<?php echo $this->get_field_id( 'widget_phone' ); ?>" name="<?php echo $this->get_field_name( 'widget_phone' ); ?>" type="text" class="widefat" value="<?php echo esc_attr( $widget_phone ); ?>" /></label>
        </p>
		
		<?php
	}
}
?>