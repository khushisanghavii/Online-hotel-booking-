<?php
if ( ! class_exists( 'CozyStay_Theme_Core_Filters' ) ) {
    class CozyStay_Theme_Core_Filters {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'loftocean_get_widget_title', array( $this, 'change_widget_title' ), 99, 2 );
            add_filter( 'loftocean_get_widget_class', array( $this, 'change_widget_class' ), 99, 2 );
            add_filter( 'loftocean_instagram_widget_class', array( $this, 'instagram_widget_class' ) );
            add_filter( 'loftocean_get_image_sizes', array( $this, 'get_image_sizes' ), 99, 2 );
            add_filter( 'loftocean_get_image_size', array( $this, 'get_image_size' ), 99, 2 );
            add_filter( 'loftocean_front_has_social_menu', array( $this, 'has_social_menu' ), 99, 1 );
            add_filter( 'cozystay_content_class', array( $this, 'content_class' ), 9999999 );
            add_filter( 'loftocean_single_room_use_custom_template', array( $this, 'is_single_room_custom_template' ) );
            add_filter( 'loftocean_room_top_section', array( $this, 'get_single_room_default_top_section' ) );
            add_filter( 'loftocean_room_availability_calendar_section_title', array( $this, 'get_single_room_availability_calendar_section_title' ) );
            add_filter( 'loftocean_room_reservation_form_hide_fields', array( $this, 'check_reservation_form_fields' ) );

            add_filter( 'loftocean_translate_page_options', array( $this, 'translate_pages' ) );
            add_filter( 'loftocean_translate_attachment_options', array( $this, 'translate_attachments' ) );
            add_filter( 'loftocean_translate_taxonomy', array( $this, 'translate_taxonomy' ) );
            add_filter( 'loftocean_translate_mc4wp_forms', array( $this, 'translate_mc4wp_form_settings' ) );
            add_filter( 'loftocean_translate_mc4wp_form',  array( $this, 'translate_mc4wp_form' ) );

			add_filter( 'loftocean_get_primary_color', array( $this, 'get_primary_color' ) );
            add_filter( 'loftocean_is_loading_post_meta_by_ajax', array( $this, 'load_post_metas_by_ajax' ) );
            add_filter( 'loftocean_front_post_list_args', array( $this, 'post_list_args' ), 99 );
            add_filter( 'loftocean_front_get_opening_hours', array( $this, 'get_opening_hours' ) );
            add_filter( 'loftocean_front_get_restaurant_info', array( $this, 'get_restaurant_info' ) );
            add_filter( 'dynamic_sidebar_params', array( $this, 'check_sidebar_params' ) );

    		add_filter( 'loftocean_enable_media_lazy_load', '__return_false', 1 );
    		add_filter( 'loftocean_disable_media_preload', '__return_true', 1 );

            add_filter( 'loftocean_elementor_category_title', array( $this, 'element_category_title' ) );
            add_filter( 'loftocean_elementor_widget_name', array( $this, 'change_elementor_widget_name' ), 10, 2 );

            add_filter( 'loftocean_elementor_days', array( $this, 'get_count_down_label_days' ) );
            add_filter( 'loftocean_elementor_hours', array( $this, 'get_count_down_label_hours' ) );
            add_filter( 'loftocean_elementor_minutes', array( $this, 'get_count_down_label_minutes' ) );
            add_filter( 'loftocean_elementor_seconds', array( $this, 'get_count_down_label_seconds' ) );

            add_filter( 'loftocean_room_readmore_button_text', array( $this, 'get_room_readmore_text' ) );
            add_filter( 'loftocean_room_enable_availibility_calendar', array( $this, 'enable_room_availibility_calendar' ) );

            add_action( 'loftocean_front_the_social_menu', array( $this, 'the_social_menu' ), 10, 1 );
            add_action( 'cozystay_image_loading_attributes', array( $this, 'image_loading_attributes' ) );
            add_action( 'loftocean_elementor_navigation_menu', array( $this, 'the_navigation_menu' ), 10, 2 );

            add_action( 'loftocean_elementor_loaded', array( $this, 'elementor_loaded' ) );
            add_action( 'cozystay_after_site_footer', array( $this, 'after_site_footer' ) );
        }
		/**
		* Get primary for gutenberg
		*/
		public function get_primary_color( $color ) {
			return cozystay_get_theme_mod( 'cozystay_primary_accent_color' );
		}
        /**
        * Condition function if have social menu
        * @param boolean
        * @return boolean
        */
        public function has_social_menu( $has ) {
            return cozystay_has_nav_menu( 'social-menu' );
        }
        /**
        * Output the social menu for widget profile
        * @param string
        */
        public function the_social_menu( $id ) {
            cozystay_social_menu( array(
                'container' => 'div',
                'container_class' => 'socialwidget',
                'menu_id' => $id . '-social-menu',
                'menu_class' => 'social-nav menu'
            ) );
        }
        /**
        * Change widget title
        */
        public function change_widget_title( $title, $args ) {
            if ( ! empty( $args ) && ! empty( $args['id' ] ) ) {
                switch ( $args['id'] ) {
                    case 'profile':
                        return esc_html__( 'CozyStay Profile', 'cozystay' );
                    case 'facebook':
                        return esc_html__( 'CozyStay Facebook', 'cozystay' );
                    case 'instagram':
                        return esc_html__( 'CozyStay Instagram', 'cozystay' );
                    case 'social':
                        return esc_html__( 'CozyStay Social', 'cozystay' );
                    case 'opening-hours':
                        return esc_html__( 'CozyStay Opening Hours', 'cozystay' );
                    case 'category':
                        return esc_html__( 'CozyStay Category', 'cozystay' );
                    case 'posts':
                        return esc_html__( 'CozyStay Posts', 'cozystay' );
                }
            }
            return $title;
        }
        /**
        * Change widget class name
        */
        public function change_widget_class( $class, $args ) {
            if ( ! empty( $args ) && ! empty( $args['id' ] ) ) {
                switch ( $args['id'] ) {
                    case 'profile':
                        return 'cs-widget_about';
                    case 'instagram':
                        return 'cs-widget_instagram';
                    case 'social':
                        return 'cs-widget_social';
                    case 'opening-hours':
                        return 'cs-widget_opening_hours';
                    case 'category':
                        return 'cs-widget_cat';
                    case 'posts':
                        return 'cs-widget_posts';
                }
            }
            return $class;
        }
        /**
        * Get the instagram widget wrapper div classname
        */
        public function instagram_widget_class( $class ) {
            return 'cs-widget_instagram';
        }
        /**
        * Get image sizes
        */
        public function get_image_sizes( $sizes, $args ) {
            return CozyStay_Utils_Image::get_image_sizes( $args );
        }
        /**
        * Get image size
        */
        public function get_image_size( $size, $args ) {
            return CozyStay_Utils_Image::get_image_size( $args );
        }
        /**
        * Set the options need to be translated as page id
        */
        public function translate_pages( $pages ) {
            return array_merge( array( 'cozystay_general_404_page_custom_block' ), (array) $pages );
        }
        /**
        * Set the options need to be translated as page id
        */
        public function translate_attachments( $attach ) {
            return array_merge( array(
                'theme_mod_custom_logo',
                'theme_mod_cozystay_page_background_image',
                'theme_mod_cozystay_general_popup_box_background_image',
                'theme_mod_cozystay_mobile_site_header_background_image',
                'theme_mod_cozystay_page_title_default_background_image'
            ), (array) $attach );
        }
        /**
        * Set the taxonomy need to be translated
        */
        public function translate_taxonomy( $tax ) {
            return $tax;
        }
        /**
        * Get current form settings
        */
        public function translate_mc4wp_form_settings( $val ) {
            return get_option( 'cozystay_polylang_mc4wp_settings', $val );
        }
        /**
        * Set the mc4wp form theme mod need to be translated
        */
        public function translate_mc4wp_form( $mods ) {
            return $mods;
        }
        /**
        * Disable image preload
        */
        public function disable_image_preload( $disable ) {
            return ! cozystay_module_enabled( 'cozystay_general_enable_progressive_image_loading' );
        }
        /**
        * Enable image lazy load
        */
        public function enable_lazy_load( $enable ) {
            return cozystay_module_enabled( 'cozystay_general_enable_lazy_load' );
        }
        /**
        * If show post metas dyanmically by AJAX
        */
        public function load_post_metas_by_ajax( $enable ) {
            return cozystay_module_enabled( 'cozystay_blog_general_load_post_metas_dynamically' );
        }
        /**
        * Post List args
        */
        public function post_list_args( $args ) {
            $params = array( 'layout' => 'layout-', 'columns' => 'column-' );
            foreach ( $params as $param => $rid ) {
                if ( isset( $args[ $param ] ) ) {
                    $args[ $param ] = str_replace( $rid, '', $args[ $param ] );
                }
            }
            return $args;
        }
        /**
        * Get opening hours
        */
        public function get_opening_hours( $list = array() ) {
            $open_hours = get_option( 'cozystay_open_hours', false );
            return ( false === $open_hours ) ? $list : array_merge( $list, $open_hours );
        }
        /**
        * Get restaurant info
        */
        public function get_restaurant_info( $info = array() ) {
            $settings = array(
                'cozystay_restaurant_address' => 'address',
                'cozystay_restaurant_map_url' => 'mapURL',
                'cozystay_restaurant_phone' => 'tel',
                'cozystay_restaurant_email' => 'email'
            );
            $valus = array();
            foreach ( $settings as $setting => $id ) {
                $value[ $id ] = get_option( $setting, '' );
            }
            return array_merge( $info, $value );
        }
        /**
        * Change elementor category for theme
        */
        public function element_category_title( $title ) {
            return esc_html__( 'CozyStay Elements', 'cozystay' );
        }
        /**
        * Change elementor widget name
        */
        public function change_elementor_widget_name( $name, $args ) {
            if ( ! empty( $args ) && ! empty( $args['id' ] ) ) {
                switch ( $args['id'] ) {
                    case 'button':
                        return 'cs_button';
                    case 'circle-button':
                        return 'cs_circle_button';
                    case 'section-title':
                        return 'cs_title';
                    case 'rounded-image':
                        return 'cs_rounded_image';
                    case 'food-menu':
                        return 'cs_food_menu';
                    case 'opening-hours':
                        return 'cs_open_hour';
                    case 'restaurant-info':
                        return 'cs_info';
                    case 'social-menu':
                        return 'cs_social';
                    case 'site-logo':
                        return 'cs_logo';
                    case 'testimonials':
                        return 'cs_testimonials';
                    case 'image-gallery':
                        return 'cs_gallery';
                    case 'info-box':
                        return 'cs_info_box';
                    case 'contact-form7':
                        return 'cs_form_cf7';
                    case 'mc4wp':
                        return 'cs_signup';
                    case 'block-links':
                        return 'cs_block_links';
                    case 'opentable':
                        return 'cs_open_table';
                    case 'list':
                        return 'cs_list';
                    case 'instagram':
                        return 'cs_instagram';
                    case 'food-card':
                        return 'cs_food_card';
                    case 'count-down':
                        return 'cs_countdown';
                    case 'call-to-action':
                        return 'cs_call_to_action';
                    case 'team-member':
                        return 'cs_team';
                    case 'divider':
                        return 'cs_divider';
                    case 'vertical-divider':
                        return 'cs_vertical_divider';
                    case 'mobile-menu-toggle':
                        return 'cs_menu_toggle';
                    case 'navigation-menu':
                        return 'cs_menu';
                    case 'search-button':
                        return 'cs_search';
                    case 'mini-cart':
                        return 'cs_mini_cart';
                    case 'tabs':
                        return 'cs_tabs';
                    case 'blog':
                        return 'cs_blog';
                    case 'products':
                        return 'cs_products';
                    case 'slider':
                        return 'cs_slider';
                    case 'video':
                        return 'cs_video';
                    case 'fancy-card':
                        return 'cs_fancy_card';
                    case 'reservation-filter':
                        return 'cs_reservation';
                    case 'rooms':
                        return 'cs_rooms';
                }
            }
            return $name;
        }
        /**
        * Count down label days
        */
        public function get_count_down_label_days( $label ) {
            return esc_html__( 'Days', 'cozystay' );
        }
        /**
        * Count down label hours
        */
        public function get_count_down_label_hours( $label ) {
            return esc_html__( 'Hours', 'cozystay' );
        }
        /**
        * Count down label minutes
        */
        public function get_count_down_label_minutes( $label ) {
            return esc_html__( 'Minutes', 'cozystay' );
        }
        /**
        * Count down label seconds
        */
        public function get_count_down_label_seconds( $label ) {
            return esc_html__( 'Seconds', 'cozystay' );
        }
        /**
        * Alter the widget title before/after html
        */
        public function check_sidebar_params( $params ) {
            if ( 0 === strpos( $params[0]['widget_id'], 'mc4wp_form_widget' ) ) {
                $params[0]['before_title'] = '<div class="widget-header"><h5 class="widget-title">';
                $params[0]['after_title'] = '</h5></div>';
            }
            return $params;
        }
        /**
        * Image loading attributes action callback function
        */
        public function image_loading_attributes() {
            add_filter( 'loftocean_disable_image_loading_optization', '__return_false', 9999 );
            add_filter( 'loftocean_disable_media_preload', array( $this, 'disable_image_preload' ), 10 );
            add_filter( 'loftocean_enable_media_lazy_load', array( $this, 'enable_lazy_load' ), 10 );
        }
        /**
        * Navigation menu for elementor widget
        */
        public function the_navigation_menu( $menu_id, $args ) {
            if ( ! empty( $menu_id ) ) {
                $nav = wp_get_nav_menu_object( $menu_id );
                if ( $nav ) {
                    $args = array_merge( array( 'container_class' => '', 'menu_class' => '', 'menu_id' => '', 'style' => 'inline' ), $args );
                    $menu_args = array(
                        'fallback_cb' => '',
                        'menu' => $nav,
                        'container' => 'nav',
                        'container_class' => $args[ 'container_class' ],
                        'container_id' => '',
                        'menu_class' => $args[ 'menu_class' ],
                        'menu_id' => $args[ 'menu_id' ],
            			'link_before' => '<span>',
            			'link_after' => '</span>'
                    );
                    switch( $args[ 'style' ] ) {
                        case 'footer':
                            $menu_args[ 'depth' ] = 1;
                            break;
                        case 'mobile':
                        case 'primary':
                            if ( ! class_exists( 'CozyStay_Walker_Nav_Menu' ) ) {
                                require_once COZYSTAY_THEME_INC . 'front/class-walker-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
                            }
                            $menu_args[ 'walker' ] = ( 'mobile' == $args[ 'style' ] ) ? ( new CozyStay_Walker_Fullscreen_Nav_Menu() ) : ( new CozyStay_Walker_Nav_Menu() );
                            break;
                    }
                    wp_nav_menu( $menu_args );
                }
            }
        }
        /**
        * Elementor related features
        */
        public function elementor_loaded() {
        	add_filter( 'elementor/fonts/groups', array( $this, 'add_custom_font_group' ) );
            add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_custom_fonts_to_theme_group' ) );
        }
    	/**
    	* Add custom font group to font control
    	*/
    	public function add_custom_font_group( $font_groups ) {
    		return array_merge( array(
                'cozystay_adobe_fonts' => esc_html__( 'Adobe Fonts', 'cozystay' ),
                'cozystay_custom_fonts' => esc_html__( 'Custom Fonts', 'cozystay' )
            ), $font_groups );
    	}
    	/**
    	* Add custom fonts to theme group
    	* @return array
    	*/
    	public function add_custom_fonts_to_theme_group( $additional_fonts ) {
            $custom_fonts = cozystay_get_custom_fonts();
            if ( false !== $custom_fonts ) {
                $theme_fonts = array();
                if ( isset( $custom_fonts[ 'adobe' ] ) ) {
                    foreach( $custom_fonts[ 'adobe' ][ 'fonts' ] as $font ) {
                        $theme_fonts[ $font ] = 'cozystay_adobe_fonts';
                    }
                }
                if ( isset( $custom_fonts[ 'custom' ] ) ) {
                    $fonts = array();
                    foreach ( $custom_fonts[ 'custom' ] as $font ) {
                        $theme_fonts[ $font[ 'name' ] ] = 'cozystay_custom_fonts';
                    }
                }
                if ( cozystay_is_valid_array( $theme_fonts ) ) {
                    return array_merge( $theme_fonts, $additional_fonts );
                }
            }

    		return $additional_fonts;
    	}
        /**
        * After site footer content
        */
        public function after_site_footer() {
            do_action( 'loftocean_site_footer' );
        }
        /*
        * Page content class
        */
        public function content_class( $class ) {
            return apply_filters( 'loftocean_content_class', $class );
        }
        /*
        * Single room template
        */
        public function is_single_room_custom_template( $custom = false ) {
            $default_template = cozystay_get_theme_mod( 'cozystay_rooms_single_default_template' );
            if ( 'custom' == $default_template ) {
                $custom_template = cozystay_get_theme_mod( 'cozystay_rooms_single_custom_default_template' );
                return ! empty( $custom_template );
            }
            return false;
        }
        /*
        * Single room top section
        */
        public function get_single_room_default_top_section( $top_section = '' ) {
            return cozystay_get_theme_mod( 'cozystay_rooms_single_default_top_section' );
        }
        /**
        * Room list read more button text
        */
        public function get_room_readmore_text( $text ) {
            return cozystay_get_theme_mod( 'cozystay_room_list_read_more_button_text' );
        }
        /**
        * Check if enabled room availability calendar
        */
        public function enable_room_availibility_calendar( $enabled ) {
            return cozystay_module_enabled( 'cozystay_room_availability_calendar_enable' );
        }
        /**
        * Get single room availability calendar section title
        */
        public function get_single_room_availability_calendar_section_title( $title ) {
            return cozystay_get_theme_mod( 'cozystay_room_availability_calendar_section_title' );
        }
        /**
        * Check if hide reservation form fields
        */
        public function check_reservation_form_fields( $fields ) {
            return array(
                'room' => cozystay_module_enabled( 'cozystay_room_reservation_form_hide_field_room' ),
                'adult' => cozystay_module_enabled( 'cozystay_room_reservation_form_hide_field_adult' ),
                'child' => cozystay_module_enabled( 'cozystay_room_reservation_form_hide_field_child' )
            );
        }
    }
    new CozyStay_Theme_Core_Filters();
}
