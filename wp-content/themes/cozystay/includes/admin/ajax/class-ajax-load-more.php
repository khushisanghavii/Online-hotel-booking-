<?php
/**
* Load more button ajax class
*/
if ( ! class_exists( 'CozyStay_Ajax_Load_More' ) ) {
 	class CozyStay_Ajax_Load_More {
		/**
		* String ajax action name
		*/
		public $action = 'cozystay_load_more';
		// Construct function
		public function __construct() {
			add_action( 'admin_init', array( $this, 'load_handler' ) );
		}
		/**
		* Load ajax handler if ajax request received
		*/
		public function load_handler() {
			$action = $this->action;
			if ( wp_doing_ajax() ) {
				add_action( 'wp_ajax_' . $action, array( $this, 'ajax_handler' ) );
				add_action( 'wp_ajax_nopriv_' . $action, array( $this, 'ajax_handler' ) );
				add_action( 'cozystay_pre_ajax_handler', array( $this, 'pre_ajax_start' ), 1 );
			}
		}
		/**
		* Pre ajax start
		*/
		public function pre_ajax_start() {
			$this->load_files();
			add_filter( 'cozystay_get_current_page_layout', array( $this, 'set_ajax_page_layout' ), 99999 );
            do_action( 'cozystay_image_loading_attributes' );
		}
		/**
		* Set page layout for ajax handler
		*/
		public function set_ajax_page_layout( $layout ) {
			if ( isset( $_REQUEST[ 'settings' ], $_REQUEST[ 'settings' ][ 'page_layout' ] ) ) {
				$args = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST[ 'settings' ] ) );
				$layout = $args[ 'page_layout' ];
			} else {
				$layout = 'with-sidebar-right';
			}
			return $layout;
		}
		/**
		* Ajax request handler
		*/
		public function ajax_handler() {
			if ( isset( $_REQUEST[ 'settings' ], $_REQUEST[ 'query' ], $_REQUEST[ 'query' ][ 'paged' ], $_REQUEST[ 'settings' ][ 'archive_page' ] ) ) {
				$settings = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST[ 'settings' ] ) );
				$settings = array_map( 'maybe_unserialize', $settings );
				$query = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST[ 'query' ] ) );
				$query = array_map( 'maybe_unserialize', $query );

				$paged = max( 1, intval( $query[ 'paged' ] ) );
				$archive_page = $settings[ 'archive_page' ];
				do_action( 'cozystay_pre_ajax_handler', $archive_page );

				if ( isset( $settings[ 'action' ] ) ) {
					do_action( $settings[ 'action' ], $query, $settings );
					return true;
				}

				query_posts( apply_filters( 'cozystay_ajax_handler_query_args', $query, $settings ) );
				global $wp_query;
				$results = array();
				if ( have_posts() ) {
                    $is_post_archive = ! empty( $archive_page ) && ( 'archive' != $archive_page );
					if ( $is_post_archive ) {
                        $layout = isset( $settings[ 'layout' ] ) ? $settings[ 'layout' ] : 'list';
                        $post_list_args = array(
							'layout' => $layout,
							'columns' => isset( $settings[ 'columns' ] ) ? $settings[ 'columns' ] : '',
							'post_meta'	=> $settings[ 'post_meta' ],
							'page_layout' => isset( $settings[ 'page_layout' ] ) ? $settings[ 'page_layout' ] : ''
						);
						do_action( 'cozystay_start_post_list_loop', $post_list_args );
					}
					add_filter( 'post_class' ,array( $this, 'add_post_class' ) );
					while( have_posts() ) {
						the_post();
						ob_start();
						if ( $is_post_archive ) {
							get_template_part( 'template-parts/content/content', $layout );
						} else {
							do_action( 'loftocean_ajax_the_list_content', $settings );
						}
						$results[] = ob_get_clean();
					}
					if ( $is_post_archive ) {
						do_action( 'cozystay_end_post_list_loop' );
					}
					ob_start();
					cozystay_list_pagination();
					$nav = ob_get_clean();
					wp_reset_postdata();
					$more = ( $paged < $wp_query->max_num_pages );
					wp_reset_query();
					wp_send_json_success( array(
						'more' => $more,
						'items' => $results,
						'nav' => '',
					) );
				} else {
					ob_start();
					get_template_part( 'template-parts/content/content-none', $archive_page );
					$results[] = ob_get_clean();
					wp_send_json_success( array(
						'more' => false,
						'nav' => '',
						'items' => $results
					) );
				}
			}
			wp_send_json_error();
		}
		/**
		* Load required files
		*/
		protected function load_files() {
			require_once COZYSTAY_THEME_INC . 'utils/class-utils-sanitize.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'front/functions-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'front/class-front-archive.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'front/class-front-metas-cache.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Add classname post to class list
		*/
		public function add_post_class( $class ) {
			array_push( $class, 'post' );
			return $class;
		}
 	}
	new CozyStay_Ajax_Load_More();
}
