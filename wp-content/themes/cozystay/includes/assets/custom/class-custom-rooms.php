<?php
if ( ! class_exists( 'CozyStay_Custom_Rooms' ) ) {
    class CozyStay_Custom_Rooms {
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'cozystay_custom_style_vars', array( $this, 'custom_style_vars' ) );
            add_filter( 'cozystay_custom_styles', array( $this, 'custom_styles' ) );
        }
		/**
		* Generate custom style variables
		*/
		public function custom_style_vars( $vars ) {
			return $vars;
		}
        /**
        * Generate custom styles
        */
        public function custom_styles( $styles ) {
            $colors = array(
                array(
                    'id' => 'cozystay_room_booking_search_form_dropdown_styles',
                    'prefix' => 'cozystay_room_booking_search_form_dropdown_',
                    'list' => array( 'background' => '--dropdown-bg', 'text_color' => '--dropdown-color', 'border_color' => '--dropdown-border' ),
                    'selector' => '.cs-reservation-form .csf-dropdown, .theme-cozystay .daterangepicker'
                ),
                array(
                    'id' => 'cozystay_room_calendar_styles',
                    'prefix' => 'cozystay_room_calendar_',
                    'list' => array(
                        'date_border_color' => '--td-border',
                        'available_date_background' => '--available-bg',
                        'start_end_date_background' => '--active-bg',
                        'start_end_date_text_color' => '--active-color',
                        'in_range_date_background' => '--inrange-bg',
                        'in_range_date_text_color' => '--inrange-color',
                        'hover_highlight' => '--hover-highlight'
                    ),
                    'selector' => '.theme-cozystay .daterangepicker'
                ),
                array(
                    'id' => 'cozystay_room_availability_calendar_styles',
                    'prefix' => 'cozystay_room_availability_calendar_',
                    'list' => array(
                        'start_end_date_background' => '--active-bg',
                        'start_end_date_text_color' => '--active-color',
                        'in_range_date_background' => '--inrange-bg',
                        'in_range_date_text_color' => '--inrange-color',
                        'hover_highlight' => '--hover-highlight'
                    ),
                    'selector' => '.theme-cozystay .room-availability .daterangepicker'
                )
            );
            foreach ( $colors as $attrs ) {
                $list = array();
                foreach ( $attrs[ 'list' ] as $set => $var ) {
                    $color = cozystay_get_theme_mod( $attrs[ 'prefix' ] . $set );
                    if ( ! empty( $color ) ) {
                        array_push( $list, $var . ': ' . $color . ';' );
                    }
                }
                if ( count( $list ) > 0 ) {
                    $styles[ $attrs[ 'id' ] ] = $attrs[ 'selector' ] . ' {' . implode( ' ', $list ) . '} ';
                }
            }
            return $styles;
        }
    }
    new CozyStay_Custom_Rooms();
}
