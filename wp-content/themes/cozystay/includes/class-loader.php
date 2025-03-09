<?php
/*
* Theme Loader Class
*/

if ( ! class_exists( 'CozyStay_Loader' ) ) {
	class CozyStay_Loader {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'load_text_domain' ) );

			$this->theme_constants();
			// Test current environment
			if ( $this->check_versions() ) {
				$this->includes();
				$this->init_hooks();
			}
		}
		/**
		* Load text domain
		*/
		public function load_text_domain() {
			load_theme_textdomain( 'cozystay' );
		}
		/**
		* Define theme constants
		*/
		protected function theme_constants() {
			$this->define( 'COZYSTAY_THEME_VERSION', 	'1.3.0' );
			$this->define( 'COZYSTAY_THEME_URI', 		get_template_directory_uri() . '/' );
			$this->define( 'COZYSTAY_THEME_DIR', 		get_template_directory() . '/' );
			$this->define( 'COZYSTAY_THEME_INC', 		COZYSTAY_THEME_DIR . 'includes/' );
			$this->define( 'COZYSTAY_ASSETS_URI', 		COZYSTAY_THEME_URI . 'assets/' );
			$this->define( 'COZYSTAY_PREVIEW_BASE',		'https://cozystay.loftocean.com/' );
			$this->define( 'COZYSTAY_ASSETS_VERSION', 	'2023101601' );
			$this->define( 'COZYSTAY_DEBUG_MODE', 		false );
			$this->define( 'COZYSTAY_CORE_VERSION', 	'1.3.0' );
		}
		/**
		* Helper function to actually define constant
		*/
		protected function define( $name, $value ) {
			defined( $name ) ? '' : define( $name, $value );
		}
		/*
		* Check environment requirements for current theme
		* @return boolean
		*/
		protected function check_versions() {
			require_once COZYSTAY_THEME_INC . 'class-back-compat.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			return CozyStay_Back_Compat::check_versions();
		}
		/**
		* Load required modules
		*/
		private function includes() {
			$inc_dir = COZYSTAY_THEME_INC;

			// Core functions and class
			require_once $inc_dir . 'class-upgrader.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-wp-core.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'utils/class-utils-image.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-theme-core-filters.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'utils/class-custom-block.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'utils/class-rest-api.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// For assets manager class
			require_once $inc_dir . 'assets/class-assets-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'gutenberg/class-gutenberg.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'importer/class-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'admin/plugins/one-click-demo-import-config.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'front/class-check-requirements.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			if ( cozystay_is_woocommerce_activated() ) {
				require_once $inc_dir . 'woocommerce/class-woocommerce.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			if ( $this->is_request( 'ajax' ) ) {
				require_once $inc_dir . 'default-option-values.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/class-front-block-render.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				// Ajax request hanlder
				require_once $inc_dir . 'admin/ajax/class-ajax-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			if ( $this->is_request( 'admin' ) ) {
				require_once $inc_dir . 'default-option-values.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions-post-list.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions-post-list-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/class-front-block-render.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				if ( current_user_can( 'install_plugins' ) ) {
					// Plugins installation third part plugin
					require_once $inc_dir . 'admin/plugins/tgm-plugin-activation-config.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				}
				if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
					// For theme admin metas
					require_once $inc_dir . 'admin/metas/class-meta-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				}
				if ( current_user_can( 'edit_theme_options' ) ) {
					// For theme options page
					require_once $inc_dir . 'admin/options/class-option-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				}
			}
			if ( $this->is_request( 'customize' ) ) {
				// For theme customize
				require_once $inc_dir . 'default-option-values.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'admin/customize/class-customize-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
			if ( $this->is_request( 'front' ) ) {
				// For frontend rendering
				require_once $inc_dir . 'default-option-values.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions-post-list.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions-post-list-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/class-front-manager.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
		/**
		* Register hooks
		*/
		protected function init_hooks() {
			add_action( 'after_setup_theme', 'CozyStay_Setup_Environment::init', 1 );
			add_action( 'init', 'CozyStay_Assets_Manager::init' );
			if ( $this->is_request( 'front' ) ) {
				add_action( 'init', 'CozyStay_Front_Manager::init', 999 );
			}
			// Trigger theme loaded action
			do_action( 'cozystay_loaded' );
		}
		/**
		* What type of request is this?
		* @param string
		* @return boolean
		*/
		protected function is_request( $type ) {
			return cozystay_is_request( $type );
		}
		/**
		* Instance Loader class, there can only be one instance of loader
		* @return class Loader instance
		*/
		public static function _instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	/**
	* Get loader instance
	* @return object instance of class CozyStay_Loader
	*/
	function cozystay() {
		return CozyStay_Loader::_instance();
	}
}
