<?php
if ( ! class_exists( 'CozyStay_Custom_Page_Title' ) ) {
    class CozyStay_Custom_Page_Title {
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
            $page_vars = array();
			$css_vars = array(
                '--page-title-bg' => 'cozystay_page_title_default_background_color',
                '--page-title-color' => 'cozystay_page_title_default_text_color'
			);
			foreach( $css_vars as $var => $id ) {
				$custom_value = cozystay_get_theme_mod( $id );
				if ( $custom_value != $cozystay_default_settings[ $id ] ) {
					$page_vars[ $var ] = $custom_value;
				}
			} 
            if ( cozystay_is_valid_array( $page_vars ) ) {
                $vars[ '#page' ] = isset( $vars[ '#page' ] ) ? array_merge( $vars[ '#page' ], $page_vars ) : $page_vars;
            }

			return $vars;
		}
    }
    new CozyStay_Custom_Page_Title();
}
