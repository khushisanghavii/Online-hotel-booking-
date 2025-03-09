<?php
/**
* Customize panel site footer configuration files.
*/

if ( ! class_exists( 'CozyStay_Customize_Site_Footer' ) ) {
	class CozyStay_Customize_Site_Footer extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_site_footer';
		/**
		* Array default customize option values
		*/
		public $defaults = array();
		/**
		* Boolean if theme core activated
		*/
		public $is_theme_core_activated = false;
		/**
		* Boolean if elementor activated
		*/
		public $is_elementor_activated = false;
		/**
		* Int section priority
		*/
		protected $section_priority = 0;
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			$this->defaults = $cozystay_default_settings;
			$this->is_theme_core_activated = cozystay_is_theme_core_activated();
			$this->is_elementor_activated = cozystay_is_elementor_activated();

			$this->add_panel( $wp_customize );
			$this->add_section_main_fotoer( $wp_customize );
			$this->add_section_above_footer( $wp_customize );
			$this->add_section_footer_instagram( $wp_customize );
			$this->add_section_footer_bottom( $wp_customize );
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( $this->panel_id, array(
				'title'    => esc_html__( 'Site Footer', 'cozystay' ),
				'priority' => 25
			) );
		}
		/**
		* Register section main footer
		*/
		protected function add_section_main_fotoer( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_site_footer_main';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Footer Main', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );

			if ( $this->is_theme_core_activated ) {
				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_main_custom_block', array(
					'default' 			=> $defaults['cozystay_site_footer_main_custom_block'],
					'transport'			=> 'refresh',
					'sanitize_callback' => 'absint'
				) ) );

				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_main_custom_block', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Select Footer', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_site_footer_main_custom_block',
					'choices' 	=> cozystay_get_custom_post_type(),
					'description' => sprintf(
						// translators: 1/2. html tag
						esc_html__( 'Select your default footer for your website from the list. If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom blocks first.', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_blocks' ) ),
						'</a>'
					)
				) ) );
			}
		}
		/**
		* Register section above main footer
		*/
		protected function add_section_above_footer( $wp_customize ) {
			if ( $this->is_theme_core_activated ) {
				$defaults = $this->defaults;
				$this->section_priority += 5;
				$section_id = 'cozystay_site_footer_section_above';
				$wp_customize->add_section( $section_id, array(
					'title'		=> esc_html__( 'Before Footer', 'cozystay' ),
					'panel' 	=> $this->panel_id,
					'priority'	=> $this->section_priority
				) );

				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_above_site_footer_content_source', array(
					'default'   		=> $defaults['cozystay_above_site_footer_content_source'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_above_site_footer_text_content', array(
					'default'   		=> $defaults['cozystay_above_site_footer_text_content'],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html',
					'dependency'		=> array( 'cozystay_above_site_footer_content_source' => array( 'value' => array( 'text' ) ) )
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_above_site_footer_custom_block', array(
					'default'   		=> $defaults['cozystay_above_site_footer_custom_block'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'absint',
					'dependency'		=> array( 'cozystay_above_site_footer_content_source' => array( 'value' => array( 'custom-block' ) ) )
				) ) );

				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_above_site_footer_content_source', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Content Source', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_above_site_footer_content_source',
					'choices' 	=> array(
						'text' => esc_html__( 'Text', 'cozystay' ),
						'custom-block' => esc_html__( 'Custom Block', 'cozystay' )
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_above_site_footer_text_content', array(
					'type' 				=> 'mce_editor',
					'label'				=> '',
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_above_site_footer_text_content',
					'active_callback'	=> array( $this, 'customize_control_active_cb' )
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_above_site_footer_custom_block', array(
					'type' 				=> 'select',
					'label' 			=> '',
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_above_site_footer_custom_block',
					'active_callback'	=> array( $this, 'customize_control_active_cb' ),
					'choices'			=> cozystay_get_custom_post_type(),
					'description' => sprintf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom blocks first.', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_blocks' ) ),
						'</a>'
					)
				) ) );
			}
		}
		/**
		* Register section instagram
		*/
		protected function add_section_footer_instagram( $wp_customize ) {
			if ( $this->is_theme_core_activated && function_exists( 'sbi_get_database_settings' ) ) {
				$defaults = $this->defaults;
				$this->section_priority += 5;
				$section_id = 'cozystay_site_footer_section_instagram';
				$wp_customize->add_section( $section_id, array(
					'title'		=> esc_html__( 'Instagram', 'cozystay' ),
					'panel' 	=> 'cozystay_panel_site_footer',
					'priority'	=> $this->section_priority
				) );

				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_enable_instagram', array(
					'default'   		=> $defaults['cozystay_site_footer_enable_instagram'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_feed', array(
					'default'			=> '',
					'transport'			=> 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'dependency'		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_warning', array(
					'default'			=> '',
					'transport'			=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty',
					'dependency'		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );

				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_title', array(
					'default'   		=> $defaults['cozystay_site_footer_instagram_title'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'dependency' 		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_title_link', array(
					'default'   		=> $defaults['cozystay_site_footer_instagram_title_link'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'dependency'		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_columns', array(
					'default'   		=> $defaults['cozystay_site_footer_instagram_columns'],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
					'dependency' 		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_footer_instagram_new_tab', array(
					'default'   		=> $defaults['cozystay_site_footer_instagram_new_tab'],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
					'dependency' 		=> array(
						'cozystay_site_footer_enable_instagram' => array( 'value' => array( 'on' ) )
					)
				) ) );

				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_enable_instagram', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Display Instagram Feed', 'cozystay' ),
					'section' 		=> 'cozystay_site_footer_section_instagram',
					'settings' 		=> 'cozystay_site_footer_enable_instagram'
				) ) );
				$feeds = \LoftOcean\get_instagram_feeds();
				if ( cozystay_is_valid_array( $feeds ) ) {
					$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_feed', array(
						'type' 		=> 'select',
						'label'		=> esc_html__( 'Select a Feed', 'cozystay' ),
						'section'	=> 'cozystay_site_footer_section_instagram',
						'settings' 	=> 'cozystay_site_footer_instagram_feed',
						'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
						'choices'	=> $feeds
					) ) );
				} else {
					$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_warning', array(
						'type' 		=> 'notes',
						'section'	=> 'cozystay_site_footer_section_instagram',
						'settings' 	=> 'cozystay_site_footer_instagram_warning',
						'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
						'description' => sprintf(
							// translators: 1. html tag start 2. html tag end
							esc_html__( 'Click %1$shere%2$s to know how to set up and configure your Instagram account.', 'cozystay' ),
							'<a href="https://loftocean.com/doc/cozystay/ptkb/instagram/" target="_blank">',
							'</a>'
						)
					) ) );
				}
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_title', array(
					'type'				=> 'text',
					'label' 			=> esc_html__( 'Title (optional)', 'cozystay' ),
					'section' 			=> 'cozystay_site_footer_section_instagram',
					'settings' 			=> 'cozystay_site_footer_instagram_title',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
					'input_attrs' 		=> array(
						'placeholder'=> esc_html__( 'Title (optional)', 'cozystay' )
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_title_link', array(
					'type' 				=> 'text',
					'label' 			=> esc_html__( 'Title Link', 'cozystay' ),
					'description_below' => true,
					'section' 			=> 'cozystay_site_footer_section_instagram',
					'settings' 			=> 'cozystay_site_footer_instagram_title_link',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
					'input_attrs' 		=> array(
						'placeholder'=> esc_html__( 'Instagram Title Link', 'cozystay' )
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_columns', array(
					'type' 				=> 'select',
					'label' 			=> esc_html__( 'Photos Layout', 'cozystay' ),
					'section' 			=> 'cozystay_site_footer_section_instagram',
					'settings' 			=> 'cozystay_site_footer_instagram_columns',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
					'choices'			=> array(
						'4' => esc_html__( '4 Columns', 'cozystay' ),
						'5' => esc_html__( '5 Columns', 'cozystay' ),
						'6' => esc_html__( '6 Columns', 'cozystay' ),
						'7' => esc_html__( '7 Columns', 'cozystay' ),
						'8' => esc_html__( '8 Columns', 'cozystay' )
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_instagram_new_tab', array(
					'type' 				=> 'checkbox',
					'label_first'		=> true,
					'label' 			=> esc_html__( 'Open links in new tab', 'cozystay' ),
					'section' 			=> 'cozystay_site_footer_section_instagram',
					'settings' 			=> 'cozystay_site_footer_instagram_new_tab',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );
			}
		}
		/**
		* Register section footer bottom
		*/
		protected function add_section_footer_bottom( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_site_footer_section_bottom';
			$wp_customize->add_section( $section_id, array(
				'title'		=> esc_html__( 'Footer Bottom', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority'	=> $this->section_priority
			) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_bottom_layout', array(
				'default'   		=> $defaults['cozystay_site_footer_bottom_layout'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_bottom_text', array(
				'default'   		=> $defaults['cozystay_site_footer_bottom_text'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_bottom_background_color', array(
				'default'   		=> $defaults['cozystay_site_footer_bottom_background_color'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_bottom_text_color', array(
				'default'   		=> $defaults['cozystay_site_footer_bottom_text_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_footer_hide_bottom', array(
				'default'   		=> $defaults['cozystay_site_footer_hide_bottom'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );

			$wp_customize->add_control ( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_bottom_layout', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Layout', 'cozystay' ),
				'section' 	=> 'cozystay_site_footer_section_bottom',
				'settings' 	=> 'cozystay_site_footer_bottom_layout',
				'choices'	=> array(
					'column-single' => esc_html__( '1 Column', 'cozystay' ),
					'' 				=> esc_html__( '2 Columns', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control ( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_bottom_text', array(
				'type' 		=> 'textarea',
				'label' 	=> esc_html__( 'Footer Text', 'cozystay' ),
				'section' 	=> 'cozystay_site_footer_section_bottom',
				'settings' 	=> 'cozystay_site_footer_bottom_text'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_site_footer_bottom_background_color', array(
				'label'    => esc_html__( 'Background Color', 'cozystay' ),
				'section'  => 'cozystay_site_footer_section_bottom',
				'settings' => 'cozystay_site_footer_bottom_background_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_site_footer_bottom_text_color', array(
				'label'    => esc_html__( 'Text Color', 'cozystay' ),
				'section'  => 'cozystay_site_footer_section_bottom',
				'settings' => 'cozystay_site_footer_bottom_text_color'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_footer_hide_bottom', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label' 		=> esc_html__( 'Hide Footer Bottom', 'cozystay' ),
				'section' 		=> 'cozystay_site_footer_section_bottom',
				'settings' 		=> 'cozystay_site_footer_hide_bottom'
			) ) );
		}
	}
	new CozyStay_Customize_Site_Footer();
}
