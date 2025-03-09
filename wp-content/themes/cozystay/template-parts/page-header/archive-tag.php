<header<?php cozystay_the_page_title_class(); ?>><?php
    $term_queried = get_queried_object();
    if ( apply_filters( 'loftocean_front_has_taxonomy_featured_image', false, $term_queried ) ) {
        do_action(
            'loftocean_front_the_taxonomy_featured_image',
            $term_queried,
    		CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
    		array( 'class' => 'page-title-bg' )
    	);
    } else {
        cozystay_the_default_page_header_background_image();
    } ?>
    <div class="container">
        <?php if ( cozystay_module_enabled( 'cozystay_page_title_show_breadcrumb' ) ) {
            cozystay_the_yoast_seo_breadcrumbs();
        } ?>
        <h1 class="entry-title"><?php single_term_title(); ?></h1>
        <?php the_archive_description( '<div class="description">', '</div>' ); ?>
    </div>
    <?php do_action( 'cozystay_page_title_section_after' ); ?>
</header>
