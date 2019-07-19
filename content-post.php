<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="post-header">

		<?php

		// Get the post meta top values
		$post_meta_top = get_theme_mod( 'sullivan_post_meta_top' );

		// If it's empty, use the default set of post meta
		if ( ! $post_meta_top ) {
			$post_meta_top = array(
				'post-date',
				'sticky',
				'edit-link',
			);
		}

		// If it has the value empty, it's explicitly empty and the default post meta shouldn't be output
		if ( $post_meta_top && ! in_array( 'empty', $post_meta_top ) ) : ?>

			<p class="header-meta subheading">

				<?php

				// Post date
				if ( in_array( 'post-date', $post_meta_top ) ) : ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
				<?php endif;

				// Post author
				if ( in_array( 'author', $post_meta_top ) ) :
					// Translators: %s = author name with link ?>
					<span class="post-author"><?php printf( _x( 'By %s', 'Translators: %s = author name with link', 'sullivan' ), '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . wp_kses_post( get_the_author_meta( 'display_name' ) ) . '</a>' ); ?></span>
				<?php endif;

				// Comments
				if ( in_array( 'comments', $post_meta_top ) && comments_open() ) : ?>
					<span><?php comments_popup_link(); ?></span>
				<?php endif;

				// Sticky
				if ( in_array( 'sticky', $post_meta_top ) && is_sticky() ) : ?>
					<span class="sticky-post"><?php _e( 'Sticky', 'sullivan' ); ?></span>
				<?php endif;

				// Edit link
				if ( in_array( 'edit-link', $post_meta_top ) && current_user_can( 'edit_post', get_the_id() ) ) : ?>
					<span class="edit-post"><?php edit_post_link( __( 'Edit post', 'sullivan' ) ); ?></span>
				<?php endif; ?>

			</p><!-- .post-top-meta -->

			<?php
		endif;

		if ( get_the_title() ) :
			if ( ! is_single() ) : ?>
				<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<?php
			else :
				the_title( '<h1 class="post-title">', '</h1>' );
			endif;
		endif;
		?>

	</header><!-- .post-header -->

	<?php

	// Check whether the current post is format-gallery and starts with a gallery shortcode
	$show_gallery = sullivan_has_post_gallery( $post->ID );

	if ( ( has_post_thumbnail() || $show_gallery ) && ! post_password_required() ) : ?>

		<div class="featured-media">

			<?php

			// Either show the gallery
			if ( $show_gallery ) {
				sullivan_post_gallery( $post->ID );

			// Or display the post thumbnail
			} else {
				the_post_thumbnail();
			}

			?>

		</div><!-- .featured-media -->

	<?php endif; ?>

	<div class="post-inner between">

		<?php if ( has_excerpt() ) : ?>

			<p class="excerpt mobile-excerpt"><?php echo get_the_excerpt(); ?></p>

		<?php endif; ?>

		<?php

		// Get the post meta top values
		$post_meta_bottom = get_theme_mod( 'sullivan_post_meta_bottom' );

		// If it's empty, use the default set of post meta
		if ( ! $post_meta_bottom ) {
			$post_meta_bottom = array(
				'author',
				'categories',
				'comments-link',
			);
		}

		// If it has the value empty, it's explicitly empty and the default post meta shouldn't be output
		if ( $post_meta_bottom && ! in_array( 'empty', $post_meta_bottom ) ) : ?>

			<div class="post-meta top">

				<?php

				// Author
				if ( in_array( 'author', $post_meta_bottom ) ) : ?>
					<p class="post-author">
						<span class="meta-title subheading"><?php _e( 'Posted by', 'sullivan' ); ?></span>
						<span class="meta-title mobile-meta-title subheading"><?php _e( 'By', 'sullivan' ); ?> </span>
						<span class="meta-content"><?php the_author_posts_link(); ?></span>
					</p>
					<?php
				endif;


				// Categories
				if ( in_array( 'categories', $post_meta_bottom ) ) : ?>
					<p class="post-categories">
						<span class="meta-title subheading"><?php _e( 'Posted in', 'sullivan' ); ?></span>
						<span class="meta-content"><?php the_category( ', ' ); ?></span>
					</p>
					<?php
				endif;

				// Categories
				if ( in_array( 'tags', $post_meta_bottom ) && has_tag() ) : ?>
					<p class="post-tags">
						<span class="meta-title subheading"><?php _e( 'Tagged with', 'sullivan' ); ?></span>
						<span class="meta-content"><?php the_tags( '', ', ', '' ); ?></span>
					</p>
					<?php
				endif;

				// Comments link
				if ( in_array( 'comments', $post_meta_bottom ) && comments_open() ) : ?>
					<p class="post-comment-link">
						<span class="meta-title subheading"><?php _e( 'Discussion', 'sullivan' ); ?></span>
						<span class="meta-content"><?php comments_popup_link(); ?></span>
					</p>
					<?php
				endif;

				// Sticky
				if ( in_array( 'sticky', $post_meta_bottom ) && is_sticky() ) : ?>
					<p class="sticky-post">
						<span class="meta-title subheading"><?php _e( 'Featured', 'sullivan' ); ?></span>
						<span class="meta-content"><?php _e( 'Sticky post', 'sullivan' ); ?></span>
					</p>
				<?php endif;

				// Edit link
				if ( in_array( 'edit-link', $post_meta_bottom ) && current_user_can( 'edit_post', get_the_id() ) ) : ?>
					<p class="edit-post">
						<span class="meta-title subheading"><?php _e( 'Administration', 'sullivan' ); ?></span>
						<span class="meta-content">
							<?php
							// Make sure we display something in the customizer, as edit_post_link() doesn't output anything there
							if ( is_customize_preview() ) {
								_e( 'Edit post', 'sullivan' );
							} else {
								edit_post_link( __( 'Edit post', 'sullivan' ) );
							}
							?>
						</span>
					</p>
				<?php endif; ?>

			</div><!-- .post-meta -->

		<?php endif; ?>

		<div class="post-content-wrapper">

			<?php if ( has_excerpt() ) : ?>

				<p class="excerpt desktop-excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>

			<?php endif; ?>

			<div class="entry-content post-content">

				<?php the_content(); ?>
				<?php wp_link_pages(); ?>

			</div><!-- .entry-content -->

		</div><!-- .post-content-wrapper -->

	</div><!-- .post-inner -->

	<?php if ( is_single() ) :

		$tags = get_the_tags(); ?>

		<div class="post-inner compensate">

			<div class="post-meta bottom<?php if ( ! $tags ) echo ' no-tags'; ?>">

				<?php if ( $tags ) : ?>

					<p class="post-tags">
						<span class="meta-title subheading"><?php _e( 'Tags:', 'sullivan' ); ?></span>
						<span class="meta-content"><?php the_tags( '', ', ', '' ); ?></span>
					</p>

				<?php endif; ?>

				<p class="post-categories">
					<span class="meta-title subheading"><?php _e( 'Categories:', 'sullivan' ); ?></span>
					<span class="meta-content"><?php the_category( ', ' ); ?></span>
				</p>

			</div>

			<?php
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( $next_post || $prev_post ) : ?>

				<div class="single-pagination<?php if ( ! $next_post || ! $prev_post ) echo ' only-one'; ?>">

					<?php if ( $next_post ) :
						$next_post_title_args = array(
							'post' => $next_post->ID,
						);
						?>

						<a class="next-post" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php the_title_attribute( $next_post_title_args ); ?>">
							<span class="subheading"><?php _e( 'Next post', 'sullivan' ); ?></span>
							<span class="title"><?php echo wp_kses_post( get_the_title( $next_post->ID ) ); ?></span>
						</a>

					<?php endif;

					if ( $prev_post ) :
						$prev_post_title_args = array(
							'post' => $prev_post->ID,
						);
						?>

						<a class="previous-post" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" title="<?php the_title_attribute( $prev_post_title_args ); ?>">
							<span class="subheading"><?php _e( 'Previous post', 'sullivan' ); ?></span>
							<span class="title"><?php echo wp_kses_post( get_the_title( $prev_post->ID ) ); ?></span>
						</a>

					<?php endif; ?>

				</div><!-- .single-pagination -->

			<?php endif; ?>

		</div><!-- .post-inner -->

		<?php

		// If comments are open, or there are at least one comment
		if ( ( get_post_type() == 'post' || comments_open() || get_comments_number() ) && ! post_password_required() ) : ?>

			<div class="hanging-titles">
				<?php comments_template(); ?>
			</div>

		<?php endif;

		// Display related posts
		get_template_part( 'related-posts' );

	endif; ?>

</article>
