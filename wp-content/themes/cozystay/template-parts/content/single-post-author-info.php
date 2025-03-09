<?php
if ( cozystay_module_enabled( 'cozystay_blog_single_post_page_footer_show_author_info_box' ) ) :
	$author_id 		= get_the_author_meta( 'ID' );
	$author_email 	= get_the_author_meta( 'user_email', $author_id );
	$avatar 		= get_avatar( $author_email, 160 );
	$author_url 	= esc_url( get_author_posts_url( $author_id ) );
	$author_name 	= get_the_author();
	$description 	= get_the_author_meta( 'description' );
	if ( ! empty( $description ) ) : ?>

    <aside class="author-info-box">
        <?php if ( ! empty( $avatar ) ) : ?>
        <div class="author-photo">
            <?php echo get_avatar( $author_email, 160 ); ?>
        </div>
        <?php endif; ?>
        <div class="author-info">
            <h4 class="author-name">
                <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
            </h4>
            <div class="author-bio-text"><?php the_author_meta( 'description', $author_id ); ?></div>
            <div class="author-info-footer">
                <div class="author-profile-link">
                    <a href="<?php echo esc_url( $author_url ); ?>"><?php esc_html_e( 'View All Posts', 'cozystay' ); ?></a>
                </div>
                <?php do_action( 'loftocean_front_the_user_social' ); ?>
            </div>
        </div>
    </aside><?php

	endif;
endif;
