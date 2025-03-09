<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$loop_wrap_class = array( 'products', 'columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ) );
$product_list_style = cozystay_get_theme_mod( 'cozystay_woocommerce_product_list_style' );
if ( ! empty( $product_list_style ) ) {
    array_push( $loop_wrap_class, 'cs-food-menu' );
    array_push( $loop_wrap_class, cozystay_get_theme_mod( 'cozystay_woocommerce_product_list_food_menu_style' ) );
}
?>
<ul class="<?php echo esc_attr( implode( ' ', $loop_wrap_class ) ); ?>">
