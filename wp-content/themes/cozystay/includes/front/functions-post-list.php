<?php
/**
* Setup post list args
* @param array
*/
function cozystay_setup_post_list_args( $args ) {
	$GLOBALS['cozystay']['post_list'] = $args;
	do_action( 'loftocean_pre_post_list' );
}
add_action( 'cozystay_start_post_list_loop', 'cozystay_setup_post_list_args' );
/**
* Reset post list args
*/
function cozystay_reset_post_list_args() {
	unset( $GLOBALS['cozystay']['post_list'] );
	do_action( 'loftocean_reset_post_list' );
}
add_action( 'cozystay_end_post_list_loop', 'cozystay_reset_post_list_args' );
/**
* Get setting from post list args
* @param string
* @param string
* @return mix
*/
function cozystay_get_post_list_prop( $prop, $default = '' ) {
	if ( isset( $GLOBALS['cozystay'], $GLOBALS['cozystay']['post_list'], $GLOBALS['cozystay']['post_list'][ $prop ] ) ) {
		return $GLOBALS['cozystay']['post_list'][ $prop ];
	} else {
		return $default;
	}
}
/**
* Sets a property in the post list global.
* @param string
* @param string
*/
function cozystay_set_post_list_prop( $prop, $value = '' ) {
	if ( isset( $GLOBALS['cozystay'], $GLOBALS['cozystay']['post_list'] ) ) {
		$GLOBALS['cozystay']['post_list'][ $prop ] = $value;
	}
}
/**
* Display content after post list
*/
function cozystay_after_post_list_content() {
	$layout = cozystay_get_post_list_prop( 'layout', false );
	$nav_style = cozystay_get_theme_mod( 'cozystay_list_pagination_style' );
	if ( ( 'masonry' == $layout ) && in_array( $nav_style, array( 'ajax-manual', 'ajax-auto' ) ) ) :
		global $wp_query;
		$cols = cozystay_get_post_list_prop( 'columns', false );
		$total = $wp_query->post_count;
		$more_cols = $total < $cols ? ( $cols - $total ) : false;
		if ( $more_cols ) :
			for( $i = 0; $i < $more_cols; $i ++ ) : ?>
				<div class="masonry-column"></div><?php
			endfor;
		endif;
	endif;
}
add_action( 'cozystay_after_post_list_content', 'cozystay_after_post_list_content' );
/**
* Posts class check
*/
function cozystay_post_list_wrapper_class( $class, $args = array() ) {
	if ( isset( $args, $args[ 'layout' ], $args[ 'post_meta' ] ) && ( 'overlay' == $args[ 'layout' ] ) ) {
		if ( is_array( $args[ 'post_meta' ] ) && in_array( 'read_more_btn', $args[ 'post_meta' ] ) ) {
			array_push( $class, 'btn-slide-up' );
		}
	}
	return $class;
}
add_filter( 'cozystay_post_list_wrapper_class', 'cozystay_post_list_wrapper_class', 10, 2 );
/**
* Get post list layout and column
*/
function cozystay_get_post_list_layout_settings() {
	$sets = array( 'layout' => 'standard', 'column' => false );
	$layout = cozystay_get_theme_mod( 'cozystay_blog_page_post_list_layout' );
	$actual_sets = explode( '-', $layout );
	if ( cozystay_is_valid_array( $actual_sets ) ) {
		$sets[ 'layout' ] = $actual_sets[0];
		$sets[ 'column' ] = empty( $actual_sets[ 1 ] ) ? false : str_replace( 'cols', '', $actual_sets[ 1 ] );
	}
	return $sets;
}
/**
* Change default excerpt length
*/
function cozystay_excerpt_length( $length ) {
	$layout = cozystay_get_post_list_prop( 'layout' );
	$layouts = array( 'standard', 'list', 'zigzag', 'grid', 'masonry', 'overlay' );
	if ( ! empty( $layout ) && in_array( $layout, $layouts ) ) {
		return absint( cozystay_get_theme_mod( 'cozystay_blog_general_layout_' . $layout . '_post_excerpt_length' ) );
	}
	return $length;
}
add_filter( 'excerpt_length', 'cozystay_excerpt_length', PHP_INT_MAX );
/**
* Chang excerpt text
*/
function cozystay_excerpt_more( $more ) {
	return ' ...';
}
add_filter( 'excerpt_more', 'cozystay_excerpt_more', PHP_INT_MAX );
/**
* Get archive page enabled post metas
* @param string archive page type
* @return array
*/
function cozystay_get_list_post_meta() {
	$metas = array();
	$all = array(
		'excerpt' => 'post_excerpt',
		'read_more_btn' => 'read_more_button',
		'category' => 'category',
		'author' => 'author',
		'date' => 'publish_date',
		'comment_counts' => 'comment_counter'
	);
	foreach( $all as $meta => $id ) {
		if ( cozystay_module_enabled( 'cozystay_blog_page_show_' . $id ) ) {
			array_push( $metas, $meta );
		}
	}
	return $metas;
}
/**
* Get post list wrap class
* @param string
* @param array
* @return array
*/
function cozystay_list_get_wrap_class( $sets = array( 'layout' => 'standard', 'column' => '' ) ) {
	$class = array( 'posts' );
	if ( ! empty( $sets[ 'layout' ] ) ) {
		cozystay_module_enabled( 'cozystay_blog_page_center_text' ) ? array_push( $class, 'text-center' ) : '';
		array_push( $class, 'layout-' . $sets[ 'layout' ] );
		( 'zigzag' == $sets[ 'layout' ] ) ? array_push( $class, 'layout-list' ) : '';
		empty( $sets[ 'column' ] ) ? '' : array_push( $class, 'column-' . $sets[ 'column' ] );
		if ( 'overlay' == $sets[ 'layout' ] ) {
			array_push( $class, 'layout-grid' );
			array_push( $class, cozystay_get_theme_mod( 'cozystay_blog_page_overlay_image_ratio' ) );
		} else if ( in_array( $sets[ 'layout' ], array( 'grid', 'list', 'zigzag' ) ) ) {
			array_push( $class, cozystay_get_theme_mod( 'cozystay_blog_page_image_ratio' ) );
		}

		if ( in_array( $sets[ 'layout' ], array( 'list', 'zigzag' ) ) ) {
			cozystay_module_enabled( 'cozystay_blog_page_list_zigzag_with_border' ) ? array_push( $class, 'with-border' ) : '';
		}

		$class = apply_filters( 'cozystay_post_list_wrap_class', $class, array( 'layout' => $sets[ 'layout' ], 'columns' => $sets[ 'column' ] ) );
		return array_unique( array_filter( $class ) );
	}
	return $class;
}
/**
* Get default post list posts class
*/
function cozystay_list_get_default_wrap_class() {
	return array( 'posts', 'layout-standard' );
}
/**
* Get masonry settings
* @param string layout
* @param int column
* @param int offset
* @return mix
*/
function cozystay_get_masonry_settings( $layout, $column, $query = false ) {
	if ( 'masonry' === $layout ) {
		if ( empty( $query ) ) {
			global $wp_query;
			$query = $wp_query;
		}
		$total = $query->post_count;
		$post_index = $query->current_post + 1;
		$is_start = ( 1 === $post_index );
		$is_end = ( $post_index === $total );
		$column_ends = cozystay_get_masonry_column_ends( $query, $column );
		if ( ! $is_start ) {
			foreach( $column_ends as $ce ) {
				if ( 1 == ( $post_index - $ce ) ) {
					$is_start = true;
					break;
				}
			}
		}
		if ( ! $is_end ) {
			foreach( $column_ends as $ce ) {
				if ( $post_index == $ce ) {
					$is_end = true;
					break;
				}
			}
		}
		return apply_filters( 'cozystay_masonry_column_settings', array(
			'is_start' => $is_start,
			'is_end' => $is_end
		), $query );
	}
	return false;
}
/**
* Help function to generate masonry layout necessary column settings
* @param object
* @param int
* @return array
*/
function cozystay_get_masonry_column_ends( $query, $column ) {
	$column_ends = cozystay_get_post_list_prop( 'masonry_column_ends', false );
	if ( empty( $column_ends ) ) {
		$total = $query->post_count;
		$column = empty( $column ) ? 2 : $column;
		$column_ends = array( $total );
		for( $i = $column; $i > 1; $i-- ) {
			$current = floor( $total / $i );
			if ( ! empty( $current ) ) {
				$total -= $current;
				array_unshift( $column_ends, $total );
			}
		}
		cozystay_set_post_list_prop( 'masonry_column_ends', $column_ends );
	}
	return $column_ends;
}
/**
* Show post List page pagination
*/
function cozystay_list_pagination() {
	$type = cozystay_get_theme_mod( 'cozystay_blog_general_pagination_style' );
	if ( ! empty( $type ) && in_array( $type, array( 'link-only', 'link-number', 'ajax-manual', 'ajax-auto' ) ) ) {
		get_template_part( 'template-parts/pagination/pagination', $type );
	}
}
/**
* Test if show ajax pagination
*/
function cozystay_test_ajax_nav() {
	global $wp_query, $paged;
	$current_page = max( $paged, 1 );
	return $current_page < $wp_query->max_num_pages;
}
/**
* Add class attribute for previous posts link
* @param string
* @return string
*/
function cozystay_prev_posts_link_attrs( $attrs ) {
	return $attrs . ' class="prev page-numbers"';
}
add_filter( 'previous_posts_link_attributes', 'cozystay_prev_posts_link_attrs' );
/**
* Add class attribute for next posts link
* @param string
* @return string
*/
function cozystay_next_posts_link_attrs( $attrs ) {
	return $attrs . ' class="next page-numbers"';
}
add_filter( 'next_posts_link_attributes', 'cozystay_next_posts_link_attrs' );
