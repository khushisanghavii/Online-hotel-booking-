<?php
if ( ! class_exists( 'CozyStay_Custom_Ajax' ) ) {
    class CozyStay_Custom_Ajax {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_ajax_navigation_json', array( $this, 'ajax_json' ) );
            add_filter( 'cozystay_ajax_navigation_query_vars', array( $this, 'maybe_serialize_query_vars' ), 9999999 );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets'), 1 );
        }
        /**
        * Enqueue ajax related JaveScript
        */
        public function enqueue_assets() {
			if ( $this->is_ajax_navigation_enabled() ) {
                wp_enqueue_script(
                    'cozystay-ajax-navigation',
                    COZYSTAY_ASSETS_URI . 'scripts/front/ajax-navigation' . cozystay_get_assets_suffix() . '.js',
                    array( 'jquery', 'cozystay-theme-script' ),
                    COZYSTAY_ASSETS_VERSION,
                    true
                );
				wp_localize_script(
					'cozystay-ajax-navigation',
					'cozystayAjaxNavigation',
					apply_filters( 'cozystay_ajax_navigation_json', array(
						'noMoreText' => esc_js( __( 'No More Posts', 'cozystay' ) )
					) )
				);
			}
		}
		/**
		* Condition function if load ajax related javascript
		*/
		protected function is_ajax_navigation_enabled() {
			$current_archive_page = apply_filters( 'cozystay_get_current_achive_page', '' );
            if ( is_singular() && cozystay_is_elementor_activated() ) {
                $id = get_the_ID();
                $document = \Elementor\Plugin::$instance->documents->get( $id );
                return $document && $document->is_built_with_elementor();
            } else if ( ( ! empty( $current_archive_page ) && ( 'archive' != $current_archive_page ) ) ) {
				$pagination_style = cozystay_get_theme_mod( 'cozystay_blog_general_pagination_style' );
				return in_array( $pagination_style, array( 'ajax-manual', 'ajax-auto' ) );
			}
			return false;
		}
		/**
		* Generate ajax json
		* @param array
		* @return array
		*/
		public function ajax_json( $json ) {
			$vars = $this->get_ajax_navigation_query_vars();
			return array_merge( $json, array(
				'url' => esc_js( admin_url( 'admin-ajax.php' ) ),
				'data' => apply_filters( 'loftocean_ajax_load_more_parameters', array(
					'query'	=> $vars['query'],
					'action' => 'cozystay_load_more',
					'settings' => $vars['settings']
				) )
			) );
		}
		/**
		* Get query vars for each archive page
		*/
		protected function get_ajax_navigation_query_vars() {
            global $wp_query;
            $current_archive_page = apply_filters( 'cozystay_get_current_achive_page', '' );
            if ( empty( $current_archive_page ) ||  'archive' == $current_archive_page ) {
				$sets = array( 'layout' => 'list', 'column' => false );
				$metas = array( 'excerpt', 'read_more_btn', 'author', 'date' );
			} else {
				$sets = cozystay_get_post_list_layout_settings();
				$metas = cozystay_get_list_post_meta();
			}
			$vars = array(
				'settings' => array(
					'archive_page' => $current_archive_page,
					'page_layout' => apply_filters( 'cozystay_get_current_page_layout', '' ),
                    'layout' => $sets['layout'],
					'columns' => $sets['column'],
                    'post_meta' => $metas
				),
				'query' => array_merge( $wp_query->query, array(
					'paged' => 2,
					'ignore_sticky_posts' => true,
					'post_status' => is_user_logged_in() ? array( 'publish', 'private' ) : 'publish',
				) )
			);
            $post_types = $wp_query->get( 'post_type' );
            if ( is_search() && ! empty( $post_types ) ) {
                $vars[ 'query' ][ 'post_type' ] = $post_types;
            }
			return apply_filters( 'cozystay_ajax_navigation_query_vars', $vars );
		}
		/**
		* Get list layout
		* @param string
		* @return string
		*/
		protected function get_layout( $page ) {
			$sets = cozystay_get_post_list_layout_settings( $page );
            return $sets['layout'];
		}
        /**
        * Serialize query vars
        */
        public function maybe_serialize_query_vars( $vars ) {
            $props = array( 'query', 'settings' );
            foreach ( $props as $prop ) {
                foreach ( $vars[ $prop ] as $id => $val ) {
                    if ( is_array( $val ) ) {
                        $vars[ $prop ][ $id ] = maybe_serialize( $val );
                    }
                }
            }
            return $vars;
        }
    }
    new CozyStay_Custom_Ajax();
}
