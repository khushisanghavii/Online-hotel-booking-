<?php
/**
* Customize section WooCommerce configuration files.
*/


if ( ! class_exists( 'CozyStay_Customize_WooCommerce' ) ) {
	class CozyStay_Customize_WooCommerce extends CozyStay_Customize_Configuration_Base {
		/**
		* Register customize settings/control/panel/sections for current configuration class
		* @param object
		*/
		public function register_controls( $wp_customize ) {
			$this->register_section_list( $wp_customize );
			$this->register_section_breadcumbs( $wp_customize );
			$this->register_section_sidebar( $wp_customize );
			$this->register_section_extra( $wp_customize );
		}
		/**
		* Register more controls to section Product Catelog
		*/
		public function register_section_list( $wp_customize ) {
			$section_id = 'woocommerce_product_catalog';
			global $cozystay_default_settings;

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_product_list_style', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_product_list_style'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_product_list_food_menu_style', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_product_list_food_menu_style'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency' => array(
					'cozystay_woocommerce_product_list_style' => array( 'value' => array( 'food-menu-style' ) )
				)
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_product_list_show_short_description', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_woocommerce_product_list_show_short_description' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_product_list_short_description_length', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_woocommerce_product_list_short_description_length' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'absint',
				'dependency' => array(
					'cozystay_woocommerce_product_list_show_short_description' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_product_list_style', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Style', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_woocommerce_product_list_style',
				'choices' 	=> array(
					'' => esc_html__( 'Default', 'cozystay' ),
					'food-menu-style' => esc_html__( 'Food Menu Style', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_product_list_food_menu_style', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Food Menu Style', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_woocommerce_product_list_food_menu_style',
				'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
				'choices' 	=> array(
					'food-menu-style-1' => esc_html__( 'Style 1', 'cozystay' ),
					'food-menu-style-2' => esc_html__( 'Style 2', 'cozystay' ),
					'food-menu-style-3' => esc_html__( 'Style 3', 'cozystay' ),
					'food-menu-style-4' => esc_html__( 'Style 4', 'cozystay' ),
					'food-menu-style-5' => esc_html__( 'Style 5', 'cozystay' ),
					'food-menu-style-6' => esc_html__( 'Style 6', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_product_list_show_short_description', array(
				'type'        	=> 'checkbox',
				'label_first' 	=> true,
				'label' 		=> esc_html__( 'Show Short Description', 'cozystay' ),
				'section' 		=> $section_id,
				'settings' 		=> 'cozystay_woocommerce_product_list_show_short_description'
			 ) ) );
			 $wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_product_list_short_description_length', array(
 				'type' 		=> 'number',
 				'label' 	=> esc_html__( 'Short Description Length', 'cozystay' ),
 				'section' 	=> $section_id,
 				'settings' 	=> 'cozystay_woocommerce_product_list_short_description_length',
				'input_attrs' => array( 'min' => 1 ),
				'active_callback' 	=> array( $this, 'customize_control_active_cb' ),
 			) ) );
		}
		/**
		* Register section breadcrumb
		*/
		protected function register_section_breadcumbs( $wp_customize ) {
			global $cozystay_default_settings;
            $panel_id = 'woocommerce';
			$section_id = 'cozystay_section_woocommerce_breadcrumbs';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'Breadcrumbs', 'cozystay' ),
				'priority' 	=> 100,
                'panel' => $panel_id
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_breadcrumb', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_woocommerce_breadcrumb' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
            $wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_yoast_seo_breadcrumb', array(
				'default'   		=> $cozystay_default_settings[ 'cozystay_woocommerce_yoast_seo_breadcrumb' ],
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox',
                'dependency' => array( 'cozystay_woocommerce_breadcrumb' => array( 'value' => array( 'on' ) ) )
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_breadcrumb', array(
				'type'          => 'checkbox',
                'label_first'   => true,
				'label'         => esc_html__( 'Show Breadcrumbs on WooCommerce Related Pages', 'cozystay' ),
				'section'       => $section_id,
				'settings' 	    => 'cozystay_woocommerce_breadcrumb'
			) ) );

            if ( cozystay_is_yoast_seo_activated() ) {
    			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_yoast_seo_breadcrumb', array(
    				'type'              => 'checkbox',
					'label_first'	    => true,
    				'label' 			=> esc_html__( 'Show Yoast SEO Breadcrumbs Instead of WooCoomerce\'s', 'cozystay' ),
    				'section' 			=> $section_id,
    				'settings' 			=> 'cozystay_woocommerce_yoast_seo_breadcrumb',
    				'active_callback' 	=> array( $this, 'customize_control_active_cb' )
	             ) ) );
             }
		}
		/**
		* Regsiter section sidebar
		*/
		protected function register_section_sidebar( $wp_customize ) {
			global $cozystay_default_settings;
            $panel_id = 'woocommerce';
			$section_id = 'cozystay_section_woocommerce_sidebar';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'Sidebar Layout', 'cozystay' ),
				'priority' 	=> 110,
                'panel' => $panel_id
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_archive_layout', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_archive_layout'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_single_layout', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_single_layout'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_archive_layout', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Archive Page Sidebar Layout', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_woocommerce_archive_layout',
				'choices' 	=> array(
					'' 						=> esc_html__( 'No Sidebar', 'cozystay' ),
					'with-sidebar-left' 	=> esc_html__( 'Left Sidebar', 'cozystay' ),
					'with-sidebar-right' 	=> esc_html__( 'Right Sidebar', 'cozystay' )
				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_single_layout', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Single Product Sidebar Layout', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_woocommerce_single_layout',
				'choices' 	=> array(
					'' 						=> esc_html__( 'No Sidebar', 'cozystay' ),
					'with-sidebar-left' 	=> esc_html__( 'Left Sidebar', 'cozystay' ),
					'with-sidebar-right' 	=> esc_html__( 'Right Sidebar', 'cozystay' )
				)
			) ) );
		}
		/**
		* Regsiter section extra
		*/
		protected function register_section_extra( $wp_customize ) {
			global $cozystay_default_settings;
            $panel_id = 'woocommerce';
			$section_id = 'cozystay_section_woocommerce_extra';
			$wp_customize->add_section( $section_id, array(
				'title' 	=> esc_html__( 'CS Extra', 'cozystay' ),
				'priority' 	=> 120,
                'panel' => $panel_id
			) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_single_product_hide_page_title_section', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_single_product_hide_page_title_section'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_single_product_site_header', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_single_product_site_header'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice'
			) ) );
			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_single_product_custom_site_header', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_single_product_custom_site_header'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'CozyStay_Utils_Sanitize::sanitize_choice',
				'dependency' => array(
					'cozystay_woocommerce_single_product_site_header' => array( 'value' => array( 'custom' ) )
				)
			) ) );

			$wp_customize->add_setting( new CozyStay_Customize_Setting( $wp_customize, 'cozystay_woocommerce_return_to_shop_url', array(
				'default'			=> $cozystay_default_settings['cozystay_woocommerce_return_to_shop_url'],
				'transport'			=> 'refresh',
				'sanitize_callback' => 'absint'
			) ) );

			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_single_product_hide_page_title_section', array(
				'type'			=> 'checkbox',
				'label_first'	=> true,
				'label' 		=> esc_html__( 'Hide Single Product Page Title Section', 'cozystay' ),
				'section' 		=> $section_id,
				'settings' 		=> 'cozystay_woocommerce_single_product_hide_page_title_section'
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_single_product_site_header', array(
				 'type' 	=> 'select',
				 'label' 	=> esc_html__( 'Single Product Page Site Header', 'cozystay' ),
				 'section' 	=> $section_id,
				 'settings' => 'cozystay_woocommerce_single_product_site_header',
				 'choices' 	=> array(
 					'' 			=> esc_html__( 'Default', 'cozystay' ),
 					'custom' 	=> esc_html__( 'Custom', 'cozystay' )
 				)
			) ) );
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_single_product_custom_site_header', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Select a Custom Site Header for Single Product Page', 'cozystay' ),
				'section' 	=> $section_id,
				'settings' 	=> 'cozystay_woocommerce_single_product_custom_site_header',
				'choices' 	=> cozystay_get_custom_post_type( 'custom_site_headers' ),
				'active_callback'	=> array( $this, 'customize_control_active_cb' ),
				'description' => sprintf(
					// translators: 1/2. html tag
					esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom blocks first.', 'cozystay' ),
					sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=custom_blocks' ) ),
					'</a>'
				)
			) ) );

			$pages = get_pages();
			$page_options = array( '' => esc_html__( 'Default', 'cozystay' ) );
			foreach ( $pages as $p ) {
				$page_options[ $p->ID ] = $p->post_title;
			}
			$wp_customize->add_control( new CozyStay_Customize_Control( $wp_customize, 'cozystay_woocommerce_return_to_shop_url', array(
				 'type' 	=> 'select',
				 'label' 	=> esc_html__( '"Return to shop" Button URL', 'cozystay' ),
				 'section' 	=> $section_id,
				 'settings' => 'cozystay_woocommerce_return_to_shop_url',
				 'choices' 	=> $page_options
			) ) );
		}
	}
	new CozyStay_Customize_WooCommerce();
}
