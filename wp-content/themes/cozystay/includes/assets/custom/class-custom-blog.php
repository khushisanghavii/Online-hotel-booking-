<?php
if ( ! class_exists( 'CozyStay_Custom_Blog' ) ) {
    class CozyStay_Custom_Blog {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_custom_style_vars', array( $this, 'custom_style_vars' ) );
        }
		/**
		* Generate custom style variables
		*/
		public function custom_style_vars( $vars ) {
			global $cozystay_default_settings;
            $single_page_vars = array();
			$css_vars = array(
                '--page-title-bg' => 'cozystay_blog_single_post_title_default_background_color',
                '--page-title-color' => 'cozystay_blog_single_post_title_default_text_color'
			);
			foreach( $css_vars as $var => $id ) {
				$custom_value = cozystay_get_theme_mod( $id );
				if ( $custom_value != $cozystay_default_settings[ $id ] ) {
					$single_page_vars[ $var ] = $custom_value;
				}
			}
            if ( cozystay_is_valid_array( $single_page_vars ) ) {
                $vars[ '.single #page' ] = isset( $vars[ '.single #page' ] ) ? array_merge( $vars[ '.single #page' ], $single_page_vars ) : $single_page_vars;
            }

           	$meta_item_colors = cozystay_get_theme_mod( 'cozystay_blog_single_post_meta_items_color' );
           	if ( $cozystay_default_settings[ 'cozystay_blog_single_post_meta_items_color' ] != $meta_item_colors ) {
           		$colors = array( '--post-header-meta-color' => $meta_item_colors );
           		$vars[ '.post-header-section' ] = isset( $vars[ '.post-header-section' ] ) ? array_merge( $vars[ '.post-header-section' ], $colors ) : $colors;
           	}

			return $vars;
		}
    }
    new CozyStay_Custom_Blog();
}
