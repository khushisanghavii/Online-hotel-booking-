<?php
if ( ! class_exists( 'CozyStay_Theme_Option_Required_Plugins' ) ) {
	class CozyStay_Theme_Option_Required_Plugins extends CozyStay_Theme_Option_Section {
		/**
		* Setup environment
		*/
		protected function setup_env() {
			$this->id = 'tab-plugins';
			$this->title = esc_html__( 'Required Plugins', 'cozystay' );
			$this->defaults = array(
				'cozystay_auto_update_required_plugin' => '',
				'cozystay_auto_activate_required_plugin' => ''
			);

			add_filter( 'cozystay_admin_theme_settings_panel_get_default_tab', array( $this, 'default_tab' ) );
			add_filter( 'cozystay_enable_required_plugin_auto_update', array( $this, 'is_auto_update_enabled' ) );
			add_filter( 'cozystay_enable_required_plugin_auto_activate', array( $this, 'is_auto_active_enabled' ) );
			add_filter( 'auto_update_plugin', array( $this, 'set_plugin_auto_update' ), 999, 2 );
		}
		/**
		* Render tab content
		*/
		public function render_tab_content() {
			$auto_update_status = $this->get_value( 'cozystay_auto_update_required_plugin' );
			$auto_activate_status = $this->get_value( 'cozystay_auto_activate_required_plugin' ); ?>

            <h2><?php esc_html_e( 'CozyStay Core', 'cozystay' ); ?></h2><?php
			if ( $this->is_tgmpa_exists() ) : ?>
	            <p><?php printf(
					// translators: 1 & 2. html tag <strong>
					esc_html__( 'Please make sure you have installed and activated the required plugin %1$s"CozyStay Core"%2$s to get the full functionalities of the theme.', 'cozystay' ),
					'<strong>',
					'</strong>'
				); ?></p>
				<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>">
					<?php esc_html_e( 'Install Plugins', 'cozystay' ); ?>
				</a>
	            <hr><?php
			endif; ?>
            <h2><php esc_html_e( 'Auto Options', 'cozystay' ); ?></h2>
            <ul>
                <li>
                    <label for="cozystay_auto_update_required_plugin">
                        <input type="checkbox" id="cozystay_auto_update_required_plugin" name="cozystay_auto_update_required_plugin" value="on" <?php checked( 'on', $auto_update_status ); ?>>
                        <?php esc_html_e( 'Auto update the plugin when updating the theme', 'cozystay' ); ?>
					</label>
                </li>
                <li>
                    <label for="cozystay_auto_activate_required_plugin">
                        <input type="checkbox" id="cozystay_auto_activate_required_plugin" name="cozystay_auto_activate_required_plugin" value="on" <?php checked( 'on', $auto_activate_status ); ?>>
                        <?php esc_html_e( 'Auto activate the plugin when activating the theme', 'cozystay' ); ?>
					</label>
                </li>
                <li>
                    <a href="https://www.loftocean.com/cozystay-doc/documentation.html#basic-settings" target="_blank">
                        <?php esc_html_e( 'When should I enable the auto options above?', 'cozystay' ); ?>
                    </a>
                </li>
            </ul><?php
		}
		/**
		* Register options
		*/
		protected function register_options() {
			$this->options = array(
				'cozystay_auto_update_required_plugin' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'cozystay_auto_activate_required_plugin' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			);
		}
		/**
		* Set required plugin auto update
		* @param boolean
		* @param object
		* @return boolean
		*/
		public function set_plugin_auto_update( $should_update, $plugin ) {
			if ( ! isset( $plugin->plugin ) || ( 'cozystay-core/cozystay-core.php' !== $plugin->plugin ) ) {
				return $should_update;
			} else {
				return apply_filters( 'cozystay_enable_required_plugin_auto_update', false );
			}
		}
		/**
		* If enable required plugin auto update
		* @param boolean
		* @return boolean
		*/
		public function is_auto_update_enabled( $enabled ) {
			return 'on' == $this->get_value( 'cozystay_auto_update_required_plugin' );
		}
		/**
		* If enable required plugin auto activate setting value
		* @param boolean
		* @return boolean
		*/
		public function is_auto_active_enabled( $enabled ) {
			return 'on' == $this->get_value( 'cozystay_auto_activate_required_plugin' );
		}
		/**
		* Help function to test tgmpa-install-plugins admin menu exists
		* @return boolean
		*/
		protected function is_tgmpa_exists() {
			global $submenu;
			return in_array( 'tgmpa-install-plugins', wp_list_pluck( $submenu['themes.php'], 2 ) );
		}
		/**
		* Default tab id
		*/
		public function default_tab( $tab ) {
			return $this->id;
		}
	}
	new CozyStay_Theme_Option_Required_Plugins();
}
