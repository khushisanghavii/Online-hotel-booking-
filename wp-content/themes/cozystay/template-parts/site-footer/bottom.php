<?php
if ( apply_filters( 'cozystay_show_site_footer_bottom', true ) ) :
    $footer_copyright_text = cozystay_get_theme_mod( 'cozystay_site_footer_bottom_text' );
    $has_footer_copyright_text = ! empty( $footer_copyright_text ) || cozystay_is_customize_preview();
    $has_footer_menu = cozystay_has_nav_menu( 'footer-menu' );
    if ( $has_footer_menu || $has_footer_copyright_text ) :
        $footer_bottom_class = array( 'site-footer-bottom' );
        'column-single' == cozystay_get_theme_mod( 'cozystay_site_footer_bottom_layout' ) ? array_push( $footer_bottom_class, 'column-single' ) : ''; ?>
        <div class="<?php echo esc_attr( implode( ' ', $footer_bottom_class ) ); ?>">
            <div class="container"><?php
            if ( $has_footer_copyright_text ) : ?>
                <div class="widget widget_text">
                    <div class="textwidget"><?php echo wp_kses_post( $footer_copyright_text ); ?></div>
                </div><?php
            endif;
            if ( $has_footer_menu ) : ?>
                <div class="widget widget_nav_menu">
                    <?php wp_nav_menu( array(
                        'theme_location' 	=> 'footer-menu',
                        'container' 		=> 'div',
                        'container_id' 		=> 'footer-bottom-menu-container',
                        'container_class' 	=> 'menu-footer-bottom-menu-container',
                        'menu_id' 			=> 'footer-bottom-menu',
                        'menu_class' 		=> 'menu',
                        'depth' 			=> 1,
                        'link_before'		=> '<span>',
                        'link_after'		=> '</span>'
                    ) ); ?>
                </div><?php
            endif; ?>
            </div>
        </div><?php
    endif;
endif;
