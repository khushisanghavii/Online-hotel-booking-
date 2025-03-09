<?php
if ( ! class_exists( 'CozyStay_Custom_404' ) ) {
	class CozyStay_Custom_404 {
        /**
        * Integer custom 404 page id
        */
        protected $custom_404_page_id = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'set_404', array( $this, 'init_hooks' ), 99 );
        }
        /**
        * Init custom 404 hooks
        */
        public function init_hooks( $query ) {
            $custom_block = cozystay_get_theme_mod( 'cozystay_general_404_page_custom_block' );
            if ( $query->is_main_query() && cozystay_does_item_exist( $custom_block ) ) {
                $this->custom_404_page_id = $custom_block;
	 			query_posts( array( 'page_id' => $this->custom_404_page_id ) );

                add_filter( 'cozystay_is_custom_404', '__return_true' );
                add_filter( 'body_class', array( $this, 'body_class' ) );
				add_filter( 'document_title_parts', array( $this, 'html_title' ), 999 );
                add_filter( 'cozystay_get_custom_404_page_id', array( $this, 'get_custom_404_page_id' ) );
				if ( $this->use_theme_template() ) {
	                add_filter( 'cozystay_content_class', array( $this, 'content_class' ), 999999 );
	                add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout'), 999999 );
				}
            }
		}
		/**
		* Check if use theme template
		*/
		public function use_theme_template() {
			$template = get_page_template_slug( $this->custom_404_page_id );
			return in_array( $template, array( 'template-left-sidebar.php', 'template-fullwidth.php', 'template-wide-content.php', 'elementor_theme', 'default', '' ) );
		}
		/**
		* Change 404 html title
		*/
		public function html_title( $title ) {
			$title[ 'title' ] = __( 'Page not found', 'cozystay' );
			return $title;
		}
		/**
		* Get page sidebar settings
		*/
		public function get_page_layout( $layout ) {
			$template = get_page_template_slug( $this->custom_404_page_id );
			switch ( $template ) {
				case 'template-fullwidth.php':
				case 'template-wide-content.php':
					return '';
				case 'template-left-sidebar.php':
					return 'with-sidebar-left';
				default:
					return 'with-sidebar-right';
			}
			return $layout;
		}
		/**
		* Get main content layout class
		*/
		public function content_class( $class ) {
			$layout = $this->get_page_layout( '' );
			$sidebar_id = apply_filters( 'cozystay_sidebar_id', 'main-sidebar' );
			if ( ! empty( $layout ) && is_active_sidebar( $sidebar_id ) ) {
				array_push( $class, $layout );
			}
			return $class;
		}
		/**
		* Extra body class for single page
		*/
		public function body_class( $class ) {
			array_unshift( $class, 'custom-error-404' );
            array_unshift( $class, 'error404' );
			return $class;
		}
        /**
        * Get custom 404 page id
        */
        public function get_custom_404_page_id( $id ) {
            return $this->custom_404_page_id;
        }
	}
	new CozyStay_Custom_404();
}
