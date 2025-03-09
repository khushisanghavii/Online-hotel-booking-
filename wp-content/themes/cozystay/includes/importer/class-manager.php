<?php
if ( ! class_exists( 'CozyStay_Importer_Manager' ) ) {
    class CozyStay_Importer_Manager {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_get_demo_configs', array( $this, 'get_configs' ) );
            add_action( 'cozystay_theme_options', array( $this, 'load_files' ) );
        }
        /**
        * Demo configs
        */
        public function get_configs( $config = array() ) {
            return require_once COZYSTAY_THEME_INC . 'importer/configs.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
        }
        /**
        * Load required files
        */
        public function load_files() {
            require_once COZYSTAY_THEME_INC . 'importer/class-templates.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
        }
    }
//    new CozyStay_Importer_Manager();
}
