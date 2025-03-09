<?php
if ( cozystay_module_enabled( 'cozystay_blog_single_post_enable_related_posts' ) ) :
	$filter = cozystay_get_theme_mod( 'cozystay_blog_single_post_related_post_filter' );
	$related_title = cozystay_get_theme_mod( 'cozystay_blog_single_post_related_post_section_title' );
	query_posts( apply_filters( 'cozystay_front_get_related_posts_args', '', $filter, 2 ) );
	if ( have_posts() ) : ?>
		<div class="related-posts">
			<h4 class="related-posts-title"><?php echo esc_html( $related_title ); ?></h4>
			<div class="posts layout-grid column-2 img-ratio-3-2">
               	<?php do_action( 'cozystay_the_list_content_html', array(
					'layout'	=> 'grid',
					'columns'	=> 2,
					'post_meta'	=> array( 'category', 'date', 'author' ),
					'page_layout' => apply_filters( 'cozystay_get_current_page_layout', '' )
				), true ); ?> 
			</div>
		</div><?php
	 endif;
	 wp_reset_query();
endif;
