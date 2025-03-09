( function( $ ) {
	"use strict";
    function cozystayCheckPopupBox() {
        if ( $( '.cs-popup.cs-popup-box.cs-site-popup' ).length ) {
            var currentTime = ( new Date() ).getTime();
            if ( cozystayPopupBox.oncePerSession ) {
				var visited = ( 'on' == cozystayLocalStorage.getItem( 'cozystayPopupBoxVisited' ) ), autoPopupMaxDuration = 600000,
					lastVisitTime = cozystayLocalStorage.getItem( 'cozystayPopupBoxLastVisitTimestamp' ) || 1;

				cozystayLocalStorage.setItem( 'cozystayPopupBoxLastVisitTimestamp', currentTime );
                if ( visited && ( ( currentTime - lastVisitTime ) < autoPopupMaxDuration ) ) {
                    return false;
                } else {
					cozystayLocalStorage.setItem( 'cozystayPopupBoxVisited', 'on' );
                }
            }
            cozystayShowPopupBox();
        }
    }
    function cozystayShowPopupBox() {
        var timer = false, $popupForm = $( '.cs-popup.cs-popup-box.cs-site-popup' );
        if ( cozystayPopupBox.timer && ( timer = cozystayParseInt( cozystayPopupBox.timer ) ) ) {
            setTimeout( function() { $popupForm.addClass( 'show' ); }, timer * 1000 )
        } else {
            $popupForm.addClass( 'show' );
        }
    }

    $( document ).on( 'cozystay.init', function() {
        var $popupBox = $( '.cs-popup.cs-popup-box.cs-site-popup' ), hasPopupBox = $popupBox.length;
        if ( hasPopupBox && cozystayPopupBox ) {
            cozystayCheckPopupBox();
            $( 'body' ).on( 'click', '.cs-popup.cs-popup-box.cs-site-popup.show .close-button', function( e ) {
    			e.preventDefault();
    			$popupBox.removeClass( 'show' );
    		} )
			.on( 'click', function( e ) {
				if ( $popupBox.hasClass( 'show' ) ) {
					var $target = $( e.target );
					if ( $target.parents( '.cs-popup.cs-popup-box.cs-site-popup' ).length ) {
						$target.hasClass( 'container' ) || $target.parents( '.container' ).length ? '' : $popupBox.removeClass( 'show' );
					} else {
						$popupBox.removeClass( 'show' );
					}
				}
			} );
        }
    } );
} ) ( jQuery );
