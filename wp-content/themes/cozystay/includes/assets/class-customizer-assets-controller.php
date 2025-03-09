<?php
/**
* Theme front end assets manager class
*/

if ( ! class_exists( 'CozyStay_Assets_Controller_Customizer' ) ) {
	class CozyStay_Assets_Controller_Customizer{
		/**
		* String main style.css id
		*/
		public $main_style_id = '';
		/**
		* Construct function
		*/
		public function __construct() {
            if ( ! empty( $_REQUEST['customize_changeset_uuid'] ) ) {
                add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );
            } else {
                add_filter( 'cozystay_json_customizer', array( $this, 'get_customizer_json' ) );
                add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customize_scripts' ) );
                add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_footer_scripts' ), 9999 );
            }
		}
		/**
		* Enqueue script for customizer.php
		*/
		public function enqueue_customize_scripts() {
			$assets_version = COZYSTAY_ASSETS_VERSION;
			$js_root_uri 	= COZYSTAY_ASSETS_URI . 'scripts/';
			$css_root_uri 	= COZYSTAY_ASSETS_URI . 'styles/';
			$font_root_uri	= COZYSTAY_ASSETS_URI . 'fonts/';
			$customize_deps = array( 'jquery-ui-slider', 'customize-controls', 'jquery', 'cozystay-function-lib' );
            $suffix = cozystay_get_assets_suffix();

			wp_enqueue_script( 'cozystay-function-lib', $js_root_uri . 'admin/functions' . $suffix . '.js', array( 'jquery' ), $assets_version, true );
			wp_enqueue_script( 'cozystay-customizer', $js_root_uri . 'customize/customize' . $suffix . '.js', $customize_deps, $assets_version, true );
			wp_localize_script( 'cozystay-customizer', 'cozystayCustomizer', apply_filters( 'cozystay_json_customizer', array() ) );

			do_action( 'cozystay_enqueue_font_awesome' );
			wp_enqueue_style( 'jquery-ui', $css_root_uri . 'jquery-ui/jquery-ui.min.css' );
			wp_enqueue_style( 'cozystay-customizer-style', $css_root_uri . 'customize/customizer' . $suffix . '.css', array(), $assets_version );
			wp_add_inline_style( 'cozystay-customizer-style', '#mce-modal-block { z-index: 9999998!important; } .mce-menu-align, .mce-popover, .mce-floatpanel { z-index: 9999999 !important; }' );
		}
		/**
		* Enqueue scirpts for customize previewer
		*/
		public function enqueue_preview_scripts() {
			$assets_version = COZYSTAY_ASSETS_VERSION;
			$js_root_uri 	= COZYSTAY_ASSETS_URI . 'scripts/';
            $js_dependency  = array( 'jquery', 'customize-selective-refresh' );
            $suffix = cozystay_get_assets_suffix();

			wp_enqueue_script( 'cozystay-customizer-preview', $js_root_uri . 'customize/preview' . $suffix . '.js', $js_dependency, $assets_version, true );
			wp_localize_script( 'cozystay-customizer-preview', 'cozystayCustomizerPreview', apply_filters( 'cozystay_json_customizer_preview', array() ) );
		}
        /**
        * Enqueue admin scripts
        */
		public function print_footer_scripts() {
			do_action( 'admin_print_footer_scripts' );
		}
        /**
        * Get customizer json
        * @param array
        * @return array
        */
        public function get_customizer_json( $json = array() ) {
            $editor_ids = isset( $json['editor_ids'] ) ? $json['editor_ids'] : array();
			$permalink_structure = get_option( 'permalink_structure', '' );
			array_push( $editor_ids, 'cozystay_above_site_footer_text_content' );
			array_push( $editor_ids, 'cozystay_general_cookie_law_message' );
			$json['editor_ids'] = $editor_ids;

            return array_merge( $json, array(
                'header_description' => '',
                'header_label' => esc_html__( 'Header Image', 'cozystay' ),
				'homeURL' => home_url( '/' ),
				'errorURL' => empty( $permalink_structure ) ? home_url( '/index.php/?error=404' ) : home_url( '/index.php/error-404' ),
				'i18nText' => array(
					'nan' => esc_html__( 'Please enter a number.', 'cozystay' ),
					'nain' => esc_html__( 'Please enter a positive number.', 'cozystay' ),
					// translators: %d number value from realtime
					'minTooLarge' => esc_html__( 'Please select a value that is less than the Maximum Load Time (%d seconds).', 'cozystay' ),
					// translators: %d number value from realtime
					'maxTooSmall' => esc_html__( 'Please enter a value greater than the Minimum Load Time (%d seconds).' , 'cozystay' )
				)
			) );
        }
	}
	new CozyStay_Assets_Controller_Customizer();
}
