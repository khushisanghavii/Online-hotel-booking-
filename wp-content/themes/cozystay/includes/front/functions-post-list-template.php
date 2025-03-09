<?php
/**
* Get post format label for list page
*/
function cozystay_the_list_format_label() {
    $format = get_post_format();
    if ( ! empty( $format ) ) {
        switch( $format ) {
            case 'video': ?>
                <div class="format-label format-video">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="49" viewBox="0 0 42 49">
                        <path stroke="#FFF" stroke-width="2" fill="none" d="m2.138 1.829 37.855 22.899L1.225 46.062l.913-44.233Z"></path>
                    </svg>
                </div><?php
                break;
        }
    }
}
/**
* Show featured image as needed
* @param string how to show the featured image as background image or inline image
*/
function cozystay_the_list_featured_media_section( $metas = array() ) {
    $format = get_post_format();
    $layout = cozystay_get_post_list_prop( 'layout', 'large' );
    $has_featured_media = apply_filters( 'loftocean_front_has_post_featured_media', false );
    $not_overlay_layout = ( 'overlay' != $layout );
    if ( $not_overlay_layout && ( 'gallery' === $format ) && $has_featured_media ) : ?>
        <div class="featured-img">
            <a href="<?php the_permalink(); ?>"><?php do_action( 'loftocean_front_the_post_featured_media' ); ?></a>
            <div class="slider-arrows"></div>
            <div class="slider-dots"></div><?php
            cozystay_the_list_format_label();
            cozystay_the_list_date( $metas ); ?>
        </div><?php
    elseif ( has_post_thumbnail() ) :
        if ( in_array( $layout, array( 'grid', 'masonry', 'overlay' )  ) ) {
            $cols = cozystay_get_post_list_prop( 'columns', '' );
            $layout .= '-' . $cols . 'cols';
        }
        $size = CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'archive', 'sub_module' => $layout ) ); ?>
        <div class="featured-img">
        	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $size[0] ); ?></a><?php
            cozystay_the_list_format_label();
            $not_overlay_layout ? cozystay_the_list_date( $metas ) : ''; ?>
        </div><?php
    endif;
    $not_overlay_layout ? '' : cozystay_the_list_date( $metas );
}
/**
* Show the list post title
*/
function cozystay_the_list_post_title() { ?>
    <h2 class="post-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2><?php
}
/**
* Show list categories
* @param array metas enabled
*/
function cozystay_the_list_category( $metas ) {
	if ( in_array( 'category', $metas ) ) {
        cozystay_the_meta_category();
	}
}
/**
* Show list date
* @param array metas enabled
*/
function cozystay_the_list_date( $metas ) {
    if ( in_array( 'date', $metas ) ) :
        if ( cozystay_module_enabled( 'cozystay_blog_general_featured_date_label' ) ) : ?>
            <div class="overlay-label time-label featured-style">
                <div class="meta-item time">
                    <time class="published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        <span class="month"><?php echo esc_html( get_the_date( 'M' ) ); ?></span> <span class="day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
                    </time>
                </div>
            </div><?php
        else : ?>
            <div class="overlay-label time-label">
                <div class="meta-item time">
                    <time class="published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                </div>
            </div><?php
        endif;
    endif;
}
/**
* Show list comment icon
* @param array metas enabled
*/
function cozystay_the_list_comment( $metas ) {
    if ( in_array( 'comment_counts', $metas ) ) :
    	if ( comments_open() || ( 0 < get_comments_number() ) ) : ?>
            <div class="meta-item comment-count">
                <a href="<?php the_permalink(); ?>#comments"><i class="far fa-comments"></i> <?php echo esc_html( get_comments_number() ); ?></a>
            </div><?php
    	endif;
    endif;
}
/**
* Show list like icon
* @param array metas enabled
*/
function cozystay_the_list_author( $metas ) {
    if ( in_array( 'author', $metas ) ) :
        $author_id = get_the_author_meta( 'ID' );
        $author_url = get_author_posts_url( $author_id ); ?>
        <div class="meta-item author">
            <?php esc_html_e( 'By', 'cozystay' ); ?> <a href="<?php echo esc_url( $author_url ); ?>"><?php the_author(); ?></a>
        </div><?php
    endif;
}
/**
* Show list excerpt text
* @param array metas enabled
*/
function cozystay_the_list_excerpt( $metas ) {
	if ( in_array( 'excerpt', $metas ) ) : ?>
		<div class="post-excerpt"><?php the_excerpt(); ?></div> <?php
	endif;
}
/**
* Show list read more button
* @param array metas enabled
*/
function cozystay_the_list_more_btn( $metas ) {
	if ( in_array( 'read_more_btn', $metas ) ) : ?>
        <div class="more-btn">
            <a class="read-more-btn button cs-btn-underline" href="<?php the_permalink(); ?>"><span><?php echo esc_html( cozystay_get_theme_mod( 'cozystay_blog_general_read_more_button_text' ) ); ?></span></a>
        </div><?php
	endif;
}
