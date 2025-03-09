<?php
$metas = cozystay_get_post_list_prop( 'post_meta', array() );
$metas_below_title = array_intersect( $metas, array( 'author', 'comment_counts' ) );
$has_post_thumbnail = has_post_thumbnail(); ?>

<article <?php post_class(); ?>>
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
