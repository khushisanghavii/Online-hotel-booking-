<?php
/**
* Theme customize manager class
*	and theme own customize section/controls/sanitize;
*/

if ( ! class_exists( 'CozyStay_Customize_Manager' ) ) {
	class CozyStay_Customize_Manager {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			$this->includes(); // Import theme customize option configs
		}
		/**
		* Load customize related classes
		*/
		public function load_dependency() {
			// Include the abstract class
			require_once COZYSTAY_THEME_INC . 'abstracts/class-abstract-customize-configuration-base.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'admin/customize/class-customize-control.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'admin/customize/class-customize-setting.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			if ( ! empty( $_REQUEST['customize_changeset_uuid'] ) ) {
				require_once COZYSTAY_THEME_INC . 'utils/class-utils-sanitize.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
		/**
		* Import the required files
		*/
		private function includes() {
			$configs_dir = COZYSTAY_THEME_INC . 'admin/customize/configs/';

			$this->load_dependency();

			require_once $configs_dir . 'site-identity.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'general.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'site-header.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'site-footer.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'colors-styles.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'typography.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'default-page-title.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'rooms.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'blog.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $configs_dir . 'rooms.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			if ( cozystay_is_woocommerce_activated() ) {
				require_once $configs_dir . 'woocommerce.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
		/**
		* Instance Loader class
		*	there can only be one instance of loader
		* @return class Loader
		*/
		public static function _instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	add_action( 'init', 'CozyStay_Customize_Manager::_instance' );
}
