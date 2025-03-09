<header<?php cozystay_the_page_title_class(); ?>>
    <div class="container">
        <?php if ( cozystay_module_enabled( 'cozystay_page_title_show_breadcrumb' ) && ! is_front_page() ) {
            cozystay_the_yoast_seo_breadcrumbs();
        } ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </div>
</header>
