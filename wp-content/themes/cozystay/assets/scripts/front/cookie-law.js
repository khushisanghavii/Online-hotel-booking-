( function( $ ) {
	"use strict";
    var $cookieLaw = $( '.cs-cookies-popup' );
    if ( $cookieLaw.length && cozystayCookieLaw && cozystayCookieLaw.version ) {
        var sessionID = 'cozystayCookieLaw' + cozystayCookieLaw.version, visited = cozystayLocalStorage.getItem( sessionID ),
            sessionStamp = cozystayLocalStorage.getItem( sessionID + 'Stamp' ), now = ( new Date() ).getTime(), duration = 2592000000;
        if ( visited && sessionStamp && ( 'on' == visited ) && ( now - sessionStamp < duration ) ) {
            cozystayLocalStorage.setItem( sessionID + 'Stamp', now );
            return;
        } else {
            var $win = $( window ), $doc = $( document ), cbFun = function ( top ) {
    			top > 150 ? $cookieLaw.removeClass( 'show' ) : '';
    		};
            $cookieLaw.addClass( 'show' );
            if ( ! cozystayCookieLaw.isPreview ) {
                cbFun( cozystayParseInt( $win.scrollTop() ) );
    			$doc.on( 'scrolling.cozystay.window resize.cozystay.window', function( e, args ) {
    				cbFun( args['top'] );
    			} );
            }

            $cookieLaw.on( 'click', '.cookies-buttons > a', function( e ) {
                e.preventDefault();
                $cookieLaw.removeClass( 'show' );
                cozystayLocalStorage.setItem( sessionID, 'on' );
                cozystayLocalStorage.setItem( sessionID + 'Stamp', ( new Date() ).getTime() );
            } );
        }
    }
} ) ( jQuery );
