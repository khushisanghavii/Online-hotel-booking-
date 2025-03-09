<header<?php cozystay_the_page_title_class(); ?>><?php
    $userID = get_queried_object_id();
    if ( apply_filters( 'loftocean_has_user_featured_image', false, $userID ) ) {
        do_action(
    		'loftocean_the_user_featured_image',
    		$userID,
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
        <h1 class="entry-title"><?php printf(
            // translators: %s author display name
            esc_html( __( 'Author: %s', 'cozystay'  ) ),
            esc_html( get_the_author() )
        ); ?>
        </h1>
        <?php the_archive_description( '<div class="description">', '</div>' ); ?>
        <?php do_action( 'loftocean_front_the_user_social' ); ?>
    </div>
    <?php do_action( 'cozystay_page_title_section_after' ); ?>
</header>
