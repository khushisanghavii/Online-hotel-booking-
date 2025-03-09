<?php
/**
* Check front requirement for some unusual situation
*/

if ( ! class_exists( 'CozyStay_Front_Check_Requirements' ) ) {
	class CozyStay_Front_Check_Requirements {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'cozystay_check_front_requirement', array( $this, 'load_files' ) );
			add_action( 'cozystay_check_front_requirement', array( $this, 'load_hooks' ) );
		}
		/**
		* Import required files
		*/
		public function load_files() {
            if ( ! class_exists( 'CozyStay_Front_Manager' ) ) {
    			require_once COZYSTAY_THEME_INC . 'utils/class-utils-sanitize.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/class-walker-comment.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/class-walker-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound


                require_once COZYSTAY_THEME_INC . 'default-option-values.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                require_once COZYSTAY_THEME_INC . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                require_once COZYSTAY_THEME_INC . 'front/functions-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                require_once COZYSTAY_THEME_INC . 'assets/class-front-assets-controller.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
            }
		}
		/**
		* Hooks needed
		*/
		public function load_hooks() {
            if ( ! class_exists( 'CozyStay_Front_Manager' ) ) {
				add_filter( 'body_class', array( $this, 'body_class' ), 999 );
				add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout'), 9999 );
				add_filter( 'cozystay_content_class', array( $this, 'content_class' ), 9999 );
				// Woocommerce related
				if ( cozystay_is_woocommerce_activated() ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 5 );
					add_filter( 'cozystay_front_inline_styles_handler', array( $this, 'inline_style_handler' ) );
					add_filter( 'cozystay_front_get_main_theme_style_dependency', array( $this, 'theme_css_deps' ) );
				}
			}
		}
		/**
		* Add extra class name to <body>
		* @param array class name list
		* @return array class name list
		*/
		public function body_class( $class ) {
			array_push( $class, esc_attr( cozystay_get_theme_mod( 'cozystay_general_color_scheme' ) ) );
			return $class;
		}
		/**
		* Check page layout for 404 page
		*/
		public function get_page_layout( $layout ) {
			return '';
		}
		/**
		* Check content class for 404 page
		*/
		public function content_class( $class ) {
			return array_diff( $class, array( 'with-sidebar-right', 'with-sidebar-left' ) );
		}
		/**
		* Enqueue woocommerce related style file
		*/
		public function enqueue_script() {
			$suffix = cozystay_get_assets_suffix();
			wp_enqueue_style( 'cozystay-woocommerce', COZYSTAY_ASSETS_URI . 'styles/front/shop' . $suffix . '.css', array( 'cozystay-theme-style' ), COZYSTAY_ASSETS_VERSION );
		}
		/**
		* Add woocommerce style to theme main css dependency list
		*/
		public function theme_css_deps( $deps ) {
			$deps = array_merge( $deps, array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ) );
			return $deps;
		}
		/**
		* Theme inline style handler
		*/
		public function inline_style_handler( $handler ) {
			return 'cozystay-woocommerce';
		}
		/**
		* Instance Loader class
		*	there can only be one instance of loader
		* @return class Loader
		*/
		public static function init() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
		}
	}
    CozyStay_Front_Check_Requirements::init();
}
