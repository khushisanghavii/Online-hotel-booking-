<?php
if ( ! class_exists( 'CozyStay_Page_Metas' ) ) {
	class CozyStay_Page_Metas {
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'loftocean_save_page_metabox_settings', array( $this, 'save_page_meta' ), 10, 1 );
			add_filter( 'loftocean_metabox_get_page_title', array( $this, 'change_page_metabox_title' ), 10, 2 );
			add_filter( 'loftocean_the_page_metabox_html', array( $this, 'page_metabox' ) );

			if ( cozystay_is_gutenberg_enabled() ) {
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ), 5 );
			}
		}
		/**
		* Enqueue gutenberg editor assets
		*/
		public function enqueue_editor_assets() {
			if ( ! apply_filters( 'loftocean_hide_page_settings', false ) ) {
				wp_enqueue_script(
					'cozystay-gutenberg-page-script',
					COZYSTAY_ASSETS_URI . 'scripts/editor/page-settings.js',
					array( 'wp-element', 'wp-i18n', 'wp-hooks', 'wp-components', 'wp-blocks', 'jquery' ),
					COZYSTAY_ASSETS_VERSION,
					true
				);
				wp_localize_script( 'cozystay-gutenberg-page-script', 'cozystayBlockEditorSettings', array(
					'customBlock' => cozystay_get_custom_post_type( 'custom_blocks' ),
					'yoastSEO_enabled' => cozystay_is_yoast_seo_activated() && $this->enable_breadcrumbs(),
					'customSiteHeader' => cozystay_get_custom_post_type( 'custom_site_headers' ),
					'disableStickySiteHeader' => ( 'disable' == cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) )
				) );
			}
		}
		/**
		* Change page metabox title
		* @param string
		* @param string
		* @return string
		*/
		public function change_page_metabox_title( $title, $type ) {
			if ( 'page' == $type ) {
				return esc_html__( 'CozyStay Single Page Options', 'cozystay' );
			} else {
				return $title;
			}
		}
		/**
		* Output page meta box html
		* @param object
		*/
		public function page_metabox( $post ) {
			if ( apply_filters( 'loftocean_hide_page_settings', false, $post ) ) {
				return '';
			}

			$post_id = $post->ID;
			$hide_site_header = get_post_meta( $post_id, 'cozystay_single_page_hide_site_header', true );
			$site_header_source = get_post_meta( $post_id, 'cozystay_single_page_site_header_source', true );
			$custom_site_header = get_post_meta( $post_id, 'cozystay_single_page_custom_site_header', true );
			$custom_sticky_site_header = get_post_meta( $post_id, 'cozystay_single_page_custom_sticky_site_header', true );
			$hide_page_title = get_post_meta( $post_id, 'cozystay_single_page_hide_page_title', true );
			$page_title_size = get_post_meta( $post_id, 'cozystay_single_page_header_section_size', true );
			$background_color = get_post_meta( $post_id, 'cozystay_single_page_header_background_color', true );
			$background_image = get_post_thumbnail_id();
			$has_background_image = ! empty( $background_image ) && cozystay_does_attachment_exist( $background_image );
			$background_positionx = get_post_meta( $post_id, 'cozystay_single_page_header_background_position_x', true );
			$background_positionx = empty( $background_positionx ) ? 'center' : $background_positionx;
			$background_positiony = get_post_meta( $post_id, 'cozystay_single_page_header_background_position_y', true );
			$background_positiony = empty( $background_positiony ) ? 'center' : $background_positiony;
			$background_size = get_post_meta( $post_id, 'cozystay_single_page_header_background_size', true );
			$background_size = empty( $background_size ) ? 'cover' : $background_size;
			$background_repeat = get_post_meta( $post_id, 'cozystay_single_page_header_background_repeat', true );
			$background_repeat = empty( $background_repeat ) ? 'off' : $background_repeat;
			$background_attachment = get_post_meta( $post_id, 'cozystay_single_page_header_background_scroll', true );
			$background_attachment = empty( $background_attachment ) ? 'off' : $background_attachment;
			$text_color = get_post_meta( $post_id, 'cozystay_single_page_header_text_color', true );
			$show_yoast_seo_breadcrumb = get_post_meta( $post_id, 'cozystay_single_page_header_show_breadcrumb', true );
			$hide_footer_main = get_post_meta( $post_id, 'cozystay_single_page_site_footer_hide_main', true );
			$custom_footer_main_source = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_main_source', true );
			$custom_footer_main = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_main', true );
			$hide_footer_above = get_post_meta( $post_id, 'cozystay_single_page_site_footer_hide_above', true );
			$custom_footer_above_source = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_above_source', true );
			$custom_footer_above = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_above', true );
			$hide_footer_instagram = get_post_meta( $post_id, 'cozystay_single_page_site_footer_hide_instagram', true );
			$hide_footer_bottom = get_post_meta( $post_id, 'cozystay_single_page_site_footer_hide_bottom', true );

			$custom_headers = cozystay_get_custom_post_type( 'custom_site_headers' );
			$custom_blocks = cozystay_get_custom_post_type( 'custom_blocks' );

			$mobile_menu_source = get_post_meta( $post_id, 'cozystay_single_custom_mobile_menu_source', true );
			$custom_mobile_menu = get_post_meta( $post_id, 'cozystay_single_custom_mobile_menu', true );
			$custom_mobile_menu_animation = get_post_meta( $post_id, 'cozystay_single_custom_mobile_menu_animation', true );
			$custom_mobile_menu_width = get_post_meta( $post_id, 'cozystay_single_custom_mobile_menu_width', true );
			$custom_mobile_menu_custom_width = get_post_meta( $post_id, 'cozystay_single_custom_mobile_menu_custom_width', true );

			$hide_mobile_menu_source = ! cozystay_is_valid_array( $custom_blocks );
			$hide_custom_mobile_menu = $hide_mobile_menu_source || ( 'custom' != $mobile_menu_source );
			$hide_custom_mobile_menu_custom_width = $hide_custom_mobile_menu || ( 'custom-width' != $custom_mobile_menu_width );

			$hide_source = ( 'on' == $hide_site_header );
			$hide_custom_headers = $hide_source || ( 'custom' != $site_header_source );

			$hide_footer_main_source = ( 'on' == $hide_footer_main );
			$hide_custom_footer_main = $hide_footer_main_source || ( 'custom' != $custom_footer_main_source );

			$hide_footer_above_source = ( 'on' == $hide_footer_above );
			$hide_custom_footer_above = $hide_footer_above_source || ( 'custom' != $custom_footer_above_source ); ?>

			<h4 class="section-title"><?php esc_html_e( 'Site Header', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_page_hide_site_header"><?php esc_html_e( 'Hide Site Header:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_hide_site_header" id="cozystay_single_page_hide_site_header" value="on" <?php checked( $hide_site_header, 'on' ); ?>>
			</p>
			<p class="cs-single-header-source-wrapper"<?php if ( $hide_source ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_site_header_source"><?php esc_html_e( 'Site Header Source:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_site_header_source">
					<option value="" <?php selected( $site_header_source, '' ); ?>><?php esc_html_e( 'Inherit', 'cozystay' ); ?></option>
					<option value="custom" <?php selected( $site_header_source, 'custom' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-site-headers-wrapper"<?php if ( $hide_custom_headers ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_custom_site_header"><?php esc_html_e( 'Select a Custom Site Header:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_custom_site_header">
					<?php foreach ( $custom_headers as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_site_header, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="description"><?php
					printf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own site header first.', 'cozystay' ),
						'<a href="' . esc_url( admin_url( 'edit.php?post_type=custom_site_headers' ) ) . '" target="_blank">',
						'</a>'
					); ?>
				</span>
			</p><?php
			if ( 'disable' != cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) ) : ?>
			<p class="cs-single-custom-site-headers-wrapper"<?php if ( $hide_custom_headers ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_custom_sticky_site_header"><?php esc_html_e( 'Select a Custom Sticky Site Header:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_custom_sticky_site_header">
					<?php foreach ( $custom_headers as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_sticky_site_header, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="description"><?php esc_html_e( 'You can choose another custom site header as the sticky header.', 'cozystay' ); ?></span>
			</p><?php
			endif; ?>
			<h4 class="section-title"><?php esc_html_e( 'Page Title Section', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_page_hide_page_title"><?php esc_html_e( 'Hide Page Title Section:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_hide_page_title" id="cozystay_single_page_hide_page_title" value="on" <?php checked( $hide_page_title, 'on' ); ?>>
			</p>
			<p>
				<label for="cozystay_single_page_header_section_size"><?php esc_html_e( 'Page Title Section Size:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_header_section_size">
					<option value="" <?php selected( $page_title_size, '' ); ?>><?php esc_html_e( 'Default', 'cozystay' ); ?></option>
					<option value="page-title-small" <?php selected( $page_title_size, 'page-title-small' ); ?>><?php esc_html_e( 'Small', 'cozystay' ); ?></option>
					<option value="page-title-default" <?php selected( $page_title_size, 'page-title-default' ); ?>><?php esc_html_e( 'Medium', 'cozystay' ); ?></option>
					<option value="page-title-large" <?php selected( $page_title_size, 'page-title-large' ); ?>><?php esc_html_e( 'Large', 'cozystay' ); ?></option>
					<option value="page-title-fullheight" <?php selected( $page_title_size, 'page-title-fullheight' ); ?>><?php esc_html_e( 'Screen Height', 'cozystay' ); ?></option>
				</select>
			</p>
			<p>
				<label for="cozystay_single_page_header_background_color"><?php esc_html_e( 'Background Color:', 'cozystay' ); ?></label>
				<input name="cozystay_single_page_header_background_color" class="color-picker" id="cozystay_single_page_header_background_color" type="text" value="<?php echo esc_attr( $background_color ); ?>">
			</p>
			<p>
				<label for="cozystay_single_page_header_background_image"><?php esc_html_e( 'Background Image:', 'cozystay' ); ?></label>
				<span class="description"><?php esc_html_e( 'Upload a featured image and it will be used as the background image of page title section.', 'cozystay' ); ?></span>
			</p>
			<p class="background-settings"<?php if ( ! $has_background_image ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_header_background_position_x"><?php esc_html_e( 'Background Position X:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_header_background_position_x">
					<option value="left" <?php selected( $background_positionx, 'left' ); ?>><?php esc_html_e( 'Left', 'cozystay' ); ?></option>
					<option value="center" <?php selected( $background_positionx, 'center' ); ?>><?php esc_html_e( 'Center', 'cozystay' ); ?></option>
					<option value="right" <?php selected( $background_positionx, 'right' ); ?>><?php esc_html_e( 'Right', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="background-settings"<?php if ( ! $has_background_image ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_header_background_position_y"><?php esc_html_e( 'Background Position Y:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_header_background_position_y">
					<option value="top" <?php selected( $background_positiony, 'top' ); ?>><?php esc_html_e( 'Top', 'cozystay' ); ?></option>
					<option value="center" <?php selected( $background_positiony, 'center' ); ?>><?php esc_html_e( 'Center', 'cozystay' ); ?></option>
					<option value="bottom" <?php selected( $background_positiony, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="background-settings"<?php if ( ! $has_background_image ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_header_background_size"><?php esc_html_e( 'Background Size:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_header_background_size">
					<option value="auto" <?php selected( $background_size, 'auto' ); ?>><?php esc_html_e( 'Original', 'cozystay' ); ?></option>
					<option value="contain" <?php selected( $background_size, 'contain' ); ?>><?php esc_html_e( 'Fit to Screen', 'cozystay' ); ?></option>
					<option value="cover" <?php selected( $background_size, 'cover' ); ?>><?php esc_html_e( 'Fill Screen', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="background-settings"<?php if ( ! $has_background_image ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_header_background_repeat"><?php esc_html_e( 'Background Repeat:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_header_background_repeat" id="cozystay_single_page_header_background_repeat" value="on" <?php checked( $background_repeat, 'on' ); ?>>
			</p>
			<p class="background-settings"<?php if ( ! $has_background_image ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_page_header_background_scroll"><?php esc_html_e( 'Background Image Scroll with Page:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_header_background_scroll" id="cozystay_single_page_header_background_scroll" value="on" <?php checked( $background_attachment, 'on' ); ?>>
			</p>
			<?php if ( cozystay_is_yoast_seo_activated() && $this->enable_breadcrumbs() ) : ?>
			<p>
				<label for="cozystay_single_page_header_show_breadcrumb"><?php esc_html_e( 'Display Breadcrumb:', 'cozystay' ); ?></label>
				<select name="cozystay_single_page_header_show_breadcrumb" id="cozystay_single_page_header_show_breadcrumb">
					<option value="" <?php selected( $show_yoast_seo_breadcrumb, '' ); ?>><?php esc_html_e( 'Default', 'cozystay' ); ?></option>
					<option value="show" <?php selected( $show_yoast_seo_breadcrumb, 'show' ); ?>><?php esc_html_e( 'Show', 'cozystay' ); ?></option>
					<option value="hide" <?php selected( $show_yoast_seo_breadcrumb, 'hide' ); ?>><?php esc_html_e( 'Hide', 'cozystay' ); ?></option>
				 </select>
			</p>
			<?php endif; ?>
			<p>
				<label for="cozystay_single_page_header_text_color"><?php esc_html_e( 'Text Color:', 'cozystay' ); ?></label>
				<input name="cozystay_single_page_header_text_color" class="color-picker" id="cozystay_single_page_header_text_color" type="text" value="<?php echo esc_attr( $text_color ); ?>">
			</p>

			<h4 class="section-title"><?php esc_html_e( 'Site Footer', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_page_site_footer_hide_main"><?php esc_html_e( 'Hide Site Footer Main:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_site_footer_hide_main" id="cozystay_single_page_site_footer_hide_main" value="on" <?php checked( $hide_footer_main, 'on' ); ?>>
			</p>
			<p class="cs-single-site-footer-main-source-wrapper"<?php if ( $hide_footer_main_source ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_site_footer_main_source"><?php esc_html_e( 'Site Footer Main Source:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_site_footer_main_source">
					<option value="" <?php selected( $custom_footer_main_source, '' ); ?>><?php esc_html_e( 'Inherit', 'cozystay' ); ?></option>
					<option value="custom" <?php selected( $custom_footer_main_source, 'custom' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-footer-main-wrapper"<?php if ( $hide_custom_footer_main ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_site_footer_main"><?php esc_html_e( 'Select a Custom Site Footer Main:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_site_footer_main">
					<?php foreach ( $custom_blocks as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_footer_main, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="description"><?php
					printf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom site footer main first.', 'cozystay' ),
						'<a href="' . esc_url( admin_url( 'edit.php?post_type=custom_blocks' ) ) . '" target="_blank">',
						'</a>'
					); ?>
				</span>
			</p>

			<p>
				<label for="cozystay_single_page_site_footer_hide_above"><?php esc_html_e( 'Hide Before Footer:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_site_footer_hide_above" id="cozystay_single_page_site_footer_hide_above" value="on" <?php checked( $hide_footer_above, 'on' ); ?>>
			</p>
			<p class="cs-single-site-footer-above-source-wrapper"<?php if ( $hide_footer_main_source ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_site_footer_above_source"><?php esc_html_e( 'Before Footer Source:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_site_footer_above_source">
					<option value="" <?php selected( $custom_footer_above_source, '' ); ?>><?php esc_html_e( 'Inherit', 'cozystay' ); ?></option>
					<option value="custom" <?php selected( $custom_footer_above_source, 'custom' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-footer-above-wrapper"<?php if ( $hide_custom_footer_above ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_site_footer_above"><?php esc_html_e( 'Select a Custom Before Footer:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_site_footer_above">
					<?php foreach ( $custom_blocks as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_footer_above, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="cozystay_single_page_site_footer_hide_instagram"><?php esc_html_e( 'Hide Instagram:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_site_footer_hide_instagram" id="cozystay_single_page_site_footer_hide_instagram" value="on" <?php checked( $hide_footer_instagram, 'on' ); ?>>
			</p>
			<p>
				<label for="cozystay_single_page_site_footer_hide_bottom"><?php esc_html_e( 'Hide Footer Bottom:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_page_site_footer_hide_bottom" id="cozystay_single_page_site_footer_hide_bottom" value="on" <?php checked( $hide_footer_bottom, 'on' ); ?>>
			</p>

			<?php if ( ! $hide_mobile_menu_source ) : ?><h4 class="section-title"><?php esc_html_e( 'Fullscreen/Mobile Menu', 'cozystay' ); ?></h4><?php endif; ?>
			<p class="cs-single-mobile-menu-source-wrapper"<?php if ( $hide_mobile_menu_source ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_mobile_menu_source"><?php esc_html_e( 'Fullscreen/Mobile Menu Source:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_mobile_menu_source">
					<option value="" <?php selected( $mobile_menu_source, '' ); ?>><?php esc_html_e( 'Inherit', 'cozystay' ); ?></option>
					<option value="custom" <?php selected( $mobile_menu_source, 'custom' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-mobile-menu-wrapper"<?php if ( $hide_custom_mobile_menu ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_mobile_menu"><?php esc_html_e( 'Select a Custom Fullscreen/Mobile Menu:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_mobile_menu">
					<?php foreach ( $custom_blocks as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_mobile_menu, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="description"><?php
					printf(
						// translators: 1/2. html tag
						esc_html__( 'If there is no option in the dropdown list, please click %1$shere%2$s to create your own custom mobile menu first.', 'cozystay' ),
						'<a href="' . esc_url( admin_url( 'edit.php?post_type=custom_blocks' ) ) . '" target="_blank">',
						'</a>'
					); ?>
				</span>
			</p>
			<p class="cs-single-custom-mobile-menu-wrapper"<?php if ( $hide_custom_mobile_menu ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_mobile_menu_animation"><?php esc_html_e( 'Entrance Animation:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_mobile_menu_animation">
					<option value="" <?php selected( $custom_mobile_menu_animation, '' ); ?>><?php esc_html_e( 'Slide From Right', 'cozystay' ); ?></option>
					<option value="slide-from-left" <?php selected( $custom_mobile_menu_animation, 'slide-from-left' ); ?>><?php esc_html_e( 'Slide From Left', 'cozystay' ); ?></option>
					<option value="fade-in" <?php selected( $custom_mobile_menu_animation, 'fade-in' ); ?>><?php esc_html_e( 'Fade In', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-mobile-menu-wrapper"<?php if ( $hide_custom_mobile_menu ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_mobile_menu_width"><?php esc_html_e( 'Width:', 'cozystay' ); ?></label>
				<select name="cozystay_single_custom_mobile_menu_width">
					<option value="" <?php selected( $custom_mobile_menu_width, '' ); ?>><?php esc_html_e( 'Default', 'cozystay' ); ?></option>
					<option value="fullwidth" <?php selected( $custom_mobile_menu_width, 'fullwidth' ); ?>><?php esc_html_e( 'Fit to Screen', 'cozystay' ); ?></option>
					<option value="custom-width" <?php selected( $custom_mobile_menu_width, 'custom-width' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-mobile-menu-custom-width-wrapper"<?php if ( $hide_custom_mobile_menu_custom_width ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_custom_mobile_menu_custom_width"><?php esc_html_e( 'Custom Width:', 'cozystay' ); ?></label>
				<input type="number" min="1" name="cozystay_single_custom_mobile_menu_custom_width" value="<?php echo esc_attr( $custom_mobile_menu_custom_width ); ?>" style="width: 80px;"> px
			</p><?php
		}
		/**
		* Save metas from page meta box
		* @param string id
		* @param object
		* @param string
		*/
		public function save_page_meta( $post_id ) {
			$checkbox_input = array(
				'cozystay_single_page_hide_site_header',
				'cozystay_single_page_hide_page_title',
				'cozystay_single_page_site_footer_hide_main',
				'cozystay_single_page_site_footer_hide_above',
				'cozystay_single_page_site_footer_hide_instagram',
				'cozystay_single_page_site_footer_hide_bottom'
			);
			foreach ( $checkbox_input as $checkbox_id ) {
				$checkbox_value = empty( $_REQUEST[ $checkbox_id ] ) ? '' : 'on';
				update_post_meta( $post_id, $checkbox_id, $checkbox_value );
			}

			$text_input = array(
				'cozystay_single_page_site_header_source',
				'cozystay_single_page_custom_site_header',
				'cozystay_single_page_custom_sticky_site_header',
				'cozystay_single_page_header_section_size',
				'cozystay_single_page_header_background_color',
				'cozystay_single_page_header_background_position_x',
				'cozystay_single_page_header_background_position_y',
				'cozystay_single_page_header_background_size',
				'cozystay_single_page_header_text_color',
				'cozystay_single_page_header_background_image_url',
				'cozystay_single_page_header_show_breadcrumb',
				'cozystay_single_custom_mobile_menu_source',
				'cozystay_single_custom_mobile_menu',
				'cozystay_single_custom_mobile_menu_animation',
				'cozystay_single_custom_mobile_menu_width',
				'cozystay_single_custom_mobile_menu_custom_width',
				'cozystay_single_custom_site_footer_main_source',
				'cozystay_single_custom_site_footer_main',
				'cozystay_single_custom_site_footer_above_source',
				'cozystay_single_custom_site_footer_above'
			);
			foreach ( $text_input as $text_id ) {
				$text_value = empty( $_REQUEST[ $text_id ] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST[ $text_id ] ) );
				update_post_meta( $post_id, $text_id, $text_value );
			}
			$background_image = empty( $_REQUEST[ 'cozystay_single_page_header_background_image' ] ) ? 0 : absint( wp_unslash( $_REQUEST[ 'cozystay_single_page_header_background_image' ] ) );
			update_post_meta( $post_id, 'cozystay_single_page_header_background_image', $background_image );

			$on_off = array(
				'cozystay_single_page_header_background_repeat',
				'cozystay_single_page_header_background_scroll'
			);
			foreach ( $on_off as $setting_id ) {
				update_post_meta( $post_id, $setting_id, ( empty( $_REQUEST[ $setting_id ] ) ? 'off' : 'on' ) );
			}
		}
		/**
		* Helper function
		*/
		protected function enable_breadcrumbs() {
			$pages = array( get_option( 'page_on_front' ), get_option( 'page_for_posts' ) );
			$pages = apply_filters( 'cozystay_woocommerce_static_pages', $pages );
			$pages = array_filter( $pages );
			$pages = array_unique( $pages );
			$current_post_id = 0;
			if ( isset( $_REQUEST[ 'post' ] ) ) {
				$current_post_id = sanitize_text_field( wp_unslash( $_REQUEST[ 'post' ] ) );
			}
			return ! cozystay_is_valid_array( $pages ) || ! in_array( $current_post_id, $pages );
		}
	}
	new CozyStay_Page_Metas();
}
