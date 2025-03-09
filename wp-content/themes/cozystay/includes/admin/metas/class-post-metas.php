<?php
if ( ! class_exists( 'CozyStay_Post_Metas' ) ) {
	class CozyStay_Post_Metas {
		/**
		* Construct function
		*/
		public function __construct() {
			add_action( 'loftocean_post_metabox_html', 	array( $this, 'post_metabox' ) );
			add_action( 'loftocean_save_post_metabox_settings', array( $this, 'save_post_meta' ), 10, 1 );
			add_filter( 'loftocean_metabox_get_post_title', array( $this, 'change_post_metabox_title' ), 10, 2 );

			if ( cozystay_is_gutenberg_enabled() ) {
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ), 5 );
			}
		}
		/**
		* Enqueue gutenberg editor assets
		*/
		public function enqueue_editor_assets() {
			wp_enqueue_script(
				'cozystay-gutenberg-post-script',
				COZYSTAY_ASSETS_URI . 'scripts/editor/post-settings.js',
				array( 'wp-element', 'wp-i18n', 'wp-hooks', 'wp-components', 'wp-blocks', 'jquery' ),
				COZYSTAY_ASSETS_VERSION,
				true
			);
			wp_localize_script( 'cozystay-gutenberg-post-script', 'cozystayBlockEditorSettings', array(
				'customBlock' => cozystay_get_custom_post_type( 'custom_blocks' ),
				'customSiteHeader' => cozystay_get_custom_post_type( 'custom_site_headers' ),
				'disableStickySiteHeader' => ( 'disable' == cozystay_get_theme_mod( 'cozystay_sticky_site_header' ) )
			) );
		}
		/**
		* Change post metabox title
		* @param string
		* @param string
		* @return string
		*/
		public function change_post_metabox_title( $title, $type ) {
			if ( 'post' == $type ) {
				return esc_html__( 'CozyStay Single Post Options', 'cozystay' );
			} else {
				return $title;
			}
		}
		/**
		* Show post meta box html
		* @param object
		*/
		public function post_metabox( $post ) {
			$post_id = $post->ID;
			$hide_site_header = get_post_meta( $post_id, 'cozystay_single_post_hide_site_header', true );
			$site_header_source = get_post_meta( $post_id, 'cozystay_single_post_site_header_source', true );
			$custom_site_header = get_post_meta( $post_id, 'cozystay_single_post_custom_site_header', true );
			$custom_sticky_site_header = get_post_meta( $post_id, 'cozystay_single_post_custom_sticky_site_header', true );
			$hide_post_title = get_post_meta( $post_id, 'cozystay_single_post_hide_page_title', true );
			$hide_footer_main = get_post_meta( $post_id, 'cozystay_single_post_site_footer_hide_main', true );
			$custom_footer_main_source = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_main_source', true );
			$custom_footer_main = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_main', true );
			$hide_footer_above = get_post_meta( $post_id, 'cozystay_single_post_site_footer_hide_above', true );
			$custom_footer_above_source = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_above_source', true );
			$custom_footer_above = get_post_meta( $post_id, 'cozystay_single_custom_site_footer_above', true );
			$hide_footer_instagram = get_post_meta( $post_id, 'cozystay_single_post_site_footer_hide_instagram', true );
			$hide_footer_bottom = get_post_meta( $post_id, 'cozystay_single_post_site_footer_hide_bottom', true );
			$post_template = get_post_meta( $post_id, 'cozystay_single_post_template', true );

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
				<label for="cozystay_single_post_hide_site_header"><?php esc_html_e( 'Hide Site Header:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_hide_site_header" id="cozystay_single_post_hide_site_header" value="on" <?php checked( $hide_site_header, 'on' ); ?>>
			</p>
			<p class="cs-single-header-source-wrapper"<?php if ( $hide_source ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_post_site_header_source"><?php esc_html_e( 'Site Header Source:', 'cozystay' ); ?></label>
				<select name="cozystay_single_post_site_header_source">
					<option value="" <?php selected( $site_header_source, '' ); ?>><?php esc_html_e( 'Inherit', 'cozystay' ); ?></option>
					<option value="custom" <?php selected( $site_header_source, 'custom' ); ?>><?php esc_html_e( 'Custom', 'cozystay' ); ?></option>
				</select>
			</p>
			<p class="cs-single-custom-site-headers-wrapper"<?php if ( $hide_custom_headers ) : ?> style="display: none;"<?php endif; ?>>
				<label for="cozystay_single_post_custom_site_header"><?php esc_html_e( 'Select a Custom Site Header:', 'cozystay' ); ?></label>
				<select name="cozystay_single_post_custom_site_header">
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
				<label for="cozystay_single_post_custom_sticky_site_header"><?php esc_html_e( 'Select a Custom Sticky Site Header:', 'cozystay' ); ?></label>
				<select name="cozystay_single_post_custom_sticky_site_header">
					<?php foreach ( $custom_headers as $id => $label ) : ?>
						<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $custom_sticky_site_header, $id ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="description"><?php esc_html_e( 'You can choose another custom site header as the sticky header', 'cozystay' ); ?></span>
			</p><?php
			endif; ?>

			<h4 class="section-title"><?php esc_html_e( 'Page Title Section', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_post_hide_page_title"><?php esc_html_e( 'Hide Page Title Section:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_hide_page_title" id="cozystay_single_post_hide_page_title" value="on" <?php checked( $hide_post_title, 'on' ); ?>>
			</p>

			<h4 class="section-title"><?php esc_html_e( 'Site Footer', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_post_site_footer_hide_main"><?php esc_html_e( 'Hide Site Footer Main:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_site_footer_hide_main" id="cozystay_single_post_site_footer_hide_main" value="on" <?php checked( $hide_footer_main, 'on' ); ?>>
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
				<label for="cozystay_single_post_site_footer_hide_above"><?php esc_html_e( 'Hide Before Footer:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_site_footer_hide_above" id="cozystay_single_post_site_footer_hide_above" value="on" <?php checked( $hide_footer_above, 'on' ); ?>>
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
				<label for="cozystay_single_post_site_footer_hide_instagram"><?php esc_html_e( 'Hide Instagram:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_site_footer_hide_instagram" id="cozystay_single_post_site_footer_hide_instagram" value="on" <?php checked( $hide_footer_instagram, 'on' ); ?>>
			</p>
			<p>
				<label for="cozystay_single_post_site_footer_hide_bottom"><?php esc_html_e( 'Hide Footer Bottom:', 'cozystay' ); ?></label>
				<input type="checkbox" name="cozystay_single_post_site_footer_hide_bottom" id="cozystay_single_post_site_footer_hide_bottom" value="on" <?php checked( $hide_footer_bottom, 'on' ); ?>>
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
			</p>

			<h4 class="section-title"><?php esc_html_e( 'Post Template', 'cozystay' ); ?></h4>
			<p>
				<label for="cozystay_single_post_template"><?php esc_html_e( 'Post Template:', 'cozystay' ); ?></label>
				<select name="cozystay_single_post_template">
					<option value="" <?php selected( $post_template, '' ); ?>><?php esc_html_e( 'Default ( inherit from Customize > Blog > Single Post > Default Sidebar Layout )', 'cozystay' ); ?></option>
					<option value="with-sidebar-right" <?php selected( $post_template, 'with-sidebar-right' ); ?>><?php esc_html_e( 'Right Sidebar', 'cozystay' ); ?></option>
					<option value="with-sidebar-left" <?php selected( $post_template, 'with-sidebar-left' ); ?>><?php esc_html_e( 'Left Sidebar', 'cozystay' ); ?></option>
					<option value="fullwidth" <?php selected( $post_template, 'fullwidth' ); ?>><?php esc_html_e( 'No Sidebar', 'cozystay' ); ?></option>
				</select>
			</p><?php
		}
		/**
		* Save post meta
		* @param int post id
		*/
		public function save_post_meta( $post_id ) {
			$checkbox = array(
				'cozystay_single_post_hide_site_header',
				'cozystay_single_post_hide_page_title',
				'cozystay_single_post_site_footer_hide_main',
				'cozystay_single_post_site_footer_hide_above',
				'cozystay_single_post_site_footer_hide_instagram',
				'cozystay_single_post_site_footer_hide_bottom'
			);
			$selects = array(
				'cozystay_single_post_site_header_source',
				'cozystay_single_post_custom_site_header',
				'cozystay_single_post_custom_sticky_site_header',
				'cozystay_single_post_template',
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
			foreach ( $checkbox as $option ) {
				$value = isset( $_REQUEST[ $option ] ) ? 'on' : '';
				update_post_meta( $post_id, $option, $value );
			}
			foreach ( $selects as $option ) {
				$value = isset( $_REQUEST[ $option ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $option ] ) ) : '';
				update_post_meta( $post_id, $option, $value );
			}
		}
	}
	new CozyStay_Post_Metas();
}
