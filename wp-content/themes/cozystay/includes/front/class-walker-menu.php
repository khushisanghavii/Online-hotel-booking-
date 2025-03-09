<?php
if ( class_exists( 'Walker_Nav_Menu' ) ) {
    // Waler class for fullscreen site header
	class CozyStay_Walker_Fullscreen_Nav_Menu extends Walker_Nav_Menu {
		/*
		 * @description add a wrapper div
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$wrap = sprintf(
				'<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span></button>',
				esc_html__( 'expand child menu', 'cozystay' )
			);
			$output .=  "\n$indent$wrap<ul class=\"sub-menu\">\n";
		}
	}

	/**
	* Drop down sub menu for primary menu
	*/
	class CozyStay_Walker_Nav_Menu extends Walker_Nav_Menu {
		/**
		* Rewrite built-in display function
		*/
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];
			$id       = $element->$id_field;

			if ( $this->is_dropdown_enable( $id, $depth ) ) {
				unset( $children_elements[ $id ] );
			}
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
		/**
		* Rewrite built-in start elment output function
		*/
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$classes = empty( $item->classes ) ? array() : (array)$item->classes;
			// Support mega menu for first level only
			if ( $depth > 0 ) {
				if ( in_array( 'cozystay-mega-menu', $classes ) ) {
					$item->classes = array_diff( $classes, array( 'cozystay-mega-menu' ) );
				}
			} else {
				$id_field = $this->db_fields['id'];
				if ( $this->is_dropdown_enable( $item->$id_field, $depth ) ) {
					array_push( $classes, 'cozystay-mega-menu' );
					in_array( 'menu-item-has-children', $classes ) ? '' : array_push( $classes, 'menu-item-has-children' );
					$item->classes = $classes;
				}
			}
			parent::start_el( $output, $item, $depth, $args, $id );
		}
		/**
		* Rewrite built-in end element output function
		*/
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( $this->is_dropdown_enable( $item->ID, $depth ) ) {
				$custom_block = get_post_meta( $item->ID, 'menu_item_mega_menu', true );
	            $size = get_post_meta( $item->ID, 'menu_item_dropdown_size', true );
	            $width = get_post_meta( $item->ID, 'menu_item_dropdown_custom_width', true );
	            $color_scheme = get_post_meta( $item->ID, 'menu_item_dropdown_color_scheme', true );

				$sub_menu_class = array( 'cozystay-dropdown-menu', $size, 'sub-menu', 'hide' );
				( 'default' == $color_scheme ) ? '' : array_push( $sub_menu_class, $color_scheme );
				$sub_menu_style = ( 'custom-width' == $size ) ? sprintf( ' style="width: %spx;"', $width ) : '';

				$output .= sprintf(
					'<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span></button>',
					esc_html__( 'expand child menu', 'cozystay' )
				);
				$output .= '<div class="' . implode( ' ', $sub_menu_class ) . '"' . $sub_menu_style . '><div class="container">';
				ob_start();
				do_action( 'loftocean_the_custom_blocks_content', $custom_block );
				$output .= ob_get_clean();
				$output .= '</div></div>';
			}
			parent::end_el( $output, $item, $depth, $args );
		}
		/**
		* Helper function if current dropdown menu enabled
		* @param object menu item
		* @param int menu depth
		* @return boolean
		*/
		private function is_dropdown_enable( $item_id, $depth ) {
			if ( cozystay_is_theme_core_activated() && ( $depth === 0 ) ) {
				$enabled = get_post_meta( $item_id, 'menu_item_enable_mega_menu', true );
	            $menu = get_post_meta( $item_id, 'menu_item_mega_menu', true );
				return ( 'on' == $enabled ) && cozystay_does_item_exist( $menu );
			}
			return false;
		}
		/*
		 * @description add a wrapper div
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$wrap = sprintf(
				'<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span></button>',
				esc_html__( 'expand child menu', 'cozystay' )
			);
			$output .=  "\n$indent$wrap<ul class=\"sub-menu\">\n";
		}
	}
}
