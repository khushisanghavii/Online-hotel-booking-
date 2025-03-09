<?php
if ( ! class_exists( 'CozyStay_Custom_Site_Footer' ) ) {
    class CozyStay_Custom_Site_Footer {
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
                'cozystay_site_footer_bottom_background_color' => '.site-footer .site-footer-bottom { background-color: %s; }',
                'cozystay_site_footer_bottom_text_color' => '.site-footer .site-footer-bottom { color: %s; }'
            );
            foreach( $colors as $id => $selector ) {
                $color = cozystay_get_theme_mod( $id );
                if ( ! empty( $color ) && isset( $cozystay_default_settings[ $id ] ) && ( $cozystay_default_settings[ $id ] != $color ) ) {
                    $styles[ $id ] = sprintf( $selector, $color );
                }
            }

			return $styles;
		}
    }
    new CozyStay_Custom_Site_Footer();
}
