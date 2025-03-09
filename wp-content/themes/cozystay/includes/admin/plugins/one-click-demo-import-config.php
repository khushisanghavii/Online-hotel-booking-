<?php
function cozystay_ocdi_import_files () {
	$demos 		= array();
	$data_dir 	= trailingslashit( get_template_directory() ) . 'demo-data/';
	$data_uri 	= get_template_directory_uri() . '/demo-data/';
	$configs	= array(
		'apartment' => 'City Aparthotel',
		'countryside-lodge' => 'Countryside Lodge',
		'island-resort' => 'Island Resort',
		'mountain-chalet' => 'Mountain Chalet',
		'mountain-hotel' => 'Mountain Hotel'
	);
	foreach( $configs as $path => $name ) {
		$demos[] = array(
			'import_file_name'             => $name,
			'local_import_file'            => $data_dir . $path . '/content.xml',
			'local_import_widget_file'     => $data_dir . $path . '/widgets.wie',
			'import_preview_image_url'     => $data_uri . $path . '/screenshot.jpg',
			'local_import_customizer_file' => $data_dir . $path . '/customizer.dat'
		);
	}
	return $demos;
}
add_filter( 'ocdi/import_files', 'cozystay_ocdi_import_files' );


function cozystay_ocdi_after_import_setup( $selected_import ) {
	// Assign menus to their locations.
	$main_menu 		= get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$social_menu 	= get_term_by( 'name', 'Social Menu', 'nav_menu' );
	$footer_menu 	= get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
		'primary-menu' 	=> empty( $main_menu ) ? '' : $main_menu->term_id,
		'social-menu' 	=> empty( $social_menu ) ? '' : $social_menu->term_id,
		'footer-menu'	=> empty( $footer_menu ) ? '' : $footer_menu->term_id
	) );

	$front_page = get_page_by_path( 'home' );
	if ( $front_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
	}


	if ( class_exists( 'WooCommerce' ) ) {
		$shop_page = get_page_by_path( 'shop' );
		$shop_page ? update_option( 'woocommerce_shop_page_id', $shop_page->ID ) : '';
	}

	if ( function_exists( 'elementor_load_plugin_textdomain' ) ) {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}

	$rooms = new WP_Query( array( 'post_type' => 'loftocean_room', 'posts_per_page' => -1, 'offset' => 0 ) );
	if ( ( ! is_wp_error( $rooms ) ) && $rooms->have_posts() ) {
	    $room_extra_service_taxonomy = 'lo_room_extra_services';
	    $services = get_terms( array( 'taxonomy' => $room_extra_service_taxonomy, 'orderby' => 'meta_value_num', 'order' => 'ASC', 'meta_key' => 'priority', 'hide_empty' => false, 'fields' => 'ids' ) );
	    if ( ( ! is_wp_error( $services ) ) && ( count( $services ) > 0 ) ) {
	        while ( $rooms->have_posts() ) {
	            $rooms->the_post();
	            wp_set_post_terms( get_the_ID(), $services, $room_extra_service_taxonomy );
	        }
			wp_reset_query();
	    }

		$room_facility_taxonomy = 'lo_room_facilities';
		$facilities = get_terms( array( 'taxonomy' => $room_facility_taxonomy, 'meta_key' => 'facility_type', 'meta_value' => array( 'room-footage', 'guests', 'beds', 'bathrooms' ), 'hide_empty' => false, 'fields' => 'ids' ) );
		if ( ( ! is_wp_error( $facilities ) ) && ( count( $facilities ) > 0 ) ) {
		    while ( $rooms->have_posts() ) {
		        $rooms->the_post();
		        wp_set_post_terms( get_the_ID(), $facilities, $room_facility_taxonomy );
		    }
			wp_reset_query();
		}
	}

	set_theme_mod( 'cozystay_room_availability_calendar_enable', 'on' );
}
add_action('ocdi/after_import', 'cozystay_ocdi_after_import_setup');
