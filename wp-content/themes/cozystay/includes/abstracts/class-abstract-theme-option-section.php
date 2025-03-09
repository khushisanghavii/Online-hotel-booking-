<?php

if ( ! class_exists( 'cozystay_Theme_Option_Section' ) ) {
	/**
	* Theme option section base class
	* @author Loft.Ocean
	* @since 1.0.0
	*/
	class CozyStay_Theme_Option_Section {
		/**
		* Array of option to save with id as index and sanitize function as value
		*/
		protected $options = array();
		/**
		* String section title
		*/
		protected $title = '';
		/**
		* String section id
		*/
		protected $id = '';
		/**
		* Array default values
		*/
		protected $defaults = array();
		/**
		* Construct function
		*/
		public function __construct() {
			$this->setup_env();
			add_action( 'cozystay_admin_theme_settings_save', array( $this, 'save_changes' ) );
			add_filter( 'cozystay_admin_theme_settings_get_tabs', array( $this, 'register_tabs' ) );
		}
		/**
		* Save changes
		*/
		public function save_changes() {
			$this->register_options();
			if ( cozystay_is_valid_array( $this->options ) ) {
				foreach ( $this->options as $name => $sanitize_cb_func ) {
					if ( isset( $_REQUEST[ $name ] ) && cozystay_is_callback_valid( $sanitize_cb_func ) ) {
						update_option( $name, call_user_func( $sanitize_cb_func, sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) ) ) );
					} else {
						update_option( $name, '' );
					}
				}
			}
		}
		/**
		* Register tabs
		*/
		public function register_tabs( $tabs ) {
			if ( ! empty( $this->id ) && ! empty( $this->title ) ) {
				$tabs[ $this->id ] = array(
					'title' => $this->title,
					'callback_func' => array( $this, 'render_tab_content' )
				);
			}
			return $tabs;
		}
		/**
		* Get option value
		*/
		public function get_value( $name ) {
			if ( array_key_exists( $name, $this->defaults ) ) { 
				return get_option( $name, $this->defaults[ $name ] );
			} else {
				return false;
			}
		}
		/**
		* Setup environment
		*/
		protected function setup_env(){ }
		/**
		* Render section content
		*/
		public function render_tab_content() { }
		/**
		* Register options
		*/
		protected function register_options() { }
	}
}
