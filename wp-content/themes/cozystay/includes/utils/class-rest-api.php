<?php
if ( ! class_exists( 'CozyStay_REST_API' ) ) {
	class CozyStay_REST_API {
		/**
        * Construct function
        */
		public function __construct() {
            add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
        }
        /**
        * Register REST APIs
        */
        public function register_rest_api() {
			// Sync Adobe fonts
			register_rest_route( 'loftocean/v1', '/sync-adobe-fonts/(?P<id>.+)', array(
				'methods' 	=> 'GET',
				'permission_callback' => '__return_true',
				'callback' 	=> array( $this, 'sync_adobe_font' )
			) );
			// Clear Adobe fonts
			register_rest_route( 'loftocean/v1', '/clear-adobe-fonts/', array(
				'methods' 	=> 'GET',
				'permission_callback' => '__return_true',
				'callback' 	=> array( $this, 'clear_adobe_font' )
			) );
		}
        /**
        * Sync Adobe Fonts
        */
		public function sync_adobe_font( $data ) {
			if ( ! empty( $data['id'] ) ) {
				$projectID = trim( $data['id'] );
                $response = wp_remote_get( 'https://use.typekit.net/' . $projectID . '.css' );
                if ( is_wp_error( $response ) || ! is_array( $response )) {
        			return array( 'status' => 'error', 'message' => esc_html__( 'Can\'t sync from Adobe, please try again later.', 'cozystay' ) );
        		}
                $body = $response['body'];
                if ( preg_match_all( '/@font-face\s*\{[^\}]*font-family:([\'"])([^"\']+)\1;/', $body, $matches ) ) {
                    $fonts = array();
                    foreach( $matches[2] as $font ) {
                        if ( ! in_array( $font, $fonts ) ) {
                            array_push( $fonts, $font );
                        }
                    }
					$str_fonts = implode( ',', $fonts );
					$old_value = get_option( 'cozystay_custom_fonts', array() );
	                $option_value = array_merge( array( 'adobe_typekit_id' => '', 'adobe_fonts' => '', 'custom_fonts' => false ), $old_value, array( 'adobe_typekit_id' => $projectID, 'adobe_fonts' => $str_fonts ) );
					update_option( 'cozystay_custom_fonts', $option_value );
                    return array( 'status' => 'success', 'message' => count( $fonts ) . ' ' . esc_html__( 'font(s) found.', 'cozystay' ), 'fonts' => $str_fonts );
                } else {
                    return array( 'status' => 'error', 'message' => esc_html__( 'No Adobe font found. Please make sure the Typekit ID is correct.', 'cozystay' ) );
                }
			} else {
				return array( 'status' => 'error', 'message' => esc_html__( 'Adobe Typekit ID can\' be empty.', 'cozystay' ) );
			}
		}
        /**
        * Clear Adobe Fonts
        */
		public function clear_adobe_font() {
			$old_value = get_option( 'cozystay_custom_fonts', array() );
            $option_value = array_merge( array( 'adobe_typekit_id' => '', 'adobe_fonts' => '', 'custom_fonts' => false ), $old_value, array( 'adobe_typekit_id' => '', 'adobe_fonts' => '' ) );
			update_option( 'cozystay_custom_fonts', $option_value );
			return true;
		}
	}
    new CozyStay_REST_API();
}
