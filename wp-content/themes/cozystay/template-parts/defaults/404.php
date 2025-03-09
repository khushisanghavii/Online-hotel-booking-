<?php get_header(); ?>

<div class="main">
    <div class="container">
        <div id="primary" class="primary content-area">
            <article class="page type-page page-404">
                <div class="page-404-content">
                    <h1 class="entry-title"><?php esc_html_e( 'Page Not Found', 'cozystay' ); ?></h1>
                    <p><?php esc_html_e( 'Sorry but we couldn\'t find the page you are looking for. It might have been moved or deleted.', 'cozystay' ); ?></p>
    				<p><a class="button cs-btn-outline" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'cozystay' ); ?></a></p>
                </div>
            </article>
        </div>
    </div>
</div>

<?php get_footer();
