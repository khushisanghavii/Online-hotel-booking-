<?php

if ( ! class_exists( 'CozyStay_Customize_Configuration_Base' ) ) {
	/**
	* Theme customize configuration base class
	*	Each config class will extend this class
	*		1. Action to register customize setting, panel, section and control
	*		2. Filter to add javascript variables for cutomize.php
	*
	* @author Loft.Ocean
	* @since 1.0.0
	*/
	class CozyStay_Customize_Configuration_Base {
		/**
		* Construct function to hook required actions/filters
		*/
		public function __construct() {
			add_action( 'customize_register', array( $this, 'register_controls' ) );
		}
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) { }
		/**
		* Customize control active callback function to test whether display current control.
		*    Dependency settings could be more than one, treat as logical AND.
		*    Each dependency setting value is array, so testing will be based on in/not in.
		* @param object WP_Csutomize_Control to test on
		* @return boolean if dependency test not enable return true, otherwise test if current value is in the list given.
		*/
		public function customize_control_active_cb( $control ) {
			if ( $control instanceof WP_Customize_Control ) {
				$manager  = $control->manager;
				$settings = $control->settings;
				$setting =  false;
				if ( $control instanceof WP_Customize_Background_Position_Control ) {
					$setting = $settings['x'];
				} else if ( ! empty( $settings['default'] ) ) {
					$setting = $settings['default'];
				}

				if ( $setting instanceof CozyStay_Customize_Setting ) {
					$dependency = $setting->dependency;
					if ( ! empty( $dependency ) ) {
						return $this->check_dependency( $dependency, $manager );
					}
					return true;
				}
			}
			return false;
		}
		/**
		* Helper function to determine whether show customize control
		*/
		protected function check_dependency( $deps, $wp_customize ) {
			if ( is_array( $deps ) ) {
				$relation = 'AND';
				if ( isset( $deps['relation'] ) ) {
					$relation = empty( $deps['relation'] ) ? 'AND' : strtoupper( $deps['relation'] );
					unset( $deps['relation'] );
				}

				$is_complex = ! empty( $deps['is_complex'] );
				unset( $deps['is_complex'] );
				return ( 'AND' == $relation ) ? $this->check_and( $deps, $wp_customize, $is_complex ) : $this->check_or( $deps, $wp_customize, $is_complex );
			}
			return true;
		}
		/**
		* Helper function to check dependency AND
		*/
		protected function check_and( $deps, $wp_customize, $is_complex ) {
			if ( empty( $deps ) ) return true;

			foreach( $deps as $id => $dep ) {
				if ( empty( $dep ) ) continue;
				$result = $is_complex ? $this->check_dependency( $dep, $wp_customize ) : $this->dependency_item_check( $id, $dep, $wp_customize );
				if ( ! $result ) {
					return false;
				}
			}
			return true;
		}
		/**
		* Helper function to check dependency OR
		*/
		protected function check_or( $deps, $wp_customize, $is_complex ) {
			if ( empty( $deps ) ) return true;

			foreach( $deps as $id => $dep ) {
				if ( empty( $dep ) ) continue;
				$result = $is_complex ? $this->check_dependency( $dep, $wp_customize ) : $this->dependency_item_check( $id, $dep, $wp_customize );
				if ( $result ) {
					return true;
				}
			}
			return false;
		}
		/**
		* Dependency item check
		*/
		protected function dependency_item_check( $id, $atts, $wp_customize ) {
			if ( empty( $id ) || empty( $atts ) || empty( $atts['value'] ) ) { // If not provide the test value list, return false
				return false;
			}
			if ( $wp_customize->get_setting( $id ) instanceof WP_Customize_Setting ) {
				// Test operator, potential value: in/not in. The default is in.
				$is_operator_not_in = ! empty( $atts['operator'] ) && ( strtolower($atts['operator'] ) == 'not in' );
				$value = $wp_customize->get_setting( $id )->value();
				$values = $atts['value'];
				return $is_operator_not_in ? ( ! in_array( $value, $values ) ) : in_array( $value, $values );
			}
			return true;
		}
		/**
		* Register meta customize setting and return customize control
		* @param object
		* @param string meta id
		* @param array control args
		* @param array setting args
		* @return object
		*/
		protected function register_meta_setting( $wp_customize, $meta_id, $control_args = array(), $setting_args = array() ) {
			global $cozystay_default_settings;
			$defaults = $cozystay_default_settings;
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $meta_id, array_merge( array(
				'default'			=> $defaults[ $meta_id ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			), $setting_args ) ) );
			return array_merge( array( 'value' => 'on', 'label' => '', 'setting' => $meta_id, 'description' => '' ), $control_args );
		}
	}
}
