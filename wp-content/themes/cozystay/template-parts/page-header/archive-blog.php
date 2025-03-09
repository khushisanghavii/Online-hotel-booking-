<?php
if ( apply_filters( 'cozystay_show_page_title_section', true ) ) : ?>
    <header<?php cozystay_the_page_title_class(); ?>><?php
        $show_default_page_background = true;
        $posts_page = get_option( 'page_for_posts' );
        $blog_page_description = '';
        $blog_page_title = esc_html__( 'Blog', 'cozystay' );
        if ( 'page' == get_option( 'show_on_front' ) && cozystay_does_item_exist( $posts_page ) ) {
            $add_autop = true;
            $blog_page = get_post( $posts_page );
            $blog_page_title = $blog_page->post_title;
            $blog_page_description = $blog_page->post_content;
            if ( function_exists( 'parse_blocks' ) && ! empty( $blog_page_description ) ) {
                $page_blocks = parse_blocks( $blog_page_description );
                if ( ( is_array( $page_blocks ) && ( count( $page_blocks ) > 1 ) ) || ! empty( $page_blocks[0]['blockName'] ) ) {
                    $blog_page_description = '';
                    $add_autop = false;
                    foreach ( $page_blocks as $page_block ) {
                        $blog_page_description .= render_block( $page_block );
                    }
                }
            }
            if ( $add_autop && ! empty( $blog_page_description ) ) {
                $blog_page_description = wpautop( $blog_page_description );
            }
            if ( has_post_thumbnail( $blog_page ) ) {
                $show_default_page_background = false;
                cozystay_the_preload_bg( array(
                    'id' 	=> get_post_thumbnail_id( $blog_page ),
                    'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
                    'class' => 'page-title-bg'
                ) );
            }
        }
        if ( $show_default_page_background ) {
            cozystay_the_default_page_header_background_image();
        } ?>
        <div class="container">
            <h1 class="entry-title"><?php echo esc_html( $blog_page_title ); ?></h1>
            <?php if ( ! empty( $blog_page_description ) ) : ?>
                <div class="description"><?php echo do_shortcode( $blog_page_description ); ?></div>
            <?php endif; ?>
        </div>
        <?php do_action( 'cozystay_page_title_section_after' ); ?>
    </header><?php
endif;
