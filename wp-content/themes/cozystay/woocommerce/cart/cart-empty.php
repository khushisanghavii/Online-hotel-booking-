<?php
defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_cart_is_empty' );
$custom_url = cozystay_get_theme_mod( 'cozystay_woocommerce_return_to_shop_url', '' );
$back_url = wc_get_page_id( 'shop' ) > 0 ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '';
if ( ! empty( $custom_url ) && 'page' == get_post_type( $custom_url ) ) {
    $back_url = get_permalink( $custom_url );
}

if ( $back_url ) : ?>
	<p class="return-to-shop">
		<a class="button wc-backward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" href="<?php echo esc_url( $back_url ); ?>">
			<?php
				echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'cozystay' ) ) );
			?>
		</a>
	</p>
<?php endif; ?>
