<?php
/**
* Main sidebar template
*/

$sidebar_id = apply_filters( 'cozystay_sidebar_id', 'main-sidebar' );

if ( ! empty( $sidebar_id ) && cozystay_show_sidebar() && is_active_sidebar( $sidebar_id ) ) : ?>
	<aside<?php cozystay_the_site_sidebar_attributes( array( 'id' => 'secondary' ) ); ?>>
		<div class="sidebar-container">
			<?php dynamic_sidebar( $sidebar_id ); ?>
		</div> <!-- end of .sidebar-container -->
	</aside><!-- end of .sidebar --> <?php
endif;
