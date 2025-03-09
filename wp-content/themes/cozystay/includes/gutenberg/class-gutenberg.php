<?php
if ( ! class_exists( 'CozyStay_Gutenberg' ) ) {
    class CozyStay_Gutenberg {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'loftocean_get_post_gutenberg_metas', array( $this, 'post_metas' ) );
            add_filter( 'loftocean_get_page_gutenberg_metas', array( $this, 'page_metas' ) );
			add_filter( 'loftocean_hide_page_settings', array( $this, 'hide_page_metabox' ), 10, 2 );
        }
		/**
		* Add post meta to gutenberg for post
		*/
		public function post_metas( $metas ) {
			return array_merge( $metas, array(
				'cozystay_single_post_hide_site_header' => array( 'type' => 'string', 'default' => '' ),
    			'cozystay_single_post_site_header_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_post_custom_site_header' => array( 'type' => 'string', 'default' => '0' ),
				'cozystay_single_post_custom_sticky_site_header' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_post_hide_page_title' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_post_site_footer_hide_main' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_main_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_main' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_post_site_footer_hide_above' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_above_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_above' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_post_site_footer_hide_instagram' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_post_site_footer_hide_bottom' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_source' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_custom_mobile_menu_animation' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_width' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_custom_width' => array( 'type' => 'number', 'default' => 375 ),
                'cozystay_single_post_template' => array( 'type' => 'string', 'default' => '' )
			) );
		}
		/**
		* Add page meta to gutenberg for post
		*/
		public function page_metas( $metas ) {
			return array_merge( $metas, array(
				'cozystay_single_page_hide_site_header' => array( 'type' => 'string', 'default' => '' ),
    			'cozystay_single_page_site_header_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_page_custom_site_header' => array( 'type' => 'string', 'default' => '0' ),
				'cozystay_single_page_custom_sticky_site_header' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_page_hide_page_title' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_header_section_size' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_header_background_color' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_header_background_position_x' => array( 'type' => 'string', 'default' => 'center' ),
                'cozystay_single_page_header_background_position_y' => array( 'type' => 'string', 'default' => 'center' ),
                'cozystay_single_page_header_background_size' => array( 'type' => 'string', 'default' => 'cover' ),
                'cozystay_single_page_header_background_repeat' => array( 'type' => 'string', 'default' => 'off' ),
                'cozystay_single_page_header_background_scroll' => array( 'type' => 'string', 'default' => 'on' ),
                'cozystay_single_page_header_text_color' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_header_show_breadcrumb' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_site_footer_hide_main' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_main_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_main' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_page_site_footer_hide_above' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_above_source' => array( 'type' => 'string', 'default' => '' ),
				'cozystay_single_custom_site_footer_above' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_page_site_footer_hide_instagram' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_page_site_footer_hide_bottom' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_source' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu' => array( 'type' => 'string', 'default' => '0' ),
                'cozystay_single_custom_mobile_menu_animation' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_width' => array( 'type' => 'string', 'default' => '' ),
                'cozystay_single_custom_mobile_menu_custom_width' => array( 'type' => 'number', 'default' => 375 ),
			) );
		}
		/**
		* Condition function whether to show page metabox
		* @param boolean
		* @return boolean
		*/
		public function hide_page_metabox( $show, $p = false ) {
            return false;
		}
    }
    new CozyStay_Gutenberg();
}
