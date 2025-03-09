<?php if ( $cozystay_before_site_footer_text = cozystay_get_theme_mod( 'cozystay_above_site_footer_text_content' ) ) : ?>
   <div class="before-footer">
       <div class="container"><?php echo do_shortcode( $cozystay_before_site_footer_text ); ?></div>
   </div><?php
endif;
