<?php
/**
* Theme option default values
*/

global $cozystay_default_settings;

$cozystay_default_settings = apply_filters( 'cozystay_default_option_values', array(
	// Site logo width
	'cozystay_site_logo_width'											=> 160,

	/** section general layouts **/
	'cozystay_page_content_width'										=> '',
	'cozystay_page_content_custom_width'								=> '1200',
	'cozystay_page_background_image' 									=> '',
	'cozystay_page_background_position_x' 								=> 'center',
	'cozystay_page_background_position_y' 								=> 'center',
	'cozystay_page_background_size' 									=> 'auto',
	'cozystay_page_background_repeat' 									=> '',
	'cozystay_page_background_attachment' 								=> '',

	/** section back to top **/
	'cozystay_show_back_to_top_button'									=> 'on',
	'cozystay_back_to_top_button_background_color'						=> '',
	'cozystay_back_to_top_button_border_color'							=> '',
	'cozystay_back_to_top_button_icon_color'							=> '',
	'cozystay_back_to_top_button_hover_background_color'				=> '',
	'cozystay_back_to_top_button_hover_border_color'					=> '',
	'cozystay_back_to_top_button_hover_icon_color'						=> '',

	/** section social **/
	'cozystay_general_social_like'										=> '',
	'cozystay_general_social_facebook'									=> 'on',
	'cozystay_general_social_twitter'									=> 'on',
	'cozystay_general_social_linkedin'									=> '',
	'cozystay_general_social_pinterest'									=> 'on',
	'cozystay_general_social_whatsapp'									=> '',

	/** section 404 page **/
	'cozystay_general_404_page_custom_block'							=> '0',

	/** section cookie law **/
	'cozystay_general_cookie_law_enabled'								=> '',
	'cozystay_general_cookie_law_message'								=> esc_html__( 'This website uses cookies to improve your web experience.', 'cozystay' ),
	'cozystay_general_cookie_law_accept_button_text'					=> esc_html__( 'Accept', 'cozystay' ),
	'cozystay_general_cookie_law_version'								=> 1,

	/** section popup box **/
	'cozystay_general_popup_box_enable'									=> '',
	'cozystay_general_popup_box_once_per_session'						=> 'on',
	'cozystay_general_popup_box_custom_block'							=> '0',
	'cozystay_general_popup_box_display_delay'							=> '5',
	'cozystay_general_popup_box_size'									=> 'fullscreen',
	'cozystay_general_popup_box_custom_width'							=> '600',
	'cozystay_general_popup_box_color_scheme'							=> 'light-color',
	'cozystay_general_popup_box_background_color'						=> '',
	'cozystay_general_popup_box_background_image'						=> '',
	'cozystay_general_popup_box_device_visibility'						=> 'all',

	/** section search **/
	'cozystay_general_search_default_post_types'						=> array( 'post' ),

	/** section one page menu **/
	'cozystay_enable_onepage_menu_check'								=> '',

	/** section site header general **/
	'cozystay_sticky_site_header'										=> 'scroll-up-enable',
	'cozystay_site_header'												=> '',
	'cozystay_default_site_header_enable_overlap'						=> '',
	'cozystay_default_site_header_hide_search_icon'						=> '',
	'cozystay_default_site_header_hide_cart'							=> '',

	'cozystay_site_header_main_custom_block'							=> '0',
	'cozystay_sticky_site_header_custom_block'							=> '0',

	/** section site header fullscreen/mobile menu **/
	'cozystay_mobile_site_header_menu'									=> '0',
	'cozystay_mobile_site_header_copyright_text'						=> esc_html__( '© Copyright CozyStay WordPress Theme for Hotel Booking.', 'cozystay' ),
	'cozystay_mobile_site_header_hide_default_close_button'				=> '',
	'cozystay_mobile_site_header_background_color'						=> '',
	'cozystay_mobile_site_header_background_image'						=> '',
	'cozystay_mobile_site_header_text_color'							=> '',

	'cozystay_mobile_site_header_entrance_animation'					=> '',
	'cozystay_mobile_site_header_width'									=> '',
	'cozystay_mobile_site_header_custom_width'							=> 375,

	/** section site footer main **/
	'cozystay_site_footer_main_custom_block'							=> '0',

	/** section site footer before footer **/
	'cozystay_above_site_footer_content_source'							=> 'text',
	'cozystay_above_site_footer_text_content'							=> '',
	'cozystay_above_site_footer_custom_block'							=> '0',

	/** section site footer instagram **/
	'cozystay_site_footer_enable_instagram'								=> '',
	'cozystay_site_footer_instagram_title'								=> '',
	'cozystay_site_footer_instagram_title_link'							=> '',
	'cozystay_site_footer_instagram_columns'							=> 6,
	'cozystay_site_footer_instagram_new_tab'							=> '',

	/** section site footer bottom **/
	'cozystay_site_footer_bottom_layout'								=> '',
	// translators: %1$s: font icon with color %2$s: line break
	'cozystay_site_footer_bottom_text'									=> esc_html__( '© Copyright CozyStay WordPress Theme for Hotel Booking.', 'cozystay' ),
	'cozystay_site_footer_bottom_background_color'						=> '#111111',
	'cozystay_site_footer_bottom_text_color'							=> '#FFFFFF',

	'cozystay_site_footer_hide_bottom'									=> '',

	/*** section general colors */
	'cozystay_general_color_scheme'										=> 'light-color',
	'cozystay_general_primary_color'									=> '#B99D75',
	'cozystay_general_secondary_color'									=> '#53624E',
	'cozystay_general_light_scheme_background_color'					=> '#FFFFFF',
	'cozystay_general_light_scheme_text_color'							=> '#1A1B1A',
	'cozystay_general_light_scheme_content_color'						=> '#333632',
	'cozystay_general_dark_scheme_background_color'						=> '#0E0D0A',
	'cozystay_general_dark_scheme_text_color'							=> '#FFFFFF',
	'cozystay_general_dark_scheme_content_color'						=> '#EEEEEE',

	/** section link colors **/
	'cozystay_link_light_scheme_regular_color'							=> 'primary',
	'cozystay_link_light_scheme_custom_regular_color'					=> '',
	'cozystay_link_light_scheme_hover_color'							=> 'primary',
	'cozystay_link_light_scheme_custom_hover_color'						=> '',
	'cozystay_link_dark_scheme_regular_color'							=> 'primary',
	'cozystay_link_dark_scheme_custom_regular_color'					=> '',
	'cozystay_link_dark_scheme_hover_color'								=> 'primary',
	'cozystay_link_dark_scheme_custom_hover_color'						=> '',

	/** section button colors **/
	'cozystay_button_shape'												=> '',
	'cozystay_button_background_color'									=> 'primary',
	'cozystay_button_custom_background_color'							=> '',
	'cozystay_button_text_color'										=> '#FFFFFF',
	'cozystay_button_hover_background_color'							=> 'custom',
	'cozystay_button_hover_custom_background_color'						=> '#AB916C',
	'cozystay_button_hover_text_color'									=> '#FFFFFF',
	'cozystay_button_underline_color'									=> '',
	'cozystay_button_underline_custom_color'							=> '',

	/** section form styles **/
	'cozystay_form_field_style'											=> 'cs-form-square',
	'cozystay_form_border_width'										=> '1',

	/** section other colors **/
	'cozystay_others_blog_post_meta_color'								=> 'secondary',
	'cozystay_others_blog_post_meta_custom_color'						=> '',
	'cozystay_others_rooms_subtitle_color'								=> 'secondary',
	'cozystay_others_rooms_subtitle_custom_color'						=> '',

	/** section typogaghy heading */
	'cozystay_typography_heading_font-family' 							=> 'Marcellus',
	'cozystay_typography_heading_font-weight'							=> '400',
	'cozystay_typography_heading_letter-spacing'						=> 0,
	'cozystay_typography_heading_text-transform'						=> 'none',
	'cozystay_typography_heading_font-style'							=> 'normal',

	/** section typogaghy subheading **/
	'cozystay_typography_subheading_default_color'						=> 'secondary',
	'cozystay_typography_subheading_custom_default_color'				=> '',
	'cozystay_typography_subheading_font-family'						=> 'Jost',
	'cozystay_typography_subheading_font-size'							=> 12,
	'cozystay_typography_subheading_font-weight'						=> '400',
	'cozystay_typography_subheading_letter-spacing'						=> '0.05em',
	'cozystay_typography_subheading_text-transform'						=> 'uppercase',
	'cozystay_typography_subheading_font-style'							=> 'normal',

	/** section typogaghy text **/
	'cozystay_typography_text_font-family'								=> 'Jost',
	'cozystay_typography_text_font-weight'								=> '400',
	'cozystay_typography_text_letter-spacing'							=> '0',
	'cozystay_typography_text_text-transform'							=> 'none',
	'cozystay_typography_text_font-style'								=> 'normal',

	/** section typogaghy blog **/
	'cozystay_typography_blog_title_font-weight'						=> '400',
	'cozystay_typography_blog_title_letter-spacing'						=> '0',
	'cozystay_typography_blog_title_text-transform'						=> 'none',
	'cozystay_typography_blog_title_font-style'							=> 'normal',
	'cozystay_typography_blog_content_font-size'						=> '18',
	'cozystay_typography_blog_content_line-height'						=> '1.66',

	/** section typogaghy secondary */
	'cozystay_typography_secondary_font-family' 						=> 'Jost',
	'cozystay_typography_secondary_letter-spacing'						=> '',
	'cozystay_typography_secondary_text-transform'						=> 'none',
	'cozystay_typography_secondary_font-style'							=> 'normal',

	/** section typogaghy widget title **/
	'cozystay_typography_widget_title_font-family'						=> 'Jost',
	'cozystay_typography_widget_title_font-size'						=> 14,
	'cozystay_typography_widget_title_font-weight'						=> '500',
	'cozystay_typography_widget_title_letter-spacing'					=> '0.05em',
	'cozystay_typography_widget_title_text-transform'					=> 'uppercase',
	'cozystay_typography_widget_title_font-style'						=> 'normal',

	/** section typogaghy menu **/
	'cozystay_typography_menu_font-family'								=> 'Jost',
	'cozystay_typography_menu_font-size'								=> 13,
	'cozystay_typography_menu_font-weight'								=> '500',
	'cozystay_typography_menu_letter-spacing'							=> '0.05em',
	'cozystay_typography_menu_text-transform'							=> 'uppercase',
	'cozystay_typography_footer_bottom_menu_font-size'					=> 14,
	'cozystay_typography_footer_bottom_menu_font-weight'				=> '400',
	'cozystay_typography_footer_bottom_menu_letter-spacing'				=> '0',
	'cozystay_typography_footer_bottom_menu_text-transform'				=> 'uppercase',

	/** section typography button text **/
	'cozystay_typography_button_text_font-family'						=> '',
	'cozystay_typography_button_text_font-size'							=> '16',
	'cozystay_typography_button_text_font-weight'						=> '400',
	'cozystay_typography_button_text_letter-spacing'					=> '0',
	'cozystay_typography_button_text_text-transform'					=> 'none',

	/** section page title **/
	'cozystay_page_title_section_size'									=> 'page-title-default',
	'cozystay_page_title_default_background_color'						=> '',
	'cozystay_page_title_default_background_image'						=> '',
	'cozystay_page_title_default_background_size'						=> 'cover',
	'cozystay_page_title_default_background_repeat'						=> '',
	'cozystay_page_title_default_background_position_x'					=> 'center',
	'cozystay_page_title_default_background_position_y'					=> 'center',
	'cozystay_page_title_default_background_attachment' 				=> 'on',
	'cozystay_page_title_default_text_color'							=> '',
	'cozystay_page_title_show_breadcrumb'								=> '',

	/** section blog genenral **/
	'cozystay_blog_general_read_more_button_text' 						=> esc_html__( 'Read More', 'cozystay' ),
	'cozystay_blog_general_pagination_style'							=> 'ajax-manual',
	'cozystay_blog_general_featured_date_label'							=> '',
	'cozystay_blog_general_layout_standard_post_excerpt_length' 		=> '50',
	'cozystay_blog_general_layout_list_post_excerpt_length'	 			=> '25',
	'cozystay_blog_general_layout_zigzag_post_excerpt_length'			=> '25',
	'cozystay_blog_general_layout_grid_post_excerpt_length' 			=> '25',
	'cozystay_blog_general_layout_overlay_post_excerpt_length' 			=> '10',
	'cozystay_blog_general_layout_masonry_post_excerpt_length' 			=> '25',
	'cozystay_blog_general_load_post_metas_dynamically'					=> '',

	/** section blog page **/
	'cozystay_blog_page_layout'											=> '',
	'cozystay_blog_page_post_list_layout'								=> 'masonry-3cols',
	'cozystay_blog_page_overlay_image_ratio'							=> 'img-ratio-2-3',
	'cozystay_blog_page_image_ratio'									=> 'img-ratio-3-2',
	'cozystay_blog_page_center_text'									=> '',
	'cozystay_blog_page_list_zigzag_with_border'						=> '',
	'cozystay_blog_page_show_post_excerpt'								=> 'on',
	'cozystay_blog_page_show_read_more_button'							=> 'on',
	'cozystay_blog_page_show_author'									=> '',
	'cozystay_blog_page_show_category'									=> 'on',
	'cozystay_blog_page_show_publish_date'								=> 'on',
	'cozystay_blog_page_show_comment_counter'							=> '',
	'cozystay_blog_page_posts_per_page'									=> get_option( 'posts_per_page', 10 ),

	/** section single post **/
	'cozystay_blog_single_post_title_section_size'						=> '',
	'cozystay_blog_single_post_title_default_background_color'			=> '',
	'cozystay_blog_single_post_title_default_text_color'				=> '',
	'cozystay_blog_single_post_meta_items_color'						=> '',
	'cozystay_blog_single_post_show_yoast_seo_breadcrumb'				=> '',
	'cozystay_blog_single_post_layout'									=> 'with-sidebar-right',
	'cozystay_blog_single_post_page_header_show_author'					=> 'on',
	'cozystay_blog_single_post_page_header_show_category'				=> 'on',
	'cozystay_blog_single_post_page_header_show_publish_date'			=> 'on',
	'cozystay_blog_single_post_page_header_show_comment_counter'		=> '',
	'cozystay_blog_single_post_page_footer_show_tags'					=> 'on',
	'cozystay_blog_single_post_page_footer_show_social_sharings'		=> 'on',
	'cozystay_blog_single_post_page_footer_show_author_info_box'		=> 'on',
	'cozystay_blog_single_post_page_footer_show_navigation'				=> 'on',
	'cozystay_blog_single_post_enable_related_posts'					=> '',
	'cozystay_blog_single_post_related_post_section_title'				=> esc_html__( 'You May Also Like', 'cozystay' ),
	'cozystay_blog_single_post_related_post_filter'						=> 'category',

	// Rooms
	'cozystay_room_list_read_more_button_text'							=> esc_html__( 'Discover More', 'cozystay' ),

	'cozystay_room_booking_search_form_dropdown_background'				=> '',
	'cozystay_room_booking_search_form_dropdown_text_color'				=> '',
	'cozystay_room_booking_search_form_dropdown_border_color'			=> '',
	'cozystay_room_calendar_date_border_color'							=> '',
	'cozystay_room_calendar_available_date_background'					=> '',
	'cozystay_room_calendar_start_end_date_background'					=> '',
	'cozystay_room_calendar_start_end_date_text_color'					=> '',
	'cozystay_room_calendar_in_range_date_background'    				=> '',
	'cozystay_room_calendar_in_range_date_text_color'					=> '',
	'cozystay_room_calendar_hover_highlight'							=> '',

	'cozystay_room_availability_calendar_enable'						=> '',
	'cozystay_room_availability_calendar_section_title'					=> esc_html__( 'Availability', 'cozystay' ),
	'cozystay_room_availability_calendar_start_end_date_background'		=> '',
	'cozystay_room_availability_calendar_start_end_date_text_color'		=> '',
	'cozystay_room_availability_calendar_in_range_date_background'		=> '',
	'cozystay_room_availability_calendar_in_range_date_text_color'		=> '',
	'cozystay_room_availability_calendar_hover_highlight'				=> '',

	'cozystay_room_reservation_form_hide_field_room'					=> '',
	'cozystay_room_reservation_form_hide_field_adult'					=> '',
	'cozystay_room_reservation_form_hide_field_child'					=> '', 

	'cozystay_rooms_single_default_top_section'							=> 'top-gallery-1',
	'cozystay_rooms_single_default_template'							=> '',
	'cozystay_rooms_single_custom_default_template'						=> '',

	'cozystay_rooms_single_booking_form_background_color'				=> '',
	'cozystay_rooms_single_booking_form_text_color'						=> '',
	'cozystay_rooms_single_booking_form_box_shadow'						=> '',

	// Woocommerce
	'cozystay_woocommerce_product_list_style'							=> '',
	'cozystay_woocommerce_product_list_food_menu_style'					=> 'food-menu-style-1',
	'cozystay_woocommerce_product_list_show_short_description' 			=> '',
	'cozystay_woocommerce_product_list_short_description_length' 		=> 15,

	'cozystay_woocommerce_breadcrumb'									=> 'on',
	'cozystay_woocommerce_yoast_seo_breadcrumb'							=> '',
	'cozystay_woocommerce_archive_layout'								=> 'with-sidebar-right',
	'cozystay_woocommerce_single_layout'								=> 'with-sidebar-right',

	'cozystay_woocommerce_category_filter_enable_ajax'					=> '',

	'cozystay_woocommerce_single_product_hide_page_title_section'		=> '',
	'cozystay_woocommerce_single_product_site_header' 					=> '',
	'cozystay_woocommerce_single_product_custom_site_header'			=> '0',

	'cozystay_woocommerce_return_to_shop_url'							=> ''
) );
