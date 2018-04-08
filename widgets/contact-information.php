<?php

class Sullivan_Contact_Information extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'     => 'widget_sullivan_contact',
			'description'   => __( 'Allows you to display contact information. Built with the "Footer #1" widget area in mind.', 'sullivan' ),
		);
		parent::__construct( 'widget_sullivan_contact', __( 'Contact Information', 'sullivan' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		// Outputs the content of the widget
		extract( $args ); // Make before_widget, etc available.

		$widget_title = '';
		$widget_address = '';

		$widget_title = apply_filters( 'sullivan_widget_title', $instance['widget_title'] );
		$widget_address = apply_filters( 'sullivan_widget_address', $instance['widget_address'] );
		$widget_phone = apply_filters( 'sullivan_widget_phone', $instance['widget_phone'] );

		echo $before_widget;

		if ( ! empty( $widget_title ) ) {

			echo '<h3 class="contact-title">' . wp_kses_post( $widget_title ) . '</h3>';

		}

		if ( ! empty( $widget_address ) ) {

			echo '<div class="contact-address">' . wpautop( wp_kses_post( $widget_address ) ) . '</div>';

		}

		if ( ! empty( $widget_phone ) ) {

			echo '<div class="contact-phone">' . wpautop( wp_kses_post( $widget_phone ) ) . '</div>';

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
		if ( ! isset( $instance['widget_title'] ) ) {
			$instance['widget_title'] = '';
		}

		if ( ! isset( $instance['widget_address'] ) ) {
			$instance['widget_address'] = '';
		}

		if ( ! isset( $instance['widget_phone'] ) ) {
			$instance['widget_phone'] = '';
		}

		// Get the options into variables, escaping html characters on the way
		$widget_title = wp_kses_post( $instance['widget_title'] );
		$widget_address = wp_kses_post( $instance['widget_address'] );
		$widget_phone = wp_kses_post( $instance['widget_phone'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php  _e( 'Title', 'sullivan' ); ?>:
			<input id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" class="widefat" value="<?php echo wp_kses_post( $widget_title ); ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_address' ); ?>"><?php  _e( 'Address', 'sullivan' ); ?>:
			<textarea id="<?php echo $this->get_field_id( 'widget_address' ); ?>" name="<?php echo $this->get_field_name( 'widget_address' ); ?>" class="widefat"><?php echo wp_kses_post( $widget_address ); ?></textarea></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_phone' ); ?>"><?php  _e( 'Phone number', 'sullivan' ); ?>:
			<input id="<?php echo $this->get_field_id( 'widget_phone' ); ?>" name="<?php echo $this->get_field_name( 'widget_phone' ); ?>" type="text" class="widefat" value="<?php echo wp_kses_post( $widget_phone ); ?>" /></label>
		</p>

		<?php
	}
}
?>
