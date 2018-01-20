<?php if ( $comments ) : ?>

	<div class="comments" id="comments">

		<?php 

		$comments_number = absint( get_comments_number() );
		$comments_title = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'sullivan' ), $comments_number ); ?>
	  
		<h3 class="comment-reply-title"><?php echo $comments_title; ?></h3>
		
		<?php 
		
		wp_list_comments( array( 
			'style' 		=>	'div',
			'avatar_size'	=>	0,
		) );
		
		if ( paginate_comments_links( 'echo=0' ) ) : ?>
		
			<div class="comments-pagination pagination"><?php paginate_comments_links(); ?></div>
		
		<?php endif; ?>
    
	</div> <!-- comments -->
  
<?php endif; ?>

<?php if ( comments_open() || pings_open() ) : ?>

	<?php comment_form( 'comment_notes_before=&comment_notes_after=' ); ?>

<?php elseif ( $comments ) : ?>

	<div id="respond">
		
		<p class="closed"><?php _e( 'Comments closed', 'sullivan' ); ?></p>
		
	</div><!-- #respond -->

<?php endif; ?>