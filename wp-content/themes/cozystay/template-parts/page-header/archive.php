<header<?php cozystay_the_page_title_class(); ?>>
    <?php cozystay_the_default_page_header_background_image(); ?>
    <div class="container">
        <?php if ( cozystay_module_enabled( 'cozystay_page_title_show_breadcrumb' ) ) {
            cozystay_the_yoast_seo_breadcrumbs();
        }
        the_archive_title( '<h1 class="entry-title">', '</h1>' );
        the_archive_description( '<div class="description">', '</div>' ); ?>
    </div>
    <?php do_action( 'cozystay_page_title_section_after' ); ?>
</header>
