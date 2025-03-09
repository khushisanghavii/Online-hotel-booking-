<?php
if ( ! class_exists( 'CozyStay_Ajax_Manager' ) ) {
    class CozyStay_Ajax_Manager {
        /**
        * Class instance to make sure only one instance exists
        */
        public static $_instance = false;
        /**
        * Construct function
        */
        public function __construct() {
            $this->process_ajax_request();
        }
        /**
        * Load file for the ajax request
        */
        protected function process_ajax_request() {
            if ( isset( $_REQUEST[ 'action' ] ) ) {
                $action = sanitize_text_field( wp_unslash( $_REQUEST[ 'action' ] ) );
                switch( $action ) {
                    case 'cozystay_load_more':
                        require_once COZYSTAY_THEME_INC . 'admin/ajax/class-ajax-load-more.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                        break;
                    case 'cozystay-polylang-duplicate-form':
                    case 'cozystay-polylang-update-form':
                        require_once COZYSTAY_THEME_INC . 'admin/ajax/class-ajax-polylang-forms.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                        break;
                }
            }
        }
		/*
		* Static function to initialize the class
		*/
		public static function instance() {
			if ( false === self::$_instance ) {
				self::$_instance = new self();
			}
		}
	}
	add_action( 'init', 'CozyStay_Ajax_Manager::instance', 1 );
}
