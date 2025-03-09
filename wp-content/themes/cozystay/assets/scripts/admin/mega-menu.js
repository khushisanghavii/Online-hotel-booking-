( function( $ ) {
    "use strict";

    $( document ).on( 'ready', function() {
        var $menuEditor = $( '#menu-to-edit' );
        if ( $menuEditor.length ) {
            $menuEditor.on( 'change', '.cozystay-mega-menu-settings .cs-enable-mega-menu input', function( e ) {
                e.preventDefault();
                var $settings = $( this ).closest( '.cozystay-mega-menu-settings' ).find( '.mega-menu-settings' );
                if ( $( this ).is( ':checked' ) ) {
                    $settings.show();
                    $settings.filter( '.cozystay-mega-menu-drop-down-size' ).find( 'select' ).trigger( 'change' );
                } else {
                    $settings.hide();
                }
            } )
            .on( 'change', '.cozystay-mega-menu-settings .cozystay-mega-menu-drop-down-size select', function( e ) {
                e.preventDefault();
                var $customWidth = $( this ).closest( '.cozystay-mega-menu-settings' ).find( '.cs-dropdown-custom-width' );
                'custom-width' == $( this ).val() ? $customWidth.show() : $customWidth.hide();
            } )
            .on( 'click', '.menu-item .item-edit', function( e ) {
                var $item = $( this ).closest( '.menu-item' );
                if ( $item.hasClass( 'menu-item-edit-inactive' ) ) {
                    var $megaMenu = $item.find( '.cozystay-mega-menu-settings' ),
                        $settings = $megaMenu.find( '.mega-menu-settings' );
                    if ( $megaMenu.length ) {
                        if ( $megaMenu.find( '.cs-enable-mega-menu input' ).is( ':checked' ) ) {
                            $settings.show();
                            var $customWidth = $settings.filter( '.cs-dropdown-custom-width' );
                            ( 'fullwidth' == $megaMenu.find( '.cozystay-mega-menu-drop-down-size select' ).val() ) ? $customWidth.hide() : $customWidth.show();
                        } else {
                            $settings.hide();
                        }
                    }
                }
            } );
        }
    } );

} ) ( jQuery );
