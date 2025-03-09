<?php
if ( apply_filters( 'cozystay_show_post_title_section', true ) ) : ?>
    <header<?php cozystay_the_page_title_class( array( 'post-header-section' ), true ); ?>>
        <?php if ( has_post_thumbnail() ) {
            cozystay_the_preload_bg( array(
                'id' 	=> get_post_thumbnail_id(),
                'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
                'class' => 'page-title-bg'
            ) );
        } ?>
        <div class="container">
            <?php if ( cozystay_module_enabled( 'cozystay_blog_single_post_show_yoast_seo_breadcrumb' ) ) {
                cozystay_the_yoast_seo_breadcrumbs();
            } ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php
                $cozystay_single_post_header_metas = cozystay_get_single_post_header_meta();
                $cozystay_single_post_header_normal_metas = array_intersect( $cozystay_single_post_header_metas, array( 'author', 'category', 'comment_counter', 'publish_date' ) );
                if ( cozystay_is_valid_array( $cozystay_single_post_header_normal_metas ) || in_array( 'category', $cozystay_single_post_header_metas ) ) : ?>
                    <div class="meta-wrap"><?php
                    in_array( 'category', $cozystay_single_post_header_metas ) ? cozystay_the_meta_category() : '';
                    if ( cozystay_is_valid_array( $cozystay_single_post_header_normal_metas ) ) : ?>
                        <div class="meta"><?php
                            in_array( 'author', $cozystay_single_post_header_normal_metas ) ? cozystay_the_meta_author( false, true, esc_html__( 'By', 'cozystay' ) ) : '';
                            in_array( 'publish_date', $cozystay_single_post_header_normal_metas ) ? cozystay_the_meta_date( true ) : '';
                            in_array( 'comment_counter', $cozystay_single_post_header_normal_metas ) ? cozystay_the_meta_comment( true ) : ''; ?>
                        </div><?php
                    endif; ?>
                    </div><?php
                endif;
            ?>
        </div>
        <?php do_action( 'cozystay_page_title_section_after', 'post' ); ?>
    </header><?php
endif;
