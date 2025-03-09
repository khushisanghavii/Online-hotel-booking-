<?php
/**
* Customize section page title configuration files.
*/


if ( ! class_exists( 'CozyStay_Customize_Page_Title' ) ) {
	class CozyStay_Customize_Page_Title extends CozyStay_Customize_Configuration_Base {
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			$section_id = 'cozystay_section_page_title';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'Page Title', 'cozystay' ),
				'priority' 	=> 60
			) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_page_title_notes', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_section_size', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_page_title_section_size' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_color', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_background_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_image', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_background_image'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'absint'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_size', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_background_size'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency' 		=> array( 'cozystay_page_title_default_background_image' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_repeat', array(
				'default'   		=> $cozystay_default_settings['cozystay_page_title_default_background_repeat'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency' 		=> array( 'cozystay_page_title_default_background_image' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_position_x', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_background_position_x'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency' 		=> array( 'cozystay_page_title_default_background_image' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_position_y', array(
				'default'  			=> $cozystay_default_settings['cozystay_page_title_default_background_position_y'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency' 		=> array( 'cozystay_page_title_default_background_image' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_background_attachment', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_background_attachment'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency' 		=> array( 'cozystay_page_title_default_background_image' => array( 'value' => array( '', 0, '0' ), 'operator' => 'not in' ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_title_default_text_color', array(
				'default'			=> $cozystay_default_settings['cozystay_page_title_default_text_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_page_title_show_breadcrumb', array(
				'default'   		=> $cozystay_default_settings['cozystay_page_title_show_breadcrumb'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_notes', array(
				'type'          => 'notes',
				'description'	=> esc_html__( 'These settings control the default styles for all page title sections, including single pages, blog page & archive pages, and shop pages.', 'cozystay' ),
				'section' 	    => $section_id,
				'settings' 	    => 'cozystay_page_title_notes'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_section_size', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Section Size', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_page_title_section_size',
				'choices' 			=> array(
					'page-title-small' => esc_html__( 'Small', 'cozystay' ),
					'page-title-default' => esc_html__( 'Medium', 'cozystay' ),
					'page-title-large' => esc_html__( 'Large', 'cozystay' ),
					'page-title-fullheight' => esc_html__( 'Screen Height', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_page_title_default_background_color', array(
				'label'		=> esc_html__( 'Default Background Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_page_title_default_background_color'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_default_background_image', array(
				'type'		=> 'image_id',
				'label'		=> esc_html__( 'Default Background Image', 'cozystay' ),
				'section'	=> $section_id,
				'settings'	=> 'cozystay_page_title_default_background_image'
			) ) );
			$wp_customize->add_control( new WP_Customize_Background_Position_Control( $wp_customize, 'cozystay_page_title_default_background_position', array(
				'label' 			=> esc_html__( 'Image Position', 'cozystay' ),
				'section'			=> $section_id,
				'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
				'settings' 			=> array(
					'x' => 'cozystay_page_title_default_background_position_x',
					'y' => 'cozystay_page_title_default_background_position_y'
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_default_background_size', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Size', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_page_title_default_background_size',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
				'choices' 			=> array(
					'auto' 		=> esc_html__( 'Original', 'cozystay' ),
					'contain' 	=> esc_html__( 'Fit to Screen', 'cozystay' ),
					'cover'		=> esc_html__( 'Fill Screen', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_default_background_repeat', array(
				'type' 				=> 'checkbox',
				'label' 			=> esc_html__( 'Repeat', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_page_title_default_background_repeat',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_default_background_attachment', array(
				'type' 				=> 'checkbox',
				'label' 			=> esc_html__( 'Scroll with Page', 'cozystay' ),
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_page_title_default_background_attachment',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_page_title_default_text_color', array(
				'label'		=> esc_html__( 'Default Text Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_page_title_default_text_color'
			) ) );
			if ( cozystay_is_yoast_seo_activated() ) {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_title_show_breadcrumb', array(
					'type'          => 'checkbox',
					'label_first'   => true,
					'label'         => esc_html__( 'Display Yoast SEO Breadcrumb', 'cozystay' ),
					'description'	=> sprintf(
						// translators: 1/2. html tag
						esc_html__( 'To display Yoast SEO Breadcrumb on Single Posts, please go to %1$sBlog > Single Post%2$s  to enable it.', 'cozystay' ),
						'<a href="#" class="show-control" data-control-id="cozystay_blog_single_post_show_yoast_seo_breadcrumb">',
						'</a>'
					),
					'section' 	    => $section_id,
					'settings' 	    => 'cozystay_page_title_show_breadcrumb'
				) ) );
			}
		}
	}
	new CozyStay_Customize_Page_Title();
}
