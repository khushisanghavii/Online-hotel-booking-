( function( blocks, editor, components, i18n, element, hooks, $ ) {
	"use strict";
	const __ = i18n.__;
	const addFilter = hooks.addFilter;
	const el = element.createElement;
	const {
		Fragment
	} = element;
	const {
		ToggleControl,
		TextControl,
		TextareaControl,
		SelectControl,
		PanelBody
	} = components;
	const {
		PanelColorSettings,
		MediaUpload
	} = editor;

	function addPageMetas( metas, context ) {
		var newMetas = Object.assign( metas, {
			siteHeader:	function() {
				var customHeader = false, showSource = false, showCustomHeaders = false;
				if ( cozystayBlockEditorSettings.customSiteHeader ) {
					customHeader = [];
					$.each( cozystayBlockEditorSettings.customSiteHeader, function( id, label ) {
						customHeader.push( { value: id, label: label } );
					} );
				}

				showSource = ( ! context.props.meta.cozystay_single_page_hide_site_header ) && ( !! customHeader );
				showCustomHeaders = showSource && ( 'custom' == context.props.meta.cozystay_single_page_site_header_source );

				return el( PanelBody, {
						className: 'cs-site-header-options',
						title: __( 'Site Header' ),
						initialOpen: false
					},
					el( ToggleControl, {
						label: i18n.__( 'Hide Site Header' ),
						checked: ! ! context.props.meta.cozystay_single_page_hide_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_hide_site_header: ( value ? 'on' : '' ) } );
						}
					} ),
					showSource && el( SelectControl, {
						label: __( 'Site Header Source:' ),
						value: context.props.meta.cozystay_single_page_site_header_source,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_site_header_source: value } );
						},
						options: [
							{ value: '', label: __( 'Inherit' ) },
							{ value: 'custom', label: __( 'Custom' ) }
						]
					} ),
					showCustomHeaders && el( SelectControl, {
						label: __( 'Select a Custom Site Header' ),
						value: context.props.meta.cozystay_single_page_custom_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_custom_site_header: value } );
						},
						options: customHeader
					} ),
					( ! cozystayBlockEditorSettings.disableStickySiteHeader ) && showCustomHeaders && el( SelectControl, {
						label: __( 'Select a Custom Sticky Site Header' ),
						value: context.props.meta.cozystay_single_page_custom_sticky_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_custom_sticky_site_header: value } );
						},
						options: customHeader
					} )
				);
			},
			pageTitleSection: function() {
				return el( PanelBody, {
						className: 'cs-page-title-options',
						title: __( 'Page Title Section' ),
						initialOpen: false
					},
					el( ToggleControl, {
						label: i18n.__( 'Hide Page Title Section' ),
						checked: ! ! context.props.meta.cozystay_single_page_hide_page_title,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_hide_page_title: ( value ? 'on' : '' ) } );
						}
					} ),
					el( SelectControl, {
						type: 'select',
						label: __( 'Section Size' ),
						value: context.props.meta.cozystay_single_page_header_section_size,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_section_size: value } );
						},
						options: [
							{ value: '', label: __( 'Default' ) },
							{ value: 'page-title-small', label: __( 'Small' ) },
							{ value: 'page-title-default', label: __( 'Medium' ) },
							{ value: 'page-title-large', label: __( 'Large' ) },
							{ value: 'page-title-fullheight', label: __( 'Screen Height' ) }
						]
					} ),
					el( PanelColorSettings, {
						title: __( 'Colors' ),
						colorSettings: [ {
							label: __( 'Background Color' ),
							value: context.props.meta.cozystay_single_page_header_background_color,
							onChange: ( value ) => {
								context.onSaveMeta( { cozystay_single_page_header_background_color: value || '' } );
							}
						}, {
							label: __( 'Text Color' ),
							value: context.props.meta.cozystay_single_page_header_text_color,
							onChange: ( value ) => {
								context.onSaveMeta( { cozystay_single_page_header_text_color: value || '' } );
							}
						} ]
					} ),
					el( MediaUpload, {
						onSelect: ( media ) => {
							if ( media ) {
								context.onSaveMeta( {
									cozystay_single_page_header_background_image: media.id,
									cozystay_single_page_header_background_image_url:  media.url
								} );
							}
						},
						type: 'image',
						value: context.props.meta.cozystay_single_page_header_background_image,
						render: function( obj ) {
							return el( Fragment, {},
								el( 'p', { style: { 'margin-top': '20px' } }, el( 'label', {}, __( 'Background Image' ) ) ),
								el( 'p', { 'className': 'description', 'style': { 'margin-bottom': '20px' } }, __( 'Upload a featured image and it will be used as the background image of page title section.' ) )
							);
						}
					} ),
					( !! context.props.currentFeaturedImage ) && el( SelectControl, {
						type: 'select',
						label: __( 'Background Position X' ),
						value: context.props.meta.cozystay_single_page_header_background_position_x,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_background_position_x: value } );
						},
						options: [
							{ value: 'left', label: __( 'Left' ) },
							{ value: 'center', label: __( 'Center' ) },
							{ value: 'right', label: __( 'Right' ) }
						]
					} ),
					( !! context.props.currentFeaturedImage ) && el( SelectControl, {
						type: 'select',
						label: __( 'Background Position Y' ),
						value: context.props.meta.cozystay_single_page_header_background_position_y,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_background_position_y: value } );
						},
						options: [
							{ value: 'top', label: __( 'Top' ) },
							{ value: 'center', label: __( 'Center' ) },
							{ value: 'bottom', label: __( 'Bottom' ) }
						]
					} ),
					( !! context.props.currentFeaturedImage ) && el( SelectControl, {
						type: 'select',
						label: __( 'Background Size' ),
						value: context.props.meta.cozystay_single_page_header_background_size,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_background_size: value } );
						},
						options: [
							{ value: 'auto', label: __( 'Original' ) },
							{ value: 'contain', label: __( 'Fit to Screen' ) },
							{ value: 'cover', label: __( 'Fill Screen' ) }
						]
					} ),
					( !! context.props.currentFeaturedImage ) && el( ToggleControl, {
						label: i18n.__( 'Background Repeat' ),
						checked: context.props.meta.cozystay_single_page_header_background_repeat && ( 'on' == context.props.meta.cozystay_single_page_header_background_repeat ),
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_background_repeat: ( value ? 'on' : 'off' ) } );
						}
					} ),
					( !! context.props.currentFeaturedImage ) && el( ToggleControl, {
						label: i18n.__( 'Background Image Scroll with Page' ),
						checked: context.props.meta.cozystay_single_page_header_background_scroll && ( 'on' == context.props.meta.cozystay_single_page_header_background_scroll ),
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_background_scroll: ( value ? 'on' : 'off' ) } );
						}
					} ),
					cozystayBlockEditorSettings.yoastSEO_enabled && el( SelectControl, {
						label: i18n.__( 'Display Breadcrumb' ),
						value: context.props.meta.cozystay_single_page_header_show_breadcrumb,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_header_show_breadcrumb: value } );
						},
						options: [
							{ value: '', label: __( 'Default' ) },
							{ value: 'show', label: __( 'Show' ) },
							{ value: 'hide', label: __( 'Hide' ) }
						]
					} )
				);
			},
			siteFooter: function() {
				var customBlocks = false, hasCustomBlocks = false,
					showFooterMain = false, showFooterAbove = false;
				if ( cozystayBlockEditorSettings.customBlock ) {
					customBlocks = [];
					$.each( cozystayBlockEditorSettings.customBlock, function( id, label ) {
						hasCustomBlocks = true;
						customBlocks.push( { value: id, label: label } );
					} );
					showFooterMain = hasCustomBlocks && ( 'on' != context.props.meta.cozystay_single_post_site_footer_hide_main );
					showFooterAbove = hasCustomBlocks && ( 'on' != context.props.meta.cozystay_single_post_site_footer_hide_above );
				}
				return el( PanelBody, {
						className: 'cs-site-footer-options',
						title: __( 'Site Footer' ),
						initialOpen: false
					},
					el( ToggleControl, {
						label: i18n.__( 'Hide Site Footer Main' ),
						checked: ! ! context.props.meta.cozystay_single_page_site_footer_hide_main,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_site_footer_hide_main: ( value ? 'on' : '' ) } );
						}
					} ),
					showFooterMain && el( SelectControl, {
						label: __( 'Site Footer Main Source' ),
						value: context.props.meta.cozystay_single_custom_site_footer_main_source,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_site_footer_main_source: value } );
						},
						options: [
							{ value: '', label: __( 'Inherit' ) },
							{ value: 'custom', label: __( 'Custom' ) }
						]
					} ),
					showFooterMain && ( 'custom' == context.props.meta.cozystay_single_custom_site_footer_main_source ) && el( SelectControl, {
						label: __( 'Select a Custom Site Footer Main' ),
						value: context.props.meta.cozystay_single_custom_site_footer_main,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_site_footer_main: value } );
						},
						options: customBlocks
					} ),
					el( ToggleControl, {
						label: i18n.__( 'Hide Before Footer' ),
						checked: ! ! context.props.meta.cozystay_single_page_site_footer_hide_above,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_site_footer_hide_above: value ? 'on' : '' } );
						}
					} ),
					showFooterAbove && el( SelectControl, {
						label: __( 'Before Footer Source' ),
						value: context.props.meta.cozystay_single_custom_site_footer_above_source,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_site_footer_above_source: value } );
						},
						options: [
							{ value: '', label: __( 'Inherit' ) },
							{ value: 'custom', label: __( 'Custom' ) }
						]
					} ),
					showFooterMain && ( 'custom' == context.props.meta.cozystay_single_custom_site_footer_above_source ) && el( SelectControl, {
						label: __( 'Select a Custom Before Footer' ),
						value: context.props.meta.cozystay_single_custom_site_footer_above,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_site_footer_above: value } );
						},
						options: customBlocks
					} ),
					el( ToggleControl, {
						label: i18n.__( 'Hide Instagram' ),
						checked: ! ! context.props.meta.cozystay_single_page_site_footer_hide_instagram,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_site_footer_hide_instagram: ( value ? 'on' : '' ) } );
						}
					} ),
					el( ToggleControl, {
						label: i18n.__( 'Hide Footer Bottom' ),
						checked: ! ! context.props.meta.cozystay_single_page_site_footer_hide_bottom,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_page_site_footer_hide_bottom: value ? 'on' : '' } );
						}
					} )
				);
			},
			mobileMenu: function() {
				var customBlocks = false, showBlocks = false;
				if ( cozystayBlockEditorSettings.customBlock ) {
					customBlocks = [];
					$.each( cozystayBlockEditorSettings.customBlock, function( id, label ) {
						customBlocks.push( { value: id, label: label } );
					} );
				}
				if ( ! customBlocks ) return;

				showBlocks = 'custom' == context.props.meta.cozystay_single_custom_mobile_menu_source;

				return el( PanelBody, {
						title: __( 'Fullscreen/Mobile Menu' ),
						initialOpen: false
					},
					!! customBlocks && el( SelectControl, {
						label: __( 'Fullscreen/Mobile Menu Source:' ),
						value: context.props.meta.cozystay_single_custom_mobile_menu_source,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_mobile_menu_source: value } );
						},
						options: [
							{ value: '', label: __( 'Inherit' ) },
							{ value: 'custom', label: __( 'Custom' ) }
						]
					} ),
					showBlocks && el( SelectControl, {
						label: __( 'Select a Custom Fullscreen/Mobile Menu' ),
						value: context.props.meta.cozystay_single_custom_mobile_menu,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_mobile_menu: value } );
						},
						options: customBlocks
					} ),
					showBlocks && el( SelectControl, {
						label: __( 'Entrance Animation' ),
						value: context.props.meta.cozystay_single_custom_mobile_menu_animation,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_mobile_menu_animation: value } );
						},
						options: [
							{ value: '', label: __( 'Slide From Right' ) },
							{ value: 'slide-from-left', label: __( 'Slide From Left' ) },
							{ value: 'fade-in', label: __( 'Fade In' ) }
						]
					} ),
					showBlocks && el( SelectControl, {
						label: __( 'Width' ),
						value: context.props.meta.cozystay_single_custom_mobile_menu_width,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_mobile_menu_width: value } );
						},
						options: [
							{ value: '', label: __( 'Default' ) },
							{ value: 'fullwidth', label: __( 'Fit to Screen' ) },
							{ value: 'custom-width', label: __( 'Custom' ) }
						]
					} ),
					showBlocks && ( 'custom-width' == context.props.meta.cozystay_single_custom_mobile_menu_width ) && el( TextControl, {
						type: 'number',
						label: __( 'Custom Width' ),
						value: context.props.meta.cozystay_single_custom_mobile_menu_custom_width,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_custom_mobile_menu_custom_width: value } );
						}
					} )
				);
			}
		} );
		return newMetas;
	}

	function withSelectReturn( value, select ) {
		const { getEditedPostAttribute } = select( 'core/editor' );
		value.currentTemplate = getEditedPostAttribute( 'template' ) || '';
		value.currentFeaturedImage = getEditedPostAttribute( 'featured_media' );
		return value;
	}

	addFilter( 'loftocean.page.metas.filter', 'loftocean/page-metas', addPageMetas );
	addFilter( 'loftocean.page.withSelectReturn.filter', 'loftocean/page-metas', withSelectReturn );
} ) (
	window.wp.blocks,
	window.wp.editor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
	window.wp.hooks,
	jQuery
);
