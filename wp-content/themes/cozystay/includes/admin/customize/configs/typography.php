<?php
/**
* Customize panel typography configuration files.
*/


if ( ! class_exists( 'CozyStay_Customize_Typography' ) ) {
	class CozyStay_Customize_Typography extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_typography';
		/**
		* Array default customize option values
		*/
		public $defaults = array();
		/**
		* Int current section priority
		*/
		protected $section_priority = 0;
		/**
		* Array Google Font list
		*/
		protected $fonts = false;
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			require_once COZYSTAY_THEME_INC . 'admin/customize/configs/font-family.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$this->defaults = $cozystay_default_settings;
			$this->fonts = cozystay_get_fonts();

			$this->add_panel( $wp_customize );
			$this->add_sections( $wp_customize );
			$this->add_subheading_settings( $wp_customize );
			$this->add_section_menu( $wp_customize );
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( new WP_Customize_Panel( $wp_customize, $this->panel_id, array(
				'title' 	=> esc_html__( 'Typography', 'cozystay' ),
				'priority' 	=> 50
			) ) );
		}
		/**
		* Register sections
		*/
		public function add_sections( $wp_customize ) {
			$defaults = $this->defaults;
			$sections = array(
				'heading' => array(
					'title' => esc_html__( 'Heading', 'cozystay' ),
					'description' => esc_html__( 'These settings control the typography for all heading text.', 'cozystay' ),
					'style'	=> array( 'font-family', 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
				),
				'subheading' => array(
					'title' => esc_html__( 'Subheading', 'cozystay' ),
					'style'	=> array( 'font-family', 'font-size', 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
				),
				'text' => array(
					'title' => esc_html__( 'Text', 'cozystay' ),
					'description' => esc_html__( 'These settings control the typography for all body text.', 'cozystay' ),
					'style'	=> array( 'font-family', 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
				),
				'blog' => array(
					'title' => esc_html__( 'Blog', 'cozystay' ),
					'description' => esc_html__( 'These settings control the typography for Blog (Posts).', 'cozystay' ),
					'subsections' => array(
						'title' => array(
							'title' => esc_html__( 'Blog Title', 'cozystay' ),
							'style'	=> array( 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
						),
						'content' => array(
							'title' => esc_html__( 'Post Content', 'cozystay' ),
							'style'	=> array( 'font-size', 'line-height' )
						)
					)
				),
				'secondary' => array(
					'title' => esc_html__( 'Secondary', 'cozystay' ),
					'description' => esc_html__( 'These settings control the typography for all general secondary text: post meta, breadcrumbs, shop labels, paginations, etc', 'cozystay' ),
					'style'	=> array( 'font-family', 'letter-spacing', 'text-transform', 'font-style' )
				),
				'widget_title' => array(
					'title' => esc_html__( 'Widget Title', 'cozystay' ),
					'style' => array( 'font-family', 'font-size', 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
				),
				'button_text' => array(
					'title' => esc_html__( 'Button Text', 'cozystay' ),
					'style' => array( 'font-family', 'font-size', 'font-weight', 'letter-spacing', 'text-transform' )
				)
			);

			// Sections
			foreach ( $sections as $id => $attrs ) {
				$section_id = 'cozystay_section_typography_' . $id;
				$wp_customize->add_section( $section_id, array(
					'title' => $attrs['title'],
					'panel' => $this->panel_id
				) );
				if ( ! empty( $attrs['description'] ) ) {
					$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_typography_' . $id . '_notes', array(
						'default'   		=> '',
						'transport' 		=> 'postMessage',
						'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
					) ) );
					$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_typography_' . $id . '_notes', array(
						'type' 		=> 'notes',
						'section' 	=> $section_id,
						'settings' 	=> 'cozystay_typography_' . $id . '_notes',
						'description' => $attrs['description']
					) ) );
				}
				if ( ! empty( $attrs['subsections'] ) ) {
					foreach ( $attrs['subsections'] as $subID => $sub_attrs ) {
						$setting_id = 'cozystay_typography_' . $id . '_' . $subID . '_group';
						$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $setting_id, array(
							'default'   		=> '',
							'transport' 		=> 'postMessage',
							'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
						) ) );
						$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, $setting_id, array(
							'type' 		=> 'title_only',
							'label' 	=> $sub_attrs[ 'title' ],
							'section' 	=> $section_id,
							'settings' 	=> $setting_id
						) ) );
						$this->add_settings( $wp_customize, $section_id, 'cozystay_typography_' . $id . '_' . $subID, $sub_attrs );
					}
				} else {
					$this->add_settings( $wp_customize, $section_id, 'cozystay_typography_' . $id, $attrs );
				}
			}
		}
		/**
		* Register subheading extra settings
		*/
		public function add_subheading_settings( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_section_typography_subheading';
			
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_typography_subheading_default_color', array(
				'default'   		=> $defaults['cozystay_typography_subheading_default_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_typography_subheading_custom_default_color', array(
				'default'   		=> $defaults['cozystay_typography_subheading_custom_default_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'dependency' 		=> array( 'cozystay_typography_subheading_default_color' => array( 'value' => array( 'custom' ) ) )
			) ) );


			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_typography_subheading_default_color', array(
				'type'		=> 'select',
				'priority'	=> 5,
				'label'		=> esc_html__( 'Default Color', 'cozystay' ),
				'section'	=> $section_id,
				'settings'	=> 'cozystay_typography_subheading_default_color',
				'choices'	=> array(
					'primary' => esc_html__( 'Primary Color', 'cozystay' ),
					'secondary'  => esc_html__( 'Secondary Color', 'cozystay' ),
					'custom'  => esc_html__( 'Custom Color', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_typography_subheading_custom_default_color', array(
				'section' 			=> $section_id,
				'priority'			=> 5,
				'settings'			=> 'cozystay_typography_subheading_custom_default_color',
				'active_callback'	=> array( $this, 'customize_control_active_cb' )
			) ) );
		}
		/**
		* Register section menu
		*/
		public function add_section_menu( $wp_customize ) {
			$defaults = $this->defaults;
			$section_id = 'cozystay_section_typography_menus';
			$attrs = array( 'style' => array( 'font-family', 'font-size', 'font-weight', 'letter-spacing', 'text-transform' ) );
			$sub_attrs = array( 'style' => array( 'font-size', 'font-weight', 'letter-spacing', 'text-transform' ) );
			$wp_customize->add_section( $section_id, array(
				'title' => esc_html__( 'Menus', 'cozystay' ),
				'panel' => $this->panel_id
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_typography_menus_notes', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_typography_menus_notes', array(
				'type' 		=> 'notes',
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_typography_menus_notes',
				'description' => esc_html__( 'These settings control the typography for all site navigation menus.', 'cozystay' )
			) ) );
			$this->add_settings( $wp_customize, $section_id, 'cozystay_typography_menu', $attrs );

			$setting_id = 'cozystay_typography_footer_menus_group';
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $setting_id, array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, $setting_id, array(
				'type' 		=> 'title_only',
				'label' 	=> esc_html__( 'Footer Bottom Menu', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> $setting_id
			) ) );
			$this->add_settings( $wp_customize, $section_id, 'cozystay_typography_footer_bottom_menu', $sub_attrs );
		}
		/**
		* Add settings for each section
		*/
		public function add_settings( $wp_customize, $section_id, $setting_prefix, $attrs ) {
			$defaults = $this->defaults;
			foreach ( $attrs['style'] as $style ) {
				switch ( $style ) {
					case 'font-weight':
						$this->register_font_weight( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'text-transform':
						$this->register_text_transform( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'font-style':
						$this->register_font_style( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'line-height':
						$this->register_line_height( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'letter-spacing':
						$this->register_letter_spacing( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'font-size':
						$this->register_font_size( $wp_customize, $section_id, $setting_prefix );
						break;
					case 'font-family':
						$setting_id = $setting_prefix . '_font-family';
						$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $setting_id, array(
							'default'   		=> $defaults[ $setting_id ],
							'transport' 		=> 'refresh',
							'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_html'
						) ) );
						$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, $setting_id, array(
							'type' 		=> 'font_select',
							'label' 	=> esc_html__( 'Font Family', 'cozystay' ),
							'section' 	=> $section_id,
							'settings' 	=> $setting_id,
							'choices'	=> $this->fonts
						) ) );
						break;
				}
			}
		}
		/**
		* Register customize setting
		* @param object
		* @param string setting id
		* @param string sanitize callback function
		*/
		protected function register_setting( $wp_customize, $setting_id, $sanitize_callback, $deps = false ) {
			$defaults = $this->defaults;
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $setting_id, array(
				'default'   		=> $defaults[ $setting_id ],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => $sanitize_callback,
				'dependency'		=> $deps ? $deps : array()
			) ) );
		}
		/**
		* Register font size settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_font_size( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_font-size';
			$this->register_setting( $wp_customize, $setting_id, 'absint' );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, $setting_id, array(
				'type' 			=> 'number_with_unit',
				'label' 		=> esc_html__( 'Font Size', 'cozystay' ),
				'after_text' 	=> 'px',
				'section' 		=> $section,
				'settings' 		=> $setting_id,
				'input_attrs' 	=> array( 'min' => 1, 'max' => 100 )
			) ) );
		}
		/**
		* Register font weight settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_font_weight( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_font-weight';
			$this->register_setting( $wp_customize, $setting_id, 'CozyStay_Utils_Sanitize::sanitize_choice' );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting_id, array(
				'type'	 		=> 'select',
				'label' 		=> esc_html__( 'Font Weight', 'cozystay' ),
				'description' 	=> esc_html__( 'Please note: not every font supports all the font weight values listed.', 'cozystay' ),
				'section' 		=> $section,
				'settings' 		=> $setting_id,
				'choices' 		=> array(
					'100' => 100,
					'200' => 200,
					'300' => 300,
					'400' => 400,
					'500' => 500,
					'600' => 600,
					'700' => 700,
					'800' => 800,
				)
			) ) );
		}
		/**
		* Register text transform settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_text_transform( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_text-transform';
			$this->register_setting( $wp_customize, $setting_id, 'CozyStay_Utils_Sanitize::sanitize_choice' );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting_id, array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Text Transform', 'cozystay' ),
				'section' 	=> $section,
				'settings' 	=> $setting_id,
				'choices' 	=> array(
					'none' 			=> esc_html__( 'None', 'cozystay' ),
					'uppercase' 	=> esc_html__( 'Uppercase', 'cozystay' ),
					'lowercase' 	=> esc_html__( 'Lowercase', 'cozystay' ),
					'capitalize' 	=> esc_html__( 'Capitalize', 'cozystay' )
				)
			) ) );
		}
		/**
		* Register font style settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_font_style( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_font-style';
			$this->register_setting( $wp_customize, $setting_id, 'CozyStay_Utils_Sanitize::sanitize_choice' );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting_id, array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Font Style', 'cozystay' ),
				'section' 	=> $section,
				'settings' 	=> $setting_id,
				'choices' 	=> array(
					'normal' => esc_html__( 'Normal', 'cozystay' ),
					'italic' => esc_html__( 'Italic', 'cozystay' )
				)
			) ) );
		}
		/**
		* Register line height settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_line_height( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_line-height';
			$this->register_setting( $wp_customize, $setting_id, 'sanitize_text_field' );

			$wp_customize->add_control( new WP_Customize_Control($wp_customize, $setting_id, array(
				'type' 		=> 'text',
				'label' 	=> esc_html__( 'Line Height', 'cozystay' ),
				'section' 	=> $section,
				'settings' 	=> $setting_id
			) ) );
		}
		/**
		* Register letter spacing settings
		* @param object
		* @param string section id
		* @param string setting id prefix
		* @return array
		*/
		protected function register_letter_spacing( $wp_customize, $section, $setting_id_prefix ) {
			$setting_id = $setting_id_prefix . '_letter-spacing';
			$this->register_setting( $wp_customize, $setting_id, 'CozyStay_Utils_Sanitize::sanitize_choice' );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting_id, array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Letter Spacing', 'cozystay' ),
				'section' 	=> $section,
				'settings' 	=> $setting_id,
				'choices' 	=> array(
					''			=> esc_html__( 'Default', 'cozystay' ),
					'0' 		=> '0em',
					'0.05em' 	=> '0.05em',
					'0.1em' 	=> '0.1em',
					'0.15em' 	=> '0.15em',
					'0.2em' 	=> '0.2em',
					'0.25em' 	=> '0.25em',
					'0.3em' 	=> '0.3em',
					'0.35em' 	=> '0.35em',
					'0.4em' 	=> '0.4em'
				)
			) ) );
		}
	}
	new CozyStay_Customize_Typography();
}
