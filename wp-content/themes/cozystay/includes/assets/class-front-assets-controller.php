<?php
/**
* Theme front end assets manager class
*/

if ( ! class_exists( 'CozyStay_Front_Assets_Controller' ) ) {
	class CozyStay_Front_Assets_Controller {
		/**
		* String main style.css id
		*/
		public $main_style_id = '';
		/**
		* String custom styles from customizer
		*/
		protected $custom_styles = false;
		/**
		* String custom style vars
		*/
		protected $custom_style_vars = false;
		/**
		* Construct function
		*/
		public function __construct() {
			$this->load_files();
			$this->main_style_id = 'cozystay-theme-style';

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_styles' ), 1000 );
			add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'elementor_color_scheme_simulator' ) );

			add_filter( 'cozystay_front_json', array( $this, 'frontend_json' ) );
		}
		/**
		* Import required files
		*/
		protected function load_files() {
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-ajax.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-general.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-site-header.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-site-footer.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-colors-styles.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-typography.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-page-title.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-rooms.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'assets/custom/class-custom-blog.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Generate the css variable related custom style
		*/
		public function generate_custom_style_variables() {
			if ( false === $this->custom_style_vars ) {
				$vars = array_filter( apply_filters( 'cozystay_custom_style_vars', array() ) );
				$items = array();
				if ( cozystay_is_valid_array( $vars ) ) {
					foreach ( $vars as $selector => $values ) {
						$sub_items = array();
						foreach ( $values as $var => $value ) {
							if ( ! empty( $var ) && ! empty( $value ) ) {
								$sub_items[] = sprintf( '%s: %s;', $var, $value );
							}
						}
						if ( cozystay_is_valid_array( $sub_items ) ) {
							$items[] = sprintf( '%1$s { %2$s }', $selector, implode( ' ', $sub_items ) );
						}
					}
				}
				$this->custom_style_vars = cozystay_is_valid_array( $items ) ? implode( ' ', $items ) : '';
			}
			return $this->custom_style_vars;
		}
		/**
		* Generate custom styles based on each customize sections
		*/
		protected function generate_custom_styles() {
			$styles = apply_filters( 'cozystay_custom_styles', array() );
			if ( cozystay_is_valid_array( $styles ) ) {
				$styles = array_filter( $styles );
				return implode( ' ', $styles );
			} else {
				return '';
			}
		}
		/**
		* Generate custom styles to be imported by frontend
		*/
		protected function custom_styles() {
			if ( false === $this->custom_styles ) {
				$vars = $this->generate_custom_style_variables();
				$styles = $this->generate_custom_styles();
				$custom_styles = $vars . $styles;
				$this->custom_styles = trim( $custom_styles );
			}
			return $this->custom_styles;
		}
		/**
		* Add objects to frontend json
		* @param array
		* @return array
		*/
		public function frontend_json( $json = array() ) {
			$json['errorText'] = array( 'noMediaFound'	=> esc_js( esc_html__( 'No image found', 'cozystay' ) ) );
			if ( is_singular( 'post' ) && apply_filters( 'loftocean_front_has_background_video', false, get_the_ID() ) ) {
				$json['postFeaturedVideo'] = esc_js( apply_filters( 'loftocean_front_background_video', '', get_the_ID() ) );
			}
			$json[ 'woocommerceProductFilterAjaxEnabled' ] = cozystay_get_theme_mod( 'cozystay_woocommerce_category_filter_enable_ajax' );
			$json[ 'onepagemenus' ] = apply_filters( 'cozystay_enable_onepage_menu_check', cozystay_module_enabled( 'cozystay_enable_onepage_menu_check' ) );
			return $json;
		}
		/**
		* Enqueue styles generated from customization
		*/
		public function enqueue_custom_styles() {
			$custom_styles = $this->custom_styles();
			if ( ! empty( $custom_styles ) ) {
 				wp_add_inline_style( apply_filters( 'cozystay_front_inline_styles_handler', $this->main_style_id ), $custom_styles );
			}
		}
		/**
		* Enqueue assets in head
		*/
		public function enqueue_assets() {
			$asset_uri = COZYSTAY_ASSETS_URI;
			$asset_version = COZYSTAY_ASSETS_VERSION;
			$theme_js_deps = array( 'jquery', 'cozystay-helper', 'slick' );
			$asset_suffix = cozystay_get_assets_suffix();
			$is_customize_preview = cozystay_is_customize_preview();

			$theme_style_deps = cozystay_is_gutenberg_enabled() ? array( 'wp-block-library' ) : array();
			$theme_style_deps = apply_filters( 'cozystay_front_get_main_theme_style_dependency', $theme_style_deps );
			do_action( 'cozystay_enqueue_google_fonts' ); // Load Google fonts
			do_action( 'cozystay_enqueue_adobe_fonts' ); // Load Adobe fonts
			wp_enqueue_style( 'slick', $asset_uri . 'libs/slick/slick.min.css', array(), '1.8' );
			do_action( 'cozystay_enqueue_font_awesome' );
			wp_enqueue_style( 'elegant-font', $asset_uri . 'fonts/elegant-font/font.min.css' );
			wp_enqueue_style( $this->main_style_id, $asset_uri . 'styles/front/main' . $asset_suffix . '.css', $theme_style_deps, $asset_version );
			is_rtl() ? wp_enqueue_style( 'cozystay-rtl', $asset_uri . 'styles/front/rtl' . $asset_suffix . '.css', array( $this->main_style_id ), $asset_version ) : '';

			wp_enqueue_script( 'modernizr', $asset_uri . 'scripts/libs/modernizr.min.js', array(), '3.3.1' );
			wp_enqueue_script( 'html5shiv', $asset_uri . 'scripts/libs/html5shiv.min.js', array(), '3.7.3' );
			wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9');
		}
		/**
		* Enqueue javascripts in footer
		*/
		public function enqueue_scripts() {
			$asset_uri = COZYSTAY_ASSETS_URI;
			$asset_version = COZYSTAY_ASSETS_VERSION;
			$theme_js_deps = array( 'jquery', 'cozystay-helper', 'slick', 'cozystay-animations' );
			$asset_suffix = cozystay_get_assets_suffix();

			if ( cozystay_is_theme_core_activated() ) {
				array_push( $theme_js_deps, 'loftocean-video-player' );
			}

			if ( cozystay_is_popup_box_enabled() && ! is_customize_preview() ) {
				wp_enqueue_script( 'cozystay-popup-box', $asset_uri . 'scripts/front/popup-box' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
				wp_localize_script( 'cozystay-popup-box', 'cozystayPopupBox', array(
					'popupFormEnabled' => cozystay_module_enabled( 'cozystay_popup_signup_form_enable' ) && cozystay_does_mc4wp_form_exist( $form_id ),
					'oncePerSession' => cozystay_module_enabled( 'cozystay_general_popup_box_once_per_session' ),
					'timer' => esc_js( cozystay_get_theme_mod( 'cozystay_general_popup_box_display_delay' ) )
				) );
			}

			wp_enqueue_script( 'slick', $asset_uri . 'libs/slick/slick.min.js', array( 'jquery' ), '1.8', true );

			if ( 'disable' != cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) ) {
				array_push( $theme_js_deps, 'cozystay-sticky-site-header' );
				wp_enqueue_script( 'cozystay-sticky-site-header', $asset_uri . 'scripts/front/sticky-site-header' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
			}
			if ( cozystay_module_enabled( 'cozystay_sidebar_enable_sticky' ) ) {
				array_push( $theme_js_deps, 'cozystay-sticky-sidebar' );
				wp_enqueue_script( 'cozystay-sticky-sidebar', $asset_uri . 'scripts/front/sticky-sidebar' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
			}
			if ( cozystay_module_enabled( 'cozystay_general_cookie_law_enabled' ) || cozystay_is_customize_preview() ) {
				wp_enqueue_script( 'cozystay-cookie-law', $asset_uri . 'scripts/front/cookie-law' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
				wp_localize_script( 'cozystay-cookie-law', 'cozystayCookieLaw', array(
					'version' => cozystay_get_theme_mod( 'cozystay_general_cookie_law_version' ) ,
					'isPreview' => cozystay_is_customize_preview()
				) );
			}

			if ( is_singular() ) {
				array_push( $theme_js_deps, 'justified-gallery' );
				array_push( $theme_js_deps, 'jquery-fitvids' );
				wp_enqueue_script( 'jquery-fitvids', $asset_uri . 'scripts/libs/jquery.fitvids.min.js', array( 'jquery' ), '1.1', true );
				wp_enqueue_script( 'justified-gallery', $asset_uri . 'libs/justified-gallery/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.6.5', true );
				wp_enqueue_style( 'justified-gallery', $asset_uri . 'libs/justified-gallery/justifiedGallery.min.css', array(), '3.6.3' );
				if ( comments_open() ) {
					wp_enqueue_script( 'comment-reply' );
				}
				if ( is_singular( 'post' ) ) {
					array_push( $theme_js_deps, 'cozystay-post' );
					wp_enqueue_script( 'cozystay-post', $asset_uri . 'scripts/front/single-post' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
				}
			}

			wp_enqueue_script( 'cozystay-helper', $asset_uri . 'scripts/front/helper' . $asset_suffix . '.js', array( 'jquery' ), $asset_version, true );
			wp_localize_script( 'cozystay-helper', 'cozystayHelper', array( 'siteURL' => home_url( '/' ) ) );
			wp_enqueue_script( 'cozystay-animations', $asset_uri . 'scripts/front/animations' . $asset_suffix . '.js', array( 'cozystay-helper' ), $asset_version, true );
			wp_enqueue_script( 'cozystay-theme-script', $asset_uri . 'scripts/front/main' . $asset_suffix . '.js', $theme_js_deps, $asset_version, true );
			wp_localize_script( 'cozystay-theme-script', 'cozystay', apply_filters( 'cozystay_front_json', array() ) );
		}
		/**
		* Enqueue scripts for elmentor eidtor
		*/
		public function elementor_color_scheme_simulator() {
			if ( is_singular( array( 'custom_site_headers', 'custom_blocks' ) ) && cozystay_show_elementor_simulator() ) {
				wp_enqueue_script(
					'cozystay-color-scheme-simulator',
					COZYSTAY_ASSETS_URI . 'scripts/front/elementor-color-scheme-simulator' . cozystay_get_assets_suffix() . '.js',
					array( 'jquery' ),
					COZYSTAY_ASSETS_VERSION,
					true
				);
			}
		}
	}
	new CozyStay_Front_Assets_Controller();
}
