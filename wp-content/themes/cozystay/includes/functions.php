<?php
/**
* Get theme option values from customizer
* @return string
*/
function cozystay_get_theme_mod( $id ) {
	global $cozystay_default_settings;
	$defaults = empty( $cozystay_default_settings ) ? array() : $cozystay_default_settings;
	$default = array_key_exists( $id, $defaults ) ? $defaults[ $id ] : '';

	return apply_filters( 'cozystay_theme_mod', get_theme_mod( $id, $default ), $id );
}
/**
* Condition function if module enabled
* @return boolean if the customize setting value === 'on' return true, otherwise false
*/
function cozystay_module_enabled( $id ) {
	return ( 'on' === cozystay_get_theme_mod( $id ) );
}
/**
* Conditioin function if the page requested is what we expect
* @param string the page expected
* @return boolean
*/
function cozystay_is_request( $page ) {
	if ( $page ) {
		switch ( $page ) {
			case 'ajax':
				return cozystay_is_ajax();
			case 'customize':
				return cozystay_is_customize();
			case 'admin':
				return is_admin() && ! cozystay_is_ajax();
			case 'front':
				return cozystay_is_front();
		}
	}
	return false;
}
/**
* Condition function if currently in frontend view
* @return boolean
*/
function cozystay_is_front() {
	return defined( 'WP_USE_THEMES' ) && WP_USE_THEMES;
}
/**
* Condition function if it is static blog list page currently
* @return boolean
*/
function cozystay_is_static_blog_page() {
	$blog_page_id = get_option( 'page_for_posts' );
	$front_page_id = get_option( 'page_on_front' );
	return ( 'page' === get_option( 'show_on_front' ) ) && ( ! empty( $blog_page_id ) ) && ( ! empty( $front_page_id ) );
}
/**
* Condition function if currently is in customize.php or customization preview or saving customize settings
* @return boolean
*/
function cozystay_is_customize() {
	global $pagenow;
	$is_customize_admin = is_admin() && ! empty( $pagenow ) && ( 'customize.php' === $pagenow );
	$is_customize_preview = ! empty( $_REQUEST['customize_changeset_uuid'] );
	return current_user_can( 'customize' ) && ( $is_customize_admin || $is_customize_preview );
}
/**
* Condiftion function if currently is in customize preview page
* @return boolean
*/
function cozystay_is_customize_preview() {
	return is_customize_preview();
}
/**
* Condition function if currently is in admin-ajax.php
* @return boolean
*/
function cozystay_is_ajax() {
	return defined( 'DOING_AJAX' ) && DOING_AJAX;
}
/**
* Condition function if item exists
* @return boolean
*/
function cozystay_does_item_exist( $id ) {
	return ! empty( $id ) && ( false !== get_post_status( $id ) );
}
/**
* Condition fucntion if item exists and is an attachment
*/
function cozystay_does_attachment_exist( $id, $type = 'image' ) {
	return cozystay_does_item_exist( $id ) && wp_attachment_is( $type, $id );
}
/**
* Condition function if item is mc4wp form
*/
function cozystay_does_mc4wp_form_exist( $id ) {
	return cozystay_is_mc4wp_activated() && cozystay_does_item_exist( $id ) && ( 'mc4wp-form' == get_post_type( $id ) );
}
/**
* Get image src by given image id
* @param int image id
* @param string image size
* @param boolean if filter the image size
* @return string image url
*/
function cozystay_get_image_src( $id, $size = false, $filter = true ) {
	if ( ! empty( $id ) ) {
		$size 	= empty( $size ) ? 'full' : $size;
		if ( $filter ){
			$size = apply_filters( 'cozystay_get_image_size', $size );
		}
		$image = wp_get_attachment_image_src( $id, $size );
		return $image ? $image[0] : false;
	}
	return false;
}
/**
* Get image alt text
* @param int image id
* @return string image alt text
*/
function cozystay_get_image_alt( $image_id ) {
	if ( cozystay_does_attachment_exist( $image_id ) ) {
		$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		if ( empty( $alt ) ) {
			$attachment = get_post( $image_id );
			$post_id = $attachment->post_parent;
			$post_title = ! empty( $post_id ) && ( false !== get_post_status( $post_id ) ) ? get_the_title( $post_id ) : false;
			if ( ! empty( $post_title ) ) {
				return $post_title;
			} else {
				return empty( $attachment->post_title ) ? $attachment->post_name : $attachment->post_title;
			}
		} else {
			return $alt;
		}
	}
	return '';
}
/*
* Get terms array by given argument
*
* @param array refer to https://developer.wordpress.org/reference/functions/get_terms/
* @param boolean flag to add the all option or not
* @param mix the all option label, if not provided use the default 'All'
* @return array of terms with term_id as index and term name as value
*/
function cozystay_get_terms( $tax, $all = true, $all_label = false ) {
	$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => false ) );
	if ( ! is_wp_error( $terms ) ) {
		$array = $all ? array( '' => ( empty( $all_label ) ? esc_html__( 'All', 'cozystay' ) : $all_label ) ) : array();
		foreach ( $terms as $t ) {
			$array[ $t->slug ] = $t->name;
		}
		return $array;
	}
	return array();
}
/**
* Convert tax slug to id
* @param mix string or array
* @param string taxonomy
* @return mix string or array
*/
function cozystay_convert_tax_slug2id( $slugs, $tax = 'category' ) {
	if ( ! empty( $slugs ) && ! empty( $tax ) ) {
		if ( is_array( $slugs ) ) {
			$ids = array();
			foreach ( $slugs as $slug ) {
				$term = get_term_by( 'slug', $slug, $tax );
				if ( ! empty( $term ) ) {
					array_push( $ids, $term->term_id );
				}
			}
			return empty( $ids ) ? false : $ids;
		} else if ( is_string( $slugs ) ) {
			$term = get_term_by( 'slug', $slugs, $tax );
			return empty( $term ) ? false : $term->term_id;
		}
	}
	return false;
}
/**
* Conditioin function if theme core is activated
* @return boolean
*/
function cozystay_is_theme_core_activated() {
	return function_exists( 'cozystay_core' );
}
/**
* Condition function if mailchimp for wp plugin activated
* @return boolean
*/
function cozystay_is_mc4wp_activated() {
	return function_exists( 'mc4wp_show_form' );
}
/**
* Condition function if elementor plugin activated
* @return boolean
*/
function cozystay_is_elementor_activated() {
	return defined( 'ELEMENTOR_VERSION' );
}
/**
* Conditional function is show elementor simyul
*/
function cozystay_show_elementor_simulator() {
	return cozystay_is_elementor_activated() && ( \Elementor\Plugin::$instance->editor->is_edit_mode() || isset( $_GET['preview_id'] ) || \Elementor\Plugin::$instance->preview->is_preview_mode() );
}
/**
* Condition function if woocommerce plugin activated
* @return boolean
*/
function cozystay_is_woocommerce_activated() {
	return class_exists( 'WooCommerce' );
}
/**
* Condition function if one click demo import plugin activated
* @return boolean
*/
function cozystay_is_ocdi_activated() {
	return class_exists( 'OCDI_Plugin' );
}
/**
* Condition function if polylang plugin activated
* @return boolean
*/
function cozystay_is_polylang_activated() {
	return function_exists( 'pll_current_language' );
}
/**
* Condition function if yoast seo activated
*/
function cozystay_is_yoast_seo_activated() {
	return function_exists( 'wpseo_auto_load' );
}
/**
* Condition function if social sharing button enabled
* @return boolean
*/
function cozystay_is_social_sharing_enabled( $location = '' ) {
	if ( in_array( $location, array( 'sticky', 'main', 'product' ) ) ) {
		switch( $location ) {
			case 'sticky':
				return cozystay_module_enabled( 'cozystay_single_post_show_sticky_social_bar' );
			case 'main':
				return cozystay_module_enabled( 'cozystay_single_post_show_social_bar' );
			default:
				return true;
		}
	}
	return false;
}
/**
* Get queried object id for singular pages
*/
function cozystay_get_queried_singular_id() {
	if ( is_singular() ) {
		global $wp_query;
		$query_vars = $wp_query->query_vars;
		$queried_page = empty( $query_vars[ 'pagename' ] ) ? null : get_page_by_path( $query_vars[ 'pagename' ] );
		return empty( $query_vars[ 'page_id' ] ) ? ( empty( $queried_page ) ? false : $queried_page->ID ) : $query_vars[ 'page_id' ];
	}
	return false;
}
/*
* Get Mailchimp for WP forms
* @return array of Mailchimp for WP forms with form_id as index and form title as value
*/
function cozystay_mc4wp_forms() {
	$forms = get_posts( array(
		'posts_per_page'	=> -1, // phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_posts_per_page
		'post_type' 		=> 'mc4wp-form'
	) );
	if ( ! is_wp_error( $forms ) ) {
		$array = array( '0' => esc_html__( 'Choose Form', 'cozystay' ) );
		foreach ( $forms as $f ) {
			$array[ $f->ID ] = esc_html( $f->post_title );
		}
		return $array;
	}
	return array();
}
/**
* Get default mc4wp form id
*/
function cozystay_get_default_mc4wp_form_id() {
	if ( function_exists( 'mc4wp' ) ) {
		$forms = cozystay_mc4wp_forms();
		return ! empty( $forms ) && ( count( $forms ) > 1 ) ? array_keys( $forms )[1] : '';
	}
	return '';
}
/**
* Condition function if gutenberg enabled
* @return boolean
*/
function cozystay_is_gutenberg_enabled() {
	return function_exists( 'register_block_type' );
}
/**
* Convert hex color rgba
* @param string
* @return string
*/
function cozystay_hex2rgba( $hex, $opacity ) {
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $hex ) ) {
		$hex2dec = array(
			'1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
			'9' => 9, '0' => 0, 'a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15
		);
		$hex = strtolower( substr( $hex, 1 ) );
		if ( strlen( $hex ) == 3 ) {
			$r = ( $hex2dec[ $hex[0] ] * 16 + $hex2dec[ $hex[0] ] );
			$g = ( $hex2dec[ $hex[1] ] * 16 + $hex2dec[ $hex[1] ] );
			$b = ( $hex2dec[ $hex[2] ] * 16 + $hex2dec[ $hex[2] ] );
		} else {
			$r = ( $hex2dec[ $hex[0] ] * 16 + $hex2dec[ $hex[1] ] );
			$g = ( $hex2dec[ $hex[2] ] * 16 + $hex2dec[ $hex[3] ] );
			$b = ( $hex2dec[ $hex[4] ] * 16 + $hex2dec[ $hex[5] ] );
		}
		return sprintf( 'rgba(%s, %s, %s, %s)', $r, $g, $b, $opacity );
	}
	return false;
}
/**
* Help function to test if variable is array and not empty
* @param mix
* @return boolean
*/
function cozystay_is_valid_array( $var ) {
	return ! empty( $var ) && is_array( $var ) && ( count( $var ) > 0 );
}
/**
* Get array depth
* @param array
* @return int
*/
function cozystay_get_array_depth( $array ) {
	if ( cozystay_is_valid_array( $array ) ) {
		$max_depth = 1;
		foreach ( $array as $value ) {
			if ( cozystay_is_valid_array( $value ) ) {
				$depth = cozystay_get_array_depth( $value ) + 1;
				if ( $depth > $max_depth ) {
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	} else {
		return 0;
	}
}
/**
* Get style selector
* @param array selector list
* @return string selector string
*/
function cozystay_get_selector( $lists ) {
	return implode( ', ', $lists );
}
/**
* Get the css style
* @param string setting id
* @param string selector string
* @param string css style for printing
* @param string setting value
* @return string
*/
function cozystay_generate_style( $id, $selector, $style, $value = false ) {
	global $cozystay_default_settings;
	// If not provided, get the setting value
	if ( empty( $value ) ) {
		$value = cozystay_get_theme_mod( $id );
	}
	if ( strtolower( $cozystay_default_settings[ $id ] ) != strtolower( $value ) ) {
		return sprintf(
			'%1$s { %2$s }',
			$selector,
			sprintf( $style, $value )
		);
	}
	return '';
}
/**
* Conditional function to test callback function valid
* @param mix
* @return boolean
*/
function cozystay_is_callback_valid( $func ) {
	return ! empty( $func ) && is_callable( $func );
}
/**
* Get assets suffix
*/
function cozystay_get_assets_suffix() {
	return COZYSTAY_DEBUG_MODE ? '' : '.min';
}
/**
* Get custom post type list with the given post type
*/
function cozystay_get_custom_post_type( $post_type = 'custom_blocks' ) {
	return apply_filters( 'cozystay_get_custom_post_type', array(), $post_type );
}
/**
* Get all pages
*/
function cozystay_get_pages() {
	return apply_filters( 'cozystay_get_custom_post_type', array(), 'page' );
}
/**
* Get font list
*/
function cozystay_get_fonts() {
	global $cozystay_google_fonts;
	$custom_fonts = cozystay_get_custom_fonts();
	if ( false !== $custom_fonts ) {
		$fonts = array();
		if ( isset( $custom_fonts[ 'adobe' ] ) ) {
			$adobe_fonts = array();
			foreach( $custom_fonts[ 'adobe' ][ 'fonts' ] as $font ) {
				$adobe_fonts[ '[[adobefont]]' . $font ] = $font;
			}
			$fonts[ 'adobe' ] = array( 'type' => esc_html__( 'Adobe Fonts', 'cozystay' ), 'list' => $adobe_fonts );
		}
		if ( isset( $custom_fonts[ 'custom' ] ) ) {
			$customs = array();
			foreach( $custom_fonts[ 'custom' ] as $font ) {
				$customs[ '[[customfont]]' . $font[ 'name' ] ] = $font[ 'name' ];
			}
			$fonts[ 'custom' ] = array( 'type' => esc_html__( 'Custom Fonts', 'cozystay' ), 'list' => $customs );
		}
		if ( cozystay_is_valid_array( $fonts ) ) {
			$fonts[ 'google' ] = array( 'type' => esc_html__( 'Google Fonts', 'cozystay' ), 'list' => $cozystay_google_fonts );
			return $fonts;
		}
	}
	return $cozystay_google_fonts;
}
/**
* Get custom fonts
*/
function cozystay_get_custom_fonts() {
	$custom_fonts = get_option( 'cozystay_custom_fonts', array() );
	if ( cozystay_is_valid_array( $custom_fonts ) ) {
		$fonts = array();
		if ( ! empty( $custom_fonts[ 'adobe_typekit_id' ] ) && ! empty( $custom_fonts[ 'adobe_fonts' ] ) ) {
			$adobe_fonts = explode( ',', $custom_fonts[ 'adobe_fonts' ] );
			for( $i = 0; $i < count( $adobe_fonts ); $i ++ ) {
				$adobe_fonts[ $i ] = trim( $adobe_fonts[ $i ] );
			}
			$fonts[ 'adobe' ] = array( 'fonts' => $adobe_fonts, 'code' => $custom_fonts[ 'adobe_typekit_id' ] );
		}
		if ( cozystay_is_valid_array( $custom_fonts[ 'custom_fonts' ] ) ) {
			$customs = array();
			foreach( $custom_fonts[ 'custom_fonts' ] as $font ) {
				if ( ! empty( $font[ 'name' ] ) && ( ! empty( $font[ 'woff' ] ) || ! empty( $font[ 'woff2' ] ) ) ) {
					$is_format_woff2 = empty( $font[ 'woff' ] );
					$customs[] = array(
						'name' => $font[ 'name' ],
						'weight' => $font[ 'weight' ],
						'url' => $is_format_woff2 ? $font[ 'woff2' ] : $font[ 'woff' ],
						'format' => $is_format_woff2 ? 'woff2' : 'woff'
					);
				}
			}
			if ( cozystay_is_valid_array( $customs ) ) {
				$fonts[ 'custom' ] = $customs;
			}
		}
		return cozystay_is_valid_array( $fonts ) ? $fonts : false;
	}
	return false;
}
/**
* Helper function check font family value
*/
function cozystay_check_font_value( $value, $id ) {
	if ( 'font-family' == substr( $id, -11 ) ) {
		$prefixs = array( '[[customfont]]', '[[adobefont]]' );
		foreach( $prefixs as $prefix ) {
			if ( 0 === strpos( $value, $prefix ) ) {
				return substr( $value, strlen( $prefix ) );
			}
		}
	}
	return $value;
}
/**
* Get currently registered public post types
*/
function cozystay_get_post_types() {
	$types = get_post_types( array( 'publicly_queryable' => true, '_builtin' => false ), 'objects' );
	$posts = array(
		'post' => esc_html__( 'Post', 'cozystay' ),
		'page' => esc_html__( 'Page', 'cozystay' )
	);
	foreach ( $types as $t ) {
		$posts[ $t->name ] = $t->label;
	}
	return $posts;
}
