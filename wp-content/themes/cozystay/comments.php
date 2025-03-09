<?php
/**
 * Theme comment template, contains both current comments and the comment form.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return without loading the comments.
 */
if ( post_password_required() ) {
	return;
} ?>

<div class="comments-area" id="comments"> <?php
    if ( have_comments() ) : ?>
		<div class="comments-title-wrap">
			<h2 class="comments-title"><?php esc_html_e( 'Join the Conversation', 'cozystay' ); ?></h2>
		</div>
        <ol class="comment-list">
			<?php wp_list_comments( array(
				'style' 		=> 'ol',
				'short_ping' 	=> true,
				'avatar_size' 	=> 115,
				'walker'		=> new CozyStay_Walker_Comment()
			) ); ?>
		</ol><?php
        the_comments_navigation();
    endif;
    if ( comments_open() ) :
		comment_form( array(
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>',
		) );
    elseif ( get_comments_number() && is_singular( 'post' ) ) : ?>
    	<p class="comments-closed"><?php esc_html_e( 'Comments are closed.', 'cozystay' ); ?></p><?php
    endif; ?>
</div>
