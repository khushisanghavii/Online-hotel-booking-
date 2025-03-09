<?php
defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
    <div class="cs-food-menu-item">
        <div class="product-image cs-food-menu-img"><?php
            woocommerce_template_loop_product_link_open();
            woocommerce_template_loop_product_thumbnail();
            woocommerce_template_loop_product_link_close(); ?>
        </div>
        <div class="cs-food-menu-main"><?php
            ob_start();
            woocommerce_template_loop_product_link_open();
            $product_link = ob_get_clean();
            echo str_replace( ' woocommerce-loop-product__link', ' woocommerce-loop-product__link cs-food-menu-header', $product_link ); ?>
            <h2 class="<?php echo esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title cs-food-menu-title' ) ); ?>"><?php
                the_title();
                if ( ! $product->managing_stock() && ! $product->is_in_stock() ) : ?>
    				<span class="menu-label out-of-stock"><?php esc_html_e( 'Sold Out', 'cozystay' ); ?></span><?php
    			endif;
                ob_start();
                woocommerce_show_product_loop_sale_flash();
                $sale_label = ob_get_clean();
                if ( ! empty( $sale_label ) ) {
                    echo str_replace( 'class="onsale"', 'class="menu-label"', $sale_label );
                } ?>
            </h2>
            <div class="cs-food-menu-lines"></div><?php
            ob_start();
            woocommerce_template_loop_price();
            $product_price = ob_get_clean();
            echo str_replace( 'class="price"', 'class="price cs-food-menu-price"', $product_price );
            woocommerce_template_loop_product_link_close();
            woocommerce_template_loop_rating(); ?>
            <div class="cs-food-menu-footer"><?php
                if ( cozystay_module_enabled( 'cozystay_woocommerce_product_list_show_short_description' ) ) {
					do_action( 'cozystay_woocommerce_the_short_description', 'cs-food-menu-details' );
				}
                woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    </div>
</li>
