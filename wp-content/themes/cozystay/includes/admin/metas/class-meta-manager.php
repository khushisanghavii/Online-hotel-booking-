<?php
if ( ! class_exists( 'CozyStay_Meta_Manager' ) ) {
	class CozyStay_Meta_Manager {
		/**
		* Object current class instance to make sure only once instance in running
		*/
		public static $instance = false;
		/**
		* Construct function
		*/
		public function __construct() {
			$this->nav_edit_fields();
			add_action( 'load-post.php', array( $this, 'load_admin_metas' ) );
			add_action( 'load-post-new.php', array( $this, 'load_admin_metas' ) );
			add_action( 'template_redirect', array( $this, 'load_front_metas' ) );
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		}
		/**
		* Load admin metas
		*/
		public function load_admin_metas() {
			global $typenow;
			switch ( $typenow ) {
				case 'post':
					$this->load_post_metas();
					break;
				case 'page':
					$this->load_page_metas();
					break;
			}
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
		/**
		* Nav edit mega menu settings
		*/
		public function nav_edit_fields() {
			require_once COZYSTAY_THEME_INC . 'admin/metas/class-menu-fields.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Add class to admin body class to hide metas for static pages
		*/
		public function admin_body_class( $class ) {
			if ( apply_filters( 'loftocean_hide_page_settings', false ) ) {
				return empty( $class ) ? 'cs-admin-static-pages' : $class . ' cs-admin-static-pages';
			}
			return $class;
		}
		/**
		* Load front metas
		*/
		public function load_front_metas() {
			$this->load_post_metas();
			if ( is_singular( 'page' ) ) {
				$this->load_page_metas();
			}
		}
		/**
		* Load post metas
		*/
		protected function load_post_metas() {
			require_once COZYSTAY_THEME_INC . 'admin/metas/class-post-metas.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Load page metas
		*/
		protected function load_page_metas() {
			require_once COZYSTAY_THEME_INC . 'admin/metas/class-page-metas.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		/**
		* Enqueue admin post metabox style
		*/
		public function enqueue_scripts() {
			$suffix = cozystay_get_assets_suffix();
			wp_enqueue_style( 'cozystay-meta-box', COZYSTAY_ASSETS_URI . 'styles/admin/meta-box' . $suffix . '.css', array(), COZYSTAY_ASSETS_VERSION );
			wp_enqueue_script( 'cozystay-meta-box', COZYSTAY_ASSETS_URI . 'scripts/admin/meta-box' . $suffix . '.js', array( 'jquery', 'wp-color-picker' ), COZYSTAY_ASSETS_VERSION, true );
		}
		/**
		* Instance Loader class
		*	there can only be one instance of loader
		* @return class Loader
		*/
		public static function _instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	add_action( 'cozystay_loaded', 'CozyStay_Meta_Manager::_instance' );
}
