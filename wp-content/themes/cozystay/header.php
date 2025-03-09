<!DOCTYPE html>
<html <?php cozystay_the_html_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php cozystay_body_open(); ?>
		<div id="page">
			<?php do_action( 'cozystay_the_page_open' ); ?>
			<?php do_action( 'cozystay_the_site_header' ); ?>
			<div id="content" <?php cozystay_the_content_class(); ?>>
				<?php do_action( 'cozystay_the_content_open' ); ?>
