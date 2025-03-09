<?php
/**
* Theme custom customize setting class
*/

if ( ! class_exists( 'CozyStay_Customize_Setting' ) && class_exists( 'WP_Customize_Setting' ) ) {
	/**
	* Theme customized setting class to add dependency property and rewrite the json function to print this property to frontend
	*	To determine the display of control if they dependen on some other settings
	*
	* @author Loft.Ocean
	* @since 1.0.0
	*/
	class CozyStay_Customize_Setting extends WP_Customize_Setting {
		/**
		* Array dependency list
		*/
		public $dependency = array();
		/**
		* Rewrite build-in json function to add the json value about dependency list
		*/
		public function json() {
			$json = parent::json();
			return empty( $this->dependency ) ? $json : array_merge( $json, array(
				'dependency' => $this->dependency
			) );
		}
	}
}
