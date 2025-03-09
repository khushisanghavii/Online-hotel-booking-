<?php
/**
* Theme template for search results
*/
get_header();
get_template_part( 'template-parts/page-header/archive-search' ); ?>
    <div class="main">
    	<div class="container">
    		<div id="primary" class="primary content-area"><?php
                if ( have_posts() ) {
                    do_action( 'cozystay_the_list_content', 'search' );
                } else {
                    get_template_part( 'template-parts/content/content-none', 'search' );
                } ?>
        	</div><?php
    		get_sidebar(); ?>
    	</div>
    </div><!-- end of .main --> <?php
get_footer();
