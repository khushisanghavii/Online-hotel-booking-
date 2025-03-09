<?php
if ( ! class_exists( 'CozyStay_Walker_Comment' ) && class_exists( 'Walker_Comment' ) ) {
	class CozyStay_Walker_Comment extends Walker_Comment {
		/**
		 * Outputs a comment in the HTML5 format.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_list_comments()
		 *
		 * @param WP_Comment $comment Comment to display.
		 * @param int        $depth   Depth of the current comment.
		 * @param array      $args    An array of arguments.
		 */
		protected function html5_comment( $comment, $depth, $args ) { ?>
			<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
					<div class="comment-meta">
						<div class="comment-author vcard">
							<?php if ( 0 != $args['avatar_size'] ) {
								echo get_avatar( $comment, $args['avatar_size'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
							} ?>
							<?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
							<b class="fn"><?php echo get_comment_author_link( $comment ); ?></b><?php $this->author_label( $comment ); ?> <span class="says"><?php esc_html_e( 'says:', 'cozystay' ); ?></span>
						</div><!-- end of .comment-author -->

						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
									<?php esc_html__( 'at', 'cozystay' ); ?>
									<?php echo esc_html( get_comment_time() ); ?>
								</time>
							</a>
							<?php edit_comment_link( esc_html__( 'Edit', 'cozystay' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- end of .comment-metadata -->

						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'cozystay' ); ?></p>
						<?php endif; ?>
					</div><!-- end of .comment-meta -->

					<div class="comment-content">
						<?php comment_text(); ?>
					</div><!-- end of .comment-content -->

					<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>'
					) ) );
					?>
				</div><!-- end of .comment-body --> <?php
		}
		/**
		* Get author label if needed
		* @param object
		* @return string
		*/
		protected function author_label( $comment ) {
			if ( $comment->user_id && ( get_the_author_meta( 'ID' ) == $comment->user_id ) ) : ?>
				<span class="author-label"><?php esc_html_e( 'Author', 'cozystay' ); ?></span><?php
			endif;
		}
	}
}
