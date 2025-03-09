<?php
/**
* Customize section blog configuration files.
*/


if ( ! class_exists( 'CozyStay_Customize_Blog' ) ) {
	class CozyStay_Customize_Blog extends CozyStay_Customize_Configuration_Base {
		/**
		* String panel id
		*/
		public $panel_id = 'cozystay_panel_blog';
		/**
		* Array default customize option values
		*/
		public $defaults = array();
		/**
		* Int current section priority
		*/
		protected $section_priority = 0;
		/**
		* Boolean is theme core activated
		*/
		protected $is_theme_core_activated = false;
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			global $cozystay_default_settings;
			$this->defaults = $cozystay_default_settings;
			$this->is_theme_core_activated = cozystay_is_theme_core_activated();

			$this->add_panel( $wp_customize );
			$this->add_section_blog_general( $wp_customize );
			$this->add_section_blog_page( $wp_customize );
			$this->add_section_single_post( $wp_customize );
		}
		/**
		* Register panel
		*/
		protected function add_panel( $wp_customize ) {
			$wp_customize->add_panel( $this->panel_id, array(
				'title' 	=> esc_html__( 'Blog', 'cozystay' ),
				'priority' 	=> 70
			) );
		}
		/**
		* Register section blog general
		*/
		protected function add_section_blog_general( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_blog_section_general';
			$wp_customize->add_section( $section_id, array(
				'title' => esc_html__( 'Blog General', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => $this->section_priority
			) );

			$layout_excerpt_length = array();
			$post_list_layouts = array(
				'standard' 	=> esc_html__( 'Standard', 'cozystay' ),
				'list' 		=> esc_html__( 'List', 'cozystay' ),
				'zigzag' 	=> esc_html__( 'Zigzag', 'cozystay' ),
				'grid' 		=> esc_html__( 'Grid', 'cozystay' ),
				'masonry' 	=> esc_html__( 'Masonry', 'cozystay' ),
				'overlay' 	=> esc_html__( 'Overlay', 'cozystay' )
			);

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_general_read_more_button_text', array(
				'default'			=> $defaults['cozystay_blog_general_read_more_button_text'],
				'transport'			=> 'refresh',
				'sanitize_callback'	=> 'sanitize_text_field'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_blog_general_pagination_style', array(
				'default'			=> $defaults['cozystay_blog_general_pagination_style'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_blog_general_featured_date_label', array(
				'default'			=> $defaults['cozystay_blog_general_featured_date_label'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_blog_general_load_post_metas_dynamically', array(
				'default'			=> $defaults['cozystay_blog_general_load_post_metas_dynamically'],
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );

			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_blog_general_post_excerpt_length_group', array(
				'default'   		=> '',
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_general_read_more_button_text', array(
				'type' 		=> 'text',
				'label' 	=> esc_html__( 'Read More Button Text', 'cozystay'),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_blog_general_read_more_button_text'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_general_pagination_style', array(
				'type' 		=> 'select',
				'label'		=> esc_html__( 'Pagination Style', 'cozystay' ),
				'section'	=> $section_id,
				'settings' 	=> 'cozystay_blog_general_pagination_style',
				'choices' 	=> array(
					'link-only' 	=> esc_html__( 'Next/Prev Links', 'cozystay' ),
					'link-number' 	=> esc_html__( 'With Page Number', 'cozystay' ),
					'ajax-manual' 	=> esc_html__( 'Load More Button', 'cozystay' ),
					'ajax-auto'		=> esc_html__( 'Infinite Scroll', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_general_featured_date_label', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Featured Date Label', 'cozystay' ),
				'description_below' => true,
				'section'		=> $section_id,
				'settings' 		=> 'cozystay_blog_general_featured_date_label',
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_general_load_post_metas_dynamically', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Load Post Metas ( like count ) Dynamically with AJAX', 'cozystay' ),
				'description' 	=> esc_html__( 'Recommend enabling this option if any caching plugins are used on your site.', 'cozystay' ),
				'description_below' => true,
				'section'		=> $section_id,
				'settings' 		=> 'cozystay_blog_general_load_post_metas_dynamically',
			) ) );

			foreach ( $post_list_layouts as $layout => $label ) {
				$layout_id = 'cozystay_blog_general_layout_' . $layout . '_post_excerpt_length';
				$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, $layout_id, array(
					'default'   		=> $defaults[ $layout_id ],
					'transport' 		=> 'refresh',
					'sanitize_callback' => 'absint'
				) ) );
				array_push( $layout_excerpt_length, new CozyStay_Customize_Control( $wp_customize, $layout_id, array(
						'type'			=> 'number_slider',
						'label' 		=> $label,
						'after_text'	=> esc_html__( 'words', 'cozystay' ),
						'section' 		=> $section_id,
						'settings' 		=> $layout_id,
						'input_attrs'	=> array(
							'data-min'	=> '10',
							'data-max'	=> '60',
							'data-step'	=> '5'
						)
					) )
				);
			}
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_general_post_excerpt_length_group', array(
				'type' 		=> 'group',
				'label' 	=> esc_html__( 'Post Excerpt Length', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_blog_general_post_excerpt_length_group',
				'children'	=> $layout_excerpt_length
			) ) );
		}
		/**
		* Register section blog page
		*/
		protected function add_section_blog_page( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_blog_section_page';
			$wp_customize->add_section( $section_id, array(
				'title' => esc_html__( 'Blog Page', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_layout', array(
				'default'			=> $defaults['cozystay_blog_page_layout'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_post_list_layout', array(
				'default'   		=> $defaults['cozystay_blog_page_post_list_layout'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_overlay_image_ratio', array(
				'default'   		=> $defaults['cozystay_blog_page_overlay_image_ratio'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency'		=> array(
					'cozystay_blog_page_post_list_layout' => array( 'value' => array( 'overlay-2cols', 'overlay-3cols' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_image_ratio', array(
				'default'   		=> $defaults['cozystay_blog_page_image_ratio'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency'		=> array(
					'cozystay_blog_page_post_list_layout' => array( 'value' => array( 'grid-2cols', 'grid-3cols', 'list', 'zigzag' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_center_text', array(
				'default'   		=> $defaults['cozystay_blog_page_center_text'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_list_zigzag_with_border', array(
				'default'   		=> $defaults['cozystay_blog_page_list_zigzag_with_border'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
				'dependency'		=> array( 'cozystay_blog_page_post_list_layout' => array( 'value' => array( 'list', 'zigzag' ) ) )
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_show_post_excerpt', array(
				'default'   		=> $defaults['cozystay_blog_page_show_post_excerpt'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_show_read_more_button', array(
				'default'   		=> $defaults['cozystay_blog_page_show_read_more_button'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_page_post_meta_group', array(
				'default'   		=> '',
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'posts_per_page', array(
				'type'				=> 'option',
				'default'   		=> get_option( 'posts_per_page', 10 ),
				'transport'			=> 'refresh',
				'sanitize_callback' => 'absint'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_layout', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Sidebar Layout', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_blog_page_layout',
				'choices' 	=> array(
					'' 						=> esc_html__( 'No Sidebar', 'cozystay' ),
					'with-sidebar-left' 	=> esc_html__( 'Left Sidebar', 'cozystay' ),
					'with-sidebar-right' 	=> esc_html__( 'Right Sidebar', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_post_list_layout', array(
				'type'		=> 'select',
				'label'		=> esc_html__( 'Posts Style', 'cozystay' ),
				'settings'	=> 'cozystay_blog_page_post_list_layout',
				'section'	=> $section_id,
				'choices'	=> array(
					'standard'		=> esc_html__( 'Standard', 'cozystay' ),
					'list'			=> esc_html__( 'List', 'cozystay' ),
					'zigzag'		=> esc_html__( 'ZigZag', 'cozystay' ),
					'grid-2cols'	=> esc_html__( 'Grid 2 Columns', 'cozystay' ),
					'grid-3cols'	=> esc_html__( 'Grid 3 Columns', 'cozystay' ),
					'masonry-2cols'	=> esc_html__( 'Masonry 2 Columns', 'cozystay' ),
					'masonry-3cols'	=> esc_html__( 'Masonry 3 Columns', 'cozystay' ),
					'overlay-2cols'	=> esc_html__( 'Overlay 2 Columns', 'cozystay' ),
					'overlay-3cols'	=> esc_html__( 'Overlay 3 Columns', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_overlay_image_ratio', array(
				'type'		=> 'select',
				'label'		=> esc_html__( 'Image Ratio', 'cozystay' ),
				'settings'	=> 'cozystay_blog_page_overlay_image_ratio',
				'section'	=> $section_id,
				'choices'	=> array(
					'img-ratio-3-2' => esc_html__( '3:2', 'cozystay' ),
					'img-ratio-4-3' => esc_html__( '4:3', 'cozystay' ),
					'img-ratio-1-1' => esc_html__( '1:1', 'cozystay' ),
					'img-ratio-4-5' => esc_html__( '4:5', 'cozystay' ),
					'img-ratio-2-3' => esc_html__( '2:3', 'cozystay' )
				),
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_image_ratio', array(
				'type'		=> 'select',
				'label'		=> esc_html__( 'Image Ratio', 'cozystay' ),
				'settings'	=> 'cozystay_blog_page_image_ratio',
				'section'	=> $section_id,
				'choices'	=> array(
					'img-ratio-3-2' => esc_html__( '3:2', 'cozystay' ),
					'img-ratio-4-3' => esc_html__( '4:3', 'cozystay' ),
					'img-ratio-1-1' => esc_html__( '1:1', 'cozystay' ),
					'img-ratio-4-5' => esc_html__( '4:5', 'cozystay' ),
					'img-ratio-2-3' => esc_html__( '2:3', 'cozystay' )
				),
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_center_text', array(
				'type'			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Center Text', 'cozystay' ),
				'settings'		=> 'cozystay_blog_page_center_text',
				'section'		=> $section_id
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_list_zigzag_with_border', array(
				'type'				=> 'checkbox',
				'label_first'		=> true,
				'label'				=> esc_html__( 'With Border', 'cozystay' ),
				'settings'			=> 'cozystay_blog_page_list_zigzag_with_border',
				'section'			=> $section_id,
				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_show_post_excerpt', array(
				'type'			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Show Post Excerpt', 'cozystay' ),
				'settings'		=> 'cozystay_blog_page_show_post_excerpt',
				'section'		=> $section_id
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_show_read_more_button', array(
				'type'			=> 'checkbox',
				'label_first'	=> true,
				'label'			=> esc_html__( 'Show Read More Button', 'cozystay'),
				'settings'		=> 'cozystay_blog_page_show_read_more_button',
				'section'		=> $section_id
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_page_post_meta_group', array(
				'type' 		=> 'multiple_checkbox',
				'label' 	=> esc_html__( 'Display Selected Post Meta:', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_blog_page_post_meta_group',
				'choices' 	=> $this->register_post_meta_settings( $wp_customize )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'posts_per_page', array(
				'type' 			=> 'number',
				'label' 		=> esc_html__( 'Number of Posts Displayed Per Page', 'cozystay' ),
				'input_attrs' 	=> array( 'min' => 1 ),
				'section' 		=> $section_id,
				'settings' 		=> 'posts_per_page'
			) ) );
		}
		/**
		* Register section single_post
		*/
		protected function add_section_single_post( $wp_customize ) {
			$defaults = $this->defaults;
			$this->section_priority += 5;
			$section_id = 'cozystay_blog_section_single';
			$wp_customize->add_section( $section_id, array(
				'title' => esc_html__( 'Single Post', 'cozystay' ),
				'panel' => $this->panel_id,
				'priority' => $this->section_priority
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_header_title', array(
				'default'   		=> '',
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_title_section_size', array(
				'default'   		=> $defaults['cozystay_blog_single_post_title_section_size'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new WP_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_title_default_background_color', array(
				'default'   		=> $defaults['cozystay_blog_single_post_title_default_background_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_title_default_text_color', array(
				'default'   		=> $defaults['cozystay_blog_single_post_title_default_text_color'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_meta_items_color', array(
				'default'   		=> $defaults['cozystay_blog_single_post_meta_items_color'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_show_yoast_seo_breadcrumb', array(
				'default'   		=> $defaults['cozystay_blog_single_post_show_yoast_seo_breadcrumb'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_layout', array(
				'default'   		=> $defaults['cozystay_blog_single_post_layout'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_header_meta_group', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_footer_title', array(
				'default'   		=> '',
				'transport'			=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_footer_meta_group', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_page_footer_show_social_sharings', array(
				'default'   		=> $defaults['cozystay_blog_single_post_page_footer_show_social_sharings'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_page_footer_show_author_info_box', array(
				'default'   		=> $defaults['cozystay_blog_single_post_page_footer_show_author_info_box'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_page_footer_show_navigation', array(
				'default'   		=> $defaults['cozystay_blog_single_post_page_footer_show_navigation'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_related_post_group', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_empty'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_enable_related_posts', array(
				'default'   		=> $defaults['cozystay_blog_single_post_enable_related_posts'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_related_post_section_title', array(
				'default'   		=> $defaults['cozystay_blog_single_post_related_post_section_title'],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency'		=> array(
					'cozystay_blog_single_post_enable_related_posts' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_blog_single_post_related_post_filter', array(
				'default'   		=> $defaults['cozystay_blog_single_post_related_post_filter'],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency'		=> array(
					'cozystay_blog_single_post_enable_related_posts' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_header_title', array(
				'type' 		=> 'title_only',
				'label' 	=> esc_html__( 'Post Header Section', 'cozystay' ),
				'settings'	=> 'cozystay_blog_single_post_header_title',
				'section'	=> $section_id
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_title_section_size', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Default Header Layout', 'cozystay' ),
				'settings'	=> 'cozystay_blog_single_post_title_section_size',
				'section'	=> $section_id,
				'choices' 	=> array(
					'' 						=> esc_html__( 'Default', 'cozystay' ),
					'page-title-small' 		=> esc_html__( 'Small', 'cozystay' ),
					'page-title-default'	=> esc_html__( 'Medium', 'cozystay' ),
					'page-title-large'		=> esc_html__( 'Large', 'cozystay' ),
					'page-title-fullheight' => esc_html__( 'Screen Height', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_blog_single_post_title_default_background_color', array(
				'label'		=> esc_html__( 'Default Background Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_blog_single_post_title_default_background_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_blog_single_post_title_default_text_color', array(
				'label'		=> esc_html__( 'Default Text Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_blog_single_post_title_default_text_color'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cozystay_blog_single_post_meta_items_color', array(
				'label'		=> esc_html__( 'Meta Items Color', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_blog_single_post_meta_items_color'
			) ) );
			if ( cozystay_is_yoast_seo_activated() ) {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_show_yoast_seo_breadcrumb', array(
					'type'			=> 'checkbox',
					'label_first'	=> true,
					'label'			=> esc_html__( 'Display Yoast SEO Breadcrumb', 'cozystay' ),
					'section' 		=> $section_id,
					'settings'		=> 'cozystay_blog_single_post_show_yoast_seo_breadcrumb',
				) ) );
			}
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_layout', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Default Sidebar Layout', 'cozystay' ),
				'settings'	=> 'cozystay_blog_single_post_layout',
				'section'	=> $section_id,
				'choices' 	=> array(
					'' 						=> esc_html__( 'No sidebar', 'cozystay' ),
					'with-sidebar-left' 	=> esc_html__( 'Left sidebar', 'cozystay' ),
					'with-sidebar-right'	=> esc_html__( 'Right sidebar', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_header_meta_group', array(
				'type' 			=> 'multiple_checkbox',
				'label' 		=> esc_html__( 'Header Meta', 'cozystay' ),
				'description' 	=> esc_html__( 'Display selected meta in post header', 'cozystay' ),
				'section'		=> $section_id,
				'settings' 		=> 'cozystay_blog_single_post_header_meta_group',
				'choices'		=> $this->get_header_meta_settings( $wp_customize )
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_footer_title', array(
				'type' 		=> 'title_only',
				'label' 	=> esc_html__( 'Post Footer Section', 'cozystay' ),
				'settings'	=> 'cozystay_blog_single_post_footer_title',
				'section'	=> $section_id
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_footer_meta_group', array(
				'type' 		=> 'multiple_checkbox',
				'label' 	=> esc_html__( 'Footer Meta', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_blog_single_post_footer_meta_group',
				'choices'	=> $this->get_footer_meta_settings( $wp_customize )
			) ) );

			if ( $this->is_theme_core_activated ) {
				$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_page_footer_show_social_sharings', array(
					'type' 			=> 'checkbox',
					'label_first'	=> true,
					'label' 		=> esc_html__( 'Show Social Media Sharing Buttons', 'cozystay' ),
					'section' 		=> $section_id,
					'settings' 		=> 'cozystay_blog_single_post_page_footer_show_social_sharings'
				) ) );
			}
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_page_footer_show_author_info_box', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label' 		=> esc_html__( 'Display Author Info Box', 'cozystay' ),
				'section' 		=> $section_id,
				'settings' 		=> 'cozystay_blog_single_post_page_footer_show_author_info_box'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_page_footer_show_navigation', array(
				'type' 			=> 'checkbox',
				'label_first'	=> true,
				'label' 		=> esc_html__( 'Display Post Pagination', 'cozystay' ),
				'section' 		=> $section_id,
				'settings' 		=> 'cozystay_blog_single_post_page_footer_show_navigation'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_related_post_group', array(
				'type' 		=> 'group',
				'label' 	=> esc_html__( 'Related Posts', 'cozystay' ),
				'section' 	=> $section_id,
				'settings'	=> 'cozystay_blog_single_post_related_post_group',
				'children'	=> array(
					new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_enable_related_posts', array(
						'type' 			=> 'checkbox',
						'label_first'	=> true,
						'label' 		=> esc_html__( 'Display Related Posts', 'cozystay' ),
						'section' 		=> $section_id,
						'settings'		=> 'cozystay_blog_single_post_enable_related_posts'
					) ),
					new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_related_post_section_title', array(
						'type' 				=> 'text',
						'label' 			=> esc_html__( 'Related Posts Title', 'cozystay' ),
						'input_attrs' 		=> array( 'placeholder' => esc_html__( 'e.g. Other Posts You May Enjoy', 'cozystay' ) ),
						'section' 			=> $section_id,
						'settings' 			=> 'cozystay_blog_single_post_related_post_section_title',
						'active_callback' 	=> array( $this, 'customize_control_active_cb' )
					) ),
					new CozyStay_Customize_Control( $wp_customize, 'cozystay_blog_single_post_related_post_filter', array(
						'type' 				=> 'select',
						'label' 			=> esc_html__( 'Pick Posts by', 'cozystay' ),
						'section' 			=> $section_id,
						'settings' 			=> 'cozystay_blog_single_post_related_post_filter',
						'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
						'choices' 			=> array(
							'category' 	=> esc_html__( 'Category', 'cozystay' ),
							'tag'	 	=> esc_html__( 'Tag', 'cozystay' ),
							'author' 	=> esc_html__( 'Author', 'cozystay' )
						)
					) )
				)
			) ) );
		}
		/**
		* Register post meta settings
		* @param object
		* @param string page
		* @return array
		*/
		protected function register_post_meta_settings( $wp_customize ) {
			$list = array();
			$metas = array(
				'author'			=> esc_html__( 'Author', 'cozystay' ),
				'category' 			=> esc_html__( 'Category', 'cozystay' ),
				'publish_date'		=> esc_html__( 'Publish Date', 'cozystay' ),
				'comment_counter'	=> esc_html__( 'Comment Counts', 'cozystay' ),
			);
			foreach ( $metas as $mid => $title ) {
				$meta_id = 'cozystay_blog_page_show_' . $mid;
				$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, $meta_id, array(
					'default'   		=> $this->defaults[ $meta_id ],
					'transport'			=> 'refresh',
					'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
				) ) );

				$list[ $mid ] = array( 'value' => 'on', 'label' => $title, 'setting' => $meta_id );
			}
			return $list;
		}
		/**
		* Get header meta settings
		* @param object
		* @return array
		*/
		protected function get_header_meta_settings( $wp_customize ) {
			$settings = array();
			$metas = array(
				'author'			=> esc_html__( 'Author', 'cozystay' ),
				'category' 			=> esc_html__( 'Categories', 'cozystay' ),
				'publish_date' 		=> esc_html__( 'Publish Date', 'cozystay' ),
				'comment_counter'	=> esc_html__( 'Comment Counts', 'cozystay' )
			);
			foreach ( $metas as $id => $title ) {
				$meta_id = 'cozystay_blog_single_post_page_header_show_' . $id;
				$settings[ $id ] = $this->register_meta_setting( $wp_customize, $meta_id, array( 'label' => $title ) );
			}
			return $settings;
		}
		/**
		* Get footer meta settings
		* @param object
		* @return array
		*/
		protected function get_footer_meta_settings( $wp_customize ) {
			$settings = array();
			$metas = array(
				'tags' => esc_html__( 'Tags', 'cozystay' )
			);
			foreach ( $metas as $id => $title ) {
				$meta_id = 'cozystay_blog_single_post_page_footer_show_' . $id;
				$settings[ $id] = $this->register_meta_setting( $wp_customize, $meta_id, array( 'label' => $title ) );
			}
			return $settings;
		}
	}
	new CozyStay_Customize_Blog();
}
