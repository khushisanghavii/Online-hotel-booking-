<?php do_action( 'cozystay_the_content_close' ); ?>
            </div> <!-- end of #content -->
            <?php get_template_part( 'template-parts/site-footer/footer' ); ?>
            <?php get_template_part( 'template-parts/site-footer/back-to-top' ); ?>
            <?php do_action( 'cozystay_the_page_close' ); ?>
        </div> <!-- end of #page -->

        <?php do_action( 'cozystay_the_mobile_menu' ); ?>
        <?php get_template_part( 'template-parts/site-footer/cookie-law' ); ?>
        <?php get_template_part( 'template-parts/site-footer/popup-box' ); ?>
        <?php get_template_part( 'template-parts/site-footer/fullscreen-search' ); ?>
        <?php wp_footer(); ?>
        <?php do_action( 'cozystay_after_site_footer' ); ?>
    </body>
</html>
