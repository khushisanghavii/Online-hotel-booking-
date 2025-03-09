/**
* Copyright (c) Loft.Ocean
* http://www.loftocean.com
*/

( function( api, $, parent ) {
	"use strict";

	/** Global jQuery objects **/
	var $body = $( 'body' ), $head = $( 'head' ), $siteTitle = $( '.site-title a' ), $siteDescription = $( '.site-description' ), $toTop = $( '.to-top' ), $mobileMenuWrap = $( '.sidemenu' ),
		$popupBox = $( '.cs-popup.cs-popup-box' ), $mobileMenuContainer = $( '.sidemenu .container' ), $pageTitleSection = $( '.page-title-section' ), $cookieLaw = $( '.cs-cookies-popup' ),
		$siteFooterInstagram = $( '.site-footer .cs-widget_instagram' ), $siteFooterBottom = $( '.site-footer .site-footer-bottom' ), $defaultPageTitleBackground = $( '.page-title-default-background-image' ),
		$relatedPostTitle = $( '.related-posts .related-posts-title' ), $loader = $( '#cs-loader-wrapper' ), settingDefaultValues = {
			'cozystay_typography_heading_letter-spacing': '0',
			'cozystay_typography_subheading_letter-spacing': '0.05em',
			'cozystay_typography_text_letter-spacing': '0',
			'cozystay_typography_blog_title_letter-spacing': '0',
			'cozystay_typography_secondary_letter-spacing': '0.05em',
			'cozystay_typography_widget_title_letter-spacing': '0.05em',
			'cozystay_typography_menu_letter-spacing': '0.05em',
			'cozystay_typography_footer_bottom_menu_letter-spacing': '0'
		};

	/**
	* Get attachment url
	* @param string attachment id
	* @return mix if attachment exists attachment url, otherwise boolean false
	*/
	function getAttachmentUrl( id ) {
		if ( id && parent && parent.wp && parent.wp.media ) {
			var attachment = parent.wp.media.attachment( id );
			attachment.fetch();
			return attachment && attachment.attributes ? attachment.get( 'url' ) : false;
		}
		return false;
	}
	/**
	* Get attachment details
	*/
	function getAttachmentDetails( id ) {
		if ( id && parent && parent.wp && parent.wp.media ) {
			var attachment = parent.wp.media.attachment( id );
			attachment.fetch();
			return attachment && attachment.attributes ? attachment : false;
		}
		return false;
	}
	/**
	* Set inline styles to <head>
	* @param style id
	* @param string style
	*/
	function updateStyle( id, style ) {
		var $style 	= $head.find( '#' + id );
		style 	= style || '';
		if ( ! $style.length ) {
			$style = $( '<style>', { 'id': id } )
				.appendTo( $head );
		}
		$style.html( style );
	}
	/**
	* Refresh preview iframe
	*/
	function refreshPreviewFrame() {
		parent.wp.customize.previewer.refresh();
	}

	/** Customize setting event hanlder if their transort are set with postMessage **/
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$siteTitle.length ? $siteTitle.text( to ) : '';
		} );
	} );
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$siteDescription.length ? $siteDescription.text( to ) : '';
		} );
	} );

	// Site general
	api( 'cozystay_page_content_width', function( value ) {
		value.bind( function( to ) {
			if ( $body.length ) {
				if ( 'custom' == to ) {
					$body.removeClass( 'site-layout-fullwidth' ).addClass( 'custom-site-width' );
					updateStyle( 'cozystay-site-width', ':root { --custom-site-width: ' + api( 'cozystay_page_content_custom_width' )() + 'px; }' );
				} else {
					$body.removeClass( 'custom-site-width' ).addClass( 'site-layout-fullwidth' );
				}
			}
		} );
	} );
	api( 'cozystay_page_content_custom_width', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-site-width', ':root { --custom-site-width: ' + api( 'cozystay_page_content_custom_width' )() + 'px; }' );
		} );
	} );

	api( 'cozystay_show_back_to_top_button', function( value ) {
		value.bind( function( to ) {
			if ( $toTop.length ) {
				to ? $toTop.removeClass( 'hide' ) : $toTop.addClass( 'hide' );
			}
		} );
	} );

	api( 'cozystay_general_cookie_law_enabled', function( value ) {
		value.bind( function( to ) {
			if ( $cookieLaw.length ) {
				to ? $cookieLaw.addClass( 'show' ) : $cookieLaw.removeClass( 'show' );
			}
		} );
	} );
	api( 'cozystay_general_cookie_law_accept_button_text', function( value ) {
		value.bind( function( to ) {
			if ( $cookieLaw.length ) {
				$cookieLaw.find( '.cookies-buttons a.button' ).text( to );
			}
		} );
	} );

	api( 'cozystay_general_popup_box_enable', function( value ) {
		value.bind( function ( to ) {
			if ( $popupBox.length ) {
				to ? $popupBox.addClass( 'show' ) : $popupBox.removeClass( 'show' );
			}
		} );
	} );
	api( 'cozystay_general_popup_box_size', function( value ) {
		value.bind( function( to ) {
			if ( $popupBox.length ) {
				if ( 'fullscreen' == to ) {
					$popupBox.addClass( 'cs-popup-fullsize' );
				} else {
					$popupBox.removeClass( 'cs-popup-fullsize' );
					updateStyle( 'cozystay-popupbox-custom-width', ':root { --popup-width: ' + api( 'cozystay_general_popup_box_custom_width' )() + 'px; }' );
				}
			}
		} );
	} );
	api( 'cozystay_general_popup_box_custom_width', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-popup-custom-width', ':root { --popup-width: ' + to + 'px; }' );
		} );
	} );
	api( 'cozystay_general_popup_box_color_scheme', function( value ) {
		value.bind( function( to ) {
			if ( $popupBox.length ) {
				$popupBox.removeClass( 'light-color dark-color' ).addClass( to );
			}
		} );
	} );
	api( 'cozystay_general_popup_box_background_color', function( value ) {
		value.bind( function( to ) {
			if ( $popupBox.length ) {
				$popupBox.css( 'background-color', to ? to : 'var(--bg-color)' );
			}
		} );
	} );
	api( 'cozystay_general_popup_box_background_image', function( value ) {
		value.bind( function( to ) {
			if ( $popupBox.length ) {
				var $background = $popupBox.find( '.screen-bg' );
				if ( to ) {
					var imageURL = getAttachmentUrl( to );
					$background.length ? $background.css( 'background-image', 'url(' + imageURL + ')' )
						: $popupBox.prepend( $( '<div>', { 'class': 'screen-bg' } ).css( 'background-image', 'url(' + imageURL + ')' ) );
				} else {
					$background.length ? $background.remove() : '';
				}
			}
		} );
	} );

	// Site header
	api( 'cozystay_mobile_site_header_copyright_text', function( value ) {
		value.bind( function( to ) {
			var $mobileCopyright = $( '.sidemenu.sidemenu-default .copyright' );
			if ( $mobileCopyright.length ) {
				to ? $mobileCopyright.html( to ).removeClass( 'hide' ) : $mobileCopyright.html( '' ).addClass( 'hide' );
			}
		} );
	} );

	api( 'cozystay_mobile_site_header_background_image', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuContainer.length ) {
				to ? $mobileMenuContainer.css( 'background-image', 'url(' + getAttachmentUrl( to ) + ')' ) : $mobileMenuContainer.css( 'background-image', '' );
			}
		} );
	} );
	api( 'cozystay_mobile_site_header_background_color', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuContainer.length ) {
				 $mobileMenuContainer.css( 'background-color', ( to ? to : '#000000' ) );
			}
	 	} );
	 } );
	api( 'cozystay_mobile_site_header_text_color', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuContainer.length ) {
				 $mobileMenuContainer.css( 'color', ( to ? to : '#FFFFFF' ) );
			 }
		} );
	} );
	api( 'cozystay_mobile_site_header_entrance_animation', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuWrap.length ) {
				$mobileMenuWrap.removeClass( 'slide-from-left fade-in' );
				to ? $mobileMenuWrap.addClass( to ) : '';
			}
		} );
	} );
	api( 'cozystay_mobile_site_header_width', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuWrap.length ) {
				$mobileMenuWrap.removeClass( 'fullwidth custom-width' );
				to ? $mobileMenuWrap.addClass( to ) : '';
			}
		} );
	} );
	api( 'cozystay_mobile_site_header_custom_width', function( value ) {
		value.bind( function( to ) {
			if ( $mobileMenuWrap.length ) {
				updateStyle( 'cozystay-site-header-mobile-custom-width', '.sidemenu.custom-width { max-width: ' + to + 'px; }' );
			}
		} );
	} );

	// Site Footer
	api( 'cozystay_site_footer_instagram_new_tab', function( value ) {
		value.bind( function( to ) {
			if ( $siteFooterInstagram.length && $siteFooterInstagram.find( 'a' ).length ) {
				to ? $siteFooterInstagram.find( 'a' ).attr( 'target', '_blank' ) : $siteFooterInstagram.find( 'a' ).attr( 'target', '' );
			}
		} );
	} );
	api( 'cozystay_site_footer_bottom_layout', function( value ) {
		value.bind( function( to ) {
			if ( $siteFooterBottom.length ) {
				to ? $siteFooterBottom.addClass( 'column-single' ) : $siteFooterBottom.removeClass( 'column-single' );
			}
		} );
	} );
	api( 'cozystay_site_footer_bottom_text', function( value ) {
		value.bind( function( to ) {
			if ( $siteFooterBottom.length ) {
				var $textWidget = $siteFooterBottom.find( '.textwidget' );
				$textWidget.length ? $textWidget.html( to ) : refreshPreviewFrame();
			}
		} );
	} );
	api( 'cozystay_site_footer_bottom_background_color', function( value ) {
		value.bind( function( to ) {
			if ( $siteFooterBottom.length ) {
				$siteFooterBottom.css( 'background-color', ( to ? to : '#111111' ) );
			}
		} );
	} );
	api( 'cozystay_site_footer_bottom_text_color', function( value ) {
		value.bind( function( to ) {
			if ( $siteFooterBottom.length ) {
				$siteFooterBottom.css( 'color', ( to ? to : '#FFFFFF' ) );
			}
		} );
	} );

	// Colors and Styles
	api( 'cozystay_general_color_scheme', function( value ) {
		value.bind( function( to ) {
			$body.removeClass( 'light-color dark-color' ).addClass( to );
		} );
	} );
	api( 'cozystay_general_primary_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-primary-color', ':root { --primary-color: ' + ( to ? to : '#F56D6B' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_secondary_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-secondary-color', ':root { --secondary-color: ' + ( to ? to : '#C59764' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_light_scheme_background_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-light-background-color', ':root { --light-bg-color: ' + ( to ? to : '#FFFFFF' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_light_scheme_text_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-light-text-color', ':root { --light-text-color: ' + ( to ? to : '#000000' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_light_scheme_content_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-light-content-color', ':root { --light-content-color: ' + ( to ? to : '#111111' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_dark_scheme_background_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-dark-background-color', ':root { --dark-bg-color: ' + ( to ? to : '#0E0D0A' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_dark_scheme_text_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-dark-text-color', ':root { --dark-text-color: ' + ( to ? to : '#FFFFFF' ) + '; } ' );
		} );
	} );
	api( 'cozystay_general_dark_scheme_content_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-dark-content-color', ':root { --dark-content-color: ' + ( to ? to : '#EEEEEE' ) + '; } ' );
		} );
	} );

	api( 'cozystay_link_light_scheme_regular_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-light-link-color', ':root { --light-link-color: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-light-link-color', ':root { --light-link-color: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_link_light_scheme_custom_regular_color' )();
					updateStyle( 'cozystay-light-link-color', ':root { --light-link-color: ' + ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_link_light_scheme_custom_regular_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-light-link-color', ':root { --light-link-color: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );
	api( 'cozystay_link_light_scheme_hover_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-light-link-hover-color', ':root { --light-link-color-hover: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-light-link-hover-color', ':root { --light-link-color-hover: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_link_light_scheme_custom_hover_color' )();
					updateStyle( 'cozystay-light-link-hover-color', ':root { --light-link-color-hover: ' + ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_link_light_scheme_custom_hover_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-light-link-hover-color', ':root { --light-link-color-hover: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );
	api( 'cozystay_link_dark_scheme_regular_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-dark-link-color', ':root { --dark-link-color: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-dark-link-color', ':root { --dark-link-color: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_link_dark_scheme_custom_regular_color' )();
					updateStyle( 'cozystay-dark-link-color', ':root { --dark-link-color: ' +  ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_link_dark_scheme_custom_regular_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-dark-link-color', ':root { --dark-link-color: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );
	api( 'cozystay_link_dark_scheme_hover_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-dark-link-hover-color', ':root { --dark-link-color-hover: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-dark-link-hover-color', ':root { --dark-link-color-hover: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_link_dark_scheme_custom_hover_color' )();
					updateStyle( 'cozystay-dark-link-hover-color', ':root { --dark-link-color-hover: ' + ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_link_dark_scheme_custom_hover_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-darkt-link-hover-color', ':root { --dark-dark-color-hover: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );

	api( 'cozystay_button_shape', function( value ) {
		value.bind( function( to ) {
			$body.removeClass( 'cs-btn-rounded cs-btn-pill' );
			to ? $body.addClass( to ) : '';
		} );
	} );
	api( 'cozystay_button_background_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-button-background-color', ':root { --btn-bg: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-button-background-color', ':root { --btn-bg: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_button_custom_background_color' )();
					updateStyle( 'cozystay-button-background-color', ':root { --btn-bg: ' + ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_button_custom_background_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-button-background-color', ':root { --btn-bg: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );
	api( 'cozystay_button_text_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-button-text-color', ':root { --btn-color: ' + ( to ? to : '#FFFFFF' ) + '; } ' );
		} );
	} );
	api( 'cozystay_button_hover_background_color', function( value ) {
		value.bind( function( to ) {
			switch ( to ) {
				case 'primary':
					updateStyle( 'cozystay-button-hover-background', ':root { --btn-bg-hover: var(--primary-color); } ' );
					break;
				case 'secondary':
					updateStyle( 'cozystay-button-hover-background', ':root { --btn-bg-hover: var(--secondary-color); } ' );
					break;
				default:
					var customColor = api( 'cozystay_button_hover_custom_background_color' )();
					updateStyle( 'cozystay-button-hover-background', ':root { --btn-bg-hover: ' + ( customColor ? customColor : 'var(--primary-color)' ) + '; } ' );
			}
		} );
	} );
	api( 'cozystay_button_hover_custom_background_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-button-hover-background', ':root { --btn-bg-hover: ' + ( to ? to : 'var(--primary-color)' ) + '; } ' );
		} );
	} );
	api( 'cozystay_button_hover_text_color', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-button-hover-text-color', ':root { --btn-color-hover: ' + ( to ? to : '#FFFFFF' ) + '; } ' );
		} );
	} );

	api( 'cozystay_form_field_style', function( value ) {
		value.bind( function( to ) {
			$body.removeClass( 'cs-form-underline cs-form-square cs-form-rounded cs-form-pill' ).addClass( to );
		} );
	} );
	api( 'cozystay_form_border_width', function( value ) {
		value.bind( function( to ) {
			updateStyle( 'cozystay-form-border', ':root { --form-bd-width: ' + ( to ? to : 0 ) + 'px; }' );
		} );
	} );

	// Typopraphy
	var typographySettings = {
		'heading': {
			'font-weight': '--hf-weight',
			'letter-spacing': '--hf-letter-spacing',
			'text-transform': '--hf-text-transform',
			'font-style': '--hf-style'
		},
		'subheading': {
			'font-size': '--shf-font-size',
			'font-weight': '--shf-weight',
			'letter-spacing': '--shf-letter-spacing',
			'text-transform': '--shf-text-transform',
			'font-style': '--shf-style'
		},
		'blog_title': {
			'font-weight': '--blog-title-weight',
			'letter-spacing': '--bt-letter-spacing',
			'text-transform': '--bt-text-transform',
			'font-style': '--bt-style'
		},
		'blog_content': {
			'font-size': '--post-text-size',
			'line-height': '--post-line-height'
		},
		'secondary': {
			'letter-spacing': '--sf-letter-spacing',
			'text-transform': '--sf-text-transform',
			'font-style': '--sf-style'
		},
		'widget_title': {
			'font-size': '--widget-title-size',
			'font-weight': '--widget-title-weight',
			'letter-spacing': '--widget-title-spacing',
			'text-transform': '--widget-title-trans',
			'font-style': '--widget-title-style'
		},
		'menu': {
			'font-size': '--nav-font-size',
			'font-weight': '--nav-font-weight',
			'letter-spacing': '--nav-font-letter-spacin',
			'text-transform': '--nav-font-transform'
		},
		'footer_bottom_menu': {
			'font-size': '--fbnav-font-size',
			'font-weight': '--fbnav-font-weight',
			'letter-spacing': '--fbnav-font-letter-spacing',
			'text-transform': '--fbnav-font-transform'
		},
		'button_text': {
			'font-size': '--btn-font-size',
			'font-weight': '--btn-font-weight',
			'letter-spacing': '--btn-letter-spacing',
			'text-transform': '--btn-text-transform'
		}
	};
	var settingPrefix = 'cozystay_typography_';
	$.each( typographySettings, function( id, attrs ) {
		$.each( attrs, function( prop, cssVar ) {
			var settingID = settingPrefix + id + '_' + prop;
			api( settingID, function( value ) {
				value.bind( function( to ) {
					var currentValue = to ? to : ( settingID in settingDefaultValues ? settingDefaultValues[ settingID ] : '' );
				 	updateStyle( settingID, ':root { ' + cssVar + ': ' + currentValue + ( 'font-size' == prop ? 'px' : '' ) + '; } ' );
				} );
			} );
		} );
	} );
	var textTypographySettings = [ 'font-size', 'font-weight', 'letter-spacing', 'text-transform', 'font-style' ];
	$.each( textTypographySettings, function( index, prop ) {
		var settingID = settingPrefix + 'text_' + prop;
		api( settingID, function( value ) {
			value.bind( function( to ) {
				var currentValue = to ? to : ( settingID in settingDefaultValues ? settingDefaultValues[ settingID ] : '' );
				updateStyle( settingID, 'body { ' + prop + ': ' + to + ( 'font-size' == prop ? 'px' : '' ) + '; } ' );
			} );
		} );
	} );

	// Page title
	api( 'cozystay_page_title_section_size', function( value ) {
		value.bind( function( to ) {
			if ( $pageTitleSection.length ) {
				$pageTitleSection.removeClass( 'page-title-large page-title-small page-title-default page-title-fullheight' ).addClass( to );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_size', function( value ) {
		value.bind( function( to ) {
			if ( $defaultPageTitleBackground.length ) {
				$defaultPageTitleBackground.css( 'background-size', to );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_repeat', function( value ) {
		value.bind( function( to ) {
			if ( $defaultPageTitleBackground.length ) {
				$defaultPageTitleBackground.css( 'background-repeat', ( to ? 'repeat' : 'no-repeat' ) );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_position_x', function( value ) {
		value.bind( function( to ) {
			if ( $defaultPageTitleBackground.length ) {
				$defaultPageTitleBackground.css( 'background-position', to + ' ' + api( 'cozystay_page_title_default_background_position_y' )() );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_position_y', function( value ) {
		value.bind( function( to ) {
			if ( $defaultPageTitleBackground.length ) {
				$defaultPageTitleBackground.css( 'background-position', api( 'cozystay_page_title_default_background_position_x' )() + ' ' + to );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_attachment', function( value ) {
		value.bind( function( to ) {
			if ( $defaultPageTitleBackground.length ) {
				$defaultPageTitleBackground.css( 'background-attachment', ( to ? 'scroll' : 'fixed' ) );
			}
		} );
	} );
	api( 'cozystay_page_title_default_background_color', function( value ) {
		value.bind( function( to ) {
			if ( $pageTitleSection.length ) {
				to ? updateStyle( 'cozystay-page-title-background-color', '#page { --page-title-bg: ' + to + '; } ' ) : refreshPreviewFrame();
			}
		} );
	} );
	api( 'cozystay_page_title_default_text_color', function( value ) {
		value.bind( function( to ) {
			if ( $pageTitleSection.length ) {
				to ? updateStyle( 'cozystay-page-title-text-color', '#page { --page-title-color: ' + to + '; } ' ) : refreshPreviewFrame();
			}
		} );
	} );

	// Single post
	api( 'cozystay_blog_single_post_title_default_background_color', function( value ) {
		value.bind( function( to ) {
			if ( $pageTitleSection.length ) {
				to ? updateStyle( 'cozystay-single-title-background-color', '.single #page { --page-title-bg: ' + to + '; } ' ) : refreshPreviewFrame();
			}
		} );
	} );
	api( 'cozystay_blog_single_post_title_default_text_color', function( value ) {
		value.bind( function( to ) {
			if ( $pageTitleSection.length ) {
				to ? updateStyle( 'cozystay-single-title-text-color', '.single #page { --page-title-color: ' + to + '; } ' ) : refreshPreviewFrame();
			}
		} );
	} );
	api( 'cozystay_blog_single_post_related_post_section_title', function( value ) {
		value.bind( function( to ) {
			if ( $relatedPostTitle.length ) {
				$relatedPostTitle.text( to );
			}
		} );
	} );

	[ 'cozystay_above_site_footer_text_content', 'cozystay_general_cookie_law_message' ].forEach( function( id ) {
		api( id, function( value ) {
			value.bind( function( to ) {
				this.editorRefreshTimer ? clearTimeout( this.editorRefreshTimer ) : '';
				this.editorRefreshTimer = setTimeout( function() {
					parent.wp.customize.previewer.refresh();
				}, 600 );
			} );
		} );
	} );


	wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
		if ( 'cozystay_mobile_menu_content' == placement.partial.id ) {
			$mobileMenuWrap = $( '.sidemenu' );
			$mobileMenuContainer = $mobileMenuWrap.find( '.container' );
		}
	} );

	$( 'body' ).on( 'click', '.cs-popup.cs-popup-box.cs-site-popup.show .close-button', function( e ) {
		e.preventDefault();
		$popupBox.removeClass( 'show' );
	} )

} ) ( wp.customize, jQuery, parent );
