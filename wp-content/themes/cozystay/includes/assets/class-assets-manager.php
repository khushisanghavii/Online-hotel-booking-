<?php
/**
* Assets manager class for styles and scripts
*/

if ( ! class_exists( 'CozyStay_Assets_Manager' ) ) {
	class CozyStay_Assets_Manager {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'loftocean_enqueue_font_awesome', array( $this, 'enqueue_font_awesome' ) );
			add_action( 'cozystay_enqueue_font_awesome', array( $this, 'enqueue_font_awesome' ) );

			$not_ajax = ! cozystay_is_ajax();
			if ( $not_ajax ) {
				if ( cozystay_is_customize() ) {
					$this->load_customizer_assets();
				} else if ( is_admin() ) {
					$this->load_admin_assets();
				}
			}
			if ( cozystay_is_front() ) {
				$this->load_front_assets();
			}
		}
		/**
		* Load assets for frontend
		*/
		protected function load_front_assets() {
			$this->import_file( 'front' );
		}
		/**
		* Load assets for customize.php
		*/
		protected function load_customizer_assets() {
			$this->import_file( 'customizer' );
		}
		/**
		* Load assets for site admin pages
		*/
		protected function load_admin_assets() {
			$this->import_file( 'editor' );
		}
		/**
		* Import file by given name
		*/
		protected function import_file( $filename ) {
			$path = COZYSTAY_THEME_INC . 'assets/class-' . $filename . '-assets-controller.php';
			if ( file_exists( $path ) ) {
				require_once $path; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
		/**
		* Enqueue Font Awesome
		*/
		public function enqueue_font_awesome() {
			wp_enqueue_style( 'font-awesome', COZYSTAY_ASSETS_URI . 'fonts/font-awesome/css/all.min.css' );
		}
		/**
		* Instance Loader class
		*	there can only be one instance of loader
		* @return class Loader
		*/
		public static function init() {
			if ( false === self::$instance ) {
				self::$instance = new CozyStay_Assets_Manager();
			}
			return self::$instance;
		}
	}
}
