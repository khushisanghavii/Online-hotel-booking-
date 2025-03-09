( function( $ ) {
	"use strict";

    var url = cozystayThemeTools.apiRoot + 'loftocean/v1/clear-gutenberg-conflicts/';

	$( 'body' ).on( 'click', '#tab-tools .theme-tools .clear-gutenberg-issue', function( e ) {
        e.preventDefault();
        var $btn = $( this ), $notification = $btn.siblings( '.notification' );
        $btn.attr( 'disabled', 'disabled' );
        $notification.text( cozystayThemeTools.sendingRequest );
        $.get( url )
            .done( function( data, text, status ) { ( 200 == status.status ) && ( data && data.code && ( 200 == data.code ) ) ? $notification.text( cozystayThemeTools.done ) : $notification.text( cozystayThemeTools.failed ); } )
            .fail( function() { $notification.text( cozystayThemeTools.failed ); } )
            .always( function() { $btn.removeAttr( 'disabled' ); } );
    } );
} ) ( jQuery );
