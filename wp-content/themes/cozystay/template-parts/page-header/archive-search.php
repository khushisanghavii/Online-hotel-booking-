<header<?php cozystay_the_page_title_class(); ?>>
    <?php cozystay_the_default_page_header_background_image(); ?>
    <div class="container">
        <?php if ( cozystay_module_enabled( 'cozystay_page_title_show_breadcrumb' ) ) {
            cozystay_the_yoast_seo_breadcrumbs();
        } ?>
        <h1 class="entry-title"><?php printf(
            // translators: %s search keyword
            esc_html( __( 'Search: %s', 'cozystay'  ) ),
            esc_html( get_search_query() )
        ); ?>
        </h1>
    </div>
    <?php do_action( 'cozystay_page_title_section_after' ); ?>
</header>
