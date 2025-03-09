<?php
if ( ! class_exists( 'CozyStay_Utils_Custom_Block' ) ) {
	class CozyStay_Utils_Custom_Block {
        /**
        * Array cache the request results
        */
        protected static $cache = array();
		/**
		* Array site header args
		*/
		protected static $site_header_args = false;
        /**
        * Boolean if required plugins activated
        */
        protected static $is_plugins_activated = false;
        /**
        * Setup the initial status
        */
        public static function init() {
            CozyStay_Utils_Custom_Block::$is_plugins_activated = cozystay_is_theme_core_activated();

			add_filter( 'cozystay_get_custom_post_type', 'CozyStay_Utils_Custom_Block::get_custom_post_type_list', 10, 2 );
            add_action( 'cozystay_the_site_header', 'CozyStay_Utils_Custom_Block::the_site_header' );
			add_action( 'cozystay_the_main_site_footer', 'CozyStay_Utils_Custom_Block::the_main_site_footer' );
			add_action( 'cozystay_the_before_site_footer', 'CozyStay_Utils_Custom_Block::the_before_site_footer' );
			add_action( 'cozystay_the_mobile_menu', 'CozyStay_Utils_Custom_Block::the_mobile_menu' );
			add_action( 'cozystay_the_404', 'CozyStay_Utils_Custom_Block::the_404' );

			add_action( 'loftocean_custom_site_headers_before', 'CozyStay_Utils_Custom_Block::custom_site_header_before' );
			add_action( 'loftocean_custom_site_headers_after', 'CozyStay_Utils_Custom_Block::custom_site_header_after' );
        }
		/**
		* Get custom post type list
		*/
		public static function get_custom_post_type_list( $list, $post_type ) {
			if ( ! empty( $post_type ) && post_type_exists( $post_type ) ) {
				if ( ! isset( self::$cache[ $post_type ] ) ) {
					self::$cache[ $post_type ] = apply_filters( 'loftocean_get_custom_post_type_list', array(), $post_type );
				}
				return self::$cache[ $post_type ];
			}
			return array( '0' => esc_html__( 'Choose from the list', 'cozystay' ) );
		}
        /**
        * Check if use the custom block item
        */
        public static function check_custom_block( $custom_block ) {
			return CozyStay_Utils_Custom_Block::$is_plugins_activated && cozystay_does_item_exist( $custom_block );
        }
        /**
        * Show the site header
        */
        public static function the_site_header() {
			if ( apply_filters( 'cozystay_show_site_header', true ) ) {
				$customize_is_custom_site_header = ( 'custom' ==  cozystay_get_theme_mod( 'cozystay_site_header' ) );
				$is_custom_site_header = apply_filters( 'cozystay_is_custom_site_header', $customize_is_custom_site_header );
				if ( $is_custom_site_header ) {
					$custom_block = apply_filters(
						'cozystay_site_header_custom_block',
						( $customize_is_custom_site_header ? cozystay_get_theme_mod( 'cozystay_site_header_main_custom_block' ) : '' )
					);
					if ( CozyStay_Utils_Custom_Block::check_custom_block( $custom_block ) ) {
						self::$site_header_args = array( 'data' => apply_filters( 'cozystay_get_site_header_attrs', array( 'data-sticky-status' => cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) ) ) );
						do_action( 'loftocean_the_custom_site_headers_content', $custom_block );
					} else {
		            	CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/defaults/site-header', array( 'site_header' => 'custom' ) );
					}

					if ( 'disable' != cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) ) {
						$sticky_custom_block = apply_filters(
							'cozystay_sticky_site_header_custom_block',
							( $customize_is_custom_site_header ? cozystay_get_theme_mod( 'cozystay_sticky_site_header_custom_block' ) : '' )
						);
						if ( CozyStay_Utils_Custom_Block::check_custom_block( $sticky_custom_block ) ) {
							self::$site_header_args = array( 'id' => 'sticky-site-header', 'class' => array( 'site-header', 'custom-sticky-header', 'hide' ) );
							do_action( 'loftocean_the_custom_site_headers_content', $sticky_custom_block );
						}
					}
				} else {
					CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/defaults/site-header', array( 'site_header' => 'default' ) );
				}
			}
        }
        /**
        * Show the site footer main section
        */
        public static function the_main_site_footer() {
			if ( apply_filters( 'cozystay_show_main_site_footer', true ) ) :
				$custom_block = apply_filters( 'cozystay_custom_site_footer_main', cozystay_get_theme_mod( 'cozystay_site_footer_main_custom_block' ) );
				if ( CozyStay_Utils_Custom_Block::check_custom_block( $custom_block ) ) : ?>
					<div class="site-footer-main">
						<div class="container"><?php do_action( 'loftocean_the_custom_blocks_content', $custom_block ); ?></div>
					</div><?php
				endif;
			endif;
        }
        /**
        * Show the section above site footer
        */
        public static function the_before_site_footer() {
			if ( apply_filters( 'cozystay_show_before_site_footer', true ) ) :
				$custom_site_footer_above = apply_filters( 'cozystay_custom_site_footer_above', false );
				if ( ! empty( $custom_site_footer_above ) && CozyStay_Utils_Custom_Block::check_custom_block( $custom_site_footer_above ) ) : ?>
					<div class="before-footer">
						<div class="container"><?php do_action( 'loftocean_the_custom_blocks_content', $custom_site_footer_above ); ?></div>
					</div><?php
				else :
					if ( 'text' == cozystay_get_theme_mod( 'cozystay_above_site_footer_content_source' ) ) :
						CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/defaults/before-site-footer' );
					else :
						$custom_block = cozystay_get_theme_mod( 'cozystay_above_site_footer_custom_block' );
						if ( CozyStay_Utils_Custom_Block::check_custom_block( $custom_block ) ) : ?>
							<div class="before-footer">
								<div class="container"><?php do_action( 'loftocean_the_custom_blocks_content', $custom_block ); ?></div>
							</div><?php
						endif;
					endif;
				endif;
			endif;
        }
        /**
        * Show the site footer
        */
        public static function the_mobile_menu( $args = array() ) {
			$custom_block = apply_filters( 'cozystay_custom_mobile_menu', cozystay_get_theme_mod( 'cozystay_mobile_site_header_menu' ) );
			if ( CozyStay_Utils_Custom_Block::check_custom_block( $custom_block ) ) :
				self::mobile_menu_wrapper_start();
						if ( ! cozystay_module_enabled( 'cozystay_mobile_site_header_hide_default_close_button' ) ) : ?>
							<div class="sidemenu-header"><span class="close-button"><?php esc_html_e( 'Close', 'cozystay' ); ?></span></div><?php
						endif; ?>
						<div class="sidemenu-content"><?php do_action( 'loftocean_the_custom_blocks_content', $custom_block ); ?></div>
					</div>
				</div><?php
			else :
				CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/defaults/mobile-menu', $args );
			endif;
        }
        /**
        * Show the site footer
        */
        public static function the_404() {
			if ( apply_filters( 'cozystay_is_custom_404', false ) ) {
				CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/content/custom-404' );
			} else {
				CozyStay_Utils_Custom_Block::the_template_part( 'template-parts/defaults/404' );
			}
        }
		/**
		* Display the template part
		*/
		protected static function the_template_part( $file, $args = array() ) {
			get_template_part( $file, '', $args );
		}
		/**
		* Custom site header before
		*/
		public static function custom_site_header_before( $args ) {
			if ( self::$site_header_args ) :
				$attrs = array_merge( array( 'id' => 'masthead', 'class' => array( 'site-header' ), 'data' => array() ), self::$site_header_args );
				$attrs[ 'class' ] = is_array( $attrs[ 'class' ] ) ? $attrs[ 'class' ] : (array)$attrs[ 'class' ];
				if ( ! empty( $args ) && ! empty( $args[ 'enable_overlap' ] ) && ( 'on' == $args[ 'enable_overlap' ] ) ) {
					array_push( $attrs[ 'class' ], 'overlap-header' );
				} ?>
				<header id="<?php echo esc_attr( $attrs[ 'id' ] ); ?>" class="<?php echo esc_attr( implode( ' ', $attrs[ 'class' ] ) ); ?>"<?php cozystay_the_tag_attributes( $attrs[ 'data' ] ); ?>><?php
			endif;
		}
		/**
		* Custom site header after
		*/
		public static function custom_site_header_after() {
			if ( self::$site_header_args ) : ?>
				</header><?php
				self::$site_header_args = false;
			endif;
		}
		/**
		* Mobile menu wrapper start
		*/
		public static function mobile_menu_wrapper_start( $args = array() ) {
			if ( apply_filters( 'cozystay_mobile_menu_from_custom_settings', false ) ) :
				$attrs = apply_filters( 'cozystay_mobile_menu_wrapper_start_attributes', array() ); ?>
				<div <?php self::the_html_attributes( $attrs ); ?>>
					<div class="container"><?php
			else :
				$wrap_class = isset( $args, $args[ 'class' ] ) ? 'sidemenu-custom ' . $args[ 'class' ] : 'sidemenu-custom'; ?>
				<div <?php cozystay_the_mobile_menu_class( $wrap_class ); ?>><?php
				$mobile_site_header_background_image = cozystay_get_theme_mod( 'cozystay_mobile_site_header_background_image' );
			    $background_image_args = array(
			        'id' 	=> $mobile_site_header_background_image,
			        'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'mobile-menu-background' ) )
			    ); ?>
					<div class="container"<?php cozystay_does_attachment_exist( $mobile_site_header_background_image ) ? cozystay_the_background_image_attrs( $background_image_args ) : ''; ?>><?php
			endif;
		}
		/**
		* Printe html attributes
		*/
		public static function the_html_attributes( $attrs ) {
			if ( cozystay_is_valid_array( $attrs ) ) {
				foreach( $attrs as $name => $value ) {
					printf( ' %1$s="%2$s"', esc_attr( $name ), esc_attr( $value ) );
				}
			}
		}
    }
    CozyStay_Utils_Custom_Block::init();
}
