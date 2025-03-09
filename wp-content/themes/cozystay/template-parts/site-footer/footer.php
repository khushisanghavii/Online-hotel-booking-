<?php
do_action( 'cozystay_the_before_site_footer' );
if ( apply_filters( 'cozystay_show_site_footer', true ) ) : ?>
    <footer id="colophon" class="site-footer">
        <?php do_action( 'cozystay_the_main_site_footer' ); ?>
        <?php get_template_part( 'template-parts/site-footer/instagram' ); ?>
        <?php get_template_part( 'template-parts/site-footer/bottom' ); ?>
    </footer><?php
endif;
