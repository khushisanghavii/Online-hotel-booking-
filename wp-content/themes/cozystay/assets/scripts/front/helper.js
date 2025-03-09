( function( $ ) {
	"use strict";
	var tagA = document.createElement( 'a' ), cookieRootPath = '', $doc = $( document ), $win = $( window );

	tagA.href = cozystayHelper && cozystayHelper.siteURL ? cozystayHelper.siteURL : '';
	cookieRootPath = tagA.pathname;

	window.cozystayIsRTL = $( 'body' ).hasClass( 'rtl' );
	window.cozystayParseInt = function ( val ) {
		return val ? parseInt( val, 10 ) : 0;
	}
	function cozystaySetWindowProps( e ) {
		var top = cozystayParseInt( $win.scrollTop() ), goUp = top < $win.previousTop;
		$win.previousTop = top;
		return { 'top': top, 'goUp': goUp, 'originalEvent': e };
	}
	$win.previousTop = cozystayParseInt( $win.scrollTop() );
	window.cozystayInnerHeight = cozystayParseInt( $win.innerHeight() );
	window.cozystayInnerWidth = cozystayParseInt( $win.innerWidth() );
	document.addEventListener( 'DOMContentLoaded', function() {
		$win.on( 'resize', function( e ) {
			window.cozystayInnerHeight = cozystayParseInt( $win.innerHeight() );
			window.cozystayInnerWidth = cozystayParseInt( $win.innerWidth() );
			$doc.trigger( 'resize.cozystay.window', cozystaySetWindowProps( e ) );
		} )
		.on( 'scroll', function( e ) {
			if ( Math.abs( cozystayParseInt( $win.scrollTop() ) - $win.previousTop ) > 3 ) {
				$doc.trigger( 'scrolling.cozystay.window', cozystaySetWindowProps( e ) );
			}
		} );
	} );

	$.fn.cozystayJustifiedGallery = function() {
		if ( ! $( this ).length ) return;
		return $( this ).each( function() {
			var $gallery = $( this );
			$gallery.children( '.image-gallery' ).justifiedGallery( {
				'rtl': cozystayIsRTL,
				'rowHeight': $gallery.data( 'row-height' ),
				'lastRow': $gallery.data( 'last-row' ),
				'margins': $gallery.data( 'margin' ),
				'captions': false
			} ).on( 'jg.complete', function( e ) {
				$gallery.addClass( 'justified-gallery-initialized' );
				$( document ).trigger( 'changed.cozystay.mainContent' );
			} );
		} );
	}

	$.fn.cozystaySlickSlider = function( args ) {
		if ( ! $( this ).length ) return;

		args.rtl = cozystayIsRTL;

		return $( this ).each( function() {
			$( this ).on( 'init', function( e ) {
				$( this ).find( '.hide' ).removeClass( 'hide' );
				$.fn.loftoceanImageLoading ? $( this ).loftoceanImageLoading() : '';
			} ).slick( args );
		} );
	}

	window.cozystayCookie = {
		set: function( name, value, days ) {
			try {
			   	var expires = "";
				if ( days ) {
				   var date = new Date();
				   date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
				   expires = "; expires=" + date.toGMTString();
			   }
			   document.cookie = name + "=" + value + expires + "; path=" + cookieRootPath;
		   } catch( msg ) {}
	   },
	   get: function( name ) {
		   	try {
				var nameEQ = name + "=";
				var ca = document.cookie.split( ';' );
				for ( var i = 0; i < ca.length; i++ ) {
					var c = ca[i];
					while ( c.charAt(0) == " " ) {
						c = c.substring( 1, c.length );
					}
					if ( c.indexOf( nameEQ ) == 0 ) {
						return c.substring(nameEQ.length, c.length);
					}
				}
				return null;
			} catch ( msg ) {
				return null;
			}
		},
		remove: function( name ) {
			cozystayCookie.set( name, '', -1 );
		}
	};

	window.cozystaySessionStorage = {
		getItem: function( name ) {
			try {
				return sessionStorage.getItem( name );
			} catch ( msg ) {
				return null;
			}
		},
		setItem: function( name, value ) {
			try {
				sessionStorage.setItem( name, value );
			} catch ( msg ) {}
		}
	};

	window.cozystayLocalStorage = {
		getItem: function( name ) {
			try {
				return localStorage.getItem( name );
			} catch ( msg ) {
				return null;
			}
		},
		setItem: function( name, value ) {
			try {
				localStorage.setItem( name, value );
			} catch ( msg ) {}
		}
	};

	$.fn.fixGoogleAdsenseInlineStyles = function() {
		var flex = document.getElementById( 'secondary' );
		const observer = new MutationObserver( function( mutations, observer ) {
			flex.style.height = "";
		} );
		observer.observe( flex, {
			attributes: true,
			attributeFilter: [ 'style' ]
		} );
		return this;
	}
} ) ( jQuery );
