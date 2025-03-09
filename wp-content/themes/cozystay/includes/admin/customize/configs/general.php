<?php
/**
* Customize section general configuration files.
*/

if ( ! class_exists( 'CozyStay_Customize_General' ) ) {
	class CozyStay_Customize_General extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_general';
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
			$this->add_section_layouts( $wp_customize );
			$this->add_section_backtotop( $wp_customize );
			$this->add_section_instagram( $wp_customize );
			$this->add_section_social_media( $wp_customize );
			$this->add_section_404_page( $wp_customize );
			$this->add_section_cookie_law( $wp_customize );
			$this->add_section_popup_box( $wp_customize );
			$this->add_section_search( $wp_customize );
			$this->add_section_menu_anchors( $wp_customize );
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( $this->panel_id, array(
				'title' 	=> esc_html__( 'General', 'cozystay' ),
				'priority' 	=> 5
			) );
		}
		/**
		* Register section site background
		*/
		protected function add_section_layouts( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_general_section_site_background';
			$this->section_priority += 5;
			$wp_customize->add_section( $section_id, array(
				'title' => esc_html__( 'General Layouts', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_content_width', array(
				'default'			=> $defaults['cozystay_page_content_width'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_page_content_custom_width', array(
				'default'			=> $defaults['cozystay_page_content_custom_width'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'absint',
				'dependency'		=> array(
					'cozystay_page_content_width' => array( 'value' => array( 'custom' ) )
				)
			) ) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_page_background_title', array(
				'default'			=> '',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_content_width', array(
				'type'				=> 'select',
				'label'				=> esc_html__( 'Content Max Width', 'cozystay' ),
				'settings'			=> 'cozystay_page_content_width',
				'section'			=> $section_id,
				'choices'			=> array(
					'' 			=> esc_html__( 'Default', 'cozystay' ),
					'custom' 	=> esc_html__( 'Custom', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_content_custom_width', array(
				'type' 				=> 'number_with_unit',
				'label' 			=> esc_html__( 'Custom Width', 'cozystay' ),
				'after_text' 		=> 'px',
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_page_content_custom_width',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_page_background_title', array(
				'type' 		=> 'title_only',
				'label' 	=> esc_html__( 'Site Background', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_page_background_title',
				'priority'	=> 15
			) ) );

			$bg_settings = array(
				'background_image',
				'background_preset',
				'background_position',
				'background_position_x',
				'background_position_y',
				'background_size',
				'background_repeat',
				'background_attachment',
				'background_color'
			);
			foreach ( $bg_settings as $id ) {
				$control = $wp_customize->get_control( $id );
				if ( ! empty( $control ) && ( $control instanceof WP_Customize_Control ) ) {
					$control->section = $section_id;
					$control->priority = 20;
				}
			}
			$wp_customize->remove_control( 'header_textcolor' );
		}
		/**
		* Register section back to top
		*/
		protected function add_section_backtotop( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$wp_customize->add_section( 'cozystay_general_section_back_to_top', array(
				'title'		=> esc_html__( 'Back To Top', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_show_back_to_top_button', array(
				'default'			=> $defaults['cozystay_show_back_to_top_button'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_background_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_background_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_border_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_border_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_icon_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_icon_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_hover_background_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_hover_background_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_hover_border_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_hover_border_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_back_to_top_button_hover_icon_color', array(
				'default'			=> $defaults['cozystay_back_to_top_button_hover_icon_color'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency'		=> array(
					'cozystay_show_back_to_top_button' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_show_back_to_top_button', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Display The Back To Top button', 'cozystay' ),
				'section'		=> 'cozystay_general_section_back_to_top',
				'settings' 		=> 'cozystay_show_back_to_top_button',
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_background_color', array(
				'label'		=> esc_html__( 'Background Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_background_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_border_color', array(
				'label'		=> esc_html__( 'Border Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_border_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_icon_color', array(
				'label'		=> esc_html__( 'Icon Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_icon_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_hover_background_color', array(
				'label'		=> esc_html__( 'Hover Background Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_hover_background_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_hover_border_color', array(
				'label'		=> esc_html__( 'Hover Border Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_hover_border_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_back_to_top_button_hover_icon_color', array(
				'label'		=> esc_html__( 'Hover Icon Color', 'cozystay' ),
				'section' 	=> 'cozystay_general_section_back_to_top',
				'settings'	=> 'cozystay_back_to_top_button_hover_icon_color',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
		/**
		* Register section instagram
		*/
		protected function add_section_instagram( $wp_customize ) {
			if ( cozystay_is_theme_core_activated() ) {
				$this->section_priority += 5;
				$instagram = $wp_customize->get_section( 'loftocean_section_instagram' );
				if ( ! empty( $instagram ) && ( $instagram instanceof WP_Customize_Section ) ) {
					$instagram->panel = $this->panel_id;
					$instagram->priority = $this->section_priority;
				}
				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_instagram_note', array(
					'default'			=> '',
					'transport'			=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_instagram_note', array(
					'type' 		=> 'notes',
					'priority' 	=> 0,
					'section'	=> 'loftocean_section_instagram',
					'settings' 	=> 'cozystay_instagram_note',
					'description'	=> sprintf(
						// translators: 1/2. html tag
						esc_html__( 'To display Instagram feed, please add the Instagram Access Token to connect your website with your Instagram account. Please follow the instructions in our documentation %1$shere%2$s.', 'cozystay' ),
					 	sprintf( '<a href="%s" target="_blank">', 'https://loftocean.com/doc/cozystay/ptkb/instagram/' ),
						'</a>'
					)
				) ) );
			}
		}
		/**
		* Register section social media
		*/
		protected function add_section_social_media( $wp_customize ) {
			if ( cozystay_is_theme_core_activated() ) {
				$defaults = $this->defaults;
				$section_id = 'cozystay_general_section_social_media';
				$this->section_priority += 5;
				$settings = array();
				$social_media = array(
					'like'		=> esc_html__( 'Like', 'cozystay' ),
					'facebook'	=> esc_html__( 'Facebook', 'cozystay' ),
					'twitter' 	=> esc_html__( 'Twitter', 'cozystay' ),
					'linkedin'	=> esc_html__( 'LinkedIn', 'cozystay' ),
					'pinterest'	=> esc_html__( 'Pinterest', 'cozystay' ),
					'whatsapp' 	=> esc_html__( 'Whats App', 'cozystay' )
				);
				$wp_customize->add_section( $section_id, array(
					'title' 	=> esc_html__( 'Social Share Buttons', 'cozystay' ),
					'panel' 	=> $this->panel_id,
					'priority' 	=> $this->section_priority
				) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_social_media_group' ), array(
					'default'   		=> '',
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
				) );

				foreach ( $social_media as $id => $title ) {
					$meta_id = 'cozystay_general_social_' . $id;
					$settings[ $id ] = $this->register_meta_setting( $wp_customize, $meta_id, array( 'label' => $title ) );
				}
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_social_media_group', array(
					'type' 		=> 'multiple_checkbox',
					'label' 	=> esc_html__( 'Social Icons', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_general_social_media_group',
					'choices'	=> $settings
				) ) );
			}
		}
		/**
		* Register section 404 page
		*/
		protected function add_section_404_page( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_general_section_404_page';
			$this->section_priority += 5;
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( '404 Page', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_404_page_custom_block', array(
				'default'			=> $defaults['cozystay_general_404_page_custom_block'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'absint'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_404_page_custom_block', array(
				'type' 		=> 'select',
				'label'		=> esc_html__( 'Set a Custom 404 Page', 'cozystay' ),
				'section'	=> $section_id,
				'settings' 	=> 'cozystay_general_404_page_custom_block',
				'choices'	=> cozystay_get_pages()
			) ) );
		}
		/**
		* Register section cookie law
		*/
		protected function add_section_cookie_law( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_general_section_cookie_law';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'Cookie Law', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_cookie_law_enabled', array(
				'default'   		=> $defaults['cozystay_general_cookie_law_enabled'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_cookie_law_message', array(
				'default'   		=> $defaults['cozystay_general_cookie_law_message'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html',
				'dependency' 		=> array( 'cozystay_general_cookie_law_enabled' => array( 'value' => array( 'on' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_cookie_law_accept_button_text', array(
				'default'   		=> $defaults['cozystay_general_cookie_law_accept_button_text'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency'		=> array( 'cozystay_general_cookie_law_enabled' => array( 'value' => array( 'on' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_cookie_law_version', array(
				'default'   		=> $defaults['cozystay_general_cookie_law_version'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency'		=> array( 'cozystay_general_cookie_law_enabled' => array( 'value' => array( 'on' ) ) )
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_cookie_law_enabled', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label' 		=> esc_html__( 'Display Cookie Notice', 'cozystay' ),
				'section' 		=> $section_id,
				'settings'		=> 'cozystay_general_cookie_law_enabled'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_cookie_law_message', array(
				'type' 				=> 'mce_editor',
				'label'				=> esc_html__( 'Message', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_general_cookie_law_message',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_cookie_law_accept_button_text', array(
				'type' 				=> 'text',
				'label' 			=> esc_html__( 'Accept Button Text', 'cozystay' ),
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_general_cookie_law_accept_button_text',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_cookie_law_version', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Cookies version', 'cozystay' ),
				'description'		=> esc_html__( 'If you change your cookie policy information you can increase their version to show the popup to all visitors again.', 'cozystay' ),
				'description_below' => true,
				'section' 			=> $section_id,
				'settings' 			=> 'cozystay_general_cookie_law_version',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
		/**
		* Register section popup box
		*/
		protected function add_section_popup_box( $wp_customize ) {
			if ( cozystay_is_theme_core_activated() ) {
				$defaults = $this->defaults;
				$this->section_priority += 5;
				$section_id = 'cozystay_general_section_popup_box';

				$wp_customize->add_section( $section_id, array(
					'title' 	=> esc_html__( 'Popup Box', 'cozystay' ),
					'panel' 	=> $this->panel_id,
					'priority' 	=> $this->section_priority
				) );

				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_enable', array(
    				'default'   		=> $defaults['cozystay_general_popup_box_enable'],
    				'transport' 		=> 'postMessage',
    				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
    			) ) );
                $wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_once_per_session', array(
                    'default'           => $defaults['cozystay_general_popup_box_once_per_session'],
                    'transport'         => 'postMessage',
                    'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
                ) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_custom_block', array(
    				'default'			=> $defaults['cozystay_general_popup_box_custom_block'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'absint',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
				$wp_customize->selective_refresh->add_partial( 'cozystay_popup_content', array(
					'settings' 				=> array( 'cozystay_general_popup_box_custom_block' ),
					'selector' 				=> '.cs-popup.cs-popup-box .container',
					'render_callback' 		=> array( $this, 'popup_custom_content' ),
					'container_inclusive' 	=> true,
				) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_display_delay', array(
    				'default'			=> $defaults['cozystay_general_popup_box_display_delay'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'absint',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_size', array(
    				'default'			=> $defaults['cozystay_general_popup_box_size'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_custom_width', array(
    				'default'			=> $defaults['cozystay_general_popup_box_custom_width'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'absint',
                    'dependency'        => array(
                        'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ),
						'cozystay_general_popup_box_size' => array( 'value' => array( 'custom' ) )
                    )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_color_scheme', array(
    				'default'			=> $defaults['cozystay_general_popup_box_color_scheme'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_background_color', array(
    				'default'			=> $defaults['cozystay_general_popup_box_background_color'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'sanitize_hex_color',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_background_image', array(
    				'default'			=> $defaults['cozystay_general_popup_box_background_image'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'absint',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );
    			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_popup_box_device_visibility', array(
    				'default'			=> $defaults['cozystay_general_popup_box_device_visibility'],
    				'transport'			=> 'postMessage',
    				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
                    'dependency'        => array( 'cozystay_general_popup_box_enable' => array( 'value' => array( 'on' ) ) )
    			) ) );

    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_enable', array(
    				'type'          => 'checkbox',
    				'label_first'   => true,
    				'label'         => esc_html__( 'Enable The Popup Box', 'cozystay' ),
    				'section' 	    => $section_id,
    				'settings' 	    => 'cozystay_general_popup_box_enable'
    			) ) );
                $wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_once_per_session', array(
                    'type'              => 'checkbox',
                    'label_first'       => true,
                    'label'             => esc_html__( 'Show It Only Once Per Session', 'cozystay' ),
                    'section'           => $section_id,
                    'settings'          => 'cozystay_general_popup_box_once_per_session',
                    'active_callback'   => array( $this, 'customize_control_active_cb' )
                ) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_custom_block', array(
    				'type'              => 'select',
    				'label'             => esc_html__( 'Select Content Inside The Popup Box', 'cozystay' ),
    				'section'           => $section_id,
    				'settings' 	        => 'cozystay_general_popup_box_custom_block',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
    				'choices'           => cozystay_get_custom_post_type(),
					'description' => sprintf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom blocks first.', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_blocks' ) ),
						'</a>'
					)
    			) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_display_delay', array(
    				'type'              => 'select',
    				'label'             => esc_html__( 'Display', 'cozystay' ),
    				'section'           => $section_id,
    				'settings' 	        => 'cozystay_general_popup_box_display_delay',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
    				'choices'           => array(
                        '0'     => esc_html__( 'Immediately', 'cozystay' ),
                        '5'     => esc_html__( 'After 5 seconds', 'cozystay' ),
                        '10'    => esc_html__( 'After 10 seconds', 'cozystay' ),
                        '15'    => esc_html__( 'After 15 seconds', 'cozystay' ),
                        '20'    => esc_html__( 'After 20 seconds', 'cozystay' )
                    )
    			) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_size', array(
    				'type'		        => 'select',
    				'label' 			=> esc_html__( 'Box Size', 'cozystay' ),
    				'section'  			=> $section_id,
    				'settings' 			=> 'cozystay_general_popup_box_size',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
					'choices'           => array(
                        'fullscreen' => esc_html__( 'Fullscreen', 'cozystay' ),
                        'custom' => esc_html__( 'Custom Width x Auto Height', 'cozystay' )
                    )
    			) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_custom_width', array(
					'type' 				=> 'number_with_unit',
					'label' 			=> esc_html__( 'Custom Width', 'cozystay' ),
					'after_text' 		=> esc_html( 'px', 'cozystay' ),
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_general_popup_box_custom_width',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_color_scheme', array(
    				'type'              => 'select',
    				'label'             => esc_html__( 'Color Scheme', 'cozystay' ),
    				'section'           => $section_id,
    				'settings' 	        => 'cozystay_general_popup_box_color_scheme',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
    				'choices'           => array(
                        'light-color' => esc_html__( 'Light', 'cozystay' ),
                        'dark-color'  => esc_html__( 'Dark', 'cozystay' )
                    )
    			) ) );
    			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_general_popup_box_background_color', array(
    				'label'				=> esc_html__( 'Background Color', 'cozystay' ),
    				'section' 			=> $section_id,
    				'settings'			=> 'cozystay_general_popup_box_background_color',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' )
    			) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_background_image', array(
    				'type'              => 'image_id',
    				'label'             => esc_html__( 'Background Image', 'cozystay' ),
    				'section'           => $section_id,
    				'settings' 	        => 'cozystay_general_popup_box_background_image',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' )
    			) ) );
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_popup_box_device_visibility', array(
    				'type'          => 'select',
    				'label'         => esc_html__( 'Responsive', 'cozystay' ),
    				'section' 	    => $section_id,
    				'settings' 	    => 'cozystay_general_popup_box_device_visibility',
    				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
					'choices'           => array(
						'all' => esc_html__( 'Show on all devices', 'cozystay' ),
						'desktop' => esc_html__( 'Hide on Mobile', 'cozystay' ),
						'mobile' => esc_html__( 'Hide on Desktop', 'cozystay' ),
                    )
    			) ) );
			}
		}
		/**
		* Register section search
		*/
		protected function add_section_search( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_general_section_search';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'Search', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_general_search_default_post_types', array(
				'default'   		=> $defaults['cozystay_general_search_default_post_types'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_mutiple_choices'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_general_search_default_post_types', array(
				'type' 		=> 'multiple_selection',
				'label' 	=> esc_html__( 'Post Types to Search', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_general_search_default_post_types',
				'choices' 	=> cozystay_get_post_types()
			) ) );
		}
		/**
		* Register section menu anchors
		*/
		protected function add_section_menu_anchors( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$wp_customize->add_section( 'cozystay_general_section_menu_anchors', array(
				'title'		=> esc_html__( 'Anchors In the Nav Menu', 'cozystay' ),
				'panel' 	=> $this->panel_id,
				'priority' 	=> $this->section_priority
			) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_enable_onepage_menu_check', array(
				'default'			=> $defaults['cozystay_enable_onepage_menu_check'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_enable_onepage_menu_check', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Solve Multiple Highlighting in Nav Menu', 'cozystay' ),
				'description' 	=> esc_html__( 'Please enable this option if your nav menu contains multiple anchors.', 'cozystay' ),
				'section'		=> 'cozystay_general_section_menu_anchors',
				'settings' 		=> 'cozystay_enable_onepage_menu_check',
			) ) );
		}
		/**
		* Popup custom content
		*/
		public function popup_custom_content() {
			$custom_block = cozystay_get_theme_mod( 'cozystay_general_popup_box_custom_block' ); ?>
			<div class="container"><?php
	            if ( CozyStay_Utils_Custom_Block::check_custom_block( $custom_block ) ) {
	                do_action( 'loftocean_the_custom_blocks_content', $custom_block );
	            }; ?>
			</div><?php
		}
	}
	new CozyStay_Customize_General();
}
