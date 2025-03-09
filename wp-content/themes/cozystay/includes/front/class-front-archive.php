<?php
if ( ! class_exists( 'CozyStay_Front_Archive' ) ) {
	class CozyStay_Front_Archive {
		/**
		* String archive page currently requested
		*/
		protected $archive_page = '';
		/**
		* Construct function
		*/
		public function __construct() {
            add_action( 'pre_get_posts', array( $this, 'set_archive_page_args' ), 1 );
            add_action( 'template_redirect', array( $this, 'init_hooks' ), 999 );
			add_action( 'cozystay_the_list_content', array( $this, 'the_list_content' ), 10, 1 );
			add_action( 'cozystay_the_list_content_html', array( $this, 'the_list_content_html' ), 10, 2 );
			add_action( 'cozystay_pre_ajax_handler', array( $this, 'pre_ajax_start' ), 99 );
		}
        /**
        * Init hooks for archive pages
        */
        public function init_hooks() {
			if ( ! empty( $this->archive_page ) || is_archive() ) {
                add_filter( 'cozystay_get_current_achive_page', array( $this, 'get_archive_page' ) );
                add_filter( 'cozystay_get_current_page_layout', array( $this, 'get_page_layout' ), 9999 );
				add_filter( 'cozystay_content_class', array( $this, 'content_class' ) );
				add_filter( 'body_class', array( $this, 'body_class' ) );
				add_filter( 'get_the_archive_title_prefix', '__return_false', 999 );
			}
        }
		/**
		* Action before ajax start
		*/
		public function pre_ajax_start( $page ) {
			$this->archive_page = $page;
			require_once COZYSTAY_THEME_INC . 'front/functions-post-list.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once COZYSTAY_THEME_INC . 'front/functions-post-list-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			add_filter( 'cozystay_get_current_achive_page', array( $this, 'get_archive_page' ) );
			add_filter( 'cozystay_content_class', array( $this, 'content_class' ) );
		}
		/**
		* Set arvhice page query args
		* @param object
		*/
		public function set_archive_page_args( $query ) {
			if ( $query->is_main_query() && ! is_admin() ) {
				if ( is_category() ) {
					$this->archive_page = 'category';
				} else if ( is_author() ) {
					$this->archive_page = 'author';
				} else if ( is_search() ) {
                    $this->archive_page = 'search';
					if ( empty( $_REQUEST['post_type'] ) ) {
						$supported_post_types = array_keys( cozystay_get_post_types() );
						$current_post_types = cozystay_get_theme_mod( 'cozystay_general_search_default_post_types' );
						$default_post_types = array_intersect( $supported_post_types, $current_post_types );
					 	$query->set( 'post_type', cozystay_is_valid_array( $default_post_types ) ? $default_post_types : 'post' );
					} else {
						$query->set( 'post_type', explode( ',', sanitize_text_field( wp_unslash( $_REQUEST['post_type'] ) ) ) );
					}
                } else if ( is_tag() ) {
					$this->archive_page = 'tag';
				} else if ( is_date() ) {
					$this->archive_page = 'date';
				} else if ( is_home() ) {
					$this->archive_page = 'blog';
				} else if ( is_post_type_archive( 'post' ) || is_tax( 'post_format' ) ) {
					$this->archive_page = 'post-archive';
				} else if ( is_post_type_archive() || is_tax() ) {
					$this->archive_page = 'archive';
				}
			}
		}
		/**
		* Get main content layout class
		*/
		public function content_class( $class ) {
			$layout = $this->get_page_layout( '' );
			$has_sidebar = ! empty( $layout );
			$class = array_diff( $class, array( 'with-sidebar-right', 'with-sidebar-left' ) );
			$sidebar_id = apply_filters( 'cozystay_sidebar_id', 'main-sidebar' );
			if ( $has_sidebar && is_active_sidebar( $sidebar_id ) ) {
				array_push( $class, $layout );
			}
			return $class;
		}
        /**
        * Get archive page requested currently
        * @param string
        * @return string
        */
        public function get_archive_page( $request ) {
            return $this->archive_page;
        }
        /**
        * Get archive page layout
        * @param string
        * @return string
        */
        public function get_page_layout( $layout ) {
			if ( ! empty( $this->archive_page ) ) {
				return 'archive' == $this->archive_page ? 'with-sidebar-right' : cozystay_get_theme_mod( 'cozystay_blog_page_layout' );
            }
            return $layout;
        }
		/**
		* Output the list content
		*/
		public function the_list_content( $page = '' ) {
			$page = empty( $page ) ? $this->archive_page : $page;
			if ( empty( $page ) ) {
				return;
			}

			if ( 'archive' == $page ) {
				$sets = array( 'layout' => 'list', 'column' => false );
				$metas = array( 'excerpt', 'read_more_btn', 'author', 'date' );
				$class = cozystay_list_get_default_wrap_class();
			} else {
				$sets = cozystay_get_post_list_layout_settings();
				$metas = cozystay_get_list_post_meta();
				$class = cozystay_list_get_wrap_class( $sets );
				$class = apply_filters( 'cozystay_post_list_wrapper_class', $class, array( 'layout' => $sets[ 'layout' ], 'post_meta' => $metas ) );
			}
			if ( have_posts() ) : ?>
				<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>"><?php
				do_action( 'cozystay_the_list_content_html', array(
					'layout'	=> $sets['layout'],
					'columns'	=> $sets['column'],
					'post_meta'	=> $metas,
					'page_layout' => apply_filters( 'cozystay_get_current_page_layout', '' )
				) ); ?>
				</div><?php
			else :
				get_template_part( 'template-parts/content/content-none' );
			endif;
		}
		/**
		* Output the list content html
		*/
		public function the_list_content_html( $args, $no_pagination = false ) {
			$masonry_offset = 0;
			do_action( 'cozystay_start_post_list_loop', $args );
			if ( have_posts() ) : ?>
				<div class="posts-wrapper"><?php
				do_action( 'cozystay_before_list_content' );
				while( have_posts() ) {
					the_post();
					cozystay_set_post_list_prop( 'masonry', cozystay_get_masonry_settings( $args['layout'], $args['columns'], $masonry_offset ) );
					get_template_part( 'template-parts/content/content', $args['layout'] );
				}
				do_action( 'cozystay_after_post_list_content' );
				wp_reset_postdata(); ?>
				</div><?php
				$no_pagination ? '' : cozystay_list_pagination();
			endif;
			wp_reset_postdata();
			do_action( 'cozystay_end_post_list_loop' );
		}
		/**
		* Add class to body classname list
		*/
		public function body_class( $class ) {
			return $class;
		}
		/**
		* Change archive title prefix
		*/
		public function change_archive_title_prefix( $prefix ) {
			return '';
		}
	}
	new CozyStay_Front_Archive();
}
