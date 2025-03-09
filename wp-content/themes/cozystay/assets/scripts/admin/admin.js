( function( $ ) {
    "use strict";
    var $tabs = $( '#cs-dashboard-tabs-wrapper > a' ), $contents = $( '#cs-dashboard-form > .cs-dashboard-form-page' ), $demoTab = $( '#tab-demo.cs-dashboard-form-page' ),
        $hiddenActivaTab = $( 'input[name=cozystay_theme_settings_active_tab]' ), $openHours = $( '#tab-info.cs-dashboard-form-page .opening-hours-form tbody' ),
        $submenus = $( '#toplevel_page_cs-theme .wp-submenu > li' ).not( '.wp-submenu-head' ), $submit = $( '.cs-submit-button' ), $body = $( 'body' ),
        $customFonts = $( '.cs-custom-fonts-wrapper' ), $adobeID = $( '.cs-adobe-typekit-id' ), $adobeSyncBtn = $( '.cs-sync-adobe-fonts' );
    if ( $tabs.length && $contents.length ) {
        $tabs.on( 'click', function( e ) {
            e.preventDefault();
            var $tab = $( this ), targetID = $tab.attr( 'href' ), index = $tabs.index( $tab );
            if ( ! $tab.hasClass( 'nav-tab-active' ) && targetID && $contents.filter( targetID ).length ) {
                $submenus.removeClass( 'current' ).eq( index ).addClass( 'current' );
                [ '#tab-support', '#tab-demo', '#tab-tools', '#tab-integrations' ].includes( targetID ) ? $submit.hide() : $submit.show();

                $tabs.removeClass( 'nav-tab-active' );
                $tab.addClass( 'nav-tab-active' );
                $contents.removeClass( 'tab-active' );
                $contents.filter( targetID ).addClass( 'tab-active' );
                $hiddenActivaTab.val( targetID.replace( '#', '' ) );

            }
            return false;
        } );
    }
    if ( $openHours.length ) {
        $openHours.on( 'click', '.add', function( e ) {
            e.preventDefault();
            var $tr = $( this ).closest( 'tr' ).clone( true, true );
            $tr.find( 'input[type=text]' ).val( '' );
            $openHours.append( $tr );
            $openHours.find( 'tr' ).each( function( index, el ) {
                $( el ).find( 'input[type=text]' ).each( function() {
                    var $input = $( this );
                    $input.attr( 'name', $input.attr( 'name' ).replace( /\d/, index ) );
                    $input.attr( 'id', $input.attr( 'id' ).replace( /\d/, index ) );
                } );
            } );
        } ).on( 'click', '.remove', function( e ) {
            e.preventDefault();
            var $tr = $( this ).closest( 'tr' );
            if ( $tr.siblings().length ) {
                $tr.remove();
            } else {
                $tr.find( 'input[type=text]' ).val( '' );
            }
        } );
    }
    if ( $demoTab.length ) {
        $demoTab.on( 'click', '.cs-demo-box .button-install-open-modal', function( e ) {
            e.preventDefault();
            var $popup = $( this ).closest( '.cs-demo-box' ).children( '.cs-demo-popup-wrap' );
            $popup.length ? $popup.css( 'display', '' ) : '';
            return false;
        } )
        .on( 'click', '.cs-demo-popup-wrap .cs-demo-popup-corner-close', function( e ) {
            e.preventDefault();
            $( this ).closest( '.cs-demo-popup-wrap' ).css( 'display', 'none' );
            return false;
        } );
    }
    if ( $adobeID.length ) {
        var $message = $adobeID.siblings( '.message' ), message = cozystayThemeSettings.adobeFonts.i18nText;
        $adobeID.on( 'keyup', function() {
            $message.html( '' ).hide();
        } );
        $adobeSyncBtn.on( 'click', function( e ) {
            e.preventDefault();
            $message.html( '' ).hide();
            if ( $adobeID.val() ) {
    			var $self = $( this ), url = wpApiSettings.root + 'loftocean/v1/sync-adobe-fonts/' + $adobeID.val();
    			$self.text( message[ 'sending' ] ).attr( 'disabled', 'disabled' );
    			$.get( url ).done( function( response ) {
                    if ( response.status ) {
                        $message.html( response.message ).show();
                    } else {
                        $message.html( message.error ).show();
                    }
    				$self.text( message[ 'done' ] );
    			} ).fail( function() {
                    $message.html( message.error ).show();
    				$self.text( message['fail'] );
    			} ).always( function() {
                    $self.removeAttr( 'disabled' );
                } );
            } else {
                $message.html( message.empty ).show();
            }
        } );
        $( '.cs-clear-adobe-fonts' ).on( 'click', function( e ) {
            e.preventDefault();
            var $self = $( this ), url = wpApiSettings.root + 'loftocean/v1/clear-adobe-fonts/';
            $adobeID.val( '' );
            $message.html( '' ).hide();
            $self.attr( 'disabled', 'disabled' );
            $.get( url ).always( function() {
                $self.removeAttr( 'disabled' );
            } );
        } );
    }
    if ( $customFonts.length ) {
        var $addBtn = $customFonts.find( '.cs-custom-font-add' ), tmpl = wp.template( 'cs-custom-font' ),
            defaultListSettins = [ { 'name': '', 'weight': '400', 'woff': '', 'woff2': '' } ], fontMedia = {
    		input: '',
    		frame: '',
    		frames: {},
    		mediaFrame: function() {
    			if ( ! this.frame ) {
    				this.frame = wp.media( {
    					id: 'cs-font-uploader',
    					editing: true,
    					multiple: false
    				} )
    				.on( 'select', function() {
    					var media =fontMedia.frame.state().get( 'selection' ).first().toJSON();
    					fontMedia.el.trigger( 'changed.cozystay.media', media );
    					fontMedia.el = '';
    				})
    				.on( 'open', function() {
    					var selection = fontMedia.frame.state().get( 'selection' );
    					selection.reset();
    				} );
    			}
    			return this.frame;
    		},
    		open: function( el, frame ) {
    			this.el = $( el ).first();
    			this.mediaFrame().open();
    		}
    	};
        function addDefaultCustomFontItem() {
            $addBtn.before( tmpl( { 'index': $addBtn.data( 'current-index' ), 'list': defaultListSettins } ) );
        }
        $customFonts.on( 'click', '.cs-custom-font-item-remove', function( e ) {
            e.preventDefault();
            var $item = $( this ).closest( '.cs-custom-font-item' );
            if ( ! $item.siblings( '.cs-custom-font-item' ).length ) {
                addDefaultCustomFontItem();
                $addBtn.data( 'current-index', ( 1 + $addBtn.data( 'current-index' ) ) );
            }
            $item.remove();
        } ).on( 'keyup', '.cs-custom-font-name', function( e ) {
            var $title = $( this ).closest( '.cs-custom-font-item' ).find( 'h3 .item-font-name' ),
                name = $( this ).val();
            if ( $title.length ) {
                name ? $title.html( ' - ' + name ) : $title.html( '' );
            }
        } ).on( 'click', '.cs-media-uploader', function( e ) {
            e.preventDefault();
            fontMedia.open( $( this ) );
        } ).on( 'changed.cozystay.media', '.cs-media-uploader', function( e, media ) {
            e.preventDefault();
            $( this ).siblings( 'input' ).val( media.url );
        } ).on( 'click', '.cs-media-remove', function( e ) {
            e.preventDefault();
            $( this ).siblings( 'input' ).val( '' );
        } );
        $addBtn.on( 'click', function( e ) {
            e.preventDefault();
            addDefaultCustomFontItem();
            $addBtn.data( 'current-index', ( 1 + $addBtn.data( 'current-index' ) ) );
        } );

        var defaultList = cozystayThemeSettings && cozystayThemeSettings.customFonts && Array.isArray( cozystayThemeSettings.customFonts ) ? cozystayThemeSettings.customFonts : defaultListSettins;
        $addBtn.before( tmpl( { 'index': 0, 'list': defaultList } ) );
        $addBtn.data( 'current-index', defaultList.length );
    }
} ) ( jQuery );
