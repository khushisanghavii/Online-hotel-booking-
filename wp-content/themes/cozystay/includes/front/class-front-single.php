<?php
if ( ! class_exists( 'CozyStay_Front_Single' ) ) {
	class CozyStay_Front_Single {
		/**
		* Boolean is woocommerce shop page
		*/
		protected $is_shop_page = false;
		/**
		* Number current post id
		*/
		protected $current_post_id = false;
		/**
		* String current post type
		*/
		protected $current_post_type = '';
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'template_redirect', array( $this, 'init_hooks' ), 999 );
		}
		/**
		* Init hooks
		*/
		public function init_hooks() {
			$this->single_settings();
			if ( is_singular() ) {
				add_action( 'wp_head', array( $this, 'singular_pingback' ), 1 );
				add_filter( 'comment_form_fields', array( $this, 'change_comment_field_order' ), 9999, 1 );
				add_filter( 'cozystay_content_class', array( $this, 'content_class' ) );
				add_filter( 'prepend_attachment', array( $this, 'attachment_page_content' ), 99 );
				add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout'), 99 );
				add_filter( 'cozystay_front_get_related_posts_args', array( $this, 'related_posts_query_args' ), 10, 3 );
			}
		}
		/**
		* Single page/post individual settings
		*/
		public function single_settings() {
			$this->is_shop_page = cozystay_is_woocommerce_shop_page();
			$is_custom_404 = apply_filters( 'cozystay_is_custom_404', false );
			if ( $this->is_singular() || $this->is_shop_page || $is_custom_404 ) {
				$this->current_post_id = $this->is_shop_page ? wc_get_page_id( 'shop' )
					: ( $is_custom_404 ? apply_filters( 'cozystay_get_custom_404_page_id', false ) : get_queried_object_id() );
				$this->current_post_type = is_singular( 'post' ) ? 'post' : 'page';
				$show_site_header = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_hide_site_header', true );
				$show_site_footer_above = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_site_footer_hide_above', true );
				$show_site_footer_main = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_site_footer_hide_main', true );
				$show_site_footer_instagram = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_site_footer_hide_instagram', true );
				$show_site_footer_bottom = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_site_footer_hide_bottom', true );

				add_filter( 'cozystay_show_site_header', $show_site_header ? '__return_true' : '__return_false', 999 );
				add_filter( 'cozystay_show_before_site_footer', $show_site_footer_above ? '__return_true' : '__return_false', 999 );
				add_filter( 'cozystay_show_main_site_footer', $show_site_footer_main ? '__return_true' : '__return_false', 999 );
				add_filter( 'cozystay_show_site_footer_instagram', $show_site_footer_instagram ? '__return_true' : '__return_false', 999 );
				add_filter( 'cozystay_show_site_footer_bottom', $show_site_footer_bottom ? '__return_true' : '__return_false', 999 );
				add_filter( 'cozystay_show_site_footer', $show_site_footer_main || $show_site_footer_above || $show_site_footer_instagram || $show_site_footer_bottom ? '__return_true' : '__return_false', 999 );

				if ( 'custom' == get_post_meta( $this->current_post_id, 'cozystay_single_' . $this->current_post_type . '_site_header_source', true ) ) {
					add_filter( 'cozystay_is_custom_site_header', '__return_true', 999 );
					add_filter( 'cozystay_site_header_custom_block', array( $this, 'custom_site_header' ), 99, 1 );
					add_filter( 'cozystay_sticky_site_header_custom_block', array( $this, 'custom_sticky_site_header' ), 99, 1 );
				}
				if ( 'custom' == get_post_meta( $this->current_post_id, 'cozystay_single_custom_site_footer_main_source', true ) ) {
					add_filter( 'cozystay_custom_site_footer_main', array( $this, 'custom_site_footer_main' ), 99 );
				}
				if ( 'custom' == get_post_meta( $this->current_post_id, 'cozystay_single_custom_site_footer_above_source', true ) ) {
					add_filter( 'cozystay_custom_site_footer_above', array( $this, 'custom_site_footer_above' ), 99 );
				}

				if ( 'custom' == get_post_meta( $this->current_post_id, 'cozystay_single_custom_mobile_menu_source', true ) ) {
					add_filter( 'cozystay_mobile_menu_from_custom_settings', '__return_true' );
					add_filter( 'cozystay_custom_mobile_menu', array( $this, 'custom_mobile_menu' ), 99 );
					add_filter( 'cozystay_mobile_menu_wrapper_start_attributes', array( $this, 'custom_mobile_menu_wrapper_attributes' ) );
				}
				if ( 'page' == $this->current_post_type ) {
					$show_page_title = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_page_hide_page_title', true );
					add_filter( 'cozystay_show_page_title_section', $show_page_title ? '__return_true' : '__return_false', 999 );
					add_filter( 'cozystay_theme_mod', array( $this, 'page_title_background_settings' ), 999, 2 );
					add_filter( 'cozystay_page_title_default_class', array( $this, 'page_title_background_image_class' ), 999 );
					add_filter( 'body_class', array( $this, 'page_body_class' ) );
				} else if ( 'post' == $this->current_post_type ) {
					$show_post_title = 'on' != get_post_meta( $this->current_post_id, 'cozystay_single_post_hide_page_title', true );
					add_filter( 'cozystay_show_post_title_section', $show_post_title ? '__return_true' : '__return_false', 999 );
				}
			}
		}
		/**
		* Output pingback
		*/
		public function singular_pingback() {
			if ( pings_open( get_queried_object() ) ) : ?>
				<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"><?php
			endif;
		}
		/**
		* Change page title background settings
		*/
		public function page_title_background_settings( $value, $id ) {
			$settings = array(
				'cozystay_page_title_default_background_image' => '',
				'cozystay_page_title_default_background_size' => 'cozystay_single_page_header_background_size',
				'cozystay_page_title_default_background_position_x' => 'cozystay_single_page_header_background_position_x',
				'cozystay_page_title_default_background_position_y' => 'cozystay_single_page_header_background_position_y',
				'cozystay_page_title_default_background_repeat' => 'cozystay_single_page_header_background_repeat',
				'cozystay_page_title_default_background_attachment' => 'cozystay_single_page_header_background_scroll',
				'cozystay_page_title_section_size' => 'cozystay_single_page_header_section_size',
				'cozystay_page_title_default_background_color' => 'cozystay_single_page_header_background_color',
				'cozystay_page_title_default_text_color' => 'cozystay_single_page_header_text_color',
				'cozystay_page_title_show_breadcrumb' => 'cozystay_single_page_header_show_breadcrumb'
			);
			if ( in_array( $id, array_keys( $settings ) ) ) {
				$current_page_id = $this->current_post_id;
				switch ( $id ) {
					case 'cozystay_page_title_default_background_color':
					case 'cozystay_page_title_default_text_color':
						$color = get_post_meta( $current_page_id, $settings[ $id ], true );
						return empty( $color ) ? $value : $color;
					case 'cozystay_page_title_show_breadcrumb':
						$status = get_post_meta( $current_page_id, $settings[ $id ], true );
						$woocommerce_pages = apply_filters( 'cozystay_woocommerce_static_pages', array() );
						if ( in_array( $current_page_id, $woocommerce_pages ) ) {
							return 'hide' == $status ? '' : 'on';
						} else {
							return empty( $status ) ? $value : ( 'show' == $status ? 'on' : '' );
						}
					case 'cozystay_page_title_section_size':
						$size = get_post_meta( $current_page_id, $settings[ $id ], true );
						return empty( $size ) ? $value : $size;
					default:
						$thumb_id = get_post_thumbnail_id( $current_page_id );
						if ( cozystay_does_attachment_exist( $thumb_id ) ) {
							if ( 'cozystay_page_title_default_background_image' == $id ) {
								return $thumb_id;
							} else {
								$cvalue = get_post_meta( $current_page_id, $settings[ $id ], true );
								return empty( $cvalue ) ? $value : $cvalue;
							}
						}
				}
			}
			return $value;
		}
		/**
		* Change page title background image class
		*/
		public function page_title_background_image_class( $class ) {
			$thumb_id = get_post_thumbnail_id();
			return cozystay_does_attachment_exist( $thumb_id ) ? 'page-title-bg' : $class;
		}
		/**
		* Get main content layout class
		*/
		public function content_class( $class ) {
			$layout = $this->get_page_layout( '' );
			$sidebar_id = apply_filters( 'cozystay_sidebar_id', 'main-sidebar' );
			if ( ! empty( $layout ) && is_active_sidebar( $sidebar_id ) ) {
				array_push( $class, $layout );
			}
			return $class;
		}
		/**
		* Change the attachment page image size to 'large'
		* @param string $attachment_content the attachment html
		* @return string $attachment_content the attachment html
		*/
		public function attachment_page_content( $content ) {
			if ( is_attachment() ) {
				$attachment_id = get_queried_object_id();
				$attachment = wp_get_attachment_image( $attachment_id );
				if ( ! empty( $attachment ) ) {
					$caption = wp_get_attachment_caption( $attachment_id );
					$content = sprintf(
						'<figure class="entry-attachment wp-block-image">%1$s%2$s</figure>',
						wp_get_attachment_link( $attachment_id, 'cozystay_medium', false ),
						empty( $caption ) ? '' : sprintf( '<figcaption class="wp-caption-text">%s</figcaption>', $caption )
					);
				}
			}
			return $content;
		}
		/**
		* Get page sidebar settings
		*/
		public function get_page_layout( $layout ) {
			if ( 'page' == $this->current_post_type ) {
				$current_page_id = $this->current_post_id;
				$template = $this->get_template_slug();
				switch ( $template ) {
					case 'template-fullwidth.php':
					case 'template-wide-content.php':
						return '';
					case 'template-left-sidebar.php':
						return 'with-sidebar-left';
					default:
						return 'with-sidebar-right';
				}
			} else if ( 'post' == $this->current_post_type ) {
				$single_setting = get_post_meta( get_queried_object_id(), 'cozystay_single_post_template', true );
				if ( empty( $single_setting ) ) {
					return cozystay_get_theme_mod( 'cozystay_blog_single_post_layout' );
				} else {
					return ( 'fullwidth' == $single_setting ) ? '' : $single_setting;
				}
			}
			return $layout;
		}
		/**
		* Extra body class for single page
		*/
		public function page_body_class( $class ) {
			if ( 'page' == $this->current_post_type ) {
				$current_page_id = $this->current_post_id;
				$template = $this->get_template_slug();
				$may_exists_class = array(
					'page-template-template-wide-content', 'page-template-template-wide-content-php', 'cs-template-wide',
					'page-template-template-fullwidth', 'page-template-template-fullwidth-php',
					'page-template-template-left-sidebar', 'page-template-template-left-sidebar-php',
					'page-template-default'
				);
				$class = array_diff( $class, $may_exists_class );
				if ( in_array( $template, array( 'template-fullwidth.php', 'template-left-sidebar.php', 'template-wide-content.php' ) ) ) {
					$template_class = array(
						'template-left-sidebar.php' => array( 'page-template-template-left-sidebar', 'page-template-template-left-sidebar-php' ),
						'template-fullwidth.php' => array( 'page-template-template-fullwidth', 'page-template-template-fullwidth-php' ),
						'template-wide-content.php' => array( 'page-template-template-wide-content', 'page-template-template-wide-content-php', 'cs-template-wide' ),
					);
					$class = array_merge( $class, $template_class[ $template ] );
				} else if ( empty( $template ) || ( 'elementor_theme' == $template ) ) {
					array_push( $class, 'page-template-default' );
				}
			}
			return $class;
		}
		/**
		* Get related posts
		* @param object WP_Query results
		* @param string filter, category or tag ...
		* @param number posts number needed, default to 3
		* @return object WP_Qurey results
		*/
		public function related_posts_query_args( $related, $filter, $ppp = 2 ) {
			$filters = array( 'category', 'tag', 'author' );
			if ( ! empty( $filter ) && in_array( $filter, $filters ) && ( intval( $ppp ) > 0 ) ) {
				$args = array(
					'posts_per_page'	=> intval( $ppp ),
					'post__not_in'		=> array( get_the_ID() ),
					'orderby' 			=> 'rand'
				);
				$cats = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
				$tags = wp_get_post_tags( get_the_ID(), array( 'fields' => 'ids' ) );
				$author_id = get_the_author_meta( 'ID' );
				switch ( $filter ) {
					case 'category':
						if ( ! empty($cats ) ) {
							$args['category__in'] = $cats;
						}
						break;
					case 'tag':
						if ( ! empty( $tags ) ) {
							$args['tag__in'] = $tags;
						}
						break;
					default:
						if ( ! empty( $author_id ) ) {
							$args['author'] = $author_id;
						}
				}
			}
			return $args;
		}
		/**
		* Change comment form field order
		*/
		public function change_comment_field_order( $fields ) {
			$reorder = array( 'comment', 'cookies' );
			foreach ( $reorder as $field ) {
				if ( isset( $fields[ $field ] ) ) {
					$comment_field = $fields[ $field ];
					unset( $fields[ $field ] );
					$fields = array_merge( $fields, array( $field => $comment_field ) );
				}
			}
			return $fields;
		}
		/**
		* Check single site header custom block setting
		*/
		public function custom_site_header( $custom_block ) {
			if ( false !== $this->current_post_id ) {
				$meta_name = is_singular( 'post' ) ? 'cozystay_single_post_custom_site_header' : 'cozystay_single_page_custom_site_header';
				$meta_value = get_post_meta( $this->current_post_id, $meta_name, true );
				if ( ! empty( $meta_value ) && cozystay_does_item_exist( $meta_value ) ) {
					return $meta_value;
				}
			}
			return $custom_block;
		}
		/**
		* Check single site header custom block setting
		*/
		public function custom_sticky_site_header( $custom_block ) {
			if ( false !== $this->current_post_id ) {
				$meta_name = is_singular( 'post' ) ? 'cozystay_single_post_custom_sticky_site_header' : 'cozystay_single_page_custom_sticky_site_header';
				$meta_value = get_post_meta( $this->current_post_id, $meta_name, true );
				if ( ! empty( $meta_value ) && cozystay_does_item_exist( $meta_value ) ) {
					return $meta_value;
				}
			}
			return $custom_block;
		}
		/**
		* Get current page template slug
		*/
		protected function get_template_slug() {
			if ( ( 'page' == $this->current_post_type ) && cozystay_is_elementor_activated() && ( is_preview() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
				$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $this->current_post_id );
				if ( $document->is_built_with_elementor() ) {
				 	return $document->get_meta( '_wp_page_template' );
				}
			}
			return get_page_template_slug( $this->current_post_id );
		}
		/**
		* Helper function
		*/
		protected function is_singular() {
			if ( is_singular( array( 'post', 'page' ) ) ) {
				return true;
			} else {
				$queried_object = get_queried_object();
				return ( $queried_object instanceof WP_Post ) && ( 'page' == $queried_object->post_type );
			}
		}
		/**
		* Custom site footer main
		*/
		public function custom_site_footer_main( $custom_block ) {
			if ( false !== $this->current_post_id ) {
				$meta_value = get_post_meta( $this->current_post_id, 'cozystay_single_custom_site_footer_main', true );
				if ( ! empty( $meta_value ) && cozystay_does_item_exist( $meta_value ) ) {
					return $meta_value;
				}
			}
			return $custom_block;
		}
		/**
		* Custom site footer above
		*/
		public function custom_site_footer_above( $custom_block ) {
			if ( false !== $this->current_post_id ) {
				$meta_value = get_post_meta( $this->current_post_id, 'cozystay_single_custom_site_footer_above', true );
				if ( ! empty( $meta_value ) && cozystay_does_item_exist( $meta_value ) ) {
					return $meta_value;
				}
			}
			return $custom_block;
		}
		/**
		* Custom mobile menu
		*/
		public function custom_mobile_menu( $custom_block ) {
			if ( false !== $this->current_post_id ) {
				$meta_value = get_post_meta( $this->current_post_id, 'cozystay_single_custom_mobile_menu', true );
				if ( ! empty( $meta_value ) && cozystay_does_item_exist( $meta_value ) ) {
					return $meta_value;
				}
			}
			return $custom_block;
		}
		/**
		* Custom mobile menu wrapper attributes
		*/
		public function custom_mobile_menu_wrapper_attributes( $attrs = array() ) {
			if ( false !== $this->current_post_id ) {
				$classes = array( 'sidemenu', 'sidemenu-custom' );
				$styles = '';
				$animation = get_post_meta( $this->current_post_id, 'cozystay_single_custom_mobile_menu_animation', true );
				$width = get_post_meta( $this->current_post_id, 'cozystay_single_custom_mobile_menu_width', true );
				$custom_width = get_post_meta( $this->current_post_id, 'cozystay_single_custom_mobile_menu_custom_width', true );
				if ( ! empty( $animation ) ) {
					array_push( $classes, $animation );
				}
				if ( ! empty( $width ) ) {
					array_push( $classes, $width );
					if ( 'custom-width' == $width ) {
						$styles = sprintf( 'max-width: %spx;', $custom_width );
					}
				}
				$attrs[ 'class' ] = implode( ' ', $classes );
				if ( ! empty ( $styles ) ) {
					$attrs[ 'style' ] = $styles;
				}
			}
			return $attrs;
		}
	}
	new CozyStay_Front_Single();
}
