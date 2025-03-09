<?php
if ( ! class_exists( 'CozyStay_Theme_Settings' ) ) {
	class CozyStay_Theme_Settings {
		/**
		* String default tab
		*/
		protected $default_tab = '';
		/**
		* Array tabs settings
		*/
		protected $tabs = array();
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			$this->includes();
			$this->save_changes();
			add_action( 'admin_menu', array( $this, 'add_theme_settings_page' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'cozystay_admin_theme_settings_the_tab_contents', array( $this, 'render_tab_contents' ) );
			add_action( 'cozystay_admin_theme_settings_the_tab_titles', array( $this, 'render_tab_titles' ) );
			add_action( 'cozystay_admin_theme_settings_the_hidden_input', array( $this, 'hidden_inputs' ) );
			add_filter( 'cozystay_admin_theme_settings_panel_get_default_tab', array( $this, 'set_default_tab' ), 9999 );
		}
		/**
		* Include the files needed
		*/
		protected function includes() {
			$inc_dir = COZYSTAY_THEME_INC . 'admin/options/theme-settings/';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'utils/class-utils-sanitize.php';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'abstracts/class-abstract-theme-option-section.php';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-require-plugins.php';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-custom-font.php';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-integrations.php';
			// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $inc_dir . 'class-doc-support.php';
		}
		/**
		* Save changes and redirect
		*/
		protected function save_changes() {
			if ( current_user_can( 'manage_options' ) && isset( $_REQUEST['cozystay_theme_settings_nonce'] )
				&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cozystay_theme_settings_nonce'] ) ), 'cs-theme-settings-nonce' ) ) {
				// Save changes
				do_action( 'cozystay_admin_theme_settings_save' );
				update_option( 'loftocean_flush_rewrite', '' );
				$active_tab = '';
				if ( isset( $_REQUEST[ 'cozystay_theme_settings_active_tab' ] ) ) {
					$active_tab = sanitize_text_field( wp_unslash( $_REQUEST[ 'cozystay_theme_settings_active_tab' ] ) );
				}
				$return_url = 'admin.php?page=cs-theme-' . $active_tab . '&cs-updated=1';
				// Redirect, avoid resubmission
				wp_safe_redirect( admin_url( $return_url ) );
				exit;
			}
		}
		/**
		* Add theme options page
		*/
		public function add_theme_settings_page() {
			global $submenu;
			$main_menu_title = esc_html__( 'CozyStay', 'cozystay' );
			$sub_menus = array(
				'tab-plugins' => esc_html__( 'Required Plugins', 'cozystay' ),
				'tab-custom-font' => esc_html__( 'Custom Font', 'cozystay' )
			);
			if ( cozystay_is_mc4wp_activated() && cozystay_is_theme_core_activated() && cozystay_is_polylang_activated() ) {
				$sub_menus[ 'tab-integrations' ] = esc_html__( 'Integrations', 'cozystay' );
			}
			$sub_menus[ 'tab-support' ] = esc_html__( 'Docs & Support', 'cozystay' );
			$title = esc_html__( 'Dashboard', 'cozystay' );

			$icon = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMyAyMyI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTIwLjcxLDcuNjNhMTAuMjUsMTAuMjUsMCwwLDAtMS44Mi0yLjg1TDE3LjY3LDcuNjNaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTEuNSwxLjVBMTAuMDksMTAuMDksMCwwLDAsOSwxLjgzdjUuOGg4LjFsMS40MS0zLjI4QTEwLDEwLDAsMCwwLDExLjUsMS41WiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTE4LjE1LDE5QTkuOTEsOS45MSwwLDAsMCwyMC45Miw4LjE4SDE3LjQzbC0yLjc5LDYuNDlaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTQuNCwxNS4yNGwtMi42OSw2LjI1YTkuOTMsOS45MywwLDAsMCw2LTIuMTlaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNOC40Miw4LjE4SDIuMDhBOS44Niw5Ljg2LDAsMCwwLDEuNSwxMS41LDEwLDEwLDAsMCwwLDguNDIsMjFaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNS4wOCwzLjg2YTkuODksOS44OSwwLDAsMC0yLjgsMy43N0g4LjE3WiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTguNDIsNy4wN1YyQTkuOTEsOS45MSwwLDAsMCw1LjUsMy41MVoiLz48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTQuMjUgMTQuMTkgMTYuODMgOC4xOCA5LjMzIDguMTggMTQuMjUgMTQuMTkiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik05LDguNjFWMjEuMTZhMTAuMTUsMTAuMTUsMCwwLDAsMi4xNC4zMkwxNCwxNC43NloiLz48L3N2Zz4=';


			add_menu_page( $main_menu_title, $main_menu_title, 'manage_options', 'cs-theme', array( $this, 'render_theme_settings_page' ), $icon, 2 );
			foreach( $sub_menus as $id => $label ) {
		    	add_submenu_page( 'cs-theme', $label, $label, 'manage_options', 'cs-theme-' . $id, array( $this, 'render_theme_settings_page' ) );
			}
			remove_submenu_page( 'cs-theme', 'cs-theme' );
		}
		/**
		* Render theme option page content
		*/
		public function render_theme_settings_page() {
			do_action( 'cozystay_theme_options' );
			$this->default_tab = apply_filters( 'cozystay_admin_theme_settings_panel_get_default_tab', '' );
			$this->tabs = apply_filters( 'cozystay_admin_theme_settings_get_tabs', array() );
			if ( cozystay_is_valid_array( $this->tabs ) && ! isset( $this->tabs[ $this->default_tab ] ) ) {
					$this->default_tab = array_keys( $this->tabs )[0];
			} ?>

			<div class="wrap">
				<h1 class="inline-title hidden"><?php esc_html_e( 'CozyStay', 'cozystay' ); ?></h1>
				<div class="cs-dashboard">
					<?php $this->the_dashboard_header(); ?>
	               	<?php do_action( 'cozystay_admin_theme_settings_the_tab_titles' ); ?>
					<form id="cs-dashboard-form" action="<?php echo esc_url( admin_url( 'themes.php?page=cs-theme-' . $this->default_tab ) ); ?>" method="POST">
	                    <?php do_action( 'cozystay_admin_theme_settings_the_tab_contents' ); ?>
						<?php do_action( 'cozystay_admin_theme_settings_the_hidden_input' ); ?>
	                    <p class="submit cs-submit-button"<?php if ( $this->hide_submit_button() ) : ?> style="display: none;"<?php endif; ?>>
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'cozystay' ); ?>">
						</p>
	                </form>
	            </div>
			</div><?php
		}
		/**
		* Render panels for theme option
		*/
		public function render_tab_titles() {
			$tabs = $this->tabs;
			if ( cozystay_is_valid_array( $tabs ) ) : ?>
				<div id="cs-dashboard-tabs-wrapper" class="nav-tab-wrapper"><?php
					foreach( $tabs as $id => $tab ) {
						printf(
							// translators: 1. id 2. active classname 3. title
							'<a id="cs-dashboard-%1$s" class="nav-tab%2$s" href="#%1$s">%3$s</a>',
							esc_attr( $id ),
							$this->default_tab == $id ? ' nav-tab-active' : '',
							esc_html( $tab[ 'title' ] )
						);
					} ?>
				</div><?php
			endif;
		}
		/**
		* Render widgets for theme option
		*/
		public function render_tab_contents() {
			$tabs = $this->tabs;
			if ( cozystay_is_valid_array( $tabs ) ) :
				foreach( $tabs as $id => $tab ) :
					if ( cozystay_is_callback_valid( $tab['callback_func'] ) ) : ?>
						<div id="<?php echo esc_attr( $id ); ?>" class="cs-dashboard-form-page<?php if ( $this->default_tab == $id ) : ?> tab-active<?php endif; ?>">
							<?php call_user_func( $tab['callback_func'], $tab ); ?>
						</div><?php
					endif;
				endforeach;
			endif;
		}
		/**
		* Render the dashboard header
		*/
		protected function the_dashboard_header() { ?>
			<div class="cs-dashboard-header">
				<div class="header-logo">
					<img width="40" height="40" alt="<?php esc_attr_e( 'theme-icon', 'cozystay' ); ?>" src="<?php echo esc_url( COZYSTAY_ASSETS_URI ); ?>images/loftocean-icon.png">
					<h2><?php esc_html_e( 'CozyStay', 'cozystay' ); ?> <small class="version">v<?php echo esc_html( COZYSTAY_THEME_VERSION ); ?></small></h2>
				</div>

				<div class="header-buttons">
					<a href="https://loftocean.com/doc/cozystay/" target="_blank" class="button button-primary"><?php esc_html_e( 'Documentation', 'cozystay' ); ?></a>
					<a href="https://www.loftocean.com/help-center/" target="_blank" class="button button-primary"><?php esc_html_e( 'Get Support', 'cozystay' ); ?></a>
				</div>
			</div><?php
		}
		/**
		* Output hidden inputs
		*/
		public function hidden_inputs() { ?>
			<input type="hidden" name="cozystay_theme_settings_active_tab" value="<?php echo esc_attr( $this->default_tab ); ?>" />
			<input type="hidden" name="cozystay_theme_settings_nonce" value="<?php echo esc_attr( wp_create_nonce( 'cs-theme-settings-nonce' ) ); ?>" />
			<input type="hidden" name="cozystay_create_timestamp" value="<?php echo esc_attr( time() ); ?>" /><?php
		}
        /**
        * Enqueue assets
        */
        public function enqueue_assets() {
			if ( current_user_can( 'manage_options' ) && isset( $_GET['page'] ) && ( 0 === strpos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 'cs-theme-tab-' ) ) ) {
				$suffix = cozystay_get_assets_suffix();
				wp_enqueue_media();
				wp_enqueue_style( 'cozystay-admin-settings', COZYSTAY_ASSETS_URI . 'styles/admin/admin' . $suffix . '.css', array(), COZYSTAY_ASSETS_VERSION );
				wp_enqueue_script( 'cozystay-admin-settings', COZYSTAY_ASSETS_URI . 'scripts/admin/admin' . $suffix . '.js', array( 'jquery', 'wp-util' ), COZYSTAY_ASSETS_VERSION, true );
				wp_localize_script( 'cozystay-admin-settings', 'cozystayThemeSettings', apply_filters( 'cozystay_theme_settings_json', array() ) );
			}
		}
		/**
		* Set default tab
		*/
		public function set_default_tab( $tab ) {
			if ( isset( $_REQUEST['active-tab' ] ) ) {
				return sanitize_text_field( wp_unslash( $_REQUEST[ 'active-tab' ] ) );
			}
			if ( isset( $_REQUEST['page' ] ) ) {
				$page = sanitize_text_field( wp_unslash( $_REQUEST[ 'page' ] ) );
				if ( 0 === strpos( $page, 'cs-theme-tab-' ) ) {
					return str_replace( 'cs-theme-', '', $page );
				}
			}
			return $tab;
		}
		/**
		* Helper function if hide submit button for current tab
		*/
		protected function hide_submit_button() {
			return in_array( $this->default_tab, array( 'tab-support', 'tab-demo', 'tab-tools', 'tab-integrations' ) );
		}
	}
	new CozyStay_Theme_Settings();
}
