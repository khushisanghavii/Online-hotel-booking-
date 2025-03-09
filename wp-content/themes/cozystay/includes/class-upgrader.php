<?php
/**
* Theme upgrader class
*	To update the settings for update between versions
*/
if ( ! class_exists( 'CozyStay_Upgrader' ) ) {
	class CozyStay_Upgrader {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* String current theme version
		*/
		private $version = '';
		/**
		* If the previous verion if older than current version,
		*	do the upgrade and update theme version
		*/
		public function __construct() {
			$this->version = COZYSTAY_THEME_VERSION;

			$old_version = get_theme_mod( 'theme-version', '0.1' );
			if ( version_compare( $old_version, $this->version, '<' ) ) {
				if ( version_compare( $old_version, '1.0.0', '<' ) ) {
					$this->initial_settings();
				}
				$this->update_version();
			}
			$this->auto_update_theme_core();
		}
		/**
		* Initial settings
		*/
		protected function initial_settings() { }
		/**
		* Update version number to db
		*/
		protected function update_version(){
			set_theme_mod( 'theme-version', $this->version );
		}
		/**
		* Check theme core version
		*/
		protected function auto_update_theme_core() {
			if ( $this->check_auto_update_theme_core_permission() ) {
				set_theme_mod( 'last-time-try-theme-core-update', time() );
				$update_url = add_query_arg(
					array(
						'page' => 'tgmpa-install-plugins',
						'plugin' => urlencode( 'cs-core' ),
						'tgmpa-update' => 'update-plugin',
						'tgmpa-nonce' => wp_create_nonce( 'tgmpa-update' )
					),
					admin_url( 'themes.php' )
				);
				wp_safe_redirect( $update_url );
			}
		}
		/**
		* Conditional function if current user has the right persimission to auto update theme core
		*/
		protected function check_auto_update_theme_core_permission() {
			if ( 'on' == get_option( 'cozystay_auto_update_required_plugin' ) ) {
				$is_admin = is_admin() && ! wp_doing_ajax() && current_user_can( 'update_plugins' );
				// Disabled on tgmpa plugin page
				$valid_location = empty( $_GET[ 'page'] ) || ( $_GET[ 'page'] != 'tgmpa-install-plugins' );
				// Check the time of last try
				$last_try = get_theme_mod( 'last-time-try-theme-core-update' );
				$valid_retry = empty( $last_try ) || ( time() > ( $last_try + ( 24 * 3600 ) ) );
				// Check if theme core exists
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				}
				$all_plugins = get_plugins();
				$core = 'cozystay-core/cozystay-core.php';
				$has_low_core = ! empty( $all_plugins[ $core ] ) && version_compare( $all_plugins[ $core ]['Version'], COZYSTAY_CORE_VERSION, '<' );

				return $has_low_core && $is_admin && $valid_location && $valid_retry;
			}
			return false;
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
	CozyStay_Upgrader::_instance();
}
