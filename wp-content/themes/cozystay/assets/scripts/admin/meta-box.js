( function( $ ) {
    "use strict";

    var $pageMetaBox = $( '#loftocean-page-meta-box' );
    if ( $pageMetaBox.length ) {
        var $colors = $pageMetaBox.find( 'input.color-picker' ), $images = $pageMetaBox.find( '.background-image' ),
            $backgroundSettings = $pageMetaBox.find( '.background-settings' ), mediaLibrary = '';

        mediaLibrary = {
            input: '',
            frame: '',
            frames: {},
            mediaFrame: function() {
                if ( ! this.frames[ this.frame ] ) {
                    this.frames[ this.frame ] = wp.media( {
                        id: 'cs-meta-box-media-uploader',
                        editing: true,
                        library: {
                            type : 'image' == this.frame ? 'image' : ['image', 'video']
                        },
                        multiple: false  // Set this to true to allow multiple files to be selected
                    } )
                    .on( 'select', function() {
                        var media = mediaLibrary.frames[ mediaLibrary.frame ].state().get( 'selection' ).first().toJSON();
                        mediaLibrary.input.trigger( 'cozystay.metabox.media.changed', media );
                        mediaLibrary.input = ''; // reset input
                    } )
                    .on( 'open', function() {
                        var selection = mediaLibrary.frames[ mediaLibrary.frame ].state().get( 'selection' ),
                            imageID  = mediaLibrary.input.val();
                        selection.reset();
                        if ( imageID && ( '' !== imageID ) ) {
                            var attachment = wp.media.attachment( imageID );
                            attachment.fetch();
                            selection.add( attachment ? [ attachment ] : [] );
                        }
                    } );
                }
                return this.frames[ this.frame ];
            },
            open: function( $input, frame ) {
                this.input = $input.first();
                this.frame = frame || 'image';
                this.mediaFrame().open();
            }
        };
        if ( $colors.length ) {
            $colors.each( function() {
				var $colorPicker = $( this );
				$colorPicker.wpColorPicker( {
					change: function( event, ui ) {
						var color = ui.color ? ui.color.toString() : '';
						$colorPicker.val( color );
					},
					clear: function() {
						$colorPicker.val( '' );
					}
				} );
            } );
        }
        if ( $images.length ) {
            $images.on( 'click', '.button.upload-button, .placeholder', function( e ) {
				e.preventDefault();
				mediaLibrary.open( $( this ).parent().siblings( 'input.image-id[type=hidden]' ), 'image' );
			} )
			.on( 'click', '.button.remove-button', function( e ) {
				e.preventDefault();
				var $this = $( this ), $wrap = $this.parent(), $placeholder = $wrap.siblings( '.placeholder' ), $preview = $wrap.siblings( '.thumbnail-image' );
				$this.hide();
                $preview.hide();
				$placeholder.show();
				$wrap.siblings( 'input[type=hidden]' ).val( '' );
                $backgroundSettings.hide();
			} )
			.on( 'cozystay.metabox.media.changed', 'input.image-id[type=hidden]', function( e, media ) {
				e.preventDefault();
				if ( media ) {
					var $input = $( this ), $preview = $input.siblings( '.thumbnail-image' ), $buttons = $input.siblings( '.actions' ) ;
					$preview.find( 'img' ).attr( 'src', media.url );
                    $preview.show();
                    $input.siblings( '.placeholder' ).hide();
					$buttons.find( '.remove-button' ).show();
					$input.val( media.id ).siblings( 'input.image-url' ).val( media.url );
                    $backgroundSettings.show();
				}
			} );
        }
        if ( $( 'input[name=_thumbnail_id]' ) ) {
            $( document ).on( 'ajaxSuccess', function() {
                if ( arguments[3] && arguments[3].data && ( typeof arguments[3].data === 'string' || arguments[3].data instanceof String ) ) {
                    var $fragment = $( '<div>' ).append( $( arguments[3].data ) );
                    if ( $fragment && $fragment.find( 'input[name=_thumbnail_id]' ).length ) {
                        var thumbID = parseInt( $fragment.find( 'input[name=_thumbnail_id]' ).val() || 0, 10 );
                        ( thumbID > 0 ) ? $backgroundSettings.show() : $backgroundSettings.hide();
                    }
                }
            } );
        }

        var $wrapper = $( '#loftocean-page-meta-box' ), $siteHeaderSource = $wrapper.find( '.cs-single-header-source-wrapper' ),
            $customSiteHeaders = $wrapper.find( '.cs-single-custom-site-headers-wrapper' );
        $wrapper.on( 'change', '#cozystay_single_page_hide_site_header', function( e ) {
            if ( $( this ).is( ':checked' ) ) {
                $siteHeaderSource.hide();
                $customSiteHeaders.hide();
            } else {
                $siteHeaderSource.show();
                'custom' == $wrapper.find( 'select[name=cozystay_single_page_site_header_source]' ).val()
                    ? $customSiteHeaders.show() : $customSiteHeaders.hide();
            }
        } ).on( 'change', 'select[name=cozystay_single_page_site_header_source]', function( e ) {
            'custom' == $( this ).val() ? $customSiteHeaders.show() : $customSiteHeaders.hide();
        } );
    }

    if ( $( '#loftocean-post-meta-box' ).length ) {
        var $wrapper = $( '#loftocean-post-meta-box' ), $siteHeaderSource = $wrapper.find( '.cs-single-header-source-wrapper' ),
            $customSiteHeaders = $wrapper.find( '.cs-single-custom-site-headers-wrapper' );
        $wrapper.on( 'change', '#cozystay_single_post_hide_site_header', function( e ) {
            if ( $( this ).is( ':checked' ) ) {
                $siteHeaderSource.hide();
                $customSiteHeaders.hide();
            } else {
                $siteHeaderSource.show();
                'custom' == $wrapper.find( 'select[name=cozystay_single_post_site_header_source]' ).val()
                    ? $customSiteHeaders.show() : $customSiteHeaders.hide();
            }
        } ).on( 'change', 'select[name=cozystay_single_post_site_header_source]', function( e ) {
            'custom' == $( this ).val() ? $customSiteHeaders.show() : $customSiteHeaders.hide();
        } );
    }

    var $customMetas = $( '#loftocean-post-meta-box, #loftocean-page-meta-box' );
    if ( ! $customMetas.length ) return;

    if ( $( '.cs-single-mobile-menu-source-wrapper' ).length ) {
        var $mobileMenu = $( '.cs-single-custom-mobile-menu-wrapper' ),
            $mobileMenuWidth = $( 'select[name=cozystay_single_custom_mobile_menu_width]' ),
            $mobileMenuCustomWidth = $( '.cs-single-custom-mobile-menu-custom-width-wrapper' );
        $( '.cs-single-mobile-menu-source-wrapper [name=cozystay_single_custom_mobile_menu_source]' ).on( 'change', function( e ) {
            var widthValue = $mobileMenuWidth.val();
            if ( 'custom' == $( this ).val() ) {
                $mobileMenu.show();
                'custom-width' == widthValue ? $mobileMenuCustomWidth.show() : $mobileMenuCustomWidth.hide();
            } else {
                $mobileMenu.hide();
                $mobileMenuCustomWidth.hide();
            }
        } );
        $mobileMenuWidth.on( 'change', function( e ) {
            'custom-width' == $( this ).val() ? $mobileMenuCustomWidth.show() : $mobileMenuCustomWidth.hide();
        } );
    }
    if ( $( '.cs-single-site-footer-main-source-wrapper' ) ) {
        var $siteFooterMain = $( '.cs-single-custom-footer-main-wrapper' ),
            $siteFooterMainSource = $customMetas.find( '.cs-single-site-footer-main-source-wrapper' );
        $customMetas.on( 'change', '#cozystay_single_page_site_footer_hide_main, #cozystay_single_post_site_footer_hide_main', function( e ) {
            if ( $( this ).is( ':checked' ) ) {
                $siteFooterMain.hide();
                $siteFooterMainSource.hide();
            } else {
                $siteFooterMainSource.show();
                'custom' == $siteFooterMainSource.find( 'select[name=cozystay_single_custom_site_footer_main_source]' ).val()
                    ? $siteFooterMain.show() : $siteFooterMain.hide();
            }
        } ).on( 'change', 'select[name=cozystay_single_custom_site_footer_main_source]', function( e ) {
            'custom' == $( this ).val() ? $siteFooterMain.show() : $siteFooterMain.hide();
        } );
    }
    if ( $( '.cs-single-site-footer-above-source-wrapper' ) ) {
        var $siteFooterAbove = $( '.cs-single-custom-footer-above-wrapper' ),
            $siteFooterAboveSource = $customMetas.find( '.cs-single-site-footer-above-source-wrapper' );
        $customMetas.on( 'change', '#cozystay_single_page_site_footer_hide_above, #cozystay_single_post_site_footer_hide_above', function( e ) {
            if ( $( this ).is( ':checked' ) ) {
                $siteFooterAbove.hide();
                $siteFooterAboveSource.hide();
            } else {
                $siteFooterAboveSource.show();
                'custom' == $siteFooterAboveSource.find( 'select[name=cozystay_single_custom_site_footer_above_source]' ).val()
                    ? $siteFooterAbove.show() : $siteFooterAbove.hide();
            }
        } ).on( 'change', 'select[name=cozystay_single_custom_site_footer_above_source]', function( e ) {
            'custom' == $( this ).val() ? $siteFooterAbove.show() : $siteFooterAbove.hide();
        } );
    }
} ) ( jQuery );
