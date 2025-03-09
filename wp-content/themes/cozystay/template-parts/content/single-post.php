<article <?php post_class(); ?>>
    <?php do_action( 'cozystay_before_main_content' ); ?>
    <div class="entry-content"><?php 
        the_content();
        cozystay_the_single_pages(); ?>
    </div><!-- .post-entry -->
    <?php get_template_part( 'template-parts/content/single-post-footer' ); ?>
    <?php get_template_part( 'template-parts/content/single-post-author-info' ); ?>
    <?php get_template_part( 'template-parts/content/single-post-navigation' ); ?>
    <?php do_action( 'cozystay_after_main_content' ); ?>
</article><?php
get_template_part( 'template-parts/content/single-post-related' );
// Comment section
if ( comments_open() || get_comments_number() ) {
    comments_template();
}
