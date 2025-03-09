<?php
/**
* Customize section header configuration files.
*/

if ( ! class_exists( 'CozyStay_Customize_Site_Header' ) ) {
	class CozyStay_Customize_Site_Header extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_site_header';
		/**
		* Array default customize option values
		*/
		public $defaults = array();
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			$this->defaults = $cozystay_default_settings;

			$this->add_panel( $wp_customize );
			$this->add_section_header( $wp_customize );
			$this->add_section_mobile_menu( $wp_customize );

			$header_image_section = $wp_customize->get_section( 'header_image' );
			if ( ! empty( $header_image_section ) && ( $header_image_section instanceof WP_Customize_Section ) ) {
				$header_image_section->panel = $this->panel_id;
				$header_image_section->priority = 20;
			}
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( $this->panel_id, array(
				'title'    => esc_html__( 'Site Header', 'cozystay' ),
				'priority' => 10
			) );
		}
		/**
		* Register section general
		*/
		protected function add_section_header( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_site_header_section_general';
			$show_custom_block = cozystay_is_theme_core_activated();

			$wp_customize->add_section( $section_id, array(
				'title'	=> esc_html__( 'Header', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => 10
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_sticky_site_header', array(
				'default'			=> $defaults['cozystay_sticky_site_header'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_site_header', array(
				'default'   		=> $defaults['cozystay_site_header'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_default_site_header_enable_overlap', array(
				'default'   		=> $defaults['cozystay_default_site_header_enable_overlap'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency' 		=> array( 'cozystay_site_header' => array( 'value' => array( '' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_default_site_header_hide_search_icon', array(
				'default'   		=> $defaults['cozystay_default_site_header_hide_search_icon'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency' 		=> array( 'cozystay_site_header' => array( 'value' => array( '' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_default_site_header_hide_cart', array(
				'default'   		=> $defaults['cozystay_default_site_header_hide_cart'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency' 		=> array( 'cozystay_site_header' => array( 'value' => array( '' ) ) )
			) ) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_site_header_main_custom_block', array(
				'default'   		=> $defaults['cozystay_site_header_main_custom_block'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array( 'cozystay_site_header' => array( 'value' => array( 'custom' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_sticky_site_header_custom_block', array(
				'default'   		=> $defaults['cozystay_sticky_site_header_custom_block'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'cozystay_site_header' => array( 'value' => array( 'custom' ) ),
					'cozystay_sticky_site_header' => array( 'value' => array( 'always-enable', 'scroll-up-enable' ) )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_sticky_site_header', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Sticky Header', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_sticky_site_header',
				'choices' 	=> array(
					'disable'			=> esc_html__( 'No', 'cozystay' ),
					'always-enable'		=> esc_html__( 'Always sticky', 'cozystay' ),
					'scroll-up-enable'	=> esc_html__( 'Sticky when scroll up', 'cozystay' )
				)
			) ) );

			if ( $show_custom_block ) {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_header', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Site Header', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_site_header',
					'choices' 	=> array(
						'' => esc_html__( 'Default', 'cozystay' ),
						'custom' => esc_html__( 'Custom', 'cozystay' )
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_enable_overlap', array(
					'type' 				=> 'checkbox',
					'label_first'		=> true,
					'label' 			=> esc_html__( 'Enable Overlap Header', 'cozystay' ),
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_default_site_header_enable_overlap',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_hide_search_icon', array(
					'type' 				=> 'checkbox',
					'label_first'		=> true,
					'label' 			=> esc_html__( 'Hide Search Icon', 'cozystay' ),
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_default_site_header_hide_search_icon',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_hide_cart', array(
					'type' 				=> 'checkbox',
					'label_first'		=> true,
					'label' 			=> esc_html__( 'Hide Mini Cart', 'cozystay' ),
					'section' 			=> $section_id,
					'settings' 			=> 'cozystay_default_site_header_hide_cart',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );

				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_site_header_main_custom_block', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Select a Custom Site Header', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_site_header_main_custom_block',
					'choices' 	=> cozystay_get_custom_post_type( 'custom_site_headers' ),
					'active_callback'	=> array( $this, 'customize_control_active_cb' ),
					'description' => sprintf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own site header first.', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_site_headers' ) ),
						'</a>'
					)
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_sticky_site_header_custom_block', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Select Sticky Header', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_sticky_site_header_custom_block',
					'choices'	=> cozystay_get_custom_post_type( 'custom_site_headers' ),
					'active_callback' => array( $this, 'customize_control_active_cb' ),
					'description' => esc_html__( 'You can choose another custom site header as the sticky header', 'cozystay' )
				) ) );
			} else {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_enable_overlap', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Enable Overlap Header', 'cozystay' ),
					'section' 		=> $section_id,
					'settings' 		=> 'cozystay_default_site_header_enable_overlap'
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_hide_top_bar', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Hide Top Bar', 'cozystay' ),
					'section' 		=> $section_id,
					'settings' 		=> 'cozystay_default_site_header_hide_top_bar'
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_hide_search_icon', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Hide Search Icon', 'cozystay' ),
					'section' 		=> $section_id,
					'settings' 		=> 'cozystay_default_site_header_hide_search_icon'
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_default_site_header_hide_cart', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Hide Mini Cart', 'cozystay' ),
					'section' 		=> $section_id,
					'settings' 		=> 'cozystay_default_site_header_hide_cart'
				) ) );
			}
		}
		/**
		* Register section fullscreen/mobile menu
		*/
		protected function add_section_mobile_menu( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_site_header_section_mobile_menu';

			$wp_customize->add_section( $section_id, array(
				'title'	=> esc_html__( 'Fullscreen/Mobile Menu', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => 30
			) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_notes', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );

			if ( cozystay_is_theme_core_activated() ) {
				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_menu', array(
					'default'   		=> $defaults[ 'cozystay_mobile_site_header_menu' ],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'absint'
				) ) );
				$wp_customize->selective_refresh->add_partial( 'cozystay_mobile_menu_content', array(
					'settings' 				=> array( 'cozystay_mobile_site_header_menu', 'cozystay_mobile_site_header_copyright_text', 'cozystay_mobile_site_header_hide_default_close_button' ),
					'selector' 				=> '.sidemenu',
					'render_callback' 		=> array( $this, 'get_mobile_menu' ),
					'container_inclusive' 	=> true,
				) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_copyright_text', array(
					'default'   		=> $defaults['cozystay_mobile_site_header_copyright_text'],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html',
					'dependency'		=> array( 'cozystay_mobile_site_header_menu' => array( 'value' => array( '0' ) ) )
				) ) );
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_hide_default_close_button', array(
					'default'   		=> $defaults['cozystay_mobile_site_header_hide_default_close_button'],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
					'dependency'		=> array( 'cozystay_mobile_site_header_menu' => array( 'value' => array( '0', '' ), 'operator' => 'not in' ) )
				) ) );
			} else {
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_copyright_text', array(
					'default'   		=> $defaults['cozystay_mobile_site_header_copyright_text'],
					'transport' 		=> 'postMessage',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html'
				) ) );
			}
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_background_color', array(
				'default'   		=> $defaults[ 'cozystay_mobile_site_header_background_color' ],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_background_image', array(
				'default'   		=> $defaults[ 'cozystay_mobile_site_header_background_image' ],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'absint'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_text_color', array(
				'default'   		=> $defaults[ 'cozystay_mobile_site_header_text_color' ],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_entrance_animation', array(
				'default'			=> $defaults[ 'cozystay_mobile_site_header_entrance_animation' ],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_width', array(
				'default'			=> $defaults[ 'cozystay_mobile_site_header_width' ],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_mobile_site_header_custom_width', array(
				'default'			=> $defaults[ 'cozystay_mobile_site_header_custom_width' ],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'absint',
				'dependency' => array(
					'cozystay_mobile_site_header_width' => array( 'value' => array( 'custom-width' ) )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_notes', array(
				'type' 		=> 'notes',
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_mobile_site_header_notes',
				'description' => esc_html__( 'Click the hamburger menu button to preview.', 'cozystay' )
			) ) );

			if ( cozystay_is_theme_core_activated() ) {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_menu', array(
					'type' 		=> 'select',
					'label' 	=> esc_html__( 'Select Fullscreen/Mobile Menu', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_mobile_site_header_menu',
					'choices' 	=> cozystay_get_custom_post_type( 'custom_blocks' ),
					'description' => sprintf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom blocks first.', 'cozystay' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_blocks' ) ),
						'</a>'
					)
				) ) );
				$wp_customize->add_control ( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_copyright_text', array(
					'type' 		=> 'textarea',
					'label' 	=> esc_html__( 'Copyright Text', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_mobile_site_header_copyright_text',
					'active_callback' => array( $this, 'customize_control_active_cb' )
				) ) );
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_hide_default_close_button', array(
					'type' 				=> 'checkbox',
					'label_first'		=> true,
					'label'				=> esc_html__( 'Hide Default Close Button', 'cozystay' ),
					'section'			=> $section_id,
					'settings' 			=> 'cozystay_mobile_site_header_hide_default_close_button',
					'active_callback' 	=> array( $this, 'customize_control_active_cb' )
				) ) );
			} else {
				$wp_customize->add_control ( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_copyright_text', array(
					'type' 		=> 'textarea',
					'label' 	=> esc_html__( 'Copyright Text', 'cozystay' ),
					'section' 	=> $section_id,
					'settings' 	=> 'cozystay_mobile_site_header_copyright_text'
				) ) );
			}
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_mobile_site_header_background_color', array(
				'label'		=> esc_html__( 'Background Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_mobile_site_header_background_color'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_background_image', array(
				'type'		=> 'image_id',
				'label'		=> esc_html__( 'Background Image', 'cozystay' ),
				'section'	=> $section_id,
				'settings'	=> 'cozystay_mobile_site_header_background_image'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_mobile_site_header_text_color', array(
				'label'		=> esc_html__( 'Text Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_mobile_site_header_text_color'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_entrance_animation', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Entrance Animation', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_mobile_site_header_entrance_animation',
				'choices' 	=> array(
					'' => esc_html__( 'Slide From Right', 'cozystay' ),
					'slide-from-left' => esc_html__( 'Slide From Left', 'cozystay' ),
					'fade-in' => esc_html__( 'Fade In', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_width', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Width', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_mobile_site_header_width',
				'choices' 	=> array(
					'' => esc_html__( 'Default', 'cozystay' ),
					'fullwidth'	=> esc_html__( 'Fit to Screen', 'cozystay' ),
					'custom-width' => esc_html__( 'Custom', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_mobile_site_header_custom_width', array(
				'type' 		=> 'number_with_unit',
				'label' 	=> esc_html__( 'Custom Width', 'cozystay' ),
				'after_text' => esc_html__( 'px', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_mobile_site_header_custom_width',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
		/**
		* Mobile menu content
		*/
		public function get_mobile_menu() {
			if ( ! function_exists( 'cozystay_the_background_image_attrs' ) ) {
				$inc_dir = COZYSTAY_THEME_INC;
				require_once $inc_dir . 'front/functions-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/class-walker-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
			add_filter( 'cozystay_mobile_menu_class', array( $this, 'selective_refresh_mobile_menu_class' ), 999 );
			CozyStay_Utils_Custom_Block::the_mobile_menu( array( 'class' => 'show' ) );
		}
		/*
		* Mobile menu wrapper extra class for seletive refresh
		*/
		public function selective_refresh_mobile_menu_class( $class ) {
			array_push( $class, 'show' );
			return $class;
		}
	}
	new CozyStay_Customize_Site_Header();
}
