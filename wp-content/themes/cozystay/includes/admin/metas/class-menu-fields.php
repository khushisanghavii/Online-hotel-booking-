<?php
if ( ! class_exists( 'CozyStay_Utils_Menu_Custom_fields' ) ) {
	class CozyStay_Utils_Menu_Custom_fields {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
            add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'the_mega_menu_settings' ), 10, 5 );
            add_action( 'wp_update_nav_menu_item', array( $this, 'save_mega_menu_settings' ), 10, 2 );
			add_action( 'admin_print_scripts-nav-menus.php', array( $this, 'admin_scripts' ) );
        }
        /**
        * Add mega menu settings to menu item
        */
        public function the_mega_menu_settings( $item_id, $menu_item, $depth, $args, $current_object_id ) {
            $enabled = get_post_meta( $item_id, 'menu_item_enable_mega_menu', true );
            $menu = get_post_meta( $item_id, 'menu_item_mega_menu', true );
            $size = get_post_meta( $item_id, 'menu_item_dropdown_size', true );
            $width = get_post_meta( $item_id, 'menu_item_dropdown_custom_width', true );
            $color_scheme = get_post_meta( $item_id, 'menu_item_dropdown_color_scheme', true );

            $size = empty( $size ) ? 'fullwidth' : $size;
            $width = empty( $width ) ? '400' : $width;
            $color_scheme = empty( $color_scheme ) ? 'default' : $color_scheme;

            $mega_menu = cozystay_get_custom_post_type(); ?>
        	<div class="cozystay-mega-menu-settings" style="clear: both;">
                <h4 class=""><?php esc_html_e( 'Custom fields [for theme]', 'cozystay' ); ?></h4>
                <p class="mega-menu-setting-enable cs-enable-mega-menu">
    				<label for="edit-menu-item-enbale-mega-menu-<?php echo esc_attr( $item_id ); ?>">
    					<?php esc_html_e( 'Dropdown Displays a Mega Menu', 'cozystay' ); ?>
                        <input type="checkbox" <?php checked( $enabled, 'on' ); ?> style="float:right;" id="edit-menu-item-enbale-mega-menu-<?php echo esc_attr( $item_id ); ?>" value="on" name="menu_item_enable_mega_menu[<?php echo esc_attr( $item_id ); ?>]">
    				</label>
    			</p>
                <p class="mega-menu-settings cozystay-mega-menu">
    				<label for="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>">
    					<?php esc_html_e( 'Select a Mega Menu', 'cozystay' ); ?><br>
                        <select id="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu_item_mega_menu[<?php echo esc_attr( $item_id ); ?>]">
                            <?php foreach ( $mega_menu as $id => $title ) : ?>
                                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, $menu ); ?>><?php echo esc_html( $title ); ?></option>
                            <?php endforeach; ?>
                        </select>
    				</label>
    			</p>
                <p class="mega-menu-settings cozystay-mega-menu-drop-down-size">
    				<label for="edit-menu-item-dropdown-size-<?php echo esc_attr( $item_id ); ?>">
    					<?php esc_html_e( 'Dropdown Size', 'cozystay' ); ?><br>
                        <select id="edit-menu-item-dropdown-size-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu_item_dropdown_size[<?php echo esc_attr( $item_id ); ?>]">
                            <option value="fullwidth" <?php selected( $size, 'fullwidth' ); ?>><?php esc_html_e( 'Fullwidth', 'cozystay' ); ?></option>
                            <option value="custom-width" <?php selected( $size, 'custom-width' ); ?>><?php esc_html_e( 'Custom Width x Auto Height', 'cozystay' ); ?></option>
                        </select>
    				</label>
    			</p>
                <p class="mega-menu-settings cs-dropdown-custom-width">
    				<label for="edit-menu-item-dropdown-custom-width-<?php echo esc_attr( $item_id ); ?>">
    					<?php esc_html_e( 'Custom Width', 'cozystay' ); ?><br>
                        <input type="number" id="edit-menu-item-dropdown-custom-width-<?php echo esc_attr( $item_id ); ?>" value="<?php echo esc_attr( $width ); ?>" size="4" name="menu_item_dropdown_custom_width[<?php echo esc_attr( $item_id ); ?>]" min="1"> px
    				</label>
    			</p>
                <p class="mega-menu-settings cs-dropdown-color-scheme">
    				<label for="edit-menu-item-dropdown-color-scheme-<?php echo esc_attr( $item_id ); ?>">
    					<?php esc_html_e( 'Dropdown Color Scheme', 'cozystay' ); ?><br>
                        <select id="edit-menu-item-dropdown-color-scheme-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu_item_dropdown_color_scheme[<?php echo esc_attr( $item_id ); ?>]">
                            <option value="default" <?php selected( 'default', $color_scheme ); ?>><?php esc_html_e( 'Default', 'cozystay' ); ?></option>
                            <option value="light-color" <?php selected( 'light-color', $color_scheme ); ?>><?php esc_html_e( 'Light', 'cozystay' ); ?></option>
                            <option value="dark-color" <?php selected( 'dark-color', $color_scheme ); ?>><?php esc_html_e( 'Dark', 'cozystay' ); ?></option>
                        </select>
    				</label>
    			</p>
        	</div><?php
        }
        /**
        * Save mega menu settings
        */
        public function save_mega_menu_settings( $menu_id, $menu_item_db_id ) {
        	if ( isset( $_POST[ 'menu_item_dropdown_color_scheme' ][ $menu_item_db_id ] ) ) {
                $enabled = isset( $_POST[ 'menu_item_enable_mega_menu' ][ $menu_item_db_id ] ) ? 'on' : '';
                $menu = isset( $_POST[ 'menu_item_mega_menu' ][ $menu_item_db_id ] ) ? absint( wp_unslash( $_POST[ 'menu_item_mega_menu' ][ $menu_item_db_id ] ) ) : '';
                $size = isset( $_POST[ 'menu_item_dropdown_size' ][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'menu_item_dropdown_size' ][ $menu_item_db_id ] ) ) : 'fullwidth';
                $width = isset( $_POST[ 'menu_item_dropdown_custom_width' ][ $menu_item_db_id ] ) ? absint( wp_unslash( $_POST[ 'menu_item_dropdown_custom_width' ][ $menu_item_db_id ] ) ) : 400;
                $color_scheme = isset( $_POST[ 'menu_item_dropdown_color_scheme' ][ $menu_item_db_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'menu_item_dropdown_color_scheme' ][ $menu_item_db_id ] ) ) : 'default';

        		update_post_meta( $menu_item_db_id, 'menu_item_enable_mega_menu', $enabled );
                update_post_meta( $menu_item_db_id, 'menu_item_mega_menu', $menu );
                update_post_meta( $menu_item_db_id, 'menu_item_dropdown_size', $size );
                update_post_meta( $menu_item_db_id, 'menu_item_dropdown_custom_width', $width );
                update_post_meta( $menu_item_db_id, 'menu_item_dropdown_color_scheme', $color_scheme );
        	}
        }
		/**
		* Enqueue scripts
		*/
		public function admin_scripts() {
			wp_enqueue_script( 'cozystay-mega-menu', COZYSTAY_ASSETS_URI . 'scripts/admin/mega-menu' . cozystay_get_assets_suffix() . '.js', array( 'jquery', 'nav-menu' ), COZYSTAY_ASSETS_VERSION, true );
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
	if ( cozystay_is_theme_core_activated() ) {
    	CozyStay_Utils_Menu_Custom_fields::_instance();
	}
}
