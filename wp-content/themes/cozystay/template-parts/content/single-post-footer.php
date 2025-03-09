<?php
$cozystay_single_post_footer_show_tags = cozystay_module_enabled( 'cozystay_blog_single_post_page_footer_show_tags' );
$cozystay_single_post_footer_show_media_sharing = cozystay_is_theme_core_activated() && cozystay_module_enabled( 'cozystay_blog_single_post_page_footer_show_social_sharings' );
if ( $cozystay_single_post_footer_show_tags || $cozystay_single_post_footer_show_media_sharing ) : ?>
    <footer class="article-footer">
        <?php $cozystay_single_post_footer_show_tags ? cozystay_the_meta_tags() : ''; ?>
        <?php $cozystay_single_post_footer_show_media_sharing ? cozystay_the_social_bar() : ''; ?>
    </footer><?php
endif;
