<?php
// Main template
get_header();
$cozystay_current_archive_page = apply_filters( 'cozystay_get_current_achive_page', '' );
$cozystay_is_archvie_page = ! empty( $cozystay_current_archive_page );
$cozystay_is_archvie_page ? get_template_part( 'template-parts/page-header/archive', $cozystay_current_archive_page ) : get_template_part( 'template-parts/page-header/single-page' ); ?>

<div class="main">
	<div class="container">
		<div id="primary" class="primary content-area"><?php
        if ( $cozystay_is_archvie_page ) {
			do_action( 'cozystay_the_list_content', $cozystay_current_archive_page );
        } else {
            while( have_posts() ) {
                the_post();
                get_template_part( 'template-parts/content/single' );
            }
        } ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div><!-- end of .main --><?php

get_footer();
