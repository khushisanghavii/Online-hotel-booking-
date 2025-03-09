<?php

if ( ! class_exists( 'CozyStay_Customize_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	* Theme customized control class to add more control types or modify the default control type
	*
	* @author Loft.Ocean
	* @since 1.0.0
	*/
	class CozyStay_Customize_Control extends WP_Customize_Control {
		/**
		* String text after input field for some type of controls
		*/
		public $after_text = '';
		/**
		* String extra class name for input field
		*/
		public $input_class = '';
		/**
		* Boolean to tell if label go first for some checkbox input
		*/
		public $label_first = false;
		/**
		* Boolean to tell if description go under input field
		*/
		public $description_below = false;
		/**
		* Boolean to tell if has background image for some radio input
		*/
		public $with_bg	= false;
		/**
		* String wrap id for radio type control
		*/
		public $wrap_id	= '';
		/**
		* Array children contorl list for type group
		*/
		public $children = array();
		/**
		* Boolean to tell if currently render the editor control
		*/
		private $render_theme_editor = false;
		/**
		* Boolean to tell if the editor filter is added
		*/
		static private $editor_filter_added = false;
		/**
		* Rewrite build-in render_content function to add more control types
		*/
		public function render_content(){
			switch ( $this->type ) {
				case 'title_only': ?>
					<h3><?php echo esc_html( $this->label ); ?></h3><?php
					$this->the_description( $this->description );
					break;
				case 'radio':
					if ( ! empty( $this->choices ) ) :
						if ( $this->with_bg && ! empty( $this->wrap_id ) ) :
							$control_id = $this->id;
							$value 		= $this->value();

							$this->the_title( $this->label ); ?>
							<div id="<?php echo esc_attr( $this->wrap_id ); ?>"><?php
							foreach ( $this->choices as $val => $title ) : ?>
								<label for="<?php echo esc_attr( $control_id ); ?>-<?php echo esc_attr( $val ); ?>" title="<?php echo esc_attr( $title ); ?>">
									<input
										id="<?php echo esc_attr( $control_id ); ?>-<?php echo esc_attr( $val ); ?>"
										class="cs-radiobtn"
										type="radio"
										value="<?php echo esc_attr( $val ); ?>"
										<?php checked( $val, $value ); ?>
										<?php $this->link(); ?>
										name="_customize-radio-<?php echo esc_attr( $control_id ); ?>"
									>
									<span class="thumbnail"></span>
									<span class="thumbnail-title"><?php echo esc_html( $title ); ?></span>
								</label><?php
							endforeach; ?>
							</div><?php
							$this->the_notification();
						else :
							$description = '';
							if ( $this->description_below ) {
								$description = $this->description;
								$this->description = '';
							}
							parent::render_content();
							$this->the_description( $description );
						endif;
					endif;
					break;
				case 'checkbox':
					if ( $this->label_first ) { ?>
						<label class="title-first-checkbox"> <?php
						$this->the_title( $this->label ); ?>
							<input type="checkbox" value="on" <?php $this->link(); ?> <?php checked( 'on', $this->value() ); ?>> <?php
							if ( ! empty( $this->description ) ) {
								$has_description_wrap = ! empty( $this->description_link );
								$this->the_description(
									$this->description,
									$has_description_wrap ? '<a href="' . $this->description_link . '" target="_blank">' : '',
									$has_description_wrap ? '</a>' : ''
								);
							} ?>
						</label> <?php
						$this->the_notification();
					} else {
						parent::render_content();
					}
					break;
				case 'multiple_checkbox':
					if ( empty( $this->choices ) ) {
						return false;
					}

					$this->the_title( $this->label, 'h3', 'group-title' );
					if ( ! $this->description_below ) {
						$this->the_description( $this->description );
					}
					$values = (array) $this->value();
					$manager = $this->manager; ?>
					<div class="multiple-checkbox-wrap"><?php
					foreach ( $this->choices as $attr ) :
						$value = $attr['value'];
						$setting = $manager->get_setting( $attr['setting'] );
						if ( $setting ) : ?>
							<label>
								<input
									type="checkbox"
									data-customize-setting-link="<?php echo esc_attr( $attr['setting'] ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									<?php checked( $value, $setting->value() ); ?>
								/><?php echo esc_html( $attr['label'] ); ?>
								<?php isset( $attr['description'] ) ? $this->the_description( $attr['description'] ) : ''; ?>
							</label> <?php
						endif;
					endforeach; ?>
					</div><?php

					if ( $this->description_below ) {
						$this->the_description( $this->description );
					}
					$this->the_notification();
					break;
				case 'number_slider':
					if ( empty( $this->input_attrs ) ) {
						return;
					}
					$wrapper_class = empty( $this->input_class ) ? 'cs-align-right' : $this->input_class . ' cs-align-right'; ?>

					<label class="amount opacity">
						<?php $this->the_title( $this->label, 'span', 'cs-display-inline customize-control-title' );  ?>
						<span class="<?php echo esc_attr( $wrapper_class ); ?>">
							<input readonly="readonly" type="text" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" >
							<?php echo esc_html( $this->after_text ); ?>
						</span>
					</label>
					<div
						class="ui-slider loader-ui-slider"
						data-value="<?php echo esc_attr( $this->manager->get_setting( $this->id )->value() ); ?>"
						<?php $this->input_attrs(); ?>
					></div> <?php
					$this->the_notification();
					break;
				case 'number_with_unit': ?>
					<label><?php
						$this->the_title( $this->label, 'span', 'cs-display-inline-block customize-control-title' );
						$this->the_description( $this->description ); ?>
						<span class="inline-block number-with-label-wrapper cs-align-right">
							<input
								class="<?php echo esc_attr( $this->input_class ); ?>"
								type="number"
								<?php $this->input_attrs(); ?>
								value="<?php echo esc_attr( $this->value() ); ?>"
								<?php $this->link(); ?>
							/> <?php echo esc_html( $this->after_text ); ?>
						</span>
						<?php $this->the_notification(); ?>
					</label> <?php
					break;
				case 'multiple_selection':
					if ( empty( $this->choices ) ) {
						return false;
					} ?>

					<label><?php
						$this->the_title( $this->label, 'span', 'cs-display-inline-block customize-control-title' );
						$this->the_description( $this->description ); ?>
						<select <?php $this->link(); ?> multiple> <?php
							$val = $this->value();
							$is_array = ! empty( $val ) && is_array( $val );
							foreach ( $this->choices as $value => $label ) : ?>
								<option
									value="<?php echo esc_attr( $value ); ?>"
									<?php if ( $is_array && in_array( $value, $val ) ) : ?> selected<?php endif; ?>
								><?php echo esc_html( $label ); ?></option> <?php
							endforeach; ?>
						</select>
					</label> <?php
					$this->the_notification();
					break;
				case 'select':
					$description = '';
					if ( $this->description_below ) {
						$description = $this->description;
						$this->description = '';
					}
					parent::render_content();
					$this->the_description( $description );
					break;
				case 'font_select':
					$description = ''; ?>
					<label><?php 
						$this->the_title( $this->label, 'span', 'cs-display-inline-block customize-control-title' );
						if ( $this->description_below ) {
							$description = $this->description;
							$this->description = '';
						} ?>
					</label>
					<select <?php $this->link(); ?>><?php
						$val = $this->value();
						if ( isset( $this->choices[ 'google' ] ) ) :
							foreach ( $this->choices as $fonts ) : ?>
								<optgroup label="<?php echo esc_attr( $fonts[ 'type' ] ); ?>"><?php
								foreach ( $fonts[ 'list' ] as $value => $label ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>"<?php if ( $value == $val ) : ?> selected<?php endif; ?>><?php echo esc_html( $label ); ?></option><?php
								endforeach; ?>
								</optgroup><?php
							endforeach;
						else :
							foreach ( $this->choices as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php if ( $value == $val ) : ?> selected<?php endif; ?>><?php echo esc_html( $label ); ?></option><?php
							endforeach;
						endif; ?>
					</select><?php
					$this->the_description( $description );
					break;
				case 'select_sortable':
					if ( ! empty( $this->choices ) ) :
						$vals = $this->value();
						$vals = is_array( $vals ) ? implode( ',', $vals ) : $vals;
						$this->the_title( $this->label ); ?>
						<select multiple class="sortable-selection-list">
						<?php foreach ( $this->choices as $val => $attr ) : ?>
							<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $attr ); ?></option>
						<?php endforeach; ?>
						</select>
						<input type="hidden" value="<?php echo esc_attr( $value ); ?>" class="sortable-selection-value" <?php $this->link(); ?> />
						<ul class="sortable"></ul><?php
						$this->the_notification();
					endif;
					break;
				case 'image_id':
					$image_id = intval( $this->value() );
					$has_image = cozystay_does_attachment_exist( $image_id ) && ( false !== strpos( get_post_mime_type( $image_id ), 'image' ) ); ?>
					<label for="<?php echo esc_attr( $this->id ); ?>-button">
						<?php $this->the_title( $this->label ); ?>
					</label>
					<div class="attachment-media-view<?php if ( $has_image ) : ?> attachment-media-view-image<?php endif; ?>">
						<div class="placeholder<?php if ( $has_image ) : ?> hide<?php endif; ?>">
							<?php esc_html_e( 'No image selected', 'cozystay' ); ?>
						</div>
						<?php if ( $has_image ) : ?>
							<?php $tmp = wp_get_attachment_image_src( $image_id, 'medium' ); ?>
							<?php $image_url = esc_url( $tmp[0] ); ?>
							<?php $image_alt = cozystay_get_image_alt( $image_id ); ?>
							<div class="thumbnail thumbnail-image">
								<img width=<?php echo esc_attr( $tmp[1] ) ; ?> height=<?php echo esc_attr( $tmp[2] ) ; ?> class="attachment-thumb" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
							</div>
						<?php endif; ?>
						<div class="actions">
							<button type="button" class="button upload-button cs-customize-upload-image" id="<?php echo esc_attr( $this->id ); ?>-button">
								<?php esc_html_e( 'Choose Image', 'cozystay' ); ?>
							</button>
							<button type="button" class="button remove-button cs-customize-remove-image<?php if ( ! $has_image ) :?> hide<?php endif; ?>">
								<?php esc_html_e( 'Remove Image', 'cozystay' ); ?>
							</button>
							<div class="cs-clear-float"></div>
						</div>
						<input type="hidden" value="<?php echo esc_attr( $image_id ); ?>" <?php $this->link(); ?> />
					</div> <?php
					$this->the_notification();
					break;
				case 'notes':
					$this->the_description( $this->description );
					break;
				case 'mce_editor':
					$this->the_title( $this->label );
					$this->the_description( $this->description );
					$this->render_theme_editor = true;
					$this->add_editor_filter();
					wp_editor( $this->value(), $this->id, array( 'media_buttons' => true ) );
					$this->render_theme_editor = false;
					$this->the_notification();
					break;
				case 'button':
					$this->the_title( $this->label );
					$this->the_description( $this->description ); ?>
					<input
						type="button"
						<?php $this->link(); ?>
						<?php $this->input_attrs(); ?>
						value="<?php echo esc_attr( $this->value() ); ?>"
						class="button button-primary"
					/> <?php
					$this->the_notification();
					break;
				case 'group':
					if ( empty( $this->children ) ) {
						return ;
					}
					$this->the_title( $this->label, 'h3', 'group-title' );
					$this->the_description( $this->description ); ?>
					<ul class="group-controls-wrap"></ul> <?php
					break;
				default:
					$description = '';
					if ( $this->description_below ) {
						$description = $this->description;
						$this->description = '';
					}
					parent::render_content();
					$this->the_description( $description );
			}
		}
		/**
		* Add filter for theme control editor
		*/
		private function add_editor_filter() {
			if ( ! self::$editor_filter_added ) {
				add_filter( 'the_editor', array( $this, 'mce_editor_html' ), 999 );
				self::$editor_filter_added = true;
			}
		}
		/**
		* Add data-customize-setting-link attribute to editor
		* 	Called by core function wp_editor
		* @param $html string
		* @return string
		*/
		public function mce_editor_html( $html ) {
			if ( $this->render_theme_editor ) {
				$id = $this->id;
				$data_link = $this->get_link();
				if ( strpos( $html, $id ) !== false ) {
					$html = str_replace( 'id="' . $id . '">', 'id="' . $id . '" ' . $data_link . '>' , $html );
				}
			}
			return $html;
		}
		/**
		* Rewrite build-in to_json function to add children control list json value if needed
		*/
		public function to_json() {
			parent::to_json();
			switch ( $this->type ) {
				case 'group':
					if ( ! empty( $this->children ) ) {
						$children = array();
						foreach ( $this->children as $sub_control ) {
							$children[ $sub_control->id ] = $sub_control->json();
						}
						$this->json['children'] = $children;
					}
					break;
			}
		}
		/**
		* Output the title text with tag given wrapped
		* @param string title text
		* @param string tag name, default 'span'
		*/
		public function the_title( $title = '', $tag = 'span', $class = 'customize-control-title' ) {
			if ( ! empty( $title ) ) : ?>
				<<?php echo esc_attr( $tag ); ?> class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $tag ); ?>><?php
			endif;
		}
		/**
		* Output the description text with tag <span> wrapped
		* @param string description text
		*/
		public function the_description( $description = '', $before = '', $after = '' ) {
			if ( ! empty( $description ) ) :
				$allowed_html = array( 'a' => array( 'href' => 1, 'class' => 1, 'data-*' => 1, 'target' => 1, 'rel' => 1 ) );
				echo wp_kses( $before, $allowed_html ); ?>
				<span class="description customize-control-description"><?php echo wp_kses( $description, $allowed_html ); ?></span> <?php
				echo wp_kses( $after, $allowed_html );
			endif;
		}
		/**
		* Output the notification wrap html
		*/
		public function the_notification() { ?>
			<div class="customize-control-notifications-container"></div> <?php
		}
	}
}
