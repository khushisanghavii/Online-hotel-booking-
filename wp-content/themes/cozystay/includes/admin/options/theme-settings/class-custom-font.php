<?php
if ( ! class_exists( 'CozyStay_Theme_Settings_Tab_Custom_Font' ) ) {
	class CozyStay_Theme_Settings_Tab_Custom_Font extends CozyStay_Theme_Option_Section {
		/**
		* Setup environment
		*/
		protected function setup_env() {
            $this->id = 'tab-custom-font';
			$this->title = esc_html__( 'Custom Font', 'cozystay' );

            add_filter( 'cozystay_theme_settings_json', array( $this, 'custom_fonts' ) );
		}
		/**
		* Render tab content
		*/
		public function render_tab_content() {
            $custom_fonts = get_option( 'cozystay_custom_fonts', array() );
            $custom_fonts = array_merge( array( 'adobe_typekit_id' => '', 'adobe_fonts' => '', 'custom_fonts' => array( array( 'name' => '', 'weight' => '400', 'woff' => '', 'woff2' => '' ) ) ), $custom_fonts );  ?>

            <h2><?php esc_html_e( 'Adobe Fonts', 'cozystay' ); ?></h2>
            <p><?php printf(
                // translators: 1/2 html tag
                esc_html__( 'Please check %1$sthis tutorial%2$s for details about how to add Adobe Fonts on your website.', 'cozystay' ),
                '<a href="https://loftocean.com/doc/cozystay/ptkb/how-to-add-adobe-fonts-to-your-website/">',
                '</a>'
            ); ?></p>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><?php esc_html_e( 'Adobe Project (TypeKit) ID:', 'cozystay' ); ?></th>
                        <td>
                            <input name="cozystay_adobe_typekit_id" id="adobe_typekit_id" type="text" class="cs-adobe-typekit-id" value="<?php echo esc_attr( $custom_fonts[ 'adobe_typekit_id' ] ); ?>">
                            <button class="cs-sync-adobe-fonts button-primary"><?php esc_html_e( 'Sync', 'cozystay' ); ?></button>
                            <button class="cs-clear-adobe-fonts"><?php esc_html_e( 'Clear', 'cozystay' ); ?></button>
                            <span class="message"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h2><?php esc_html_e( 'Custom Fonts', 'cozystay' ); ?></h2>
			<p><?php printf(
                // translators: 1/2 html tag
                esc_html__( 'Please check %1$sthis tutorial%2$s for details about how to add Custom Fonts on your website.', 'cozystay' ),
                '<a href="https://loftocean.com/doc/cozystay/ptkb/how-to-upload-custom-fonts-to-your-website/">',
                '</a>'
            ); ?></p>

            <div class="cs-custom-fonts-wrapper">
                <a href="#" class="cs-custom-font-add" data-current-index="0"><?php esc_html_e( 'Add Font', 'cozystay' ); ?></a>
            </div>
            <script id="tmpl-cs-custom-font" type="text/html">
                <# data.list.forEach( function( item ) {
                    var namePrefix = 'cozystay_custom_fonts[' + ( data.index ++ ) + ']'; #>
                    <div class="cs-custom-font-item">
                        <h3><?php esc_html_e( 'Custom Font', 'cozystay' ); ?><span class="item-font-name"><# if ( item[ 'name' ] ) { #> - {{{ item.name }}}<# } #></span></h3>
                        <a href="#" class="cs-custom-font-item-remove"><?php esc_html_e( 'Remove', 'cozystay' ); ?></a>
                        <div class="cs-custom-font-controls-wrapper">
                            <div class="controls-row">
                                <div class="control-wrapper">
                                    <label><?php esc_html_e( 'Font Name:', 'cozystay' ); ?></label>
                                    <input name="{{{ namePrefix }}}[name]" class="cs-custom-font-name" type="text" value="{{{ item.name }}}">
                                </div>
                                <div class="control-wrapper">
                                    <label><?php esc_html_e( 'Font Weight:', 'cozystay' ); ?></label>
                                    <select name="{{{ namePrefix }}}[weight]">
                                        <option value=""<# if ( '' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Variable Fonts', 'cozystay' ); ?></option>
                                        <option value="100"<# if ( '100' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Ultra-Light 100', 'cozystay' ); ?></option>
                                        <option value="200"<# if ( '200' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Light 200', 'cozystay' ); ?></option>
                                        <option value="300"<# if ( '300' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Book 300', 'cozystay' ); ?></option>
                                        <option value="400"<# if ( '400' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Normal 400', 'cozystay' ); ?></option>
                                        <option value="500"<# if ( '500' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Medium 500', 'cozystay' ); ?></option>
                                        <option value="600"<# if ( '600' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Semi-Bold 600', 'cozystay' ); ?></option>
                                        <option value="700"<# if ( '700' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Bold 700', 'cozystay' ); ?></option>
                                        <option value="800"<# if ( '800' == item.weight ) { #> selected="selected"<# } #>><?php esc_html_e( 'Extra-Bold 800', 'cozystay' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="controls-row">
                                <div class="control-wrapper">
                                    <label><?php esc_html_e( 'Font (.woff):', 'cozystay' ); ?></label>
                                    <input name="{{{ namePrefix }}}[woff]" type="text" value="{{{ item.woff }}}"><br>
                                    <button class="cs-media-uploader"><?php esc_html_e( 'Upload', 'cozystay' ); ?></button>
                                    <button class="cs-media-remove"><?php esc_html_e( 'Remove', 'cozystay' ); ?></button>
                                </div>
                                <div class="control-wrapper">
                                    <label><?php esc_html_e( 'Font (.woff2):', 'cozystay' ); ?></label>
                                    <input name="{{{ namePrefix }}}[woff2]" type="text" value="{{{ item.woff2 }}}"><br>
                                    <button class="cs-media-uploader"><?php esc_html_e( 'Upload', 'cozystay' ); ?></button>
                                    <button class="cs-media-remove"><?php esc_html_e( 'Remove', 'cozystay' ); ?></button>
                                </div>
                            </div>
                        </div>
                    </div><#
                } ); #>
            </script><?php
        }
		/**
		* Save changes
		*/
		public function save_changes() {
            parent::save_changes();
            if ( isset( $_REQUEST[ 'cozystay_custom_fonts' ] ) ) {
				$custom_fonts = wp_unslash( $_REQUEST[ 'cozystay_custom_fonts' ] );
                if ( cozystay_is_valid_array( $custom_fonts ) ) {
                    $custom_fonts = array_filter( $custom_fonts, function( $item ) {
                        return ! empty( $item[ 'name' ] );
                    } );
                }
				$old_value = get_option( 'cozystay_custom_fonts', array() );
                $option_value = array_merge( array( 'adobe_typekit_id' => '', 'adobe_fonts' => '', 'custom_fonts' => false ), $old_value, array( 'custom_fonts' => cozystay_is_valid_array( $custom_fonts ) ? $custom_fonts : false ) );
                update_option( 'cozystay_custom_fonts', $option_value );
            }
        }
        /**
        * Theme settings JSON
        */
        public function custom_fonts( $json = array() ) {
            $settings = get_option( 'cozystay_custom_fonts', array() );
            $json[ 'customFonts' ] = isset( $settings[ 'custom_fonts' ] ) && ( ! empty( $settings[ 'custom_fonts' ] ) ) ? $settings[ 'custom_fonts' ] : false;
            $json[ 'adobeFonts' ] = array( 'i18nText' => array(
                'sending' => esc_html__( 'Syncing', 'cozystay' ),
                'done' => esc_html__( 'Sync', 'cozystay' ),
                'error' => esc_html__( 'Please try again later.', 'cozystay' ),
                'empty' => esc_html__( 'Adobe Typekit ID can\' be empty.', 'cozystay' )
            ) );
            return $json;
        }
    }
    new CozyStay_Theme_Settings_Tab_Custom_Font();
}
