<?php
/**
* Prevents theme from running on WordPress/PHP versions prior to the version specified
*/

if ( ! class_exists( 'CozyStay_Back_Compat' ) ) {
	class CozyStay_Back_Compat {
		/**
		* Error message for version check failed
		*/
		protected $message;
		/**
		* Int minimal WordPress version to be chanllenged
		*/
		public static $minimal_wp = '5.0';
		/**
		* Int minimal PHP version to be chanllenged
		*/
		public static $minimal_php = '5.6';
		/**
		* Construct function
		*/
		public function __construct() {
			if ( ! self::check_versions() ) {
				$this->message = sprintf(
					/* translators: %1$s: WordPress version. %2$s: PHP version. %3$s: current WordPress version. %4$s: current PHP version. */
					esc_html__(
						'CozyStay requires at least WordPress version %1$s and PHP version %2$s. You are running WordPress version %3$s and PHP version %4$s. Please upgrade and try again.',
						'cozystay'
					),
					self::$minimal_wp,
					self::$minimal_php,
					$GLOBALS['wp_version'],
					phpversion()
				);

				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
				add_action( 'load-customize.php', array( $this, 'customize' ) );
				add_action( 'template_redirect', array( $this, 'preview' ) );
			}
		}
		/**
		* @description switches to the default theme if failed the requirements
		*/
		public function switch_theme() {
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			unset( $_GET['activated'] );
			add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );
		}
		/**
		 * @description add a message for unsuccessful theme switch
		 */
		public function upgrade_notice() { ?>
			<div class="error"><p><?php echo esc_html( $this->message ); ?></p></div> <?php
		}
		/**
		 * @description prevent the Customizer from being loaded on the old version WordPress
		 */
		public function customize() {
			wp_die( esc_html( $this->message ), '', array(
				'back_link' => true,
			) );
		}
		/**
		 * @description prevent the Theme Preview from being loaded on the old version WordPress
		 */
		public function preview() {
			if ( isset( $_GET['preview'] ) ) {
				wp_die( esc_html( $this->message ) );
			}
		}
		/**
		* Public interface function to tell if the version checks passed
		* @return boolean
		*/
		public static function check_versions() {
			$wp_passed = version_compare( $GLOBALS['wp_version'], self::$minimal_wp, '>=' );
			$php_passed = version_compare( phpversion(), self::$minimal_php, '>=' );
			return $wp_passed && $php_passed;
		}
	}
	new CozyStay_Back_Compat();
}
