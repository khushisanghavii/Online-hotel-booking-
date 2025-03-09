<?php
if ( ! class_exists( 'CozyStay_Custom_Typography' ) ) {
    class CozyStay_Custom_Typography {
        /**
        * Setting keys
        */
        public $setting_keys = false;
        /**
        * Construct function
        */
        public function __construct() {
			add_filter( 'cozystay_custom_styles', array( $this, 'custom_styles' ) );
			add_filter( 'cozystay_custom_style_vars', array( $this, 'custom_style_vars' ) );
            add_filter( 'cozystay_get_gutenberg_custom_styles', array( $this, 'guternberg_custom_styles' ) );

			add_action( 'cozystay_enqueue_google_fonts', array( $this, 'enqueue_google_fonts' ) );
            add_action( 'cozystay_enqueue_adobe_fonts', array( $this, 'enqueue_adobe_fonts' ) );
        }
		/**
		* Generate custom style variables
		*/
        public function custom_style_vars( $vars ) {
            global $cozystay_default_settings;
            $css_vars = array(
                '--heading-font' => array( 'id' => 'cozystay_typography_heading_font-family', 'before' => '"', 'after' => '"' ),
                '--hf-weight' => array( 'id' => 'cozystay_typography_heading_font-weight', 'before' => '', 'after' => '' ),
                '--hf-letter-spacing' => array( 'id' => 'cozystay_typography_heading_letter-spacing', 'before' => '', 'after' => '' ),
                '--hf-text-transform' => array( 'id' => 'cozystay_typography_heading_text-transform', 'before' => '', 'after' => '' ),
                '--hf-style' => array( 'id' => 'cozystay_typography_heading_font-style', 'before' => '', 'after' => '' ),
                '--subheading-font' => array( 'id' => 'cozystay_typography_subheading_font-family', 'before' => '"', 'after' => '"' ),
                '--shf-font-size' => array( 'id' => 'cozystay_typography_subheading_font-size', 'before' => '', 'after' => 'px' ),
                '--shf-weight' => array( 'id' => 'cozystay_typography_subheading_font-weight', 'before' => '', 'after' => '' ),
                '--shf-letter-spacing' => array( 'id' => 'cozystay_typography_subheading_letter-spacing', 'before' => '', 'after' => '' ),
                '--shf-text-transform' => array( 'id' => 'cozystay_typography_subheading_text-transform', 'before' => '', 'after' => '' ),
                '--shf-style' => array( 'id' => 'cozystay_typography_subheading_font-style', 'before' => '', 'after' => '' ),
                '--body-font' => array( 'id' => 'cozystay_typography_text_font-family', 'before' => '"', 'after' => '"' ),
                '--blog-title-weight' => array( 'id' => 'cozystay_typography_blog_title_font-weight', 'before' => '', 'after' => '' ),
                '--bt-letter-spacing' => array( 'id' => 'cozystay_typography_blog_title_letter-spacing', 'before' => '', 'after' => '' ),
                '--bt-text-transform' => array( 'id' => 'cozystay_typography_blog_title_text-transform', 'before' => '', 'after' => '' ),
                '--bt-style' => array( 'id' => 'cozystay_typography_blog_title_font-style', 'before' => '', 'after' => '' ),
                '--post-text-size' => array( 'id' => 'cozystay_typography_blog_content_font-size', 'before' => '', 'after' => 'px' ),
                '--post-line-height' => array( 'id' => 'cozystay_typography_blog_content_line-height', 'before' => '', 'after' => '' ),
                '--secondary-font' => array( 'id' => 'cozystay_typography_secondary_font-family', 'before' => '"', 'after' => '"' ),
                '--sf-letter-spacing' => array( 'id' => 'cozystay_typography_secondary_letter-spacing', 'before' => '', 'after' => '' ),
                '--sf-text-transform' => array( 'id' => 'cozystay_typography_secondary_text-transform', 'before' => '', 'after' => '' ),
                '--sf-style' => array( 'id' => 'cozystay_typography_secondary_font-style', 'before' => '', 'after' => '' ),
                '--widget-title-font' => array( 'id' => 'cozystay_typography_widget_title_font-family', 'before' => '"', 'after' => '"' ),
                '--widget-title-size' => array( 'id' => 'cozystay_typography_widget_title_font-size', 'before' => '', 'after' => 'px' ),
                '--widget-title-weight' => array( 'id' => 'cozystay_typography_widget_title_font-weight', 'before' => '', 'after' => '' ),
                '--widget-title-spacing' => array( 'id' => 'cozystay_typography_widget_title_letter-spacing', 'before' => '', 'after' => '' ),
                '--widget-title-trans' => array( 'id' => 'cozystay_typography_widget_title_text-transform', 'before' => '', 'after' => '' ),
                '--widget-title-style' => array( 'id' => 'cozystay_typography_widget_title_font-style', 'before' => '', 'after' => '' ),
                '--nav-font' => array( 'id' => 'cozystay_typography_menu_font-family', 'before' => '"', 'after' => '"' ),
                '--nav-font-size' => array( 'id' => 'cozystay_typography_menu_font-size', 'before' => '', 'after' => 'px' ),
                '--nav-font-weight' => array( 'id' => 'cozystay_typography_menu_font-weight', 'before' => '', 'after' => '' ),
                '--nav-font-letter-spacing' => array( 'id' => 'cozystay_typography_menu_letter-spacing', 'before' => '', 'after' => '' ),
                '--nav-font-transform' => array( 'id' => 'cozystay_typography_menu_text-transform', 'before' => '', 'after' => '' ),
                '--fbnav-font-size' => array( 'id' => 'cozystay_typography_footer_bottom_menu_font-size', 'before' => '', 'after' => 'px' ),
                '--fbnav-font-weight' => array( 'id' => 'cozystay_typography_footer_bottom_menu_font-weight', 'before' => '', 'after' => '' ),
                '--fbnav-font-letter-spacing' => array( 'id' => 'cozystay_typography_footer_bottom_menu_letter-spacing', 'before' => '', 'after' => '' ),
                '--fbnav-font-transform' => array( 'id' => 'cozystay_typography_footer_bottom_menu_text-transform', 'before' => '', 'after' => '' ),
                '--btn-font' => array( 'id' => 'cozystay_typography_button_text_font-family', 'before' => '"', 'after' => '"' ),
                '--btn-font-size' => array( 'id' => 'cozystay_typography_button_text_font-size', 'before' => '', 'after' => 'px' ),
                '--btn-font-weight' => array( 'id' => 'cozystay_typography_button_text_font-weight', 'before' => '', 'after' => '' ),
                '--btn-letter-spacing' => array( 'id' => 'cozystay_typography_button_text_letter-spacing', 'before' => '', 'after' => '' ),
                '--btn-text-transform' => array( 'id' => 'cozystay_typography_button_text_text-transform', 'before' => '', 'after' => '' )
            );
            $root_vars = array();
            foreach ( $css_vars as $var => $attrs ) {
                $custom_value = cozystay_get_theme_mod( $attrs[ 'id' ] );
                $custom_value = cozystay_check_font_value( $custom_value, $attrs[ 'id' ] );
                if ( $cozystay_default_settings[ $attrs[ 'id' ] ] != $custom_value && ! empty( $custom_value ) ) {
                    $root_vars[ $var ] = sprintf( '%1$s%2$s%3$s', $attrs['before'], $custom_value, $attrs[ 'after' ] );
                }
            }
            $default_color = cozystay_get_theme_mod( 'cozystay_typography_subheading_default_color' );
            $default_color_var = '--shf-color';
            switch ( $default_color ) {
                case 'primary':
                    $root_vars[ $default_color_var ] = 'var(--primary-color)';
                    break;
                case 'secondary':
                    $root_vars[ $default_color_var ] = 'var(--secondary-color)';
                    break;
                case 'custom':
                    $custom_color = cozystay_get_theme_mod( 'cozystay_typography_subheading_custom_default_color' );
                    if ( ! empty( $custom_color ) ) {
                        $root_vars[ $default_color_var ] = $custom_color;
                    }
                    break;
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
			global $cozystay_default_settings;
			$this->setting_keys = array_keys( $cozystay_default_settings );

			$styles['typography_body'] = $this->get_typography_styles(
				'body',
				'cozystay_typography_text',
				array( 'font-weight', 'letter-spacing', 'text-transform', 'font-style' )
			);

            $custom_fonts = $this->self_hosted_custom_fonts();
            if ( ! empty( $custom_fonts ) ) {
                $styles['self_hosted_custom_fonts'] = $custom_fonts;
            }

			return $styles;
		}
        /**
        * Guternberg custom style
        */
        public function guternberg_custom_styles( $style = '' ) {
            $custom_fonts = $this->self_hosted_custom_fonts();
            if ( ! empty( $custom_fonts ) ) {
                $style .= ' ' . $custom_fonts;
            }
            return $style;
        }
        /**
        * Generate typography styles
        */
		private function get_typography_styles( $element, $prefix, $attrs, $no_check = false ) {
			global $cozystay_default_settings;
			if ( ! empty( $element ) && ! empty( $prefix ) && ! empty( $attrs ) && is_array( $attrs ) ) {
				$styles = '';
				foreach ( $attrs as $attr ) {
					$name = $prefix . '_' . $attr;
					if ( in_array( $name, $this->setting_keys ) ) {
						$value = esc_attr( cozystay_get_theme_mod( $name ) );
						if ( $no_check || ( $cozystay_default_settings[ $name ] != $value ) ) {
							$unit = ( $attr == 'font-size' ) ? 'px' : '';
							$styles .= "\n\t" . $attr . ': ' . $value . $unit . ';';
						}
					}
				}
				return empty( $styles ) ? '' : sprintf( "\n%s {%s\n}\n", $element, $styles );
			}
			return '';
		}
		/**
		* Enqueue google fonts
		*/
		public function enqueue_google_fonts() {
            $google_fonts = apply_filters( 'cozystay_google_fonts', $this->get_google_fonts() );
			$google_fonts = array_unique( $google_fonts );
			// Add Google font in safe way. Refer to https://gist.github.com/richtabor/b85d317518b6273b4a88448a11ed20d3
			if ( ! empty( $google_fonts ) ) {
				wp_enqueue_style(
					'cozystay-theme-google-fonts',
					add_query_arg( array( 'family' => urlencode( implode( '|', $google_fonts ) ), 'display' => 'swap' ), 'https://fonts.googleapis.com/css' ),
					array(),
					COZYSTAY_ASSETS_VERSION
				);
			}
		}
        /**
        * Get Google fonts
        */
        protected function get_google_fonts() {
            global $cozystay_default_settings;
            $fonts = array();
            $fonts_prefix = array(
                'cozystay_typography_heading_',
                'cozystay_typography_subheading_',
                'cozystay_typography_text_',
                'cozystay_typography_secondary_',
                'cozystay_typography_widget_title_',
                'cozystay_typography_menu_',
                'cozystay_typography_button_text'
            );
            $full_list = array( 'cozystay_typography_heading_', 'cozystay_typography_text_' );
            foreach ( $fonts_prefix as $prefix ) {
                $font = cozystay_get_theme_mod( $prefix . 'font-family' );
                if ( empty( $font ) ) continue;

                $font_checked = cozystay_check_font_value( $font, $prefix . 'font-family' );
                if ( $font != $font_checked ) continue;

                $font_weight = isset( $cozystay_default_settings[ $prefix . 'font-weight' ] ) ? cozystay_get_theme_mod( $prefix . 'font-weight' ) : false;
                $font_style = isset( $cozystay_default_settings[ $prefix . 'font-style' ] ) ? cozystay_get_theme_mod( $prefix . 'font-style' ) : 'normal';
                $is_font_style_italic = ( 'italic' == $font_style );
                $font_attrs = isset( $fonts[ $font ] ) ? $fonts[ $font ] : array();
                if ( in_array( $prefix, $full_list ) ) {
                    $font_attrs = array_merge(
                        $font_attrs,
                        array( '100italic', '200italic', '300italic', '400italic', '500italic', '600italic', '700italic', '800italic', '100', '200', '300', '400', '500', '600', '700', '800' )
                    );
                } else {
                    if ( empty( $font_weight ) ) {
                        $font_attrs = array_merge(
                            $font_attrs,
                            ( $is_font_style_italic ? array( '100italic', '200italic', '300italic', '400italic', '500italic', '600italic', '700italic', '800italic' ) : array( '100', '200', '300', '400', '500', '600', '700', '800' ) )
                        );
                    } else {
                        $font_attrs[] = $font_weight . ( $is_font_style_italic ? 'italic' : '' );
                    }
                }
                $fonts[ $font ] = $font_attrs;
            }
            array_walk( $fonts, function( &$item, $key ) {
                $item = sprintf( '%1$s:%2$s', $key, implode( ',', array_unique( $item ) ) );
            } );
            return array_values( $fonts );
        }
        /**
        * Enqueue adobe fonts
        */
        public function enqueue_adobe_fonts() {
            $custom_fonts = cozystay_get_custom_fonts();
            if ( false !== $custom_fonts ) {
                if ( isset( $custom_fonts[ 'adobe' ] ) ) {
                    wp_enqueue_style( 'typekit-style', 'https://use.typekit.net/' . $custom_fonts[ 'adobe' ][ 'code' ] . '.css', array(), COZYSTAY_ASSETS_VERSION );
                }
            }
        }
        /**
        * Self hosted custom fonts
        */
        public function self_hosted_custom_fonts() {
            $custom_fonts = cozystay_get_custom_fonts();
            if ( false !== $custom_fonts ) {
                if ( isset( $custom_fonts[ 'custom' ] ) ) {
                    $fonts = array();
                    foreach ( $custom_fonts[ 'custom' ] as $font ) {
                        array_push( $fonts, sprintf(
                            '@font-face { font-family: "%1$s";%2$s src: url("%3$s") format("%4$s"); }',
                            $font[ 'name' ],
                            ( empty( $font[ 'weight' ] ) ? '' : ' font-weight: ' . $font[ 'weight' ] . ';' ),
                            $font[ 'url' ],
                            $font[ 'format' ]
                        ) );
                    }
                    if ( cozystay_is_valid_array( $fonts ) ) {
                        return implode( ' ', $fonts );
                    }
                }
            }
            return false;
        }
    }
    new CozyStay_Custom_Typography();
}
