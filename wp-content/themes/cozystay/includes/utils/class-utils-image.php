<?php
if ( ! class_exists( 'CozyStay_Utils_Image' ) ) {
	class CozyStay_Utils_Image {
		/**
		* Boolean if is mobile device
		*/
		public static $is_mobile = false;
		/**
		* String default image size, for image tag or static source requested
		*/
		public static $default_size = 'full';
		/**
		* Array default image sizes
		*	1. First element for normal device
		* 	2. Second element for retina device
		*/
		public static $default_sizes = array( 'full', 'full' );
		/**
		* Construct function
		*/
		public static function init() {
			add_filter( 'loftocean_modify_image_attributes', 'CozyStay_Utils_Image::change_image_attrs', 10, 2 );
			add_filter( 'loftocean_placeholder_size', 'CozyStay_Utils_Image::placeholder_size' );
			add_filter( 'loftocean_lazy_load_image_size', 'CozyStay_Utils_Image::lazy_load_image_size' );
			add_filter( 'loftocean_room_featured_image_size', 'CozyStay_Utils_Image::room_featured_image_size', 99, 3 );
			add_action( 'init', 'CozyStay_Utils_Image::set_settings' );
		}
		/**
		* Setup settings
		*/
		public static function set_settings() {
			self::$is_mobile = apply_filters( 'loftocean_is_mobile', false );
		}
		/**
		* Placeholder image size
		*/
		public static function placeholder_size( $size ) {
			return 'cozystay_255x9999';
		}
		/**
		* Lazy load image size
		*/
		public static function lazy_load_image_size( $size ) {
			return '255px';
		}
		/**
		* Calculate image size for each situation
		* @param array
		*	1. String: module
		*	2. String: sub_module
		* @return array image size for normal and retina screen [ normal screen, retina screen ]
		*/
		protected static function get_image_sizes_array( $args ) {
			if ( cozystay_module_enabled( 'cozystay_general_enable_full_image_size' ) ) {
				return cozystay_module_enabled( 'cozystay_general_enable_lazy_load' ) || cozystay_module_enabled( 'cozystay_general_enable_progressive_image_loading' )
					? self::$default_sizes : array( self::$default_size );
			}

			if ( ! empty( $args['module'] ) ) {
				$sub_module = empty( $args['sub_module'] ) ? false : $args['sub_module'];

				if ( self::$is_mobile ) {
					switch ( $args['module'] ) {
						case 'instagram':
							return array( 'cozystay_600x600-crop' );
						case 'archive':
							if ( in_array( $sub_module, array( 'masonry-2cols', 'masonry-3cols', 'large' ) ) ) {
								return array( 'cozystay_550x9999' );
							} else if ( 'carousel' == $sub_module ) {
								return array( 'cozystay_300x300-crop' );
							}
							break;
						case 'widget':
							switch ( $sub_module ) {
								case 'profile-image':
								case 'banner':
									return array( 'cozystay_370x9999' );
								case 'category-background':
									return array( 'cozystay_600x9999' );
							}
							break;
						case 'singular':
							if ( 'pagination' == $sub_module ) {
								return array( 'cozystay_255x9999' );
							}
						case 'site':
							if ( 'popup-form-background' == $sub_module ) {
								return array( 'cozystay_780x9999' );
							} else if ( 'page-header-404' == $sub_module ) {
								return array( 'cozystay_300x300-crop' );
							}
							break;
						case 'block':
							switch( $sub_module ) {
								case 'inline-post':
									return array( 'cozystay_550x9999' );
							}
							break;
					}
					return array( 'cozystay_1200x9999' );
				}

				switch ( $args['module'] ) {
					case 'instagram':
						$instagram_args = array( 'location' => 'footer', 'column' => 6 );
						if ( ! empty( $args['args'] ) && is_array( $args['args'] ) ) {
							$instagram_args = array_merge( $instagram_args, $args['args'] );
						}
						switch( $instagram_args['location'] ) {
							case 'footer':
								switch( $instagram_args['column'] ) {
									case '8':
										return array( 'cozystay_255x9999', 'cozystay_550x9999' );
									case '7':
										return array( 'cozystay_370x9999', 'cozystay_550x9999' );
									case '6':
										return array( 'cozystay_370x9999', 'cozystay_780x9999' );
									case '5':
										return array( 'cozystay_550x9999', 'cozystay_780x9999' );
									default:
										return array( 'cozystay_780x9999', 'cozystay_1200x9999' );
								}
							default:
								return array( 'cozystay_255x9999', 'cozystay_255x9999' );
						}
					case 'menu':
						if ( $sub_module ) {
							switch ( $sub_module ) {
								case 'mega-menu-1':
									return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
								case 'mega-menu-2':
									return array( 'cozystay_1200x9999', 'cozystay_1920x9999' );
								case 'mega-menu-3':
									return array( 'cozystay_780x9999', 'cozystay_1440x9999' );
								default:
								 	return array( 'cozystay_550x9999', 'cozystay_1200x9999' );
							}
						}
						break;
					case 'widget':
						if ( $sub_module ) {
							switch ( $sub_module ) {
								case 'category-background':
									return array( 'cozystay_370x9999', 'cozystay_600x9999' );
								case 'banner':
									return array( 'cozystay_370x9999', 'cozystay_600x9999' );
								case 'profile-image':
								 	return array( 'medium', 'cozystay_600x9999' );
								case 'list-thumbnail':
									return array( 'thumbnail', 'thumbnail' );
								case 'list-background':
									return array( 'cozystay_600x9999', 'cozystay_600x9999' );
							}
						}
						break;
					case 'singular':
						if ( $sub_module ) {
							switch ( $sub_module ) {
								case 'pagination':
									return array( 'thumbnail', 'cozystay_255x9999' );
								case 'content': // Images inside post content
								 	return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
								case 'gallery': // Gallery inside post content
									return array( 'cozystay_1200x9999', 'cozystay_1440x9999' );
								case 'related-posts': // Related posts after post main content
								 	return array( 'cozystay_600x9999', 'cozystay_600x9999' );
								case 'popup-slider': // Popup sliders
									return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
								case 'signup-form': // Signup form background image after post main content
									return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
							}
						}
						break;
					case 'archive':
						$image_ratio = cozystay_get_post_list_prop( 'image_ratio', '' );
						$page_layout = cozystay_get_post_list_prop( 'page_layout', '' );
						$fullwidth = empty( $page_layout );

						switch ( $sub_module ) {
							case 'featured-slider':
								return array( 'cozystay_1200x9999', 'cozystay_1920x9999' );
							case 'standard':
								return $fullwidth ? array( 'cozystay_1200x9999', 'cozystay_1920x9999' ) : array( '780x9999', 'cozystay_1440x9999' );
							case 'masonry-2cols':
								return $fullwidth ? array( 'cozystay_550x9999', 'cozystay_1200x9999' ) : array( 'cozystay_370x9999', 'cozystay_780x9999' );
							case 'masonry-3cols':
								return $fullwidth ? array( 'cozystay_370x9999', 'cozystay_780x9999' ) : array( 'cozystay_255x9999', 'cozystay_550x9999' );
							case 'list':
							case 'zigzag':
								return $fullwidth ? array( 'cozystay_780x9999', 'cozystay_1200x9999' ) : array( 'cozystay_550x9999', 'cozystay_780x9999' );
							case 'grid-2cols':
							case 'grid-3cols':
							case 'overlay-2cols':
							case 'overlay-3cols':
							case 'coverlay-2cols':
							case 'coverlay-3cols':
							case 'carousel-2cols':
							case 'carousel-3cols':
								return array( 'cozystay_780x9999', 'cozystay_1200x9999' );
							case 'carousels-1cols':
							case 'carousel-1cols':
							case 'coverlay-1cols':
							case 'coverlays-1cols':
								return array( 'cozystay_1200x9999', 'cozystay_1920x9999' );
						}
						break;
					case 'site':
						if ( $sub_module ) {
							switch ( $sub_module ) {
								case 'popup-background':
									return array( 'cozystay_550x9999', 'cozystay_780x9999' );
								case 'mobile-menu-background':
									return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
								case 'page-header':
								case 'footer-background-image':
									return array( 'cozystay_1920x9999', 'cozystay_1920x9999' );
								case 'page-header-404':
									return array( 'cozystay_300x300-crop', 'cozystay_600x600-crop' );
							}
						}
						break;
					case 'block':
						switch( $sub_module ) {
							case 'inline-post':
								return array( 'cozystay_255x9999', 'cozystay_370x9999' );
						}
						break;
				}
			}
			return self::$default_sizes;
		}
		/**
		* Get image sizes for given situation
		* @param array
		* @return array
		*/
		public static function get_image_sizes( $args ) {
			$sizes = self::get_image_sizes_array( $args );
			return apply_filters( 'cozystay_image_sizes', $sizes, $args );
		}
		/**
		* Get image size for room archive pages
		*/
		public static function room_featured_image_size( $size, $layout, $column ) {
			if ( ! function_exists( 'cozystay_get_post_list_prop' ) ) {
				$inc_dir = COZYSTAY_THEME_INC;
				require_once $inc_dir . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once $inc_dir . 'front/functions-post-list.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
			$sizes = self::get_image_sizes( array( 'module' => 'archive', 'sub_module' => ( empty( $column ) ? $layout : $layout . '-' . $column . 'cols' ) ) );
			return isset( $sizes[0] ) ? $sizes[0] : $size;

		}
		/**
		* Get image size for given situation
		* @param array
		* @return string
		*/
		public static function get_image_size( $args ) {
			$sizes = self::get_image_sizes( $args );
			return isset( $sizes[0] ) ? $sizes[0] : self::$default_size;
		}
		/**
		* Change image attribute
		*/
		public static function change_image_attrs( $attr, $original_attrs ) {
			$attribute_sizes = CozyStay_Utils_Image::get_attribute_sizes();
			$layout = cozystay_get_post_list_prop( 'layout' );
			$col = cozystay_get_post_list_prop( 'columns' );
			$newAttr = array();
			if ( ! empty( $attribute_sizes ) ) {
				$newAttr = array( 'sizes' => $attribute_sizes );
			}
			if ( ! empty( $layout ) && ! empty( $col ) ) {
				if ( empty( $original_attrs['class'] ) ) {
					$newAttr['class'] = ' image-layout-' . $layout . '-column-' . $col;
				} else {
					$newAttr['class'] = $original_attrs['class'] . ' image-layout-' . $layout . '-column-' . $col;
				}
			}
			if ( 0 === count( $newAttr ) ) {
				return $attr;
			} else if ( empty( $attr ) ) {
				return $newAttr;
			} else {
				return array_merge( (array)$attr, $newAttr );
			}
		}
		/**
		* Get image sizes
		*/
		public static function get_attribute_sizes() {
			$layout = cozystay_get_post_list_prop( 'layout' );
			if ( ( ! cozystay_module_enabled( 'cozystay_general_enable_full_image_size' ) ) && ( ! empty( $layout ) ) ) {
				$page_layout = cozystay_get_post_list_prop( 'page_layout' );
				$fullwidth = empty( $page_layout );

				switch( $layout ) {
					case 'masonry':
						if ( self::$is_mobile ) {
							return '420px';
						} else {
							$column = cozystay_get_post_list_prop( 'columns' );
							if ( 5 == $column ) {
								return '(max-width: 1366px) 255px, 303px';
							} else if ( 3 == $column ) {
								return $fullwidth ? '(max-width: 1366px) 353px, 433px' : '(max-width: 1366px) 233px, 313px';
							} else {
								return $fullwidth ? '(max-width: 1366px) 550px, 670px' : '(max-width: 1366px) 370px, 490px';
							}
						}
						break;
					case 'large':
						if ( self::$is_mobile ) {
							return '420px';
						} else {
							return $fullwidth ? '(max-width: 1366px) 1140px, 1380px' : '(max-width: 1366px) 780px, 1020px';
						}
						break;
					case 'other':
						if ( self::$is_mobile ) {
							return '420px';
						} else {
							return $fullwidth ? '(max-width: 1366px) 540px, 660px' : '(max-width: 1366px) 360px, 480px';
						}
						break;
				}
			}
			return false;
		}
	}
	CozyStay_Utils_Image::init();
}
