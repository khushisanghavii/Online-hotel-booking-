<article <?php post_class(); ?>>
    <div class="entry-content"><?php
        the_content();
        cozystay_the_single_pages(); ?>
    </div><!-- .post-entry -->
</article><?php
// Comment section
if ( comments_open() || get_comments_number() ) {
    comments_template();
}
