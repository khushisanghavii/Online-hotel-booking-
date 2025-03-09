<?php
/**
* Builder homepage managet class
*/

if ( ! class_exists( 'CozyStay_Blocker_Render' ) ) {
	class CozyStay_Blocker_Render {
		/**
		* Object instance to make sure only one instance exists
		*/
		public static $instance = false;
		/**
		* Pagination type
		*/
		protected $pagination = false;
		/**
		* Pagination filter added
		*/
		protected $pagination_filter_added = false;
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'loftocean_posts_block_the_list_content', array( $this, 'the_widget_post_list_content' ), 10, 2 );
		}
		/**
		* Output the homepage widget post list content
		* @param array settings
		*/
		public function the_widget_post_list_content( $sets, $no_pagination = true ) {
			if ( have_posts() ) :
				$this->check_required();
				if ( ! $no_pagination && ! empty( $sets[ 'pagination' ] ) ) {
					$this->pagination = $sets[ 'pagination' ];
					if ( ! $this->pagination_filter_added ) {
						add_filter( 'cozystay_theme_mod', array( $this, 'change_pagination_style' ), 9999, 2 );
						$this->pagination_filter_added = true;
					}
				}
				$class = apply_filters( 'cozystay_post_list_wrapper_class', $sets[ 'wrap_class' ], $sets[ 'args' ] ); ?>
				<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>"<?php do_action( 'loftocean_posts_wrap_attributes' ); ?>>
					<?php do_action( 'cozystay_the_list_content_html', $sets[ 'args' ], $no_pagination ); ?>
				</div><?php
				if ( $this->pagination ) {
					$this->pagination = false;
				}
			endif;
		}
		/**
		* Check required function added
		*/
		protected function check_required() {
			if ( ! function_exists( 'cozystay_the_meta_category' ) ) {
				require_once COZYSTAY_THEME_INC . 'front/functions.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/functions-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/functions-post-list.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/functions-post-list-template.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once COZYSTAY_THEME_INC . 'front/class-front-archive.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
		/**
		* Change pagination style
		*/
		public function change_pagination_style( $value, $id ) {
			if ( 'cozystay_blog_general_pagination_style' == $id && ! empty( $this->pagination ) ) {
				return $this->pagination;
			}
			return $value;
		}
		/**
		* Static function to make sure only one instance exists
		* @return object
		*/
		public static function _instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
	CozyStay_Blocker_Render::_instance();
}
