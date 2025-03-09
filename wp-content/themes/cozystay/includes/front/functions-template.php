<?php
if ( ! function_exists( 'cozystay_get_preload_bg' ) ) :
	/**
	* Return the html tag with image as background image for better user experience
	* Create your own function to override in a child theme.
	* @since 1.0.0
	* @param array settings
	* 		id 		int, 	image id
	* 		size 	string, image size
	*		class 	string, html tag class name
	*		html 	string, html string inside tag
	*		tag 	string, html tag
	*		attrs 	array, 	html tag attributes
	*/
	function cozystay_get_preload_bg( $args = array() ) {
		$default_sizes = array( 'full', 'full' );
		$options 	= array_merge( array(
			'id' 	=> null,
			'sizes' => $default_sizes,
			'class' => 'featured-img-container',
			'tag' 	=> 'div'
		), $args );

		$image_id = empty( $options['id'] ) ? get_post_thumbnail_id() : $options['id'];
		$image_sizes = empty( $options['sizes'] ) ? $default_sizes : $options['sizes'];
		$options['tag'] = empty( $options['tag'] ) ? 'div' : $options['tag'];

		if ( has_filter( 'loftocean_media_get_background_image' ) ) {
			unset( $options['id'], $options['size'] );
			return apply_filters( 'loftocean_media_get_background_image', '', $image_id, $image_sizes, $options );
		} else {
			if ( cozystay_does_attachment_exist( $image_id ) ) {
				$image_src = cozystay_get_image_src( $image_id, $image_sizes[0] );
				$styles = sprintf( 'background-image: url(%s);', esc_url( $image_src ) );
				if ( ! empty( $options['attrs']['style'] ) ) {
					$styles .= ' ' . $options['attrs']['style'];
				}
				return sprintf(
					'<%1$s class="%2$s" style="%3$s"></%1$s>',
					esc_attr( $options['tag'] ),
					esc_attr( $options['class'] ),
					esc_attr( $styles )
				);
			}
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_preload_bg' ) ) :
	/**
	* Output the html tag with image as background image for better user experience
	* Create your own function to override in a child theme.
	* @since 1.0.0
	* @param array settings
	* 		id 		int, 	image id
	* 		size 	string, image size
	*		class 	string, html tag class name
	*		html 	string, html string inside tag
	*		tag 	string, html tag
	*		attrs 	array, 	html tag attributes
	*/
	function cozystay_the_preload_bg( $args = array() ) {
		$default_sizes = array( 'full', 'full' );
		$options 	= array_merge( array(
			'id' 	=> null,
			'sizes' => $default_sizes,
			'class' => 'featured-img-container',
			'tag' 	=> 'div',
			'attrs' => ''
		), $args );

		$image_id = empty( $options['id'] ) ? get_post_thumbnail_id() : $options['id'];
		$image_sizes = empty( $options['sizes'] ) ? $default_sizes : $options['sizes'];
		$options['tag'] = empty( $options['tag'] ) ? 'div' : $options['tag'];

		if ( has_action( 'loftocean_media_the_background_image' ) ) {
			unset( $options['id'], $options['size'] );
			do_action( 'loftocean_media_the_background_image', $image_id, $image_sizes, $options );
		} else {
			if ( cozystay_does_attachment_exist( $image_id ) ) :
				$image_src = cozystay_get_image_src( $image_id, $image_sizes[0] );
				$styles = sprintf( 'background-image: url(%s);', esc_url( $image_src ) );
				if ( ! empty( $options['attrs']['style'] ) ) {
					$styles .= ' ' . $options['attrs']['style'];
				} ?>
				<<?php echo esc_attr( $options['tag'] ); ?>
					<?php if ( ! empty( $options['class'] ) ) : ?> class="<?php echo esc_attr( $options['class'] ); ?>"<?php endif; ?>
					style="<?php echo esc_attr( $styles ); ?>"
					<?php cozystay_the_tag_attributes( $options['attrs'] ); ?>
				></<?php echo esc_attr( $options['tag'] ); ?>><?php
			endif;
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_background_image_attrs' ) ) :
	/**
	* Get html attributes for preload image
	* Create your own function to override in a child theme.
	* @since 1.1.0
	* @param array settings
	* 		id 		int, 	image id
	* 		sizes 	string, image size
	* @param boolean flag identify use call preloader image filter(if exists)
	* @return html element attributes string
	*/
	function cozystay_the_background_image_attrs( $args = array() ) {
		$default_image_sizes = array( 'full', 'full' );
		$options = array_merge( array (
			'id' 	=> null,
			'sizes' => $default_image_sizes
		), $args );

		$image_id = empty( $options['id'] ) ? get_post_thumbnail_id() : $options['id'];
		$image_sizes = empty( $options['sizes'] ) || ! is_array( $options['sizes'] ) ? $default_image_sizes : $options['sizes'];

		if ( has_action( 'loftocean_media_the_background_image_attrs' ) ) {
			do_action( 'loftocean_media_the_background_image_attrs', $image_id, $image_sizes );
		} else {
			$image_src = cozystay_get_image_src( $image_id, $image_sizes[0] );
			printf( ' style="background-image: url(%s);"', esc_url( $image_src ) );
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_single_featured_background_image' ) ) :
	/**
	* Output single page/post featured image
	*/
	function cozystay_the_single_featured_background_image() {
		if ( has_post_thumbnail() ) : ?>
			<div class="featured-media-section">
				<?php cozystay_the_preload_bg( array(
					'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
					'class' => 'header-img'
				) ); ?>
			</div><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_single_featured_responsive_image' ) ) :
	/**
	* Output single page/post featured image
	*/
	function cozystay_the_single_featured_responsive_image() {
		if ( has_post_thumbnail() ) : ?>
		<div class="featured-media-section">
			<?php the_post_thumbnail( CozyStay_Utils_Image::get_image_size( array( 'module' => 'site', 'sub_module' => 'page-header' ) ) ); ?>
		</div><?php
	endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_single_featured_gallery' ) ) :
	/**
	* Print featured media if needed
	* 	Create your own function to override in a child theme.
	*/
	function cozystay_the_single_featured_gallery( $wrap_class = '' ) {
		$class = array( 'featured-media-section' );
		empty( $wrap_class ) ? '' : array_push( $class, $wrap_class ); ?>
		<div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>"><?php do_action( 'loftocean_front_the_post_featured_media', false ); ?></div> <?php
	}
endif;

if ( ! function_exists( 'cozystay_the_single_featured_video' ) ) :
	/**
	* Print featured media if needed
	* 	Create your own function to override in a child theme.
	*/
	function cozystay_the_single_featured_video( $template = '', $type = 'background' ) {
		$template_need_background = in_array( $template, array( 'post-template-1', 'post-template-2' ) ); ?>
		<div class="featured-media-section has-video"><?php
			if ( $template_need_background && has_post_thumbnail() ) {
				if ( 'image' == $type ) {
					the_post_thumbnail( CozyStay_Utils_Image::get_image_size( array( 'module' => 'site', 'sub_module' => 'page-header' ) ) );
				} else {
					cozystay_the_preload_bg( array(
						'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
						'class' => 'header-img'
					) );
				}
			} ?>
		</div><?php
	}
endif;

if ( ! function_exists( 'cozystay_the_single_featured_media' ) ) :
	/**
	* Print featured media if needed
	* 	Create your own function to override in a child theme.
	*/
	function cozystay_the_single_featured_media( $wrap_class = '', $template = '', $type = 'background' ) {
		$format = get_post_format();
		switch ( $format ) {
			case 'gallery':
				cozystay_the_single_featured_gallery( $wrap_class );
				break;
			case 'video':
				cozystay_the_single_featured_video( $template, $type );
				break;
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_category' ) ) :
	/**
	 * Prints HTML with category for current post
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_category() {
		$categories = get_the_term_list( get_the_ID(), 'category', '', ' ', '' );
		if ( ! empty( $categories ) ) :
			$allowed_html = array( 'a' => array( 'href' => 1, 'id' => 1, 'class' => 1, 'data-*' => 1, 'target' => 1, 'rel' => 1 ) ); ?>
			<div class="cat-links"><?php echo wp_kses( $categories, $allowed_html ); ?></div><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_tags' ) ) :
	/**
	 * Prints HTML with tags for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_tags() {
		$tags_list = get_the_tag_list( '', ' ', '' );
		if ( ! empty( $tags_list ) ) :
			$allowed_html = array( 'a' => array( 'href' => 1, 'id' => 1, 'class' => 1, 'data-*' => 1, 'target' => 1, 'rel' => 1 ), ); ?>
			<aside class="post-tag-cloud">
				<span class="article-footer-title"><?php esc_html_e( 'Tags:', 'cozystay' ); ?></span>
				<div class="tagcloud"><?php echo wp_kses( $tags_list, $allowed_html ); ?></div>
			</aside><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_author' ) ) :
	/**
	 * Prints post meta author for current post
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_author( $with_avatar = false, $with_name = false, $prefix = '' ) {
		$author_id = get_post_field( 'post_author', get_the_ID() );
		$author_email = get_the_author_meta( 'user_email', $author_id );
		$avatar = get_avatar( $author_email, 150 );
		$author_url = esc_url( get_author_posts_url( $author_id ) );
		$has_avatar = $with_avatar && ! empty( $avatar );
		if ( $has_avatar || $with_name ) : ?>
			<div class="meta-item author">
			<?php if ( $has_avatar ) : ?>
				<a class="author-photo" href="<?php echo esc_url( $author_url ); ?>"><?php echo get_avatar( $author_email, 150 ); ?></a>
			<?php endif; ?>
			<?php if ( $with_name ) : ?>
				<?php echo esc_html( $prefix ); ?> <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></a>
			<?php endif; ?>
			</div><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_date' ) ) :
	/**
	 * Prints post meta date information for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_date( $is_single = false ) {
		if ( $is_single ) : ?>
			<div class="meta-item time">
				<?php echo esc_html( get_the_date() ); ?>
			</div><?php
		else : ?>
			<div class="meta-item time">
				<a href="<?php the_permalink() ?>">
					<time class="published" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</a>
			</div><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_single_footer_meta_posted_date' ) ) :
	/**
	 * Prints post meta publish date information for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_single_footer_meta_posted_date() { ?>
		<div class="meta-item post-date"><?php esc_html_e( 'Posted On: ', 'cozystay' ); echo esc_html( get_the_date() ); ?></div><?php
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_updated_date' ) ) :
	/**
	 * Prints post meta update date information for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_updated_date() {
		$pid = get_the_ID();
		$show_update_date = strtotime( get_post_field( 'post_modified_gmt', $pid ) ) - strtotime( get_post_field( 'post_date_gmt', $pid ) ) > 60;
		if ( $show_update_date ) : ?>
			<div class="meta-item update-date"><?php esc_html_e( 'Updated On: ', 'cozystay' ); echo esc_html( get_the_modified_date() ); ?></div><?php
		endif;
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_view' ) ) :
	/**
	 * Prints post meta date information for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_view() {
		if ( cozystay_is_theme_core_activated() ) {
			do_action( 'loftocean_post_metas_view_label' );
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_like' ) ) :
	/**
	 * Prints post meta date information for current post.
	 * Create your own function to override in a child theme.
	 * @since 1.0.0
	 */
	function cozystay_the_meta_like() {
		do_action( 'loftocean_post_metas_like_label' );
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_comment' ) ) :
	/**
	 * Prints post meta comment link.
	 *
	 * Create your own function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function cozystay_the_meta_comment( $is_single = false ) {
		$num = get_comments_number();
		if ( comments_open() || $num ) {
			$url = sprintf( '%s#comments', get_permalink( get_the_ID() ) );
			if ( $is_single ) :
				// translators: %s comment number
				$label = sprintf( ( $num > 1 ? esc_html__( '%s Comments', 'cozystay' ) : esc_html__( '%s Comment', 'cozystay' ) ), $num ); ?>
				<div class="meta-item comment-count">
					<a href="#comments"><?php echo esc_html( $label ); ?></a>
				</div><?php
			else : ?>
				<div class="meta-item comment-count">
					<a href="<?php echo esc_url( $url ); ?>"><i class="far fa-comments"></i> <?php echo esc_html( $num ); ?></a>
				</div><?php
			endif;
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_single_pages' ) ) :
	/**
	 * Print content pagination for singles
	 *
	 * Create your own function to override in a child theme.
	 *
	 * @since 1.0.0
	*/
	function cozystay_the_single_pages() {
		wp_link_pages( array(
			'before'      => '<div class="page-links"><div class="page-links-container"><span class="page-links-title">' . esc_html__( 'Pages:', 'cozystay' ) . '</span>',
			'after'       => '</div></div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		) );
	}
endif;

if ( ! function_exists( 'cozystay_the_meta_edit_link' ) ) :
	/**
	 * Print link to edit a post or page.
	 *
	 * Create your own function to override in a child theme.
	 *
	 * @since 1.0.0
	*/
	function cozystay_the_meta_edit_link() {
		edit_post_link(
			esc_html__( 'Edit', 'cozystay' ),
			'<div class="meta-item edit-link">',
			'</div>'
		);
	}
endif;

if ( ! function_exists( 'cozystay_the_social_bar' ) ) :
	/**
	* Print social share icon html
	* Create your own function to override in a child theme
	*/
	function cozystay_the_social_bar( $is_product = false ) {
		$socials = cozystay_get_enabled_sharing();
		if ( cozystay_is_valid_array( $socials ) && cozystay_is_theme_core_activated() ) {
			if ( apply_filters( 'loftocean_has_social_sharing_icons', false, $socials ) ) : ?>
				<div class="article-share">
					<span class="article-footer-title"><?php esc_html_e( 'Share:', 'cozystay' ); ?></span>
					<div class="article-share-container">
						<?php do_action( 'loftocean_the_social_sharing_icons', $socials, false ); ?>
					</div>
				</div><?php
			endif;
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_yoast_seo_breadcrumbs' ) ) :
	/**
	* Print breadcrumbs
	* Create your own function to override in a child theme
	*/
	function cozystay_the_yoast_seo_breadcrumbs() {
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<div id="breadcrumbs" class="breadcrumbs">','</div>' );
		}
	}
endif;

if ( ! function_exists( 'cozystay_the_default_page_header_background_image' ) ) :
	/**
	* Show default page header background image
	* Create your own function to override in a child theme
	*/
	function cozystay_the_default_page_header_background_image() {
		$background_prefix = 'cozystay_page_title_default_background_';
		$background_image_id = cozystay_get_theme_mod( $background_prefix . 'image' );
		if ( cozystay_does_attachment_exist( $background_image_id ) ) {
			$image_style = array(
				sprintf( 'background-size: %s;', cozystay_get_theme_mod( $background_prefix . 'size' ) ),
				sprintf( 'background-position: %1$s %2$s;', cozystay_get_theme_mod( $background_prefix . 'position_x' ), cozystay_get_theme_mod( $background_prefix . 'position_y' ) ),
				sprintf( 'background-repeat: %s;', ( cozystay_module_enabled( $background_prefix . 'repeat' ) ? 'repeat' : 'no-repeat' ) ),
				sprintf( 'background-attachment: %s;', ( cozystay_module_enabled( $background_prefix . 'attachment' ) ? 'scroll' : 'fixed' ) )
			);
			cozystay_the_preload_bg( array(
				'id' 	=> $background_image_id,
				'sizes' => CozyStay_Utils_Image::get_image_sizes( array( 'module' => 'site', 'sub_module' => 'page-header' ) ),
				'class' => apply_filters( 'cozystay_page_title_default_class', 'page-title-bg page-title-default-background-image' ),
				'tag' 	=> 'div',
				'attrs' => array( 'style' => implode( ' ', $image_style ) )
			) );
		};
	}
endif;
