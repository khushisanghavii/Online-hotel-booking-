<?php
get_header();

while ( have_posts() ) :
    the_post();
    get_template_part( 'template-parts/page-header/single-page' ); ?>

    <div class="main">
    	<div class="container">
    		<div id="primary" class="primary content-area">
                <article <?php post_class(); ?>>
                    <div class="entry-content"><?php
                        the_content();
                        cozystay_the_single_pages(); ?>
                    </div><!-- .post-entry -->
                </article>
            </div>
    		<?php get_sidebar(); ?>
    	</div>
    </div><?php
endwhile;
wp_reset_query();

get_footer();
