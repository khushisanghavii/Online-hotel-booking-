<?php
	if ( cozystay_test_ajax_nav() ) : ?>
		<nav class="navigation pagination">
			<div class="pagination-container load-more">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Posts Navigation', 'cozystay' ); ?></h2>
				<a href="#" data-no-post-text="<?php esc_attr_e( 'No More Posts', 'cozystay' ); ?>" class="load-more-btn ajax manual">
					<span class="btn-text"><?php esc_html_e( 'Load More Posts', 'cozystay' ); ?></span>
					<span class="loading-text"><?php esc_html_e( 'Loading...', 'cozystay' ); ?></span>
				</a>
			</div>
		</nav><?php
	endif;