<?php
/**
* Customize panel colors/styles configuration files.
*/

if ( ! class_exists( 'CozyStay_Customize_Colors_Styles' ) ) {
	class CozyStay_Customize_Colors_Styles extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_colors_styles';
		/**
		* Array default customize option values
		*/
		public $defaults = array();
		/**
		* Int current section priority
		*/
		protected $section_priority = 0;
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			$this->defaults = $cozystay_default_settings;

			$this->add_panel( $wp_customize );
			$this->add_section_general_colors( $wp_customize );
			$this->add_section_link_colors( $wp_customize );
			$this->add_section_button_styles( $wp_customize );
			$this->add_section_form_styles( $wp_customize );
			$this->add_section_others( $wp_customize );
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( $this->panel_id, array(
				'title'    => esc_html__( 'Colors & Styles', 'cozystay' ),
				'priority' => 30
			) );
		}
		/**
		* Register section general colors
		*/
		protected function add_section_general_colors( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_colors_section_general';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'General Colors', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_color_scheme', array(
				'default' 			=> $defaults['cozystay_general_color_scheme'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_primary_color', array(
				'default' 			=> $defaults['cozystay_general_primary_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_secondary_color', array(
				'default' 			=> $defaults['cozystay_general_secondary_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_colors_group_title', array(
				'default' 			=> '',
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_light_scheme_background_color', array(
				'default' 			=> $defaults['cozystay_general_light_scheme_background_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_light_scheme_text_color', array(
				'default' 			=> $defaults['cozystay_general_light_scheme_text_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_light_scheme_content_color', array(
				'default' 			=> $defaults['cozystay_general_light_scheme_content_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_dark_scheme_background_color', array(
				'default' 			=> $defaults['cozystay_general_dark_scheme_background_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_dark_scheme_text_color', array(
				'default' 			=> $defaults['cozystay_general_dark_scheme_text_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_dark_scheme_content_color', array(
				'default' 			=> $defaults['cozystay_general_dark_scheme_content_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_color_scheme', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Site Color Scheme', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_general_color_scheme',
				'choices'           => array(
					'light-color' => esc_html__( 'Light', 'cozystay' ),
					'dark-color'  => esc_html__( 'Dark', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_primary_color', array(
				'label'		=> esc_html__( 'Primary Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_primary_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_secondary_color', array(
				'label'		=> esc_html__( 'Secondary Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_secondary_color'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_colors_group_title', array(
				'type'		=> 'title_only',
				'label'		=> esc_html__( 'Background & Text Colors', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_colors_group_title'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_light_scheme_background_color', array(
				'label'		=> esc_html__( 'Light Scheme Background Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_light_scheme_background_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_light_scheme_text_color', array(
				'label'		=> esc_html__( 'Light Scheme Text Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_light_scheme_text_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_light_scheme_content_color', array(
				'label'		=> esc_html__( 'Light Scheme Content Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_light_scheme_content_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_dark_scheme_background_color', array(
				'label'		=> esc_html__( 'Dark Scheme Background Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_dark_scheme_background_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_dark_scheme_text_color', array(
				'label'		=> esc_html__( 'Dark Scheme Text Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_dark_scheme_text_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_dark_scheme_content_color', array(
				'label'		=> esc_html__( 'Dark Scheme Content Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_general_dark_scheme_content_color'
			) ) );
		}
		/**
		* Register section link color
		*/
		protected function add_section_link_colors( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_link_colors';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Links', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority'	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_light_scheme_regular_color', array(
				'default' 			=> $defaults['cozystay_link_light_scheme_regular_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_light_scheme_custom_regular_color', array(
				'default' 			=> $defaults['cozystay_link_light_scheme_custom_regular_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_link_light_scheme_regular_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_light_scheme_hover_color', array(
				'default' 			=> $defaults['cozystay_link_light_scheme_hover_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_light_scheme_custom_hover_color', array(
				'default' 			=> $defaults['cozystay_link_light_scheme_custom_hover_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_link_light_scheme_hover_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_dark_scheme_regular_color', array(
				'default' 			=> $defaults['cozystay_link_dark_scheme_regular_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_dark_scheme_custom_regular_color', array(
				'default' 			=> $defaults['cozystay_link_dark_scheme_custom_regular_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_link_dark_scheme_regular_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_dark_scheme_hover_color', array(
				'default' 			=> $defaults['cozystay_link_dark_scheme_hover_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_link_dark_scheme_custom_hover_color', array(
				'default' 			=> $defaults['cozystay_link_dark_scheme_custom_hover_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_link_dark_scheme_hover_color' => array( 'value' => array( 'custom' ) ) )
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_link_light_scheme_regular_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Regular - Light Scheme', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_link_light_scheme_regular_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_link_light_scheme_custom_regular_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_link_light_scheme_custom_regular_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_link_light_scheme_hover_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Hover - Light Scheme', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_link_light_scheme_hover_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_link_light_scheme_custom_hover_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_link_light_scheme_custom_hover_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_link_dark_scheme_regular_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Regular - Dark Scheme', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_link_dark_scheme_regular_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_link_dark_scheme_custom_regular_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_link_dark_scheme_custom_regular_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_link_dark_scheme_hover_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Hover - Dark Scheme', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_link_dark_scheme_hover_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_link_dark_scheme_custom_hover_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_link_dark_scheme_custom_hover_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
		/**
		* Register section button styles
		*/
		protected function add_section_button_styles( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_button_styles';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Buttons', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority'	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_shape', array(
				'default'   		=> $defaults['cozystay_button_shape'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_colors_group_title', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_background_color', array(
				'default'   		=> $defaults['cozystay_button_background_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_custom_background_color', array(
				'default'   		=> $defaults['cozystay_button_custom_background_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_button_background_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_text_color', array(
				'default'   		=> $defaults['cozystay_button_text_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_hover_background_color', array(
				'default'   		=> $defaults['cozystay_button_hover_background_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_hover_custom_background_color', array(
				'default'   		=> $defaults['cozystay_button_hover_custom_background_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_button_hover_background_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_hover_text_color', array(
				'default'   		=> $defaults['cozystay_button_hover_text_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_underline_color', array(
				'default'   		=> $defaults['cozystay_button_underline_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_underline_custom_color', array(
				'default'   		=> $defaults['cozystay_button_underline_custom_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_button_underline_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_button_colors_notes', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_shape', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Default Button Shape', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_button_shape',
				'choices'			=> array(
					'' => esc_html__( 'Square', 'cozystay' ),
					'cs-btn-rounded' => esc_html__( 'Rounded', 'cozystay' ),
					'cs-btn-pill' => esc_html__( 'Pill', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_colors_group_title', array(
				'type' 		=> 'title_only',
				'label' 	=> esc_html__( 'Default Button Colors', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_button_colors_group_title'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_background_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Background Color', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_button_background_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_button_custom_background_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_button_custom_background_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_button_text_color', array(
				'section' 	=> $section_id,
				'label'		=> esc_html__( 'Text Color', 'cozystay' ),
				'settings'	=> 'cozystay_button_text_color'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_hover_background_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Hover Background Color', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_button_hover_background_color',
				'choices'           => array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_button_hover_custom_background_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_button_hover_custom_background_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_button_hover_text_color', array(
				'section' 	=> $section_id,
				'label'		=> esc_html__( 'Hover Text Color', 'cozystay' ),
				'settings'	=> 'cozystay_button_hover_text_color'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_underline_color', array(
				'type'              => 'select',
				'label'             => esc_html__( 'Underline Button Color', 'cozystay' ),
				'section'           => $section_id,
				'settings' 	        => 'cozystay_button_underline_color',
				'choices'           => array(
					'' => esc_html__( 'Default', 'cozystay' ),
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_button_underline_custom_color', array(
				'section' 			=> $section_id,
				'settings'			=> 'cozystay_button_underline_custom_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_button_colors_notes', array(
				'type' 		=> 'notes',
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_button_colors_group_title',
				'description' => esc_html__( 'When editing a button in Elementor, if you select "Default" for "Button Color", the button will use the colors you set here. These colors can be overwritten when editing each specific button.', 'cozystay' ),
			) ) );
		}
		/**
		* Register section form
		*/
		protected function add_section_form_styles( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_form_styles';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Forms', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority'	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_form_field_style', array(
				'default'   		=> $defaults['cozystay_form_field_style'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_form_border_width', array(
				'default'   		=> $defaults['cozystay_form_border_width'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field'
			) ) );

			$wp_customize->add_control ( new CozyStay_Customize_Control( $wp_customize, 'cozystay_form_field_style', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Form Field Style', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_form_field_style',
				'choices' 	=> array(
					'cs-form-underline' => esc_html__( 'Underlined', 'cozystay' ),
					'cs-form-square' => esc_html__( 'Square', 'cozystay' ),
					'cs-form-rounded' => esc_html__( 'Rounded', 'cozystay' ),
					'cs-form-pill' => esc_html__( 'Pill', 'cozystay' ),
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_form_border_width', array(
				'type' 			=> 'number_with_unit',
				'label' 		=> esc_html__( 'Border Width', 'cozystay' ),
				'after_text' 	=> 'px',
				'input_attrs'	=> array( 'min' => 1, 'max' => 10 ),
				'section' 		=> $section_id,
				'settings' 		=> 'cozystay_form_border_width'
			) ) );
		}
		/**
		* Section others
		*/
		public function add_section_others( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_section_others_styles';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Others', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority'	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_others_blog_post_meta_color', array(
				'default'   		=> $defaults['cozystay_others_blog_post_meta_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_others_blog_post_meta_custom_color', array(
				'default'   		=> $defaults['cozystay_others_blog_post_meta_custom_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_others_blog_post_meta_color' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_others_rooms_subtitle_color', array(
				'default'   		=> $defaults['cozystay_others_rooms_subtitle_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_others_rooms_subtitle_custom_color', array(
				'default'   		=> $defaults['cozystay_others_rooms_subtitle_custom_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_others_rooms_subtitle_color' => array( 'value' => array( 'custom' ) ) )
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_others_blog_post_meta_color', array(
				'type'		=> 'select',
				'label'		=> esc_html__( 'Blog & Post Meta Color', 'cozystay' ),
				'section'	=> $section_id,
				'settings'	=> 'cozystay_others_blog_post_meta_color',
				'choices'	=> array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary' => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom' => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_others_blog_post_meta_custom_color', array(
				'section'	=> $section_id,
				'settings'	=> 'cozystay_others_blog_post_meta_custom_color',
				'active_callback' => array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_others_rooms_subtitle_color', array(
				'type'		=> 'select',
				'label'		=> esc_html__( 'Room Subtitle Color', 'cozystay' ),
				'section'	=> $section_id,
				'settings'	=> 'cozystay_others_rooms_subtitle_color',
				'choices'	=> array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary' => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom' => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_others_rooms_subtitle_custom_color', array(
				'section'	=> $section_id,
				'settings'	=> 'cozystay_others_rooms_subtitle_custom_color',
				'active_callback' => array( $this, 'customize_control_active_cb' )
			) ) );
		}
	}
	new CozyStay_Customize_Colors_Styles();
}
