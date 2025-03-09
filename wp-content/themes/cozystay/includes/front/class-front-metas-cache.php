<?php
// Cache class for post metas

if ( ! class_exists( 'CozyStay_Front_Metas_Cache' ) ) {
    class CozyStay_Front_Metas_Cache {
		/**
		* Object instance to make sure only one instance exists
		*/
		public static $instance = false;
		/**
		* Array store currently endabled social sharing
		*/
		protected $social_enabled = null;
		/**
		* Construct function
		*/
		public function __construct() {
            add_filter( 'cozystay_get_social_enabled', array( $this, 'get_social_enabled' ), 9999 );
		}
		/**
		* Get social enabled
		* @return mix
		*/
		public function get_social_enabled( $socials = array() ) {
			if ( null === $this->social_enabled ) {
				$enabled = array();
				$metas = array(
					'cozystay_general_social_like'        => 'like',
					'cozystay_general_social_facebook'    => 'facebook',
					'cozystay_general_social_twitter'     => 'twitter',
                    'cozystay_general_social_linkedin'    => 'linkedin',
					'cozystay_general_social_pinterest'   => 'pinterest',
					'cozystay_general_social_whatsapp'    => 'whatsapp'
				);
				foreach( $metas as $mn => $m ) {
					if ( cozystay_module_enabled( $mn ) ) {
						array_push( $enabled, $m );
					}
				}
				$this->social_enabled = count( $enabled ) > 0 ? $enabled : false;
			}
			return $this->social_enabled;
		}
		/**
		* Static function to make sure only one instance exists
		* @return object
		*/
		public static function _instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	CozyStay_Front_Metas_Cache::_instance();
}
