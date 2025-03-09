<?php
$cozystay_mobile_menu_wrap_class = isset( $args, $args[ 'class' ] ) ? 'sidemenu-default ' . $args[ 'class' ] : 'sidemenu-default'; ?>

<div <?php cozystay_the_mobile_menu_class( $cozystay_mobile_menu_wrap_class ); ?>><?php
    $mobile_site_header_background_image = cozystay_get_theme_mod( 'cozystay_mobile_site_header_background_image' );
    $background_image_args = array(
        'id' 	=> $mobile_site_header_background_image,
        'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'mobile-menu-background' ) )
    ); ?>
    <div class="container"<?php cozystay_does_attachment_exist( $mobile_site_header_background_image ) ? cozystay_the_background_image_attrs( $background_image_args ) : ''; ?>>
        <div class="sidemenu-header">
            <span class="close-button"><?php esc_html_e( 'Close', 'cozystay' ); ?></span>
        </div><?php
        cozystay_primary_nav( array(
            'container_id' => 'mobile-menu-site-navigation',
            'container_class' => 'main-navigation cs-menu-mobile',
            'menu_id' => 'mobile-menu-main-menu',
            'menu_class' => 'mobile-menu',
            'walker' => new CozyStay_Walker_Fullscreen_Nav_Menu()
        ) );
        cozystay_social_menu( array( 'menu_id' => 'sidemenu-social-menu', 'container_id' => 'sidemenu-social-navigation' ) );
        $mobile_menu_copyright_text = cozystay_get_theme_mod( 'cozystay_mobile_site_header_copyright_text' );
        $mobile_is_customize_preview = cozystay_is_customize_preview();
        if ( ! empty( $mobile_menu_copyright_text ) || $mobile_is_customize_preview ) :
            $copyright_class = array( 'copyright' );
            empty( $mobile_menu_copyright_text ) && $mobile_is_customize_preview ? array_push( $copyright_class, 'hide' ) : ''; ?>
            <div class="<?php echo esc_attr( implode( ' ', $copyright_class ) ); ?>"><?php echo wp_kses_post( $mobile_menu_copyright_text ); ?></div><?php
        endif; ?>
    </div>
</div>
