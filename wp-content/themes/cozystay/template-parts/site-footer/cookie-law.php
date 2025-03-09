<?php if ( cozystay_module_enabled( 'cozystay_general_cookie_law_enabled' ) ) : ?>
    <div class="cs-cookies-popup cs-popup">
    	<div class="container">
    		<div class="cookies-msg"><?php echo do_shortcode( cozystay_get_theme_mod( 'cozystay_general_cookie_law_message' ) ); ?></div>
            <div class="cookies-buttons">
    			<a href="#" rel="nofollow noopener" class="button cs-btn-rounded cs-btn-small"><?php echo esc_html( cozystay_get_theme_mod( 'cozystay_general_cookie_law_accept_button_text' ) ); ?></a>
    		</div>
    	</div>
    </div><?php
endif;
