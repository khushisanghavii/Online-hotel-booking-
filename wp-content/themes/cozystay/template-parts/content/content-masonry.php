<?php
$metas = cozystay_get_post_list_prop( 'post_meta', array() );
$metas_below_title = array_intersect( $metas, array( 'author', 'comment_counts' ) );
$has_post_thumbnail = has_post_thumbnail();
$masonry_settings = cozystay_get_post_list_prop( 'masonry', false );
$is_masonry_start = false;
$is_masonry_end = false;
if ( cozystay_is_valid_array( $masonry_settings ) ) {
	$is_masonry_start = $masonry_settings['is_start'];
	$is_masonry_end = $masonry_settings['is_end'];
} ?>

<?php if ( $is_masonry_start ) : ?><div class="masonry-column"><?php endif; ?>
<article <?php post_class(); ?> data-post-id="<?php the_ID(); ?>">
    <?php cozystay_the_list_featured_media_section( $metas ); ?>

	<div class="post-content">
		<header class="post-header"><?php
        if ( cozystay_is_valid_array( $metas_below_title ) || in_array( 'category', $metas ) ) : ?>
            <div class="meta-wrap"><?php
	    	cozystay_the_list_category( $metas );
			if ( count( $metas_below_title ) > 0 ) : ?>
				<div class="meta"><?php
					cozystay_the_list_author( $metas );
					cozystay_the_list_comment( $metas ); ?>
				</div><?php
			endif; ?>
			</div><?php
		endif;
	    cozystay_the_list_post_title(); ?>
		</header><?php
		cozystay_the_list_excerpt( $metas );
		if ( in_array( 'read_more_btn', $metas ) ) : ?>
            <footer class="post-footer"><?php
                cozystay_the_list_more_btn( $metas ); ?>
            </footer><?php
        endif; ?>
	</div>
</article>
<?php if ( $is_masonry_end ) : ?></div><?php endif;
