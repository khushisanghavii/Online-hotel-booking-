( function( $ ) {
	"use strict";
	var $win = $( window ), $doc = $( document ), $siteHeader = $( '#masthead.site-header' ), $content = $( '#content' ), $body = $( 'body' ),
		$adminBar = $( '#wpadminbar' ), $siteHeaderMain = $siteHeader.find( '.site-header-main' ), cozystayStickySiteHeader = { }, $page = $( '#page' ),
        siteHeaderStickyStatus = $siteHeader.attr( 'data-sticky-status' ), $stickySiteHeader = $( '#sticky-site-header' ), hasCustomStickyHeader = false,
		isDefaultOverlappedSiteHeader = $siteHeader.hasClass( 'site-header-layout-default' ) && $siteHeader.hasClass( 'overlap-header' ),
		hasStickySiteEnabled = $stickySiteHeader.length || $siteHeaderMain.length;

	$siteHeaderMain = $siteHeaderMain.length ? $siteHeaderMain : $siteHeader;
	if ( $stickySiteHeader.length ) {
		hasCustomStickyHeader = true;
	} else {
		$stickySiteHeader = $siteHeader;
	}

	cozystayStickySiteHeader = {
		'init': function() {
            if ( ( ! $siteHeader.length ) || ( ! $content.length ) || ( ! hasStickySiteEnabled ) ) return;

            var site = this;
			site.maybeStickySiteHeader( cozystayParseInt( $win.scrollTop() ), false );

            $win.one( 'load', function() {
				site.maybeStickySiteHeader( cozystayParseInt( $win.scrollTop() ), false );

				$doc.on( 'scrolling.cozystay.window', function( e, args ) {
					args ? site.maybeStickySiteHeader( cozystayParseInt( args['top'] ), args['goUp'] ) : '';
				} )
				.on( 'resize.cozystay.window', function( e, args ) {
					if ( args ) {
						$siteHeader.removeAttr( 'data-sticky-threhhold' );
						site.maybeStickySiteHeader( cozystayParseInt( args['top'] ), args['goUp'] );
					}
				} );
			} );
		},
		'getStickyThreshold': function() {
			if ( ! $siteHeader.hasClass( 'hide' ) ) {
				if ( $siteHeader.data( 'sticky-threhhold' ) ) {
					return $siteHeader.data( 'sticky-threhhold' );
				} else {
					var offset = parseInt( $siteHeader.outerHeight( true, true ), 10 ) + cozystayParseInt( $page.offset().top );
					$siteHeader.data( 'sticky-threhhold', offset );
					return offset;
				}
			}
			return 0;
		},
		'maybeStickySiteHeader': function( top, goUp ) {
			if ( 'disable' != siteHeaderStickyStatus ) {
				var threshold = this.getStickyThreshold(), paddingTop = $siteHeaderMain.outerHeight( true, true );

				if ( 'always-enable' == siteHeaderStickyStatus ) {
					if ( ! $stickySiteHeader.hasClass( 'sticky' ) && ( top >= threshold ) ) {
						$stickySiteHeader.addClass( 'sticky' );
						if ( hasCustomStickyHeader ) {
							paddingTop = '';
							$stickySiteHeader.removeClass( 'hide' );
						} else if ( isDefaultOverlappedSiteHeader ) {
							paddingTop = '';
						}
						$content.css( 'padding-top', paddingTop );
					} else if ( $stickySiteHeader.hasClass( 'sticky' ) && ( top < threshold ) ) {
						$stickySiteHeader.removeClass( 'sticky' );
						if ( hasCustomStickyHeader ) {
							$stickySiteHeader.addClass( 'hide' );
						}
						$content.css( 'padding-top', '' );
					}
				} else {
					 if ( ! $stickySiteHeader.hasClass( 'is-sticky' ) && ( top >= threshold ) ) {
						$stickySiteHeader.addClass( 'is-sticky' );
						if ( hasCustomStickyHeader ) {
							paddingTop = '';
							$stickySiteHeader.removeClass( 'hide' );
						} else if ( isDefaultOverlappedSiteHeader ) {
							paddingTop = '';
						}
						$content.css( 'padding-top', paddingTop );
					} else if ( ! goUp && $stickySiteHeader.hasClass( 'is-sticky' ) ) {
						$stickySiteHeader.removeClass( 'show-header' ).addClass( 'hide-header' );
					} else if ( goUp && $stickySiteHeader.hasClass( 'is-sticky' ) && ( top >= threshold ) ) {
						$stickySiteHeader.removeClass( 'hide-header' ).addClass( 'show-header' );
					} else if ( $stickySiteHeader.hasClass( 'is-sticky' ) && ( top < threshold ) ) {
						$stickySiteHeader.removeClass( 'is-sticky show-header hide-header' );
						if ( hasCustomStickyHeader ) {
							paddingTop = '';
							$stickySiteHeader.addClass( 'hide' );
						}
						$content.css( 'padding-top', '' );
					}
				}
			}
		},
	};

	$doc.on( 'cozystay.init', function() {
		cozystayStickySiteHeader.init();
	} );
} ) ( jQuery );
