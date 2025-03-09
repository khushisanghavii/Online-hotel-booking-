<?php
if ( ! class_exists( 'CozyStay_Utils_Sanitize' ) ) {
	class CozyStay_Utils_Sanitize {
		/**
		* Check the switch checkbox value
		*
		* @param string the value from user
		* @return mix if set return string 'on', otherwise return false
		*/
		public static function sanitize_checkbox( $input ) {
			return empty( $input ) ? false : 'on';
		}
		/**
		* Check the html
		*
		* @param string the value from user
		* @return mix if set return string 'on', otherwise return false
		*/
		public static function sanitize_html( $text ) {
			return empty( $text ) ? '' : apply_filters( 'format_to_edit', $text );
		}
		/**
		* Check the value is one of the choices from customize control
		*
		* @param string the value from user
		* @param object customize setting object
		* @return string the value from user or the default setting value
		*/
		public static function sanitize_choice( $input, $setting ) {
			$control = $setting->manager->get_control( $setting->id );
			if ( $control instanceof WP_Customize_Control ) {
				$choices = $control->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			} else {
				return $input;
			}
		}
		/**
		* Check the array if all the element is from the choices of customize control
		*
		* @param array the value from user
		* @param object WP_Customize_Setting object, its id must be same as the control's.
		* @return string the value from user or the default setting value
		*/
		public static function sanitize_mutiple_choices( $input, $setting ) {
			$control = $setting->manager->get_control( $setting->id );
			if ( $control instanceof WP_Customize_Control ) {
				$choices = $control->choices;
				if ( is_array( $input ) ) {
					foreach ( $input as $i ) {
						if ( ! array_key_exists( $i, $choices ) ) {
							return $setting->default;
						}
					}
				}
			}
			return $input;
		}
		/**
		* Sanitize function for customize control homepage area
		*
		* @param string the value from user
		* @param object customize setting object
		* @return string the value from user or the default setting value
		*/
		public static function sanitize_array( $input, $setting ) {
			return cozystay_is_valid_array( $input ) ? $input : $setting->default;
		}
		/**
		* Sanitize function for customize control *_title, which are just title. Always return false
		*
		* @param string the value from user
		* @param object customize setting object
		* @return string the value from user or the default setting value
		*/
		public static function sanitize_empty( $input, $setting ) {
			return false;
		}
		/**
		* Sanitize function for customize control to check page id
		*
		* @param string the value from user
		* @return int the value from user or the default setting value
		*/
		public static function sanitize_page_id( $id ) {
			return ! empty( $id ) && ( false !== get_post_status( $id ) ) ? $id : 0;
		}
		/**
		* Sanitize function for customize control to check float value
		*
		* @param string the value from user
		* @return int the value from user or the default setting value
		*/
		public static function sanitize_float( $input, $setting ) {
			$val = floatval( $input );
			return max( 0, number_format( $val, 1, '.', '' ) );
		}
		/**
		* Get allowed attributes for image tag
		* @return array
		*/
		public static function get_responsive_image_allowed_html() {
			return array(
				'img' => array(
					'id' => true,
					'class' => true,
					'width' => true,
					'height' => true,
					'src' => true,
					'srcset' => true,
					'alt' => true,
					'title' => true,
					'sizes' => true
				)
			);
		}
		/**
		* Get allowed html tag and attributes for custom content
		* @return array
		*/
		public static function get_custom_content_allowed_html() {
			$allowed_html = wp_kses_allowed_html( 'post' );
			$img_attrs = CozyStay_Utils_Sanitize::get_responsive_image_allowed_html();
			$iframes = array( 'src' => 1, 'width' => 1, 'height' => 1, 'class' => 1, 'id' => 1, 'frameborder' => 1, 'allow' => 1, 'allowfullscreen' => 1, 'style' => 1 );
			$scripts = array( 'async' => 1, 'src' => 1, 'type' => 1, 'data-*' => 1, 'crossorigin' => 1 );
			$videos = array( 'class' => 1, 'id' => 1, 'width' => 1, 'height' => 1, 'preload' => 1, 'controls' => 1, 'src' => 1 );
			$audios = array( 'class' => 1, 'id' => 1, 'width' => 1, 'height' => 1, 'preload' => 1, 'controls' => 1, 'src' => 1 );
			$sources = array( 'type' => 1, 'src' => 1 );
			$divs = array( 'data-*' => 1, 'class' => 1, 'style' => 1, 'id' => 1, 'overflow' => 1 );
			$ins = array( 'style' => 1, 'class' => 1, 'data-*' => 1 );
			$amp_ad = array( 'width' => 1, 'height' => 1, 'type' => 1, 'data-*' => 1 );

			$allowed_html['img'] = isset( $allowed_html['img' ] ) ? array_merge( $img_attrs['img'] , $allowed_html['img'] ) : $img_attrs['img'];
			$allowed_html['source'] = isset( $allowed_html['source'] ) ? array_merge( $sources, $allowed_html['source'] ) : $sources;
			$allowed_html['video'] = isset( $allowed_html['video'] ) ? array_merge( $videos, $allowed_html['video'] ) : $videos;
			$allowed_html['audio'] = isset( $allowed_html['audio'] ) ? array_merge( $audios, $allowed_html['audio'] ) : $audios;
			$allowed_html['div'] = isset( $allowed_html['div'] ) ? array_merge( array( 'data-loftocean-video-id' => 1 ), $allowed_html['div'] ) : $divs;
			$allowed_html['iframe'] = isset( $allowed_html['iframe'] ) ? array_merge( $iframes, $allowed_html['iframe'] ) : $iframes;
			$allowed_html['script'] = isset( $allowed_html['script'] ) ? array_merge( $scripts, $allowed_html['script'] ) : $scripts;
			$allowed_html['amp-ad'] = isset( $allowed_html['amp-ad'] ) ? array_merge( $amp_ad, $allowed_html['amp-ad'] ) : $amp_ad;
			$allowed_html['ins'] = isset( $allowed_html['ins'] ) ? array_merge( $ins, $allowed_html['ins'] ) : $ins;

			return $allowed_html;
		}
	}
}
