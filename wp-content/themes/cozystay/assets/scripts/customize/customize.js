/**
* Copyright (c) Loft.Ocean
* http://www.loftocean.com
*/

( function( api, $ ) {
	"use strict";

	/** Add theme special class to control wrap */
	$( '#customize-theme-controls' ).addClass( 'cs-customizer-wrapper' );
	api.cozystay = api.cozystay || {};
	api.cozystay.controls = api.cozystay.controls || {};

	var site_layout_id = 'cozystay_site_layout';

	/**
	* Helper function is Object
	*/
	function isObject( obj ) {
		var type = typeof obj;
		return type === 'function' || type === 'object' && !!obj;
	}
	/**
	* Helper function object given is not empty
	*/
	function notEmptyObejct( obj ) {
		for ( var key in obj ) {
			return obj.hasOwnProperty( key );
		}
	}
	/**
	* Get the customize control's first setting name
	* @param object customize control
	* @return mix customize setting id string if exists, otherwise boolean false
	*/
	function getControlSettingId( control ) {
		var control_settings = control.settings,
			keys = Object.keys( control_settings ),
			first_key = ( 'default' in control_settings )  ? 'default' : ( keys.length ? keys[0] : false );
		return first_key ? control_settings[ first_key ] : false;
	}
	/**
	* Get setting dependency
	*/
	function getSettingDependency( deps, sets ) {
		if ( isObject( deps ) ) {
			if ( deps.is_complex ) {
				$.each( deps, function( index, list ) {
					if ( isObject( list ) ) {
						sets = getSettingDependency( list, sets );
					}
				} );
			} else {
				$.each( deps, function( pid, attrs ) {
					if ( ! ( pid in sets ) ) {
						sets.push( pid );
					}
				} );
			}
		}
		return sets;
	}
	/**
	* Generate setting dependency
	* 	1. Generate the dependency object for wp.customize.setting.controls
	*	2. Get child controls and append to its parent
	*/
	function generateSettingDependency() {
		var settings = api.settings.settings, dependency = {},
			controls = $.extend( {}, api.settings.controls, api.cozystay.controls );
		$.each( controls, function( id, control ) {
			var setting = getControlSettingId( control );
			if ( setting && settings[ setting ] && settings[ setting ].dependency ) {
				var deps = getSettingDependency( settings[ setting ]['dependency'], [] );
				$.each( deps, function( index, pid ) {
					var element = { 'control': ( api.control( id ) || control ), 'dependency': settings[ setting ].dependency };
					if ( pid in dependency ) {
						dependency[pid].push( element );
					} else if ( api( pid ) ) {
						dependency[ pid ] = [ element ];
						api( pid ).bind( function( to ) {
							api.trigger( 'change.cozystay.customizer', pid );
						} );
					}
				} );
			}
		} );
		api.cozystay.dependency = dependency;
	}
	/**
	* Get customize setting value by id
	* @param string setting id
	* @return string setting value
	*/
	function getSettingValue( id ) {
		if ( id in api.settings.settings ) {
			var settings = api.get(),
				setting = settings[ id ];
			return ( setting === true ) ? 'on' : setting;
		}
	}
	/**
	 * @param {String} widgetId
	 * @returns {Object}
	 */
	function parseWidgetId( widgetId ) {
		var matches, parsed = {
			number: null,
			id_base: null
		};

		matches = widgetId.match( /^(.+)-(\d+)$/ );
		if ( matches ) {
			parsed.id_base = matches[1];
			parsed.number = parseInt( matches[2], 10 );
		} else {
			// likely an old single widget
			parsed.id_base = widgetId;
		}

		return parsed;
	}
	/**
	 * @param {String} widgetId
	 * @returns {String} settingId
	 */
	function widgetIdToSettingId( widgetId ) {
		var parsed = parseWidgetId( widgetId ), settingId;

		settingId = 'widget_' + parsed.id_base;
		if ( parsed.number ) {
			settingId += '[' + parsed.number + ']';
		}

		return settingId;
	}
	/**
	* Dependency process determination
	*/
	function processDependency( deps ) {
		if ( isObject( deps ) ) {
			var relation = 'AND', isComplex = false;
			if ( deps.relation ) {
				relation = deps.relation ? deps.relation.toUpperCase() : 'AND';
			}

			isComplex = deps['is_complex'] || false;
			return ( 'AND' == relation ) ? checkDependencyAND( deps, isComplex ) : checkDependencyOR( deps, isComplex );
		}
		return true;
	}
	/**
	* Helper function to check dependency AND
	*/
	function checkDependencyAND( deps, isComplex ) {
		if ( ! isObject( deps ) ) return true;
		var passed = true;
		$.each( deps, function( id, dep ) {
			if ( isObject( dep ) ) {
				var result = isComplex ? processDependency( dep ) : dependencyItemCheck( id, dep );
				if ( ! result ) {
					passed = false;
					return false;
				}
			}
		} );
		return passed;
	}
	/**
	* Helper function to check dependency OR
	*/
	function checkDependencyOR( deps, isComplex ) {
		if ( ! isObject( deps ) ) return true;
		var passed = false;
		$.each( deps, function( id, dep ) {
			if ( isObject( dep ) ) {
				var result = isComplex ? processDependency( dep ) : dependencyItemCheck( id, dep );
				if ( result ) {
					passed = true;
					return false;
				}
			}
		} );
		return passed;
	}
	/**
	* Dependency item check
	*/
	function dependencyItemCheck( pid, attr ) {
		if ( ! pid || ! attr || ! attr['value'] ) { // If not provide the test value list, return false
			return false;
		}

		var operator = attr.operator || 'in',  value = getSettingValue( pid );
		return ( operator === 'in' ) ? ( attr.value.indexOf( value ) !== -1 ) : ( attr.value.indexOf( value ) === -1 );
	}

	/**
	* To deal with the event of setting changed
	*	This will decide to display the controls related or not
	*/
	api.bind( 'change.cozystay.customizer', function( id ) {
		if ( id in api.cozystay.dependency ) { // If current setting id is in the dependency list
			$.each( api.cozystay.dependency[ id ], function( index, item ) {
				var $control = item.control.container;
				processDependency( item.dependency ) ? $control.show() : $control.hide();
			} );
		}
	} );

	/**
	* Add new control constructor for slider type control
	*	which will enable jQuery ui to control
	**/
	api.controlConstructor.number_slider = api.Control.extend( {
		ready: function(){
			var elem = this.container.find('.loader-ui-slider'),
				input = this.container.find('input[data-customize-setting-link]');
			elem.slider( {
				'range': 	'min',
				'min': 		elem.data( 'min' ),
				'max': 		elem.data( 'max' ),
				'value': 	elem.data( 'value' ),
				'step': 	elem.data( 'step' ),
				'slide': 	function( event, ui ) {
					input.val( ui.value ).trigger( 'change' );
				}
			} );
		}
	} );

	/**
	* Register event handlers for customize control type number with unit
	*	To determine if current value excced the range set
	*/
	api.controlConstructor.number = api.controlConstructor.number_with_unit = api.Control.extend( {
		ready: function() {
			var controlID = this.id;
			this.container.find( 'input[type=number]' ).on( 'change', function( e ) {
				var $self = $( this ), val = parseInt( $self.val(), 10 ), min = parseInt( $self.attr( 'min' ), 10 ), max = parseInt( $self.attr( 'max' ), 10 );
				min = isNaN( min ) ? Number.MIN_SAFE_INTEGER : min;
				max = isNaN( max ) ? Number.MAX_SAFE_INTEGER : max;
				( ( '' === val ) || ( val < min ) ) ? api( controlID )( min ) : ( ( false === max ) || ( val < max ) ? '' : api( controlID )( max ) );
			} );
		}
	} );

	/**
	* Register event handlers for customize control type image_id
	*	To open media lib, remove current image and features after image chosed
	*/
	api.controlConstructor.image_id = api.Control.extend( {
		ready: function() {
			var $container = $( this.container );
			$container.on( 'click', '.cs-customize-upload-image, .attachment-thumb', function( e ) {
				e.preventDefault();
				cozystayMeida.open( $( this ).parent().siblings( 'input[type=hidden]' ).first() );
			} )
			.on( 'click', '.cs-customize-remove-image', function( e ) {
				e.preventDefault();
				var $action = $( this ).parent();

				$( this ).addClass( 'hide' );
				$action.siblings( 'input[type=hidden]' ).first().val( '' ).trigger( 'change' );
				$action.siblings( '.placeholder' ).removeClass( 'hide' );
				$action.siblings( '.thumbnail-image' ).remove();
				$action.parent().removeClass( 'attachment-media-view-image' );
			} )
			.on( 'changed.cozystay.media', 'input[type=hidden]', function( e, image ) {
				e.preventDefault();
				if ( image && ( 'image' == image.type ) ) {
					var $container = $( this ).closest( '.attachment-media-view' ).addClass( 'attachment-media-view-image' ),
						targetObject = image.sizes ? ( image.sizes.medium ? image.sizes.medium : ( image.sizes.thumbnail ? image.sizes.thumbnail : image ) ) : image,
						$image = $( '<div>', { 'class': 'thumbnail thumbnail-image' } ).append( $( '<img>', { 'class': "attachment-thumb", 'src': targetObject.url } ).attr( 'width', targetObject.width ).attr( 'height', targetObject.height ) );

					$container.children( '.thumbnail-image' ).remove();
					$container.children( '.placeholder' ).addClass( 'hide' ).after( $image );
					$container.find( '.cs-customize-remove-image' ).removeClass( 'hide' );
					$( this ).val( image.id ).trigger( 'change' );
				}
			} );
		}
	} );

	/**
	* Register event hanlder for customize control type multiple_selection
	*	If the drop donw has option to select all and more than one options selected,
	*		remove the selection of all option.
	*/
	api.controlConstructor.multiple_selection = api.Control.extend( {
		ready: function() {
			var $select = $( this.container ).find( 'select[multiple]' );
			if ( $select.length && $select.children( '[value=""]' ).length ) {
				$select.on( 'change', function( e ) {
					var $options = $( this ).children();

					( $options.filter( ':selected' ).length > 1 ) ? $options.filter( '[value=""]' ).removeAttr( 'selected' ) : '';
				} );
				// Check current value after page initialized
				$select.trigger( 'change' );
			}
		}
	} );
	/**
	* Sortable multiple selection
	*/
	api.controlConstructor.select_sortable = api.Control.extend( {
		ready: function() {
			var $hiddenValue = $( this.container ).find( '.sortable-selection-value' ), $list = $hiddenValue.siblings( '.sortable-selection-list' ),
				$container = $hiddenValue.siblings( '.sortable' ), controlID = this.id, value = api( controlID )();
			if ( value ) {
				value = value.split( ',' );
				if ( Array.isArray( value ) ) {
					$.each( value, function( index, val ) {
						var $item = $list.find( '[value="' + val + '"]' );
						if ( $item.length ) {
							$item.hide();
							$container.append( $( '<li>', { 'data-id': val } )
								.append( $( '<span>', { 'class': 'label' } ).append( $item.html() ) )
								.append( $( '<a>', { 'text': 'x', 'class': 'action-remove', 'href': '#' } ) )
							);
						}
					} );
				}
			}
			$container.on( 'value.update', function() {
				var newValue = [];
				$( this ).children().each( function() {
					$( this ).attr( 'data-id' ) ? newValue.push( $( this ).attr( 'data-id' ) ) : '';
				} );
				api( controlID )( newValue.join( ',' ) );
			} )
			.sortable( {
				stop: function( event, ui ) {
					$( this ).trigger( 'value.update' );
				}
			} );
			$container.on( 'click', 'a', function( e ) {
				e.preventDefault();
				var $item = $( this ).parent();
				$list.find( '[value="' + $item.attr( 'data-id' ) + '"]' ).show();
				$item.remove();
				$container.sortable( 'refresh' );
				$container.trigger( 'value.update' );
			} );
			$list.children().on( 'click', function( e ) {
				e.preventDefault();
				var $item = $( this ), val = $item.attr( 'value' ), currentHiddenValue = api( controlID )();
				$container.append( $( '<li>', { 'data-id': val } )
					.append( $( '<span>', { 'class': 'label' } ).append( $item.html() ) )
					.append( $( '<a>', { 'text': 'x', 'class': 'action-remove', 'href': '#' } ) )
				);
				$item.hide();
				$container.sortable( 'refresh' );
				api( controlID )( currentHiddenValue ? ( currentHiddenValue + ',' + val ) : val );
			} );
		}
	} );

	/**
	* Add new control constructor for mce_editor type control
	*	which will enable tinymce on it
	**/
	api.controlConstructor.mce_editor = api.Control.extend( {
		ready: function() {
			var control 	= this,
				id 			= control.id,
				in_ids 		= cozystayCustomizer && cozystayCustomizer.editor_ids && ( cozystayCustomizer.editor_ids.indexOf( id ) != -1 ),
				in_mceInit 	= tinyMCEPreInit && tinyMCEPreInit.mceInit && ( id in tinyMCEPreInit.mceInit ) && window.tinymce,
				in_qtInit 	= tinyMCEPreInit && tinyMCEPreInit.qtInit && ( id in tinyMCEPreInit.qtInit ) && quicktags;
			if ( in_ids && in_mceInit && in_qtInit ) {
				var $container = $( control.container );
				cozystayInitEditor( id, { 'mce': tinyMCEPreInit.mceInit[ id ], 'qt': tinyMCEPreInit.qtInit[ id ] }, $container );
				window.wpActiveEditor = id;
			}
		}
	} );

	/**
	* Add new control constructor for button type control
	*/
	api.controlConstructor.button = api.Control.extend( {
		ready: function( e ) {
			api.Control.prototype.ready.apply( this, arguments );
			var message = cozystayCustomizer.sync_message || {
					'sending'	: 'Data is syncing. Please wait. It can take a couple of minutes.',
					'done'		: 'Congratulations! Sync is completed.',
					'fail'		: 'Sorry but unable to sync. Please try again later.'
				},
				$container = $( this.container ),
				$notification = $container.find( '.customize-control-notifications-container' );
			$container.find( 'input[type=button]' ).on( 'click', function( e ) {
				e.preventDefault();
				var $self = $( this );
				$notification.css( 'display', 'none' );
				if ( $self.attr( 'action' ) && $self.attr( 'nonce') ) {
					$self.attr( 'disabled', 'disabled' );
					$notification.css( 'display', '' ).children().html( '<li>' + message.sending + '</li>' );
					wp.ajax.post( $self.attr( 'action'), { 'nonce': $self.attr( 'nonce' ) } )
						.done( function( response ) {
							$notification.css( 'display', '' ).children().html( '<li>' + message.done + '</li>' );
						} )
						.fail( function( response ) {
							$notification.css( 'display', '' ).children().html( '<li>' + message.fail + '</li>' );
						} )
						.always( function() {
							$self.removeAttr( 'disabled' );
						} );
				}
			} );
		}
	} );

	/**
	* Add new control type group
	*	1. add child controls when ready
	*/
	api.controlConstructor.group = api.Control.extend( {
		ready: function() {
			var control = this;
			if ( control.params.children ) {
				var $wrap = control.container.find( 'ul.group-controls-wrap' );
				$.each( control.params.children, function( cid, param ) {
					var Constructor = api.controlConstructor[ param.type ] || api.Control,
						Modified_Constructor = Constructor.extend( {
							embed: function() {
								var control = this, inject;
								inject = function( sectionId ) {
									var parentContainer;
									api.section( sectionId, function( section ) {
										// Wait for the section to be ready/initialized
										section.deferred.embedded.done( function() {
											$wrap.append( control.container );
											control.renderContent();
											control.deferred.embedded.resolve();
										} );
									} );
								};
								control.section.bind( inject) ;
								inject( control.section.get() );
							}
						} ),
						options = _.extend( { 'params': param }, param ),
						sub_controls = new Modified_Constructor( cid, options );
					api.cozystay.controls[ cid ] = $.extend( { 'container': sub_controls.container }, param );
				} );
			}
		}
	} );

	/**
	* For site layout
	*/
	api.cozystay.site_layout = api.Control.extend( {
		initialize: function(id, options){
			var control = this, settings;

			control.params = {};
			$.extend( control, options || {} );
			control.id = id;

			settings = $.map( control.params.settings, function( value ) {
				return value;
			} );

			if ( settings.length ) {
				api.apply( api, settings.concat( function() {
					var key;
					control.settings = {};
					for ( key in control.params.settings ) {
						control.settings[ key ] = api( control.params.settings[ key ] );
					}
					control.setting = control.settings['default'] || null;
				} ) );
			} else {
				control.setting = null;
				control.settings = {};
			}
			control.ready();
		},
		ready: function() {
			var control = this;
			if ( control.setting ) {
				control.setting.bind( function( value ) {
					control.settingChanged( value, control );
				} );
				control.settingChanged( control.setting(), control );
				api.trigger( 'change.cozystay.customizer', control.id );
			}
		},
		settingChanged: function( value, control ) {
			if ( value in cozystayCustomizer ) {
				$.each( cozystayCustomizer[ value ], function( id, title ) {
					control.updateControlTitle( id, title );
				});
			}
		},
		updateControlTitle: function( id, title ) {
			var c = api.control( id ), $container = c ? $( c.container ) : false;
			if ( $container && c.params.type ) {
				switch ( c.params.type ) {
					case 'title_only':
						$container.find( 'h3' ).text( title );
						break;
					case 'checkbox':
						var $label = $container.children( 'label' );
						$label.length ? $label.text( title ) : '';
						break;
					default:
						var $title = $container.find( '.customize-control-title' );
						$title.length ? $title.text( title ) : '';
				}
			}
		}
	} );

	/**
	* Register event handlers after wp.customize ready
	*/
	api.bind( 'ready', function( e ) {
		$( '#customize-control-header_image .customizer-section-intro' ).html( cozystayCustomizer.header_description );
		$( '#customize-control-header_image .current .customize-control-title' ).html( cozystayCustomizer.header_label );
		generateSettingDependency();
		if ( site_layout_id in api.settings.controls ) {
			new api.cozystay.site_layout( site_layout_id, {
				params: api.settings.controls[ site_layout_id ]
			} );
		}

		if ( $( '[data-customize-setting-link=cozystay_site_header_show_shop_cart]' ).length ) {
			var $mobileCartIcon = $( '[data-customize-setting-link=cozystay_site_header_show_mobile_cart_icon]' ).parent();
			api( 'cozystay_site_header_show_shop_cart' ).bind( function( to ) {
				to ? $mobileCartIcon.show() : $mobileCartIcon.hide();
			} );
			api( 'cozystay_site_header_show_shop_cart' )() ? $mobileCartIcon.show() : $mobileCartIcon.hide();
		}

		function checkPreviewerURL( target ) {
			var current = api.previewer.previewUrl();
			if ( ( typeof target != 'undefined' ) && target && ( current != target ) ) {
				api.previewer.previewUrl.set( target );
			}
		}

		$( 'body' ).on( 'click', 'a.show-control', function( e ) {
			e.preventDefault();
			var targetID = $( this ).data( 'control-id' );
			if ( targetID ) {
				api.previewer.trigger( 'focus-control-for-setting', targetID );
			}
		} )
		.on( 'click', 'a.show-panel, a.show-section', function( e ) {
			e.preventDefault();
			var targetID = $( this ).data( 'section-id' ), $currentOpen = $( '.control-section.open .customize-section-back' );
			if ( targetID && $( '#' + targetID ).length ) {
				$currentOpen.length ? $currentOpen.trigger( 'click' ) : '';
				$( '#' + targetID ).find( '.accordion-section-title' ).trigger( 'click' );
			}
		} )
		.on( 'click', 'a.redirect-preview-url', function( e ) {
			e.preventDefault();
			var param = $( this ).attr( 'href' );
			if ( $( this ).hasClass( 'static-home' ) ) {
				var home_id = api.get().page_for_posts ? api.get().page_for_posts : false;
				param = home_id ? '?page_id=' + home_id : '';
			}
			if ( param && ( param != '#' ) ) {
				checkPreviewerURL( api.settings.url.home + param );
			}
		} )
		.on( 'click', '#accordion-section-cozystay_general_section_404_page', function( e ) {
			checkPreviewerURL( cozystayCustomizer.errorURL );
		} )
		.on( 'click', '#sub-accordion-section-cozystay_general_section_404_page .customize-section-back', function( e ) {
			if ( api.previewer ) {
				checkPreviewerURL( cozystayCustomizer.homeURL );
			}
		} )
		.on( 'click', '#accordion-section-cozystay_general_section_popup_box', function( e ) {
			if ( api( 'cozystay_general_popup_box_enable' )() && api.previewer && api.previewer.targetWindow() ) {
				var $popupBox = $( '.cs-popup.cs-popup-box', api.previewer.targetWindow().document );
				$popupBox.length ? $popupBox.addClass( 'show' ) : '';
			}
		} )
		.on( 'click', '#sub-accordion-section-cozystay_general_section_popup_box .customize-section-back', function( e ) {
			if ( api.previewer && api.previewer.targetWindow() && api.previewer.targetWindow().document ) {
				var $popupBox = $( '.cs-popup.cs-popup-box', api.previewer.targetWindow().document );
				$popupBox.length ? $popupBox.removeClass( 'show' ) : '';
			}
		} )
		.on( 'click', '#accordion-section-cozystay_site_header_section_mobile_menu', function( e ) {
			if ( api.previewer && api.previewer.targetWindow() && api.previewer.targetWindow().document ) {
				var $sidemenu = $( '.sidemenu', api.previewer.targetWindow().document );
				if ( $sidemenu.length && ( ! $sidemenu.hasClass( 'show' ) ) ) {
					$sidemenu.addClass( 'show' );
				}
			}
		} )
		.on( 'click', '#sub-accordion-section-cozystay_site_header_section_mobile_menu .customize-section-back', function( e ) {
			if ( api.previewer && api.previewer.targetWindow() && api.previewer.targetWindow().document ) {
				var $sidemenu = $( '.sidemenu', api.previewer.targetWindow().document ),
					$closeBtn = $sidemenu.find( '.close-button' );
				if ( $closeBtn.length && $sidemenu.length && $sidemenu.hasClass( 'show' ) ) {
					$closeBtn.trigger( 'click' );
				}
			}
		} );

		$( document ).on( 'keyup', function( e ) {
			if ( 27 === e.keyCode ) {
				var $popupBox = $( '.cs-popup.cs-popup-box', api.previewer.targetWindow().document );
				$popupBox.length ? $popupBox.removeClass( 'show' ) : '';
			}
		} );
	} );
} ) (  wp.customize, jQuery);
