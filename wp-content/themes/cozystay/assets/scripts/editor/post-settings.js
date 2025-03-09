( function( editor, components, i18n, element, hooks, $ ) {
	"use strict";
	const __ = i18n.__;
	const addFilter = hooks.addFilter;
	const MediaUpload = editor.MediaUpload;
	const el = element.createElement;
	const {
		Button,
		SelectControl,
		ToggleControl,
		TextControl,
		TextareaControl,
		PanelBody
	} = components;
	const {
		Fragment,
		Component
	} = element;

	function addPostMetas( metas, context ) {
		var newMetas = Object.assign( metas, {
			siteHeader: function() {
				var customHeader = false, showSource = false, showCustomHeaders = false;
				if ( cozystayBlockEditorSettings.customSiteHeader ) {
					customHeader = [];
					$.each( cozystayBlockEditorSettings.customSiteHeader, function( id, label ) {
						customHeader.push( { value: id, label: label } );
					} );
				}
				showSource = ( ! context.props.meta.cozystay_single_post_hide_site_header ) && ( !! customHeader );
				showCustomHeaders = showSource && ( 'custom' == context.props.meta.cozystay_single_post_site_header_source );

				return el( PanelBody, {
						title: __( 'Site Header' ),
						initialOpen: false
					},
					el( ToggleControl, {
						label: i18n.__( 'Hide Site Header' ),
						checked: ! ! context.props.meta.cozystay_single_post_hide_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_hide_site_header: ( value ? 'on' : '' ) } );
						}
					} ),
					showSource && el( SelectControl, {
						label: __( 'Site Header Source:' ),
						value: context.props.meta.cozystay_single_post_site_header_source,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_site_header_source: value } );
						},
						options: [
							{ value: '', label: __( 'Inherit' ) },
							{ value: 'custom', label: __( 'Custom' ) }
						]
					} ),
					showCustomHeaders && el( SelectControl, {
						label: __( 'Select a Custom Site Header' ),
						value: context.props.meta.cozystay_single_post_custom_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_custom_site_header: value } );
						},
						options: customHeader
					} ),
					( ! cozystayBlockEditorSettings.disableStickySiteHeader ) && showCustomHeaders && el( SelectControl, {
						label: __( 'Select a Custom Sticky Site Header' ),
						value: context.props.meta.cozystay_single_post_custom_sticky_site_header,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_custom_sticky_site_header: value } );
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
						checked: ! ! context.props.meta.cozystay_single_post_hide_page_title,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_hide_page_title: ( value ? 'on' : '' ) } );
						}
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
						checked: ! ! context.props.meta.cozystay_single_post_site_footer_hide_main,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_site_footer_hide_main: ( value ? 'on' : '' ) } );
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
						checked: ! ! context.props.meta.cozystay_single_post_site_footer_hide_above,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_site_footer_hide_above: value ? 'on' : '' } );
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
						checked: ! ! context.props.meta.cozystay_single_post_site_footer_hide_instagram,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_site_footer_hide_instagram: ( value ? 'on' : '' ) } );
						}
					} ),
					el( ToggleControl, {
						label: i18n.__( 'Hide Footer Bottom' ),
						checked: ! ! context.props.meta.cozystay_single_post_site_footer_hide_bottom,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_site_footer_hide_bottom: value ? 'on' : '' } );
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
			},
			postTemplate: () => {
				return el( PanelBody, {
						title: __( 'Post Template' ),
						initialOpen: false
					},
					el( SelectControl, {
						label: __( 'Post Template' ),
						value: context.props.meta.cozystay_single_post_template,
						onChange: ( value ) => {
							context.onSaveMeta( { cozystay_single_post_template: value } );
						},
						options: [
							{ value: '', label: __( 'Default ( inherit from Customize > Blog > Single Post > Default Sidebar Layout )' ) },
							{ value: 'with-sidebar-right', label: __( 'Right Sidebar' ) },
							{ value: 'with-sidebar-left', label: __( 'Left Sidebar' ) },
							{ value: 'fullwidth', label: __( 'No Sidebar' ) }
						]
					} )
				);
			},
		} );
		return newMetas;
	}

	function withSelectReturn( value, select ) {
		const { getEditedPostAttribute } = select( 'core/editor' );
		const { getMedia } = select( 'core' );
		const secondaryFeaturedImageID = getEditedPostAttribute( 'meta' ).cozystay_single_post_secondary_featured_image || false;
		value.secondaryFeaturedImage = secondaryFeaturedImageID ? getMedia( secondaryFeaturedImageID ) : null;
		return value;
	}

	addFilter( 'loftocean.post.metas.filter', 'loftocean/post-metas', addPostMetas );

	addFilter( 'loftocean.post.withSelectReturn.filter', 'loftocean/post-metas', withSelectReturn );
} )(
	window.wp.blockEditor || window.wp.editor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
	window.wp.hooks,
	jQuery
);
