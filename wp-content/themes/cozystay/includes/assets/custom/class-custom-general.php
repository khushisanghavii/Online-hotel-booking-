<?php
if ( ! class_exists( 'CozyStay_Custom_General' ) ) {
    class CozyStay_Custom_General {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_custom_styles', array( $this, 'custom_styles' ) );
            add_filter( 'cozystay_custom_style_vars', array( $this, 'custom_style_vars' ) );
        }
		/**
		* Generate custom style variables
		*/
		public function custom_style_vars( $vars ) {
            global $cozystay_default_settings;
            $root_vars = array();
            if ( 'custom' == cozystay_get_theme_mod( 'cozystay_page_content_width' ) ) {
    			$css_vars = array( '--custom-site-width' => 'cozystay_page_content_custom_width' );
    			foreach( $css_vars as $var => $id ) {
    				$root_vars[ $var ] = cozystay_get_theme_mod( $id ) . 'px';
    			}
            }

            if ( 'custom' == cozystay_get_theme_mod( 'cozystay_general_popup_box_size' ) ) {
                $custom_width = cozystay_get_theme_mod( 'cozystay_general_popup_box_custom_width' );
                $custom_width = absint( $custom_width );
                if ( $cozystay_default_settings[ 'cozystay_general_popup_box_custom_width' ] != $custom_width ) {
                    $root_vars[ '--popup-width' ] = $custom_width . 'px';
                }
            }
            if ( cozystay_is_valid_array( $root_vars ) ) {
                $vars[ ':root' ] = isset( $vars[ ':root' ] ) ? array_merge( $vars[ ':root' ], $root_vars ) : $root_vars;
            }

			return $vars;
        }
        /**
        * Generate custom styles
        */
        public function custom_styles( $styles ) {
            global $cozystay_default_settings;
            $colors = array(
                'cozystay_back_to_top_button_background_color' => '.to-top { background-color: %s; }',
                'cozystay_back_to_top_button_border_color' => '.to-top { border-color: %s; }',
                'cozystay_back_to_top_button_icon_color' => '.to-top { color: %s; }',
                'cozystay_back_to_top_button_hover_background_color' => '.no-touch .to-top.show:hover { background-color: %s; }',
                'cozystay_back_to_top_button_hover_border_color' => '.no-touch .to-top.show:hover { border-color: %s; }',
                'cozystay_back_to_top_button_hover_icon_color' => '.no-touch .to-top.show:hover { color: %s; }',
                'cozystay_general_popup_box_background_color' => '.cs-popup.cs-popup-box.cs-site-popup { background-color: %s; }'
            );
            foreach( $colors as $id => $selector ) {
                $color = cozystay_get_theme_mod( $id );
                if ( ! empty( $color ) && isset( $cozystay_default_settings[ $id ] ) && ( $cozystay_default_settings[ $id ] != $color ) ) {
                    $styles[ $id ] = sprintf( $selector, $color );
                }
            }

            if ( apply_filters( 'cozystay_enable_onepage_menu_check', cozystay_module_enabled( 'cozystay_enable_onepage_menu_check' ) ) ) {
                $styles[ 'cozystay-onepage-menu' ] = '.cozystay-enable-onepage-menu-check .current-menu-item > a:before { display: none !important; }';
            }

            return $styles;
        }
    }
    new CozyStay_Custom_General();
}
