<header<?php cozystay_the_page_title_class(); ?>>
    <?php cozystay_the_default_page_header_background_image(); ?>
    <div class="container">
        <h1 class="entry-title"><?php echo do_shortcode( $args[ 'title' ] ); ?></h1><?php
        if ( ! empty( $args[ 'description' ] ) ) : ?>
            <div class="description"><?php echo do_shortcode( $args[ 'description' ] ); ?></div><?php
        endif; ?>
    </div>
    <?php do_action( 'cozystay_page_title_section_after' ); ?>
</header>
