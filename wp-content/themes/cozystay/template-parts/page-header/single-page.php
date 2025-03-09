<?php 
if ( apply_filters( 'cozystay_show_page_title_section', true ) ) : ?>
    <header<?php cozystay_the_page_title_class(); ?>>
        <?php cozystay_the_default_page_header_background_image(); ?>
        <div class="container"><?php
            $cozystay_woocommerce_static_pages = apply_filters( 'cozystay_woocommerce_static_pages', array() );
            if ( cozystay_is_valid_array( $cozystay_woocommerce_static_pages ) && in_array( get_the_ID(), $cozystay_woocommerce_static_pages ) ) {
                do_action( 'cozystay_woocommerce_breadcrumbs' );
            } elseif ( cozystay_module_enabled( 'cozystay_page_title_show_breadcrumb' ) && ! is_front_page() ) {
                cozystay_the_yoast_seo_breadcrumbs();
            } ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </div>
        <?php do_action( 'cozystay_page_title_section_after' ); ?>
    </header><?php
endif;
