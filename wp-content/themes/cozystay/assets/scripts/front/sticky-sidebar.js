( function( $ ) {
	"use strict";
	var $win = $( window ), $body = $( 'body' ), $doc = $( document ), $page = $( '#page' ), $siteHeader = $( '.site-header' ), cozystayStickySidebar = { };

	cozystayStickySidebar = {
		'isSticky': 			false,
		'primary': 				false,
		'secondary': 			false,
		'container': 			false,
		'mainContent': 		 	false,
		'upThreshholdSet': 		false,
		'downThresholdSet': 	false,
		'sidebarMarginTop': 	'',
		'sidebarMarginBotton':  '',
		'sidebarHeight': 		'',
		'primaryHeight': 		'',
		'delta': 				'',
		'previousAction': 		'',
		'thresholdMax': 		'',
		'thresholdMin': 		'',
		'init': function() {
			var self 				= this;
			self.primary 			= $( '#primary' );
			self.secondary 			= $( '#secondary' );
			self.container 			= $( '#secondary .sidebar-container' );
			self.mainContent 		= $( '#content' );

			if ( self.container.length && self.container.find( '.widget' ).length && self.primary.length ) {
				if ( self.secondary.attr( 'data-enable-sticky-sidebar' ) === 'on' ) {
					$( 'ins.adsbygoogle' ).length ? $body.fixGoogleAdsenseInlineStyles() : '';

					$win.one( 'load', function() {
						self.resize();
						$( '.main img, .main iframe' ).each( function() {
							self.observeElementLoaded( this );
						} );
						self.observeDOMChanges( $( '.main' ) );
					} );
					$doc.on( 'scrolling.cozystay.window', function( e, args ) {
						self.recalculate( args['top'], self.getStickySidebarOffset(), args['goUp'] );
					} ).on( 'resize.cozystay.window loftocean.facebook.rendered ajaxSuccess', function() {
						self.resize();
						self.runFixSidebar();
					} )
					.on( 'changed.cozystay.mainContent', function() {
						self.resize();
					} );
				}
			}
		},
		'observeElementLoaded': function( target ) {
			if ( $( target ).length ) {
				var self = this;
				$( target ).one( 'load', function() {
					self.resize();
				} );
			}
		},
		'observeDOMChanges': function( target ) {
			if ( $( target ).length ) {
				var self = this,
					targetNode = $( target ).get( 0 ), // Select the node that will be observed for mutations
					config = { attributes: false, childList: true, subtree: true }, // Options for the observer (which mutations to observe)
					callback = function( mutationsList, observer ) { // Callback function to execute when mutations are observed
						//for ( let mutation of mutationsList ) {
						mutationsList.forEach( function( mutation ) {
							if ( mutation.type === 'childList' ) {
								var $targets = ['IMG', 'IFRAME'].includes( mutation.target.tagName ) ? $( mutation.target ) : $( mutation.target ).find( 'img, iframe' );
								if ( $targets.length ) {
									$targets.each( function() {
										self.observeElementLoaded( this );
									} );
								}
							}
						} );
					},
					observer = new MutationObserver( callback );

				// Start observing the target node for configured mutations
				observer.observe( targetNode, config );
			}
		},
		'resize': function() {
			this.sidebarMarginTop = parseFloat( this.secondary.css( 'margin-top' ) );
			this.sidebarPaddingBottom = parseFloat( this.secondary.css( 'padding-bottom' ) );
			this.primaryHeight = this.primary.height();
			this.sidebarHeight = this.secondary.hasClass( 'sidebar-sticky' )
				? this.container.outerHeight( true, true ) + this.sidebarPaddingBottom
					: ( this.secondary.outerHeight( true, true ) - this.sidebarMarginTop );
			this.delta = this.primaryHeight - this.sidebarHeight;
			this.isSticky = this.testSticky();
		},
		'testSticky': function() {
			return ( this.primary.outerWidth( true, true ) < this.primary.parent().width() ) // Main sidebar is not below main content
				&& ( this.delta > this.sidebarMarginTop ); // Sidebar is shorter than main content
		},
		'getStickySidebarOffset': function() {
			return $siteHeader.length && $siteHeader.data( 'sticky-threhhold' ) && ( 'always-enable' == $siteHeader.data( 'sticky-status' ) ) ? $siteHeader.data( 'sticky-threhhold' ) : cozystayParseInt( $page.offset().top + 50 );
		},
		'runFixSidebar': function() {
			if ( this.isSticky && ( 'fixed' != this.container.css( 'position' ) ) ) {
				if ( 'static' == this.container.css( 'position' ) ) {
					this.recalculate( $win.scrollTop(), this.getStickySidebarOffset(), false );
				} else {
					var self = this, runAnimation = false, top = '', fixedPositionTop = '', fixPositionTop = '', fixPositionBottom = '',
						scrollTop = $win.scrollTop(), primaryOffsetTop = self.primary.offset().top,
						sidebarHeight = parseFloat( self.sidebarHeight ), primaryHeight = parseFloat( self.primaryHeight );

					if ( self.sidebarHeight > cozystayInnerHeight ) {
						if ( ! this.downThresholdSet ) {
							fixedPositionTop = primaryOffsetTop - cozystayInnerHeight;
							fixPositionTop 	= fixedPositionTop + sidebarHeight;
							fixPositionBottom 	= fixedPositionTop + primaryHeight;

							if ( ( scrollTop >= fixPositionTop ) && ( scrollTop <= fixPositionBottom ) ) {
								top = scrollTop - self.sidebarHeight + cozystayInnerHeight - primaryOffsetTop;
								runAnimation = true;
							} else if ( scrollTop > fixPositionBottom ) {
								top = self.delta;
								runAnimation = true;
							}
						}
					} else {
						var delta = parseFloat( self.delta ), sidebarMarginTop = parseFloat( self.sidebarMarginTop );
						fixedPositionTop 	= primaryOffsetTop - self.getStickySidebarOffset(),
						fixPositionBottom 	= fixedPositionTop + delta,
						fixPositionTop 	= fixedPositionTop + sidebarMarginTop;

						if ( scrollTop > fixPositionBottom ) {
							top = delta;
							runAnimation = true;
						} else if ( ( scrollTop >= fixPositionTop ) && ( scrollTop <= fixPositionBottom ) ) {
							top = scrollTop - primaryOffsetTop + sidebarMarginTop;
							runAnimation = true;
						}
					}
					if ( runAnimation ) {
						self.container.animate( { 'top': top }, 170, function() {
							self.recalculate( scrollTop, self.getStickySidebarOffset(), false );
						} );
					}
				}
			} else if ( ! this.isSticky ) {
				this.secondary.removeClass( 'sidebar-sticky' );
				this.container.css( { 'position': '', 'top': '' } );
			}
		},
		'recalculate': function( top, offset, goUp ) {
			var $primary = this.primary, $secondary = this.secondary, $container = this.container, clear = true;
			if ( this.isSticky ) {
				var sidebarMarginTop = parseFloat( this.sidebarMarginTop ), sidebarHeight = parseFloat( this.sidebarHeight ),
					primaryHeight = parseFloat( this.primaryHeight ), primaryOffsetTop = $primary.offset().top,
					containerOffsetTop = $container.offset().top, delta = parseFloat( this.delta );
				$secondary.addClass( 'sidebar-sticky' );
				if ( goUp || ( cozystayInnerHeight >= sidebarHeight ) ) {
					var fixedPositionTop = primaryOffsetTop - offset, fixPositionBottom = fixedPositionTop + delta,
						fixPositionTop = fixedPositionTop + sidebarMarginTop;

					this.downThresholdSet = false;
					if ( 'down' == this.previousAction ) {
						if ( 'fixed' == $container.css( 'position' ) ) {
							$container.css( { 'position': 'relative', 'top': ( top - primaryOffsetTop - sidebarHeight + cozystayInnerHeight ) } );
						}
						this.thresholdMin = $container.offset().top - offset;
						this.upThreshholdSet = true;
						clear = false;
					} else if( this.upThreshholdSet && ( top > this.thresholdMin ) ) {
						clear = false;
					} else {
						this.upThreshholdSet = false;
						if ( top > fixPositionBottom ) {
							$container.css( { 'position': 'relative', 'top': ( delta + 'px' ) } );
							clear = false;
						} else if ( ( top >= fixPositionTop ) && ( top <= fixPositionBottom ) ) {
							$container.css( { 'position': 'fixed', 'top': ( offset + 'px' ) } );
						 	clear = false;
						}
					}
					this.previousAction = 'up';
				} else {
					var fixedPositionTop 	= primaryOffsetTop - cozystayInnerHeight,
						fixPositionTop 	= fixedPositionTop + sidebarHeight,
						fixPositionBottom 	= fixedPositionTop + primaryHeight;
					this.upThreshholdSet = false;
					if ( 'up' == this.previousAction ) {
						if ( 'fixed' == $container.css( 'position' ) ) {
							$container.css( { 'position': 'relative', 'top': ( containerOffsetTop - primaryOffsetTop ) } );
						}
						this.thresholdMax = parseFloat( containerOffsetTop ) + parseFloat( sidebarHeight ) - cozystayInnerHeight;
						this.downThresholdSet = true;
						clear = false;
					} else if ( this.downThresholdSet && ( top < this.thresholdMax ) ) {
						clear = false;
					} else {
						this.downThresholdSet = false;
						if ( ( top >= fixPositionTop ) && ( top <= fixPositionBottom ) ) {
							$container.css( { 'position': 'fixed', 'top': ( cozystayInnerHeight - sidebarHeight ) + 'px' } );
							clear = false;
						} else if ( top > fixPositionBottom ) {
							$container.css( { 'position': 'relative', 'top': ( delta + 'px' ) } );
							clear = false;
						}
					}
					this.previousAction = 'down';
				}
			} else {
				$secondary.removeClass( 'sidebar-sticky' );
				this.previousAction = '';
			}
			if ( clear ) {
				$container.css( { 'position': '', 'top': '' } );
			}
		}
	};

	$doc.on( 'cozystay.init', function() {
		cozystayStickySidebar.init();
	} );
} ) ( jQuery );
