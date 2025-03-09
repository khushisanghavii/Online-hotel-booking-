<?php
if ( ! class_exists( 'CozyStay_Custom_Site_Header' ) ) {
    class CozyStay_Custom_Site_Header {
        /**
        * Array featured slider args base settings
        */
        protected $base_settings = array();
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_custom_styles', array( $this, 'custom_styles' ) );
        }
		/**
		* Generate custom styles
		*/
		public function custom_styles( $styles ) {
			global $cozystay_default_settings;
            $colors = array(
                'cozystay_mobile_site_header_background_color' => '.sidemenu .container { background-color: %s; }',
                'cozystay_mobile_site_header_text_color' => '.sidemenu .container { color: %s; }'
            );
            foreach( $colors as $id => $selector ) {
                $color = cozystay_get_theme_mod( $id );
                if ( ! empty( $color ) && isset( $cozystay_default_settings[ $id ] ) && ( $cozystay_default_settings[ $id ] != $color ) ) {
                    $styles[ $id ] = sprintf( $selector, $color );
                }
            }

            if ( 'custom-width' == cozystay_get_theme_mod( 'cozystay_mobile_site_header_width' ) ) {
                $custom = cozystay_get_theme_mod( 'cozystay_mobile_site_header_custom_width' );
                if ( $custom != $cozystay_default_settings[ 'cozystay_mobile_site_header_custom_width' ] ) {
                    $styles[ 'cs-site-header-mobile-custom-width' ] = sprintf(
                        '.sidemenu.custom-width { max-width: %spx; }',
                        absint( $custom )
                    );
                }
            }

            // Site header background image
            $background_image = cozystay_get_site_header_image();
            if ( ! empty( $background_image ) ) {
                $styles[ 'cs-site-header-background-image' ] = sprintf(
                    '.site-header { background-image: url(%s); }',
                    esc_url( $background_image )
                );
            }

			return $styles;
		}
    }
    new CozyStay_Custom_Site_Header();
}
