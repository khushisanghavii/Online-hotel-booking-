<?php
/**
* Woocommerce related frontend render class.
*/

if ( class_exists( 'WooCommerce' ) && ! class_exists( 'CozyStay_Woocommerce' ) ) {
	class CozyStay_Woocommerce {
		public static $_instance = null;
		private $wc_pages = array( 'myaccount', 'cart', 'checkout' );
		private $header_bg = false;
		protected $has_header_image = false;
		protected $header_image_id = false;
		private $is_archive = false;
		private $is_static_pages = false;
		public function __construct() { 
			$this->load_support();

			add_action( 'wp', array( $this, 'load_frontend_actions' ), 999 );
			add_action( 'widgets_init', array( $this, 'register_sidebar' ), 9999 );

			add_filter( 'theme_page_templates', array( $this, 'remove_page_template' ), 99, 3 );
			add_filter( 'cozystay_woocommerce_static_pages', array( $this, 'static_pages' ) );
//			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'update_cart_notification' ), 50, 1 );
			add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array( $this, 'gallery_image_size' ) );

			add_filter( 'loftocean_woocommerce_short_description_length', array( $this, 'short_description_length' ) );
		}
		/**
		* Initilize to support woocommerce features
		*/
		public function load_support() {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
		/**
		* Register sidebar for woocommerce archive/product pages
		*/
		public function register_sidebar() {
			register_sidebar( array(
				'name'          => esc_html__( 'Shop Sidebar', 'cozystay' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'cozystay' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="widget-header"><span class="title-decor-line"></span><h5 class="widget-title">',
				'after_title'   => '</h5><span class="title-decor-line"></span></div>'
			) );
		}
		/**
		* Load frontend related actions
		*/
		public function load_frontend_actions() {
			// The class will be initialized in action wp, so it's saft to call all functions
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 5 );
			add_action( 'cozystay_woocommerce_breadcrumbs', array( $this, 'the_breadcrumb' ) );
			add_filter( 'cozystay_front_inline_styles_handler', array( $this, 'inline_style_handler' ) );
			add_filter( 'cozystay_front_get_main_theme_style_dependency', array( $this, 'theme_css_deps' ) );
			add_filter( 'woocommerce_sale_flash', array( $this, 'sale_label' ), 999, 3 );

			$wc_static_page_ids = $this->get_woocommerce_pages( $this->wc_pages );
			$this->is_static_pages = ! empty( $wc_static_page_ids ) && is_page( $wc_static_page_ids );

			if ( ! is_admin() && ( $this->is_shop() || is_product_taxonomy() || is_singular( 'product' ) || $this->is_static_pages ) ) {
				add_filter( 'cozystay_sidebar_id', array( $this, 'get_sidebar_id' ), 999 );
				add_filter( 'cozystay_content_class', array( $this, 'get_content_class' ), 99999 );
				add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout' ), 99999 );
			}
			if ( $this->is_shop() || is_product_taxonomy() || is_singular( 'product' ) ) {
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			}
			if ( is_singular( 'product' ) ) {
				if ( 'custom' === cozystay_get_theme_mod( 'cozystay_woocommerce_single_product_site_header' ) ) {
					add_filter( 'cozystay_show_site_header', '__return_true', 99999 );
					add_filter( 'cozystay_is_custom_site_header', '__return_true', 99999 );
					add_filter( 'cozystay_site_header_custom_block', array( $this, 'get_single_product_page_site_header' ), 99999 );
				}
			}

			if ( $this->is_static_pages ) {
				add_filter( 'template_include', array( $this, 'static_page_template' ), 99 );
			}

			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ), 99 );
			add_filter( 'woocommerce_upsell_display_args', 			array( $this, 'upsell_products_args' ), 99 );
			add_filter( 'woocommerce_product_review_comment_form_args',	array( $this, 'add_comment_form_fields' ) );

			add_action( 'woocommerce_before_main_content', 			array( $this, 'before_main_content' ), 0 );
			add_action( 'woocommerce_after_main_content', 			array( $this, 'after_main_content' ), 999 );
			add_action( 'woocommerce_sidebar', 						array( $this, 'after_sidebar'), 999 );
			add_action( 'woocommerce_before_shop_loop_item_title', 	array( $this, 'loop_out_of_stock' ), 5 );

			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'list_item_open_wrap' ), 1 );
			add_action( 'woocommerce_after_shop_loop_item',  array( $this, 'list_item_close_wrap' ), 20 );

			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_open', 25 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_title', 30 );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'price_rating_wrap_start' ), 30 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 30 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 30 );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'price_rating_wrap_end' ), 30 );
			if ( cozystay_module_enabled( 'cozystay_woocommerce_product_list_show_short_description' ) ) {
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'the_short_description' ), 30 );
			}
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 35 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 40 );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'loop_item_close' ), 50 );

			add_action( 'cozystay_woocommerce_the_short_description', array( $this, 'the_short_description' ) );

			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			add_action( 'woocommerce_after_cart', array( $this, 'woocommerce_cross_sell_display' ) );
		}
		/**
		* Change woocommerce static page template
		*/
		public function static_page_template( $template ) {
		    $new_template = locate_template( array( 'woocommerce-pages.php' ) );
		    if ( '' != $new_template ) {
		        return $new_template ;
		    }
		    return $template;
		}
		/**
		* Get page layout setting id
		* @param string
		* @return string
		*/
		public function get_page_layout( $layout ) {
			return $this->is_static_pages ? '' : cozystay_get_theme_mod( is_singular( 'product' ) ? 'cozystay_woocommerce_single_layout' : 'cozystay_woocommerce_archive_layout' );
		}
		/**
		* Class for site content
		*/
		public function get_content_class( $class ) {
			$class = array_diff( $class, array( 'with-sidebar-right', 'with-sidebar-left' ) );
			$layout = $this->get_page_layout( '' );
			if ( ! empty( $layout ) && is_active_sidebar( 'shop-sidebar' ) ) {
				array_push( $class, $layout );
			}
			return $class;
		}
		/**
		* Remove page template option for woocommerce pages
		* @param array template list
		* @param object WP_Theme class object
		* @param object WP_Post class object
		*/
		public function remove_page_template( $page_templates, $theme, $post ) {
			$pages = $this->get_woocommerce_pages( $this->wc_pages );
			if ( $post && ( count( $pages ) > 0 ) && in_array( absint( $post->ID ), $pages ) ) {
				$page_templates = array();
			}
			return $page_templates;
		}
		/**
		* Get the woocommerce static pages
		*/
		public function static_pages( $pages ) {
			$wc_pages = $this->wc_pages;
			array_push( $wc_pages, 'shop' );
			$wc_page_ids = $this->get_woocommerce_pages( $wc_pages );
			return is_array( $wc_page_ids ) ? array_merge( $pages, $wc_page_ids ) : $pages;
		}
		/**
		* Enqueue woocommerce related style file
		*/
		public function enqueue_script() {
			$suffix = cozystay_get_assets_suffix();
			wp_enqueue_style( 'cozystay-woocommerce', COZYSTAY_ASSETS_URI . 'styles/front/shop' . $suffix . '.css', array( 'cozystay-theme-style' ), COZYSTAY_ASSETS_VERSION );
		}
		/**
		* Add woocommerce style to theme main css dependency list
		*/
		public function theme_css_deps( $deps ) {
			$deps = array_merge( $deps, array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ) );
			return $deps;
		}
		/**
		* Theme inline style handler
		*/
		public function inline_style_handler( $handler ) {
			return 'cozystay-woocommerce';
		}
		/**
		* Get sidebar id for woocommerce pages
		*/
		public function get_sidebar_id( $id ) {
			return 'shop-sidebar';
		}
		/**
		* Change related product query args
		*/
		public function related_products_args( $args ) {
			$args['posts_per_page'] = 4; // 4 related products
			$args['columns'] = 4; // arranged in 4 columns
			return $args;
		}
		/**
		* Change upsell product query args
		*/
		public function upsell_products_args( $args ) {
			$args['posts_per_page'] = 4; // 4 related products
			$args['columns'] = 4; // arranged in 4 columns
			return $args;
		}
		/**
		* Add url field for single product comment form
		*/
		public function add_comment_form_fields( $comment_form ) {
			$fields = isset($comment_form['fields']) ? $comment_form['fields'] : array();
			if ( empty( $fields['url'] ) ) {
				$commenter = wp_get_current_commenter();
				$fields['url'] = sprintf(
					'<p class="comment-form-url"><label for="url">%s</label>%s</p>',
					esc_html__( 'Website', 'cozystay' ),
					sprintf(
						'<input id="url" name="url" type="url" value="%s" size="30" maxlength="200" />',
						esc_attr( $commenter['comment_author_url'] )
					)
				);
			}
			$comment_form['fields'] = $fields;

			return $comment_form;
		}
		public function loop_out_of_stock() {
			global $product;
			if ( ! $product->managing_stock() && ! $product->is_in_stock() ) : ?>
				<span class="stock out-of-stock"><?php esc_html_e( 'Sold Out', 'cozystay' ); ?></span><?php
			endif;
		}
		/**
		* Add open wrap divs before main content
		*/
		public function before_main_content() {
			if ( $this->is_shop() ) :
				if ( apply_filters( 'cozystay_show_page_title_section', true ) ) : ?>
					<header<?php cozystay_the_page_title_class( array( 'woocommerce-products-header' ) ); ?>>
					    <?php cozystay_the_default_page_header_background_image(); ?>
					    <div class="container"><?php
							$this->the_breadcrumb();
							if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
								<h1 class="woocommerce-products-header__title entry-title"><?php woocommerce_page_title(); ?></h1><?php
							endif;
							do_action( 'woocommerce_archive_description' ); ?>
					    </div>
					</header><?php
				endif;
			elseif ( is_product_taxonomy() ) :
				$image_id = $this->get_page_image_id(); ?>
				<header<?php cozystay_the_page_title_class( array( 'woocommerce-products-header' ) ); ?>>
				    <?php if ( cozystay_does_attachment_exist( $image_id ) ) {
				        cozystay_the_preload_bg( array(
				            'id' 	=> $image_id,
				            'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
				            'class' => 'page-title-bg'
				        ) );
				    } else {
				        cozystay_the_default_page_header_background_image();
				    } ?>
				    <div class="container"><?php
						$this->the_breadcrumb();
						if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<h1 class="woocommerce-products-header__title entry-title"><?php woocommerce_page_title(); ?></h1><?php
						endif;
						do_action( 'woocommerce_archive_description' ); ?>
				    </div>
				</header><?php
			elseif ( is_singular( 'product' ) ) :
				if ( ! cozystay_module_enabled( 'cozystay_woocommerce_single_product_hide_page_title_section' ) ) : ?>
					<header<?php cozystay_the_page_title_class( array( 'woocommerce-single-product-header' ) ); ?>>
					    <?php cozystay_the_default_page_header_background_image(); ?>
					    <div class="container">
					        <?php $this->the_breadcrumb(); ?>
					        <h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
					    </div>
					</header><?php
				endif;
			endif; ?>
			<div class="main">
				<div class="container">
					<div id="primary" class="primary content-area"> <?php
		}
		/*
		* Add close wrap div after main content
		*/
		public function after_main_content() { ?>
					</div> <?php
		}
		/**
		* Add close wrap div after sidebar
		*/
		public function after_sidebar() { ?>
				</div>
			</div><?php
		}
		/**
		* Add open wrap div open before product link
		*/
		public function list_item_open_wrap() { ?>
			<div class="product-image"><?php
		}
		/**
		* Add close wrap div after add to cart link
		*/
		public function list_item_close_wrap() { ?>
			</div>
			<div class="cs-product-content"><?php
		}
		/**
		* Loop Item close div 
		*/
		public function loop_item_close() { ?>
			</div><?php
		}
		/**
		* Price and rating wrap start HTML 
		*/ 
		public function price_rating_wrap_start() { ?>
			<div class="price-rating"><?php
		}
		/**
		* Price and rating wrap end HTML 
		*/
		public function price_rating_wrap_end() { ?>
			</div><?php
		}
		/**
		* Change sales label
		*/
		public function sale_label( $label, $post, $product ) {
			return '<span class="onsale">' . esc_html__( 'Sale', 'cozystay' ) . '</span>';
		}
		/**
		* Get woocommerce static page ids
		* @param mix pages
		* @return mix return boolean false if no page passed,
		*	if page exists and only one request one page, return the id,
		*		otherwise return array of ids.
		*/
		private function get_woocommerce_pages( $pages ) {
			$ids = false;
			if ( ! empty( $pages ) ) {
				if ( is_array( $pages ) ) {
					$ids = array();
					foreach ( $pages as $p ) {
						$id = wc_get_page_id( $p );
						if ( ! empty( $id ) && ( $id !== -1 ) ) {
							array_push( $ids, $id );
						}
					}
				} else {
					$ids = wc_get_page_id( $pages );
				}
			}
			return $ids;
		}
		/**
		* Test if is in shop page
		*/
		private function is_shop() {
			return cozystay_is_woocommerce_shop_page();
		}
		/**
		* Get shop page header image id or archive page header image id
		*/
		protected function get_page_image_id() {
			if ( $this->is_shop() ) {
				$shop_page = get_post( wc_get_page_id( 'shop' ) );
				return get_post_thumbnail_id( $shop_page->ID );
			} else if ( is_product_taxonomy() ) {
				$queried = get_queried_object();
				return get_term_meta( $queried->term_id, 'thumbnail_id', true );
			}
			return false;
		}
		/**
		* Update cart notification
		*/
		public function update_cart_notification( $fragments ) {
			$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
			$item_count = WC()->cart->get_cart_contents_count();
			$content = sprintf(
				// translators: 1. url 2. title 3. cart item count
				'<a id="cs-cart-notification" class="cart-contents" href="%1$s" title="%2$s"><span class="cart-icon"></span>%3$s</a>',
				esc_url( $cart_url ),
				esc_attr__( 'View your shopping cart', 'cozystay' ),
				empty( $item_count ) ? '' : sprintf( '<span class="cart-notification has_item">%s</span>', $item_count )
			);
		    $fragments['#cs-cart-notification'] = $content;
			$fragments['#cs-mini-cart-notification'] = $content;

		    return $fragments;
		}
		/**
		* Change gallery image size
		*/
		public function gallery_image_size( $size ) {
			return array(
				'width' => 150,
				'height' => 150,
				'crop' => 0
			);
		}
		/**
		* Show breadcrumbs for woocommerce pages
		*/
		public function the_breadcrumb() {
			if ( ! cozystay_module_enabled( 'cozystay_woocommerce_breadcrumb' ) ) return '';

			cozystay_is_yoast_seo_activated() && cozystay_module_enabled( 'cozystay_woocommerce_yoast_seo_breadcrumb' )
				? cozystay_the_yoast_seo_breadcrumbs() : woocommerce_breadcrumb();
		}
		/**
		* Show short description
		*/
		public function the_short_description( $extra_class = '' ) {
			do_action( 'loftocean_woocommerce_the_short_description', $extra_class );
		}
		/**
		* Change short description length
		*/
		public function short_description_length( $length ) {
			return cozystay_get_theme_mod( 'cozystay_woocommerce_product_list_short_description_length' );
		}
		/**
		* Get custom site header
		*/
		public function get_single_product_page_site_header( $site_header ) {
			return cozystay_get_theme_mod( 'cozystay_woocommerce_single_product_custom_site_header' );
		}
		/**
		* Woocommerce cross sells section
		*/ 
		public function woocommerce_cross_sell_display() {
			woocommerce_cross_sell_display( 4, 4 );
		}
		/**
		* To make sure only one instance exists
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
	CozyStay_Woocommerce::instance();
}
