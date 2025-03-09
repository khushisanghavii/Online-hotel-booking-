<!DOCTYPE html>
<html <?php cozystay_the_html_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
        <div id="page"><?php
            if ( cozystay_show_elementor_simulator() ) :
                $cozystay_default_color_scheme = cozystay_get_theme_mod( 'cozystay_general_color_scheme' ); ?>
                <div class="cs-elementor-simulator-scheme-switcher">
                    <div class="cs-elementor-simulator-scheme-dark<?php if ( 'dark-color' == $cozystay_default_color_scheme ) : ?> active<?php endif; ?>" data-color="dark-color">
                        <?php esc_html_e( 'Dark', 'cozystay' ); ?>
                    </div>
                    <div class="cs-elementor-simulator-scheme-light<?php if ( 'light-color' == $cozystay_default_color_scheme ) : ?> active<?php endif; ?>" data-color="light-color">
                        <?php esc_html_e( 'Light', 'cozystay' ); ?>
                    </div>
                </div><?php
            endif; ?>
            <div id="content" class="site-content">
                <div class="main">
                	<div class="container">
                		<div id="primary" class="primary content-area">
                            <?php while ( have_posts() ) : ?>
                                <?php the_post(); ?>
                                <?php get_template_part( 'template-parts/content/single' ); ?>
                            <?php endwhile; ?>
                        </div>
                	</div>
                </div>
            </div> <!-- end of #content -->
        </div> <!-- end of #page -->
        <?php wp_footer(); ?>
    </body>
</html>
