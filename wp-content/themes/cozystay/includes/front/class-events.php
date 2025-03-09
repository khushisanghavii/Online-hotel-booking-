<?php
if ( ! class_exists( 'CozyStay_Events' ) && defined( 'TRIBE_EVENTS_FILE' ) ) {
	class CozyStay_Events {
        /**
        * Current page title settings
        */
        protected $page_title = false;
		/**
		* Construct function
		*/
		public function __construct() {
            add_action( 'template_redirect', array( $this, 'init_hooks' ), 99999 );
        }
        /**
        * Init custom 404 hooks
        */
        public function init_hooks() {
            if ( $this->is_event_pages() ) {
                add_filter( 'cozystay_show_page_title_section', '__return_false', 999999999 );
                add_action( 'cozystay_the_content_open', array( $this, 'event_page_title' ), 9999 );
            }
		}
        /**
        * Test if is envent related pages
        */
        protected function is_event_pages() {
            $queried_object = get_queried_object();
            if ( ! is_object( $queried_object ) ) return false;

			global $wp_query;
            $object = get_class( $queried_object );
            switch( $object ) {
                case 'WP_Post':
                    if ( 'tribe_events' == $queried_object->post_type ) {
                        $this->page_title = array( 'title' => get_the_title( $queried_object ), 'description' => '' );
                        $thumb_id = get_post_thumbnail_id( $queried_object->ID );
        				if ( cozystay_does_attachment_exist( $thumb_id ) ) {
                            add_filter( 'cozystay_theme_mod', array( $this, 'get_title_image' ), 999999, 2 );
                            add_filter( 'tribe_event_featured_image', array( $this, 'disable_event_featured_image' ), 999999, 3 );
                            add_filter( 'cozystay_page_title_default_class', array( $this, 'page_title_class' ), 999999 );
                        }
                        return true;
                    }
                    break;
                case 'WP_Post_Type':
                    if ( 'tribe_events' == $queried_object->name ) {
                        $this->page_title = array( 'title' => esc_html__( 'Events', 'cozystay' ), 'description' => '' );
                        return true;
                    }
                    break;
                case 'WP_Term':
					$post_type = $wp_query->get( 'post_type' );
                    if ( ! empty( $post_type ) && ( 'tribe_events' == $post_type ) ) {
                        $this->page_title = array( 'title' => $queried_object->name, 'description' => $queried_object->description );
                        return true;
                    }
                    break;
            }

            return false;
        }
        /**
        * Load event page title
        */
        public function event_page_title() {
            get_template_part( 'template-parts/page-header/events', '', $this->page_title );
        }
        /**
        * Get single event page title image
        */
        public function get_title_image( $value, $id ) {
            $settings = array( 'cozystay_page_title_default_background_image' );
			if ( in_array( $id, $settings ) ) {
				$current_page_id = get_queried_object_id();
				$thumb_id = get_post_thumbnail_id( $current_page_id );
				if ( cozystay_does_attachment_exist( $thumb_id ) ) {
					return $thumb_id;
				}
			}
			return $value;
        }
        /**
        * Disable single event page featured image
        */
        public function disable_event_featured_image( $featured_image, $post_id, $size ) {
            return '';
        }
        /**
        * Page title class
        */
        public function page_title_class( $class ) {
            return 'page-title-bg';
        }
	}
	new CozyStay_Events();
}
