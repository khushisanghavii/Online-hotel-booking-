<?php
if ( ! class_exists( 'CozyStay_Custom_Colors_Styles' ) ) {
    class CozyStay_Custom_Colors_Styles {
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
			global $cozystay_default_settings;
            $root_vars = array();
			$css_vars = array(
				'--primary-color' => 'cozystay_general_primary_color',
                '--secondary-color' => 'cozystay_general_secondary_color',
				'--light-bg-color' => 'cozystay_general_light_scheme_background_color',
                '--light-text-color' => 'cozystay_general_light_scheme_text_color',
                '--light-content-color' => 'cozystay_general_light_scheme_content_color',
                '--dark-bg-color' => 'cozystay_general_dark_scheme_background_color',
                '--dark-text-color' => 'cozystay_general_dark_scheme_text_color',
                '--dark-content-color' => 'cozystay_general_dark_scheme_content_color',
                '--form-bd-width' => 'cozystay_form_border_width',
                '--btn-color' => 'cozystay_button_text_color',
                '--btn-color-hover' => 'cozystay_button_hover_text_color'
			);
			foreach( $css_vars as $var => $id ) {
				$custom_value = cozystay_get_theme_mod( $id );
				if ( $custom_value != $cozystay_default_settings[ $id ] ) {
					$root_vars[ $var ] = sprintf( ( '--form-bd-width' == $var ) ? '%spx' : '%s', $custom_value );
                    if ( '--primary-color' == $var ) {
                        $root_vars[ '--primary-color-semi' ] = cozystay_hex2rgba( $custom_value, '0.3' );
                    }
				}
			}

            $css_vars = array(
                '--light-link-color' => array( 'prefix' => 'cozystay_link_light_scheme_', 'color' => 'regular_color' ),
                '--dark-link-color' => array( 'prefix' => 'cozystay_link_dark_scheme_', 'color' => 'regular_color' ),
                '--light-link-color-hover' => array( 'prefix' => 'cozystay_link_light_scheme_', 'color' => 'hover_color' ),
                '--dark-link-color-hover' => array( 'prefix' => 'cozystay_link_dark_scheme_', 'color' => 'hover_color' ),
                '--btn-bg' => array( 'prefix' => 'cozystay_button_', 'color' => 'background_color' ),
                '--btn-bg-hover' => array( 'prefix' => 'cozystay_button_hover_', 'color' => 'background_color' ),
            );
            foreach ( $css_vars as $var => $attrs ) {
                $option = cozystay_get_theme_mod( $attrs['prefix'] . $attrs[ 'color' ] );
                if ( 'primary' != $option ) {
                    if ( 'secondary' == $option ) {
                        $root_vars[ $var ] = 'var(--secondary-color)';
                    } else {
                        $id = $attrs['prefix'] . 'custom_' . $attrs[ 'color' ];
                        $custom_color = cozystay_get_theme_mod( $id );
                        if ( $cozystay_default_settings[ $id ] != $custom_color ) {
                            $root_vars[ $var ] = $custom_color;
                        }
                    }
                } else if ( '--btn-bg-hover' == $var ) {
                     $root_vars[ $var ] = 'var(--primary-color)';
                }
            }

            if ( cozystay_is_valid_array( $root_vars ) ) {
                $vars[ ':root' ] = isset( $vars[ ':root' ] ) ? array_merge( $vars[ ':root' ], $root_vars ) : $root_vars;
            }

			return $vars;
		}
        /**
        * Generate custom styles
        */
        public function custom_styles( $styles ) {
            $colors = array(
                'button_underline_color' => array( 'prefix' => 'cozystay_button_underline_', 'selector' => '.button.cs-btn-underline:not([class*="cs-btn-color-"])', 'attribute' => '--btn-bg' ),
                'others_blog_post_meta_color' => array( 'prefix' => 'cozystay_others_blog_post_meta_', 'selector' => '.meta-wrap', 'attribute' => 'color' ),
                'others_rooms_subtitle_color' => array( 'prefix' => 'cozystay_others_rooms_subtitle_', 'selector' => '.cs-rooms .cs-room-content .item-subtitle, .room .post-header .item-subtitle', 'attribute' => 'color' )
            );
            foreach ( $colors as $id => $attrs ) {
                $color = cozystay_get_theme_mod( $attrs[ 'prefix' ] . 'color' );
                switch ( $color ) {
                    case 'primary':
                        $styles[ $id ] = sprintf( '%1$s { %2$s: var(--primary-color); }', $attrs[ 'selector' ], $attrs[ 'attribute' ] );
                        break;
                    case 'secondary':
                        if ( 'button_underline_color' == $id ) {
                            $styles[ $id ] = sprintf( '%1$s { %2$s: var(--secondary-color); }', $attrs[ 'selector' ], $attrs[ 'attribute' ] );
                        }
                        break;
                    case 'custom':
                        $custom_color = cozystay_get_theme_mod( $attrs[ 'prefix' ] . 'custom_color' );
                        if ( ! empty( $custom_color ) ) {
                            $styles[ $id ] = sprintf( '%1$s { %2$s: %3$s; }', $attrs[ 'selector' ], $attrs[ 'attribute' ], $custom_color );
                        }
                        break;
                }
            }
            return $styles;
        }
    }
    new CozyStay_Custom_Colors_Styles();
}
