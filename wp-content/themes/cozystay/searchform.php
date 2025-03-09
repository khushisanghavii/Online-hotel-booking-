<?php $search_key = get_search_query(); ?>
<div class="search">
    <form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label>
            <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'cozystay' ); ?></span>
            <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Enter a keyword to search', 'cozystay' ); ?>" autocomplete="off" name="s"<?php if ( ! empty( $search_key ) ) : ?> value="<?php echo esc_attr( $search_key ); ?>" <?php endif; ?>>
        </label>
        <button type="submit" class="search-submit"><span class="screen-reader-text"><?php esc_html_e( 'Search', 'cozystay' ); ?></span></button>
    </form>
</div>
