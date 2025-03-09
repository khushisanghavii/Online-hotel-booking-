<?php
/**
* Body open action
*/
function cozystay_body_open() {
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
}
/**
* Modify the default site logo
* @param string
* @param int
* @return string
*/
function cozystay_the_custom_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );

    if ( $custom_logo_id && cozystay_does_attachment_exist( $custom_logo_id ) ) :
        $logo_size = array( absint( cozystay_get_theme_mod( 'cozystay_site_logo_width' ) ), 9999999 ); ?>

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
			<?php echo wp_get_attachment_image( $custom_logo_id, $logo_size, '', array( 'class' => 'custom-logo', 'alt' => get_bloginfo( 'name', 'display' ) ) ); ?>
		</a><?php
	endif;
}
/**
* Get site header image
*/
function cozystay_get_site_header_image() {
	$header_image_mod = get_theme_mod( 'header_image' );
	if ( ! empty( $header_image_mod ) ) {
		$header_image = '';
		switch ( $header_image_mod ) {
			case 'random-uploaded-image':
				$header_image = get_random_header_image();
				break;
			case 'remove-header':
				$header_image = '';
				break;
			default:
				$header_image = $header_image_mod;
		}
		return $header_image;
	}
	return '';
}
/**
* Output class attributes
* @param array default value
* @param string filter name
*/
function cozystay_the_class( $class = array(), $filter = '' ) {
	if ( ! empty( $filter ) ) {
		$class = apply_filters( $filter, $class );
	}
	$class = array_unique( array_filter( $class ) );
	if ( ! empty( $class ) ) {
		printf( ' class="%s"', esc_attr( implode( ' ', $class ) ) );
	}
}
/**
* Custom classes for <html>
*/
function cozystay_the_html_class() {
	$class = array( 'no-js', 'no-svg' );
	if ( wp_is_mobile() ) {
		array_push( $class, 'mobile' );
	}
	cozystay_the_class( $class, 'cozystay_get_html_class' );
}
/**
* The attributes for tag html
*/
function cozystay_the_html_attributes() {
	language_attributes();
	cozystay_the_html_class();
	$attributes = apply_filters( 'cozystay_html_attributes', array() );
	if ( cozystay_is_valid_array( $attributes ) ) {
		cozystay_the_tag_attributes( $attributes );
	}
}
/**
* Custom classes for site branding
*/
function cozystay_the_site_branding_class() {
	$class = array( 'site-branding' );

	if ( ! display_header_text() ) {
		array_push( $class, 'hide-title-tagline' );
	}
	cozystay_the_class( $class, 'cozystay_get_site_branding_class' );
}
/**
* Custom class for mobile menu
*/
function cozystay_the_mobile_menu_class( $default = '' ) {
	$class = array( 'sidemenu' );
	empty( $default ) ? '' : array_push( $class, $default );
	$animation = cozystay_get_theme_mod( 'cozystay_mobile_site_header_entrance_animation' );
	empty( $animation ) ? '' : array_push( $class, $animation );
	$width = cozystay_get_theme_mod( 'cozystay_mobile_site_header_width' );
	empty( $width ) ? '' : array_push( $class, $width );
	cozystay_the_class( $class, 'cozystay_mobile_menu_class' );
}
/**
* Custom classes for div#content
*/
function cozystay_the_content_class() {
	$class = array( 'site-content' );
	cozystay_the_class( $class, 'cozystay_content_class' );
}
/**
* Page title classes
*/
function cozystay_the_page_title_class( $class = array(), $is_single_post = false ) {
	$use_default_size = true;
	if ( $is_single_post ) {
		$size = cozystay_get_theme_mod( 'cozystay_blog_single_post_title_section_size' );
		$use_default_size = empty( $size );
	}
	if ( $use_default_size ) {
		$size = cozystay_get_theme_mod( 'cozystay_page_title_section_size' );
	}
	array_push( $class, $size );
	array_push( $class, 'page-title-section' );
	cozystay_the_class( $class, 'cozystay_page_title_class' );
}
/**
* Attributes for site main sidebar
*/
function cozystay_the_site_sidebar_attributes( $attrs = array() ) {
	$attrs[ 'class' ] = apply_filters( 'cozystay_get_sidebar_class', array( 'sidebar', 'widget-area' ) );
	if ( cozystay_module_enabled( 'cozystay_sidebar_enable_sticky' ) ) {
		$attrs[ 'data-enable-sticky-sidebar' ] = 'on';
	}
	foreach ( $attrs as $name => $value ) {
		printf( ' %1$s="%2$s"', $name, is_array( $value ) ? esc_attr( implode( ' ', $value ) ) : esc_attr( $value ) );
	}
}
/**
* Condition function if show sidebar
* @return boolean
*/
function cozystay_show_sidebar() {
	$page_layout = apply_filters( 'cozystay_get_current_page_layout', '' );
	return apply_filters( 'cozystay_show_sidebar', ! empty( $page_layout ) );
}
/**
* Attributes for site header
*/
function cozystay_the_site_header_attrs() {
	$attrs = apply_filters( 'cozystay_get_site_header_attrs', array( 'data-sticky-status' => cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) ) );
	foreach ( $attrs as $name => $value ) {
		$name = ' ' . trim( $name );
		echo esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
}
/**
* Test if given menu exists
* @param string menu location
* @return boolean
*/
function cozystay_has_nav_menu( $location ) {
	$locations = get_nav_menu_locations();
	if ( $location && $locations && isset( $locations[ $location ] ) ) {
		$nav = wp_get_nav_menu_object( $locations[ $location ] );
		if ( $nav !== false ) {
			$menu = wp_nav_menu( array( 'theme_location' => $location, 'echo' => false ) );
			return $menu !== false;
		}
	}
	return false;
}
/**
* Output social menu
* @return array
*/
function cozystay_the_socials( $args = array() ) {
	if ( cozystay_has_nav_menu( 'social-menu' ) ) {
		return wp_nav_menu( array_merge( array(
			'theme_location' 	=> 'social-menu',
			'depth' 			=> 1,
			'echo' 				=> false,
			'link_before'		=> '<span>',
			'link_after'		=> '</span>'
		), $args ) );
	}
}
/**
* Output social menu
*/
function cozystay_social_menu( $args = array() ) {
	cozystay_the_socials( array_merge( array(
		'container' 		=> 'nav',
		'container_class' 	=> 'social-navigation',
		'menu_id' 			=> 'menu-social-menu',
		'menu_class' 		=> 'social-nav menu',
		'echo'				=> true
	), $args ) );
}
/**
* Show primary nav
* @param array
* @param string
* @param string
* @param boolean
*/
function cozystay_primary_nav( $args = array(), $before = '', $after = '', $show_mega_menu = false ) {
	if ( cozystay_has_nav_menu( 'primary-menu' ) ) {
		$menu_wrap_allowed_html = array( 'div' => array( 'class' => 1, 'id' => 1, 'data-*' => 1 ) );
		echo wp_kses( $before, $menu_wrap_allowed_html );
		wp_nav_menu( array_merge( array(
			'echo'				=> true,
			'theme_location' 	=> 'primary-menu',
			'container' 		=> 'nav',
			'container_id' 		=> 'site-navigation',
			'container_class' 	=> 'main-navigation',
			'menu_id' 			=> 'menu-main-menu',
			'menu_class' 		=> 'primary-menu',
			'depth' 			=> 3,
			'link_before'		=> '<span>',
			'link_after'		=> '</span>',
			'walker'			=> new CozyStay_Walker_Nav_Menu()
		), $args ) );
		echo wp_kses( $after, $menu_wrap_allowed_html );
	}
}
/**
* Output html attributes
* @param array list of attributes
*/
function cozystay_the_tag_attributes( $attrs = array() ) {
	if ( cozystay_is_valid_array( $attrs ) ) {
		foreach ( $attrs as $key => $value ) {
			if ( ! empty( $key ) ) {
				printf( ' %s="%s" ', esc_attr( $key ), esc_attr( $value ) );
			}
		}
	}
}
/**
* Get enable social sharing icons
*/
function cozystay_get_enabled_sharing() {
	return apply_filters( 'cozystay_get_social_enabled', array() );
}
/**
* Condition function test if popup signup form enabled
*/
function cozystay_is_popup_box_enabled() {
	return cozystay_module_enabled( 'cozystay_general_popup_box_enable' ) || cozystay_is_customize_preview();
}
/**
* Template 3 featured media position
*/
function cozystay_single_reverse_featured_media_position() {
	if ( is_singular( array( 'page', 'post' ) ) ) {
		$pid = get_queried_object_id();
		$featured_media_position = '';
		if ( is_singular( array( 'page' ) ) ) {
			$featured_media_position = get_post_meta( $pid, 'cozystay_single_page_featured_media_position', true );
		} else {
			$featured_media_position = get_post_meta( $pid, 'cozystay_single_post_featured_media_position', true );
			if ( empty( $featured_media_position ) ) {
				$featured_media_position = cozystay_get_theme_mod( 'cozystay_single_post_default_featured_media_position' );
			}
		}
		return ( 'reverse' == $featured_media_position );
	}
	return false;
}
/**
* Make content images responsive
*/
function cozystay_make_content_images_responsive( $content ) {
	return function_exists( 'wp_filter_content_tags' ) ? wp_filter_content_tags( $content ) : wp_make_content_images_responsive( $content );
}
/**
* Get single post page header section metas
*/
function cozystay_get_single_post_header_meta() {
	$metas = array( 'author', 'category', 'publish_date', 'comment_counter' );
	return array_filter( $metas, function( $meta ) {
		return cozystay_module_enabled( 'cozystay_blog_single_post_page_header_show_' . $meta );
	} );
}
/**
* Conditional function is woocommerce shop page
*/
function cozystay_is_woocommerce_shop_page() {
	if ( cozystay_is_woocommerce_activated() ) {
		$page_id = wc_get_page_id( 'shop' );
		return cozystay_does_item_exist( $page_id ) && is_shop();
	}
	return false;
}
