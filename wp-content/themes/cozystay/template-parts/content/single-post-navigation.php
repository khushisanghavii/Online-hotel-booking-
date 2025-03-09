<?php
if ( cozystay_module_enabled( 'cozystay_blog_single_post_page_footer_show_navigation' ) ) {
    $prev_text = sprintf(
		'<div class="post-info"><span class="text">%1$s</span><span class="post-title">%2$s</span></div>',
		esc_html__( 'Previous post', 'cozystay' ),
		'%title'
	);
	$next_text = sprintf(
		'<div class="post-info"><span class="text">%1$s</span><span class="post-title">%2$s</span></div>',
		esc_html__( 'Next post', 'cozystay' ),
		'%title'
	);
	the_post_navigation( array( 'next_text' => $next_text, 'prev_text' => $prev_text ) );
}
