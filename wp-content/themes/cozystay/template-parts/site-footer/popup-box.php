<?php
if ( cozystay_module_enabled( 'cozystay_general_popup_box_enable' ) || cozystay_is_customize_preview() ) :
    $cozystay_popup_wrapper_class = array( 'cs-popup', 'cs-site-popup', 'cs-popup-box', cozystay_get_theme_mod( 'cozystay_general_popup_box_color_scheme' ) );
    'fullscreen' == cozystay_get_theme_mod( 'cozystay_general_popup_box_size' ) ? array_push( $cozystay_popup_wrapper_class, 'cs-popup-fullsize' ) : '';
    $cozystay_popup_background_image_id = cozystay_get_theme_mod( 'cozystay_general_popup_box_background_image' ); ?>

    <div class="<?php echo esc_attr( implode( ' ', $cozystay_popup_wrapper_class ) ); ?>">
        <?php if ( cozystay_does_attachment_exist( $cozystay_popup_background_image_id ) ) {
            cozystay_the_preload_bg( array(
                'id' 	=> $cozystay_popup_background_image_id,
    			'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'popup-background' ) ),
    			'class' => 'screen-bg'
            ) );
        } ?>
        <span class="close-button"><?php esc_html_e( 'Close', 'cozystay' ); ?></span>
        <div class="container"><?php
            $cozystay_popup_custom_block = cozystay_get_theme_mod( 'cozystay_general_popup_box_custom_block' );
            if ( CozyStay_Utils_Custom_Block::check_custom_block( $cozystay_popup_custom_block ) ) {
                do_action( 'loftocean_the_custom_blocks_content', $cozystay_popup_custom_block );
            }; ?>
        </div>
    </div>
<?php endif;
