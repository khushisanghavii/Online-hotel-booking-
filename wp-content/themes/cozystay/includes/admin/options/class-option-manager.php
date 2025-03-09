<?php
if ( ! class_exists( 'CozyStay_Options_Manager' ) ) {
    class CozyStay_Options_Manager {
        /**
        * Construct function
        */
        public function __construct() {
            $this->load_files();
        }
        /**
        * Load files
        */
        protected function load_files() {
            require_once COZYSTAY_THEME_INC . 'admin/options/theme-settings/class-theme-settings.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
        }
    }
    new CozyStay_Options_Manager();
}
