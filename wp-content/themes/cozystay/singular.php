<?php
/**
* Template for singular page
*/
get_header();
$current_post_type = get_post_type();
get_template_part( 'template-parts/page-header/single', $current_post_type ); ?>

<div class="main">
	<div class="container">
		<div id="primary" class="primary content-area">
            <?php while ( have_posts() ) : ?>
                <?php the_post(); ?>
                <?php get_template_part( 'template-parts/content/single', $current_post_type ); ?>
            <?php endwhile; ?>
        </div>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer();
