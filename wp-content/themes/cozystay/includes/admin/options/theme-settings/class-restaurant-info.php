<?php
if ( ! class_exists( 'CozyStay_Theme_Settings_Tab_Restaurant_Info' ) ) {
	class CozyStay_Theme_Settings_Tab_Restaurant_Info extends CozyStay_Theme_Option_Section {
		/**
		* Setup environment
		*/
		protected function setup_env() {
            $this->id = 'tab-info';
			$this->title = esc_html__( 'Restaurant Info', 'cozystay' );
            $this->defaults = array(
				'cozystay_restaurant_address' => '',
				'cozystay_restaurant_map_url' => '',
				'cozystay_restaurant_phone' => '',
				'cozystay_restaurant_email' => ''
			);
		}
		/**
		* Render tab content
		*/
		public function render_tab_content() {
            $open_hours = get_option( 'cozystay_open_hours', false );
            if ( ! cozystay_is_valid_array( $open_hours ) ) {
                $open_hours = array(
                    array( 'title' => '', 'hours' => '' )
                );
            }

            $basic_info =  array(
                'cozystay_restaurant_address' => array(
                    'type' => 'text',
                    'title' => esc_html__( 'Address', 'cozystay' ),
                    'value' => $this->get_value( 'cozystay_restaurant_address'),
                    'placeholder' => esc_attr__( 'e.g. 121 King St, Melbourne VIC 3000, Australia', 'cozystay' )
                ),
                'cozystay_restaurant_map_url' => array(
                    'type' => 'text',
                    'title' => esc_html__( 'Map URL', 'cozystay' ),
                    'value' => $this->get_value( 'cozystay_restaurant_map_url' ),
                    'placeholder' => esc_attr__( 'e.g. the URL from Google Map search results', 'cozystay' )
                ),
                'cozystay_restaurant_phone' => array(
                    'type' => 'tel',
                    'title' => esc_html__( 'Telephone', 'cozystay' ),
                    'value' => $this->get_value( 'cozystay_restaurant_phone' ),
                    'placeholder' => esc_attr__( 'e.g. +61383766284', 'cozystay' )
                ),
                'cozystay_restaurant_email' => array(
                    'type' => 'email',
                    'title' => esc_html__( 'Email', 'cozystay' ),
                    'value' => $this->get_value( 'cozystay_restaurant_email' ),
                    'placeholder' => esc_attr__( 'e.g. booking@cs-wp.com', 'cozystay' )
                )
            ); ?>

            <h2><?php esc_html_e( 'Restaurant Info', 'cozystay' ); ?></h2>
            <p><?php esc_html_e( 'Enter some basic information of your restaurant.', 'cozystay' ); ?></p>
            <table class="form-table">
                <tbody><?php
                foreach ( $basic_info as $id => $info ) : ?>
                    <tr>
                        <th><?php echo esc_html( $info[ 'title' ] ); ?></th>
                        <td>
                            <input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="<?php echo esc_attr( $info[ 'type' ] ); ?>" value="<?php echo esc_attr( $info[ 'value' ] ); ?>" placeholder="<?php echo esc_attr( $info[ 'placeholder' ] ); ?>">
                        </td>
                    </tr><?php
                endforeach; ?>
                </tbody>
            </table>
            <hr>
            <h2><?php esc_html_e( 'Opening Hours', 'cozystay' ); ?></h2>
            <p><?php esc_html_e( 'Enter the opening hours of your restaurant here. And then these information can be easily added to page content, sidebar, and footer.', 'cozystay' ); ?></p>

            <table class="form-table opening-hours-form">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Title', 'cozystay' ); ?></th>
                        <th><?php esc_html_e( 'Hours', 'cozystay' ); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody><?php
                foreach ( $open_hours as $index => $info ) : ?>
                    <tr>
                        <td>
                            <input name="cozystay_open_hours[<?php echo esc_attr( $index ); ?>][title]" id="cozystay_open_title_<?php echo esc_attr( $index ); ?>" type="text" value="<?php echo esc_attr( $info[ 'title' ] ); ?>" placeholder="<?php esc_attr_e( 'e.g. Monday - Friday', 'cozystay' ); ?>">
                        </td>
                        <td>
                            <input name="cozystay_open_hours[<?php echo esc_attr( $index ); ?>][hours]" id="cozystay_open_hour_<?php echo esc_attr( $index ); ?>" type="text" value="<?php echo esc_attr( $info[ 'hours' ] ); ?>" placeholder="<?php esc_attr_e( 'e.g. 09:00 - 22:00', 'cozystay' ); ?>">
                        </td>
                        <td class="actions">
                            <a href="#" class="add"><?php esc_html_e( 'Add a line', 'cozystay' ); ?></a>
                            <a href="#" class="remove"><?php esc_html_e( 'Delete', 'cozystay' ); ?></a>
                        </td>
                    </tr><?php
                endforeach; ?>
                </tbody>
            </table><?php
        }
		/**
		* Register options
		*/
		protected function register_options() {
			$this->options = array(
				'cozystay_restaurant_address' => 'sanitize_text_field',
				'cozystay_restaurant_map_url' => 'sanitize_text_field',
				'cozystay_restaurant_phone' => 'sanitize_text_field',
				'cozystay_restaurant_email' => 'sanitize_text_field'
			);
		}/**
		* Save changes
		*/
		public function save_changes() {
            parent::save_changes();
            if ( ! empty( $_REQUEST[ 'cozystay_open_hours' ] ) ) {
                $raw_data = wp_unslash( $_REQUEST[ 'cozystay_open_hours' ] );
                $open_hours = array();
                foreach ( $raw_data as $info ) {
                    $open_hours[] = array( 'title' => sanitize_text_field( $info[ 'title' ] ), 'hours' => sanitize_text_field( $info[ 'hours'] ) );
                }
                update_option( 'cozystay_open_hours', $open_hours );
            }
        }
    }
    new CozyStay_Theme_Settings_Tab_Restaurant_Info();
}
