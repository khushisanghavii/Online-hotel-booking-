<?php
/**
* Load the wp core features/supports/widgets/menus
*/

if ( ! class_exists( 'CozyStay_Setup_Environment' ) ) {
	class CozyStay_Setup_Environment {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'after_setup_theme', 	array( $this, 'wp_supports' ) );
			add_action( 'after_setup_theme', 	array( $this, 'register_menus' ) );
			add_action( 'after_setup_theme', 	array( $this, 'image_sizes' ) );
			add_action( 'widgets_init', 		array( $this, 'register_sidebars' ) );

			add_filter( 'cozystay_post_formats_supported', array( $this, 'get_post_formats' ) );
			add_filter( 'loftocean_post_formats_supported', array( $this, 'get_post_formats' ) );
		}
		/**
		* Add WP supports and content width
		*/
		public function wp_supports() {
			// set default content width to 1920px
			$GLOBALS['content_width'] = 1920;
			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			// Let WordPress manage the document title.
			add_theme_support( 'title-tag' );

			// Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 1920, 9999 );

			// Set up the WordPress core custom background feature.
			add_theme_support( 'custom-background', array(
				'default-color' 	=> '',
				'wp-head-callback' 	=> array( $this, 'custom_background_cb' )
			) );

			// Enable support for custom header
			add_theme_support( 'custom-header', apply_filters( 'cozystay_custom_header_args', array(
				'default-image' => '',
				'width' 		=> 1920,
				'height' 		=> 300,
				'flex-width' 	=> true,
				'flex-height' 	=> true
			) ) );

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption' ) );

			/*
			 * Enable support for custom logo.
			 */
			add_theme_support( 'custom-logo', array(
				'height'      => 80,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true
			) );

			/*
			 * Enable support for Post Formats.
			 */
			add_theme_support( 'post-formats', apply_filters( 'cozystay_post_formats_supported', array() ) );

			// Support Gutenberg new image align
			add_theme_support( 'align-wide' );

			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );

			// Indicate widget sidebars can use selective refresh in the Customizer.
			add_theme_support( 'customize-selective-refresh-widgets' );

			/*
			 * This theme styles the visual editor to resemble the theme style, specifically font, colors, icons, and column width.
			 */
			add_editor_style( 'style.css' );
			remove_theme_support( 'widgets-block-editor' );
		}
		/**
		* Register custom image image_sizes
		*/
		public function image_sizes() {
			add_image_size( 'cozystay_1920x9999', 		1920, 9999, false );
			add_image_size( 'cozystay_1440x9999', 		1440, 9999, false );
			add_image_size( 'cozystay_1200x9999', 		1200, 9999, false );
			add_image_size( 'cozystay_780x9999', 		780, 9999, false );
			add_image_size( 'cozystay_600x9999', 		600, 9999, false );
			add_image_size( 'cozystay_550x9999',		550, 9999, false );
			add_image_size( 'cozystay_370x9999',		370, 9999, false );
			add_image_size( 'cozystay_255x9999',		255, 9999, false );
			add_image_size( 'cozystay_600x600-crop',	600, 600, true );
			add_image_size( 'cozystay_300x300-crop', 	300, 300, true );
		}
		// Register menus
		public function register_menus() {
			// This theme uses wp_nav_menu() in two locations.
			register_nav_menus( array(
				'primary-menu' 	=> esc_html__( 'Primary Menu', 'cozystay' ),
				'social-menu' 	=> esc_html__( 'Social Menu', 'cozystay' ),
				'footer-menu'	=> esc_html__( 'Footer Bottom Menu', 'cozystay' )
			) );
		}
		// Register sidebars
		public function register_sidebars() {
			$sidebars = array(
				'main-sidebar' => array(
					'name' => esc_html__( 'Main Sidebar', 'cozystay' ),
					'description' => esc_html__( 'Add widgets here to appear in your main sidebar.', 'cozystay' )
				)
			);
			foreach ( $sidebars as $id => $attrs ) {
				register_sidebar( array(
					'name'          => $attrs['name'],
					'id'            => $id,
					'description'   => $attrs['description'],
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget-title">',
					'after_title'   => '</h5>'
				) );
			}
		}
		/***
		* Post formats supported by this theme
		*/
		public function get_post_formats( $format = array() ) {
			return array( 'standard', 'gallery', 'video' );
		}
		/**
		* Custom background styles
		*/
		public function custom_background_cb(){
		    $background = set_url_scheme( get_background_image() );
			$color = get_background_color();

		    if ( $color === get_theme_support( 'custom-background', 'default-color' ) ) {
		        $color = false;
		    }

		    if ( ! $background && ! $color ) {
		        return;
		    }

		    $style = $color ? "background-color: #$color;" : '';

		    if ( $background ) {
		        $image = ' background-image: url("' . esc_url_raw( $background ) . '");';

		        // Background Position.
		        $position_x = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
		        $position_y = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );

		        if ( ! in_array( $position_x, array( 'left', 'center', 'right' ), true ) ) {
		            $position_x = 'left';
		        }

		        if ( ! in_array( $position_y, array( 'top', 'center', 'bottom' ), true ) ) {
		            $position_y = 'top';
		        }

		        $position = " background-position: $position_x $position_y;";

		        // Background Size.
		        $size = get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) );

		        if ( ! in_array( $size, array( 'auto', 'contain', 'cover' ), true ) ) {
		            $size = 'auto';
		        }

		        $size = " background-size: $size;";

		        // Background Repeat.
		        $repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );

		        if ( ! in_array( $repeat, array( 'repeat-x', 'repeat-y', 'repeat', 'no-repeat' ), true ) ) {
		            $repeat = 'repeat';
		        }

		        $repeat = " background-repeat: $repeat;";

		        // Background Scroll.
		        $attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );

		        if ( 'fixed' !== $attachment ) {
		            $attachment = 'scroll';
		        }

		        $attachment = " background-attachment: $attachment;";

		        $style .= $image . $position . $size . $repeat . $attachment;
		    } ?>
				<style type="text/css" id="custom-background-css">
					body.custom-background #page { <?php echo sanitize_text_field( $style ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> }
				</style><?php
		}
		/**
		* Instance Loader class there can only be one instance of loader
		*/
		public static function init() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
}
