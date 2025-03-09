<?php
if ( apply_filters( 'cozystay_show_site_footer_instagram', true ) && cozystay_module_enabled( 'cozystay_site_footer_enable_instagram' ) ) :
    $feed = cozystay_get_theme_mod( 'cozystay_site_footer_instagram_feed' );
    if ( cozystay_is_theme_core_activated() && apply_filters( 'loftocean_instagram_has_feed', false, $feed ) ) :
        $instagram_title = cozystay_get_theme_mod( 'cozystay_site_footer_instagram_title' );
        $cols = intval( cozystay_get_theme_mod( 'cozystay_site_footer_instagram_columns' ) );
        $cols = ( $cols > 0 ) ? min( max( $cols, 4 ), 8 ) : 6;
        $new_tab = cozystay_module_enabled( 'cozystay_site_footer_instagram_new_tab' );
        $by_ajax = ( 'ajax' === apply_filters( 'loftocean_instagram_render_method', '' ) );
        $attrs = array( 'class' => 'widget cs-widget_instagram column-' . esc_attr( $cols ) . ' fullwidth' );
        $url = cozystay_get_theme_mod( 'cozystay_site_footer_instagram_title_link' );
        if ( $by_ajax ) {
            $attrs[ 'data-user' ] = esc_attr( $url );
            $attrs[ 'data-feed-id' ] = $feed;
    		$attrs[ 'data-limit' ] = $cols;
    		$attrs[ 'data-new-tab' ] = $new_tab;
            $attrs[ 'data-location' ] = 'footer';
        } ?>
        <div class="site-footer-instagram">
            <div<?php cozystay_the_tag_attributes( $attrs ); ?>>
                <?php if ( ! empty( $instagram_title ) ) : ?>
                    <h5 class="widget-title overlay-title"><?php
                    if ( empty( $url ) ) :
                        echo wp_kses_post( $instagram_title );
                    else : ?>
                        <a href="<?php echo esc_url( $url ); ?>"<?php if ( $new_tab ) : ?> target="_blank" rel="noopenner noreferrer"<?php endif; ?>><?php echo wp_kses_post( $instagram_title ); ?></a><?php
                    endif; ?>
                    </h5>
                <?php endif; ?>
                <?php if ( ! $by_ajax ) {
                    do_action( 'loftocean_instagram_the_html', $feed, $cols, $new_tab, array( 'location' => 'footer', 'column' => $cols ) );
                } ?>
            </div>
        </div><?php
    endif;
endif;
