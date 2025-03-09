<?php
/**
* Theme assets manager class for editor
*/

if ( ! class_exists( 'CozyStay_Assets_Controller_Editor' ) ) {
	class CozyStay_Assets_Controller_Editor {
		/**
		* Construct function
		*/
		public function __construct() {
			$this->load_files();
			add_filter( 'cozystay_get_gutenberg_custom_styles', array( $this, 'custom_css_for_gutenberg' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		}
		/**
		* Import required files
		*/
		protected function load_files() {
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-typography.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Enqueue assets for gutenberg editor
		*/
		public function enqueue_editor_assets() {
			$custom_css = apply_filters( 'cozystay_get_gutenberg_custom_styles', '' );
			$custom_css = trim( $custom_css );
			$style_handler = 'cozystay-block-editor-style';

			do_action( 'cozystay_enqueue_font_awesome' );
			wp_enqueue_style( 'elegant-font', COZYSTAY_ASSETS_URI . 'fonts/elegant-font/font.min.css' );

			wp_enqueue_style(
				$style_handler,
				COZYSTAY_ASSETS_URI . 'styles/editor/editor-style' . cozystay_get_assets_suffix() . '.css',
				array( 'wp-block-library' ),
				COZYSTAY_ASSETS_VERSION
			);

			if ( ! empty( $custom_css ) ) {
				wp_add_inline_style( $style_handler, $custom_css );
			}
			do_action( 'cozystay_enqueue_google_fonts' );
			do_action( 'cozystay_enqueue_adobe_fonts' );
		}
		/**
		* Custom CSS for gutenberg editor
		*/
		public function custom_css_for_gutenberg( $style ) {
			$vars = array();
			$settings = array(
				'--heading-font' => 'cozystay_typography_heading_font-family', // 'Cormorant Garamond', serif;
			    '--body-font' => 'cozystay_typography_text_font-family', // 'Jost', sans-serif;
			    '--primary-color' => 'cozystay_general_primary_color', // #f56d6b;
				'--secondary-color' => 'cozystay_general_secondary_color', // #c59764
				'--text-color' => 'cozystay_general_light_scheme_text_color', // #000;
			   	'--content-color' => 'cozystay_general_light_scheme_content_color' // #363636;
			);
			foreach( $settings as $var => $id ) {
				$value = cozystay_get_theme_mod( $id );
				if ( ! empty( $value ) ) {
					in_array( $var, array( '--heading-font', '--body-font' ) ) ? array_push( $vars, sprintf( '%1$s: "%2$s"', $var, cozystay_check_font_value( $value, $id ) ) ) : array_push( $vars, $var . ': ' . $value );
				}
			}
			$link_color = cozystay_get_theme_mod( 'cozystay_link_light_scheme_regular_color' );
			switch( $link_color ) {
				case 'primary':
					array_push( $vars, '--link-color: var(--primary-color)' );
					break;
				case 'secondary':
					array_push( $vars, '--link-color: var(--secondary-color)' );
					break;
				default:
					$custom_color = cozystay_get_theme_mod( 'cozystay_link_light_scheme_custom_regular_color' );
					empty( $custom_color ) ? '' : array_push( $vars, '--link-color: ' . $custom_color );
			}
			if ( cozystay_is_valid_array( $vars ) ) {
				$style .= sprintf( ':root { %s; }', implode( '; ', $vars ) );
			}

			return $style;
		}
	}
	new CozyStay_Assets_Controller_Editor();
}
