<?php
$cozystay_show_all = isset( $args, $args[ 'site_header' ] ) && ( 'custom' == $args[ 'site_header' ] );
$cozystay_site_header_class = array( 'site-header', 'site-header-layout-default', 'dropdown-dark' );
( ! $cozystay_show_all ) && cozystay_module_enabled( 'cozystay_default_site_header_enable_overlap' ) ? array_push( $cozystay_site_header_class, 'overlap-header' ) : ''; ?>

<header id="masthead" class="<?php echo esc_attr( implode( ' ', $cozystay_site_header_class ) ); ?>"<?php cozystay_the_site_header_attrs(); ?>><?php
	$cozystay_show_search_icon = $cozystay_show_all || ( ! cozystay_module_enabled( 'cozystay_default_site_header_hide_search_icon' ) );
	$cozystay_show_cart = $cozystay_show_all || ( ! cozystay_module_enabled( 'cozystay_default_site_header_hide_cart' ) );
	$cozystay_site_header_image = cozystay_get_site_header_image(); ?>

	<div class="site-header-main<?php if ( ! empty( $cozystay_site_header_image ) ) : ?> with-bg<?php endif; ?>">
        <div class="container">
            <!-- .site-branding -->
            <div class="header-section branding header-left">
                <div<?php cozystay_the_site_branding_class(); ?>>
					<?php cozystay_the_custom_logo(); ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </p><?php
					$description = get_bloginfo( 'description', 'display' );
        			if ( ! empty( $description ) || is_customize_preview() ) : ?>
        				<p class="site-description"><?php echo wp_kses_post( $description ); ?></p><?php
    				endif; ?>
                </div>
            </div> <!-- end of .site-branding -->
			<?php cozystay_primary_nav( array(), '<div class="header-section menu">', '</div>', true ); ?>
            <div class="header-section header-right"><?php
				if ( $cozystay_show_search_icon ) : ?>
	                <div class="site-header-search">
	                    <span class="toggle-button"><span class="screen-reader-text"><?php esc_html_e( 'Search', 'cozystay' ); ?></span></span>
	                </div><?php
				endif;
				if ( $cozystay_show_cart && cozystay_is_woocommerce_activated() ) :
					$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url(); ?>
	                <div id="site-header-cart" class="site-header-cart">
	                    <a id="cs-cart-notification" class="cart-contents" href="<?php echo esc_url( $cart_url ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'cozystay' ); ?>">
							<span class="cart-icon"></span>
						</a>

	                    <div class="widget woocommerce widget_shopping_cart">
	                        <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
	                    </div>
	                </div><?php
				endif; ?>
				<button id="menu-toggle" class="menu-toggle">
					<span class="menu-toggle-icon"></span>
					<span class="menu-toggle-text"><?php esc_html_e( 'Menu', 'cozystay' ); ?></span>
				</button>
            </div>
        </div>
    </div>
</header>
