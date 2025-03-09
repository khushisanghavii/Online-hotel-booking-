<?php
/**
* Main class for frontend
*/

if ( ! class_exists( 'CozyStay_Front_Manager' ) ) {
	class CozyStay_Front_Manager {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'wp_loaded', array( $this, 'load_front_render' ), 999 );
			add_action( 'template_redirect', array( $this, 'load_front_hooks' ), 999 );

			add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout'), 9999 );
			add_filter( 'cozystay_content_class', array( $this, 'content_class' ), 9999 );
			add_filter( 'get_the_author_description', 'wpautop', 9999 );
		}
		/**
		* Load single settings
		*/
		public function load_front_render() {
			$dir = COZYSTAY_THEME_INC . 'front/';
			require_once $dir . 'class-front-single.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $dir . 'class-front-block-render.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $dir . 'class-front-archive.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $dir . 'class-front-metas-cache.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $dir . 'class-404.php';  // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $dir . 'class-events.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* WP hook function
		*/
		public function load_front_hooks() {
			$this->includes();
			add_filter( 'body_class', array( $this, 'body_class' ), 999 );
			add_filter( 'post_class', array( $this, 'post_class' ), 9999 );
			add_filter( 'cozystay_show_site_footer_bottom', array( $this, 'show_footer_bottom' ), 999999 );

			add_action( 'cozystay_page_title_section_after', array( $this, 'show_scroll_down' ), 10, 1 );
			do_action( 'cozystay_image_loading_attributes' );
		}
		/**
		* Import required files
		*/
		private function includes() {
			$inc_dir = COZYSTAY_THEME_INC;
			$front_dir = COZYSTAY_THEME_INC . 'front/';

			require_once $inc_dir . 'utils/class-utils-sanitize.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $front_dir . 'functions-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $front_dir . 'class-walker-comment.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $front_dir . 'class-walker-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Add extra class name to <body>
		* @param array class name list
		* @return array class name list
		*/
		public function body_class( $class ) {
			$button_shape = cozystay_get_theme_mod( 'cozystay_button_shape' );
			empty( $button_shape ) ? '' : array_push( $class, $button_shape );
			array_push( $class, esc_attr( cozystay_get_theme_mod( 'cozystay_general_color_scheme' ) ) );
			array_push( $class, ( 'custom' == cozystay_get_theme_mod( 'cozystay_page_content_width' ) ? 'custom-site-width' : 'site-layout-fullwidth' ) );
			array_push( $class, esc_attr( cozystay_get_theme_mod( 'cozystay_form_field_style' ) ) );
			in_array( 'theme-cozystay', $class ) ? '' : array_push( $class, 'theme-cozystay' );

			if ( apply_filters( 'cozystay_enable_onepage_menu_check', cozystay_module_enabled( 'cozystay_enable_onepage_menu_check' ) ) ) {
				array_push( $class, 'cozystay-enable-onepage-menu-check' );
			}

			return $class;
		}
		/**
		* Adjust post class if needed
		* @param array
		* @return array
		*/
		public function post_class( $class ) {
			if ( wp_doing_ajax() ) {
				array_push( $class, 'list-post' );
			}
			if ( post_password_required() && has_post_thumbnail() ) {
				array_push( $class, 'has-post-thumbnail' );
			}
			return $class;
		}
		/**
		* Check page layout for 404 page
		*/
		public function get_page_layout( $layout ) {
			return is_404() ? '' : $layout;
		}
		/**
		* Check content class for 404 page
		*/
		public function content_class( $class ) {
			return is_404() ? array_diff( $class, array( 'with-sidebar-right', 'with-sidebar-left' ) ) : $class;
		}
		/**
		* If show footer bottom
		*/
		public function show_footer_bottom( $show ) {
			if ( cozystay_module_enabled( 'cozystay_site_footer_hide_bottom' ) ) {
				return false;
			}
			return $show;
		}
		/**
		* Helper function to parse terms
		*/
		protected function get_terms( $sets, $type ) {
			$sets = explode( ',', $sets );
			$sets = array_filter( $sets );
			if ( cozystay_is_valid_array( $sets ) ) {
				$results = array();
				foreach ( $sets as $slug ) {
					$term = get_term_by( 'slug', $slug, $type );
					if ( false !== $term ) {
						array_push( $results, $term );
					}
				}
				return $results;
			}
			return false;
		}
		/**
		* Show scroll down HTML for screenheight page title section
		*/
		public function show_scroll_down( $page = 'page' ) {
			$use_default = true;
			$size = '';
			if ( 'post' == $page ) {
				$size = cozystay_get_theme_mod( 'cozystay_blog_single_post_title_section_size' );
				$use_default = empty( $size );
			}
			if ( $use_default ) {
				$size = cozystay_get_theme_mod( 'cozystay_page_title_section_size' );
			}
	        if ( 'page-title-fullheight' == $size ) : ?>
	            <div class="page-title-extra">
	                <span><?php esc_html_e( 'Scroll Down', 'cozystay' ); ?></span>
	            </div><?php
	        endif;
		}
		/**
		* Instance Loader class
		*	there can only be one instance of loader
		* @return class Loader
		*/
		public static function init() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
		}
	}
}
