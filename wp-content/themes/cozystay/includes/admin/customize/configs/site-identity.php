<?php
/**
* Customize section site-identity configuration files.
*/

if ( ! class_exists( 'CozyStay_Customize_Site_Identity' ) ) {
	class CozyStay_Customize_Site_Identity extends CozyStay_Customize_Configuration_Base {
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;

			$wp_customize->get_section( 'title_tagline' )->priority = 0;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			if ( ! empty( $wp_customize->selective_refresh ) && ( $wp_customize->selective_refresh instanceof WP_Customize_Selective_Refresh ) ) {
				$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
				$wp_customize->selective_refresh->remove_partial( 'custom_logo' );
			}

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_logo_width', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_site_logo_width' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'custom_logo' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_logo_width', array(
				'type' 				=> 'number_with_unit',
				'priority' 			=> 9,
				'label' 			=> esc_html__( 'Logo Width', 'cozystay' ),
				'after_text' 		=> 'px',
				'section' 			=> 'title_tagline',
				'settings' 			=> 'cozystay_site_logo_width',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
	}
	new CozyStay_Customize_Site_Identity();
}
