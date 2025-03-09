( function( $ ) {
	"use strict";
	var $doc = $( document ), $win = $( window ), $body = $( 'body' ), $siteFooterBottom = $( '.site-footer .site-footer-bottom' ), $searchScreen = $( '.search-screen' ),
		isRTL = window.cozystayIsRTL, $sidemenu = $( '.sidemenu' ), $footerIcons = $( '.to-top' ), $megaMenus = $( '.cozystay-mega-menu' ), $hiddenContainer = $( '#sticky-site-header' ),
		topOffset = cozystayParseInt( $body.offset().top ), currentLocation = window.location, isCustomizePreview = ( typeof wp !== 'undefined' ) && ( typeof wp.customize !== 'undefined' );

	cozystay = cozystay || {};
	cozystay.pages = cozystay.pages || {};

	/**
	* For all pages features
	*	1. Scroll to top button
	* 	2. Sticky sidebar
	* 	3. Sticky page header
	* 	4. Fallback css generate
	*/
	cozystay.pages.all = {
		'init': function() {
			var site = this;
			site.fixPrimaryMenu();
			site.checkFixedElements( cozystayParseInt( $win.scrollTop() ) );

			if ( cozystay.onepagemenus ) {
				site.checkOnePageMenus();
				$body.removeClass( 'cozystay-enable-onepage-menu-check' );
			}

			$doc.on( 'scrolling.cozystay.window resize.cozystay.window', function( e, args ) {
				site.checkFixedElements( args['top'] );
			} );
		},
		'checkFixedElements': function( top ) {
			if ( $footerIcons.length ) {
				var footerHeight = $siteFooterBottom.length ? $siteFooterBottom.outerHeight() - 37 : 100;
				( top > 150 ) && ( top < ( $doc.height() - cozystayInnerHeight - footerHeight ) ) ? $footerIcons.addClass( 'show' ) : $footerIcons.removeClass( 'show' );
			}
		},
		'fixPrimaryMenu': function() {
			var $menus = $( 'nav > ul > li' );
			if ( $menus.length ) {
				$menus.find( '.sub-menu.hide' ).length ? $menus.find( '.sub-menu.hide' ).removeClass( 'hide' ) : '';
				if ( $menus.filter( '.cozystay-mega-menu' ).length ) {
					cozystayAdjustMegaMenu();
					$win.on( 'resize.cozystay.window', function() {
						cozystayAdjustMegaMenu();
					} );
				}
				if ( $menus.filter( '.menu-item-has-children' ).not( '.cozystay-mega-menu' ).length ) {
					var boundary = cozystayParseInt( window.cozystayInnerWidth - 10 );
					$menus.filter( '.menu-item-has-children' ).not( '.cozystay-mega-menu' ).on( 'mouseover', function( e ) {
						if ( $( this ).closest( '.mobile-menu' ).length ) return;

						if ( ! $( this ).hasClass( 'right' ) ) {
							var $self = $( this );
							$self.find( 'ul.sub-menu' ).each( function() {
								if ( isRTL && ( $( this ).offset().left < cozystayParseInt( $( '#page' ).offset().left + 10 ) ) ) {
									$self.addClass( 'right' );
									return false;
								} else if ( ! isRTL && ( cozystayParseInt( $( this ).offset().left + $( this ).outerWidth( true ) ) > boundary ) ) {
									$self.addClass( 'right' );
									return false;
								}
							} );
						}
					} );
					$win.on( 'resize.cozystay.window', function() {
						boundary = cozystayParseInt( window.cozystayInnerWidth - 10 );
						$menus.filter( '.menu-item-has-children' ).removeClass( 'right' );
					} );
				}
			}
		},
		'checkOnePageMenus': function() {
			var $currentMenuItems = $( '.menu-item.current-menu-item' );
			if ( $currentMenuItems.length > 1 ) {
				var $menuItems = $( '.menu-item' ), $elements = {}, sections = [], $wholePageItems = $(),
					$hashItems = $(), currentURL = window.location.origin + window.location.pathname;
				$menuItems.each( function() {
					var $item = $( this ), currentATag = $item.children( 'a' ).get( 0 ),
						itemURL = currentATag.origin + currentATag.pathname;
					if ( currentURL == itemURL ) {
						if ( currentATag.hash ) {
							if ( $body.find( currentATag.hash ).length ) {
								var $sec = $body.find( currentATag.hash );
								$hashItems = $hashItems.add( $item );
								if ( $elements[ currentATag.hash ] ) {
									$elements[ currentATag.hash ] = $elements[ currentATag.hash ].add( $item );
								} else {
									$elements[ currentATag.hash ] = $item;
									sections.push( { 'top': Math.max( 0, cozystayParseInt( $sec.offset().top - window.cozystayInnerHeight / 2 ) ), 'target': $sec, 'hash': currentATag.hash } );
								}
							}
						} else {
							$wholePageItems = $wholePageItems.add( $item );
						}
					}
				} );

				if ( sections.length ) {
					var currentTop = $win.scrollTop();
					sections.sort( ( a, b ) => ( a.top > b.top ) ? -1 : 1 );
					$hashItems.removeClass( 'current-menu-item' );
					$win.on( 'resize.cozystay.window', function() {
						sections.forEach( function( sec ) {
							sec.top = Math.max( 0, cozystayParseInt( $( sec.target ).offset().top - window.cozystayInnerHeight / 2 ) );
						} );
					} ).on( 'scrolling.cozystay.window onepagemenu.check', function() {
						var winTop = $( this ).scrollTop(), found = false;
						$hashItems.removeClass( 'current-menu-item' );
						sections.forEach( function( s ) {
							if ( found ) { return }
							if ( s.top < winTop ) {
								$elements[ s.hash ].addClass( 'current-menu-item' );
								found = true;
							}
						} );
						if ( $wholePageItems.length ) {
							found ? $wholePageItems.removeClass( 'current-menu-item' ) : $wholePageItems.addClass( 'current-menu-item' );
						}
					} );
					$win.trigger( 'onepagemenu.check' );
				}
			}
		}
	};
	/**
	* Adjust mega menu
	*/
	function cozystayAdjustMegaMenu() {
		var hasHiidenContainer = $hiddenContainer.length && $hiddenContainer.hasClass( 'hide' );
		hasHiidenContainer ? $hiddenContainer.css( 'visibility', 'hidden' ).removeClass( 'hide' ) : '';

		$megaMenus.each( function() {
			var $subMenu = $( this ).children( '.cozystay-dropdown-menu' );
			if ( $subMenu.length ) {
				if ( $subMenu.hasClass( 'fullwidth' ) ) {
					isRTL ? $subMenu.css( 'right', - ( window.cozystayInnerWidth - $( this ).offset().left - $( this ).outerWidth( true ) ) )
						: $subMenu.css( 'left', - $( this ).offset().left );
				} else {
					var width = cozystayParseInt( $subMenu.css( 'width' ) );
					if ( isRTL ) {
						( width - $( this ).offset().left ) > $( this ).outerWidth( true )
							? $subMenu.css( 'right', ( - cozystayParseInt( width - $( this ).offset().left - $( this ).outerWidth( true ) ) ) - 10 ) : '';
					} else {
						width > ( window.cozystayInnerWidth - $( this ).offset().left )
							? $subMenu.css( 'left', cozystayParseInt( window.cozystayInnerWidth - $( this ).offset().left - width  - 10 ) ) : '';
					}
				}
			}
		} );
		hasHiidenContainer ? $hiddenContainer.css( 'visibility', '' ).addClass( 'hide' ) : '';
	}
	/**
	* Check product quantity minus button visibility
	*/
	function cozystayCheckMinusBtn( $btn, $qty ) {
		if ( $btn.length && $qty.length ) {
			$qty.val() > 1 ? $btn.removeAttr( 'disabled' ) : $btn.attr( 'disabled', 'disabled' );
		}
	}

	$doc.on( 'cozystay.init', function() {
		cozystay.pages.all.init();
	} );

	/** Let render the page */
	document.addEventListener( 'DOMContentLoaded', function() {
		var $contentImages = $( '.comment-content img, .post-entry img' );

		$doc.trigger( 'cozystay.init' );

		// Fit videos if any
		if ( $( 'body.page #primary iframe, body.single #primary iframe' ).length ) {
			var $primary = $( 'body.page #primary, body.single #primary' ), sources = [
				'iframe[data-src*="videopress.com"]',
				'iframe[data-src*="video.wordpress.com"]',
				'iframe[data-src*="player.vimeo.com"]',
				'iframe[data-src*="youtube.com"]',
		        'iframe[data-src*="youtube-nocookie.com"]',
		        'iframe[data-src*="kickstarter.com"][src*="video.html"]',
			];
			$primary.fitVids( { 'customSelector': 'iframe[src*="video.wordpress.com"]' } );
			if ( $primary.find( sources.join( ',' ) ).length ) {
				$primary.find( sources.join( ',' ) ).each( function() {
					var $video = $( this ), width = cozystayParseInt( $video.attr( 'width' ) ), height = cozystayParseInt( $video.attr( 'height' ) );
					if ( ! $( this ).parents( '.fluid-width-video-wrapper' ).length && width && height ) {
						$video.attr( 'src', $video.data( 'src' ) )
							.wrap( $( '<div>', { class: 'fluid-width-video-wrapper' } ).css( 'padding-top', ( ( height / width ) * 100 ) + '%' ) )
				        	.removeAttr( 'height' ).removeAttr( 'width' );
					}
				} );
			}
		}

		$win.on( 'load', function() {
			if ( window.location.hash && ( '#comments' == window.location.hash ) && $( '#comments' ).length ) {
				var top = $( '#comments' ).offset().top - topOffset;
				$( 'html, body' ).animate( { scrollTop: top }, 200 );
			}
		} );

		$doc.on( 'beforeopen.popupbox.loftocean', function( e, el ) {
			$sidemenu = isCustomizePreview ? $( '.sidemenu' ) : $sidemenu;
			if ( $sidemenu.length && $sidemenu.hasClass( 'show' ) ) {
				$sidemenu.find( '.close-button' ).trigger( 'click' );
			}
		} )
		.on( 'click', '.to-top.show', function( e ) {
			e.preventDefault();
			$( 'html, body' ).animate( { scrollTop: 0 }, Math.min( 800, Math.max( 300, Math.round( $win.scrollTop() / 5 ) ) ) );
		} )
		.on( 'click', '.site-header #menu-toggle, .elementor-widget-cs_menu_toggle .menu-toggle', function( e ) {
			e.preventDefault();
			$sidemenu = isCustomizePreview ? $( '.sidemenu' ) : $sidemenu;
			if ( $sidemenu.length ) {
				var $current = $( this ), $toggledOnBtns = $( '.menu-toggle.toggled-on' ),
					closeSideMenu = $current.hasClass( 'close-button' ) || $current.hasClass( 'toggled-on' );

				if ( closeSideMenu ) {
					$sidemenu.removeClass( 'show' );
					$body.css( 'overflow', '' );
					$toggledOnBtns.length ? $toggledOnBtns.removeClass( 'toggled-on' ) : '';
				} else {
					$current.addClass( 'toggled-on' );
					$sidemenu.addClass( 'show' );
					$body.css( 'overflow', 'hidden' );
				}
			}
		} )
		.on( 'click', '.sidemenu.show .close-button', function( e ) {
			e.preventDefault();
			e.stopPropagation();
			$sidemenu = isCustomizePreview ? $( '.sidemenu' ) : $sidemenu;
			$( '.menu-toggle' ).removeClass( 'toggled-on' );
			$sidemenu.removeClass( 'show' );
			$body.css( 'overflow', '' );
		} )
		.on( 'click', '.sidemenu', function( e ) {
			var $target = $( e.target );
			if ( $target.hasClass( 'sidemenu' ) && $target.hasClass( 'show' ) ) {
				$( '.sidemenu .close-button' ).trigger( 'click' );
			}
		} )
		.on( 'click', '.sidemenu .menu-item a', function( e ) {
			var menu = this;
			if ( menu.hash && ( menu.hash.length > 1 ) && ( currentLocation.host + currentLocation.pathname == menu.host + menu.pathname ) ) {
				var $nav = $( this ).closest( 'nav' ), $menu = $( this );
				if ( $nav.length ) {
					var $menuItems = $nav.find( '.menu-item' ), $menuAncestor = $nav.find( 'li' ).filter( $( this ).parent().parents( 'li' ) );
					$menuItems.length ? $menuItems.removeClass( 'current-menu-item current-menu-ancestor' ) : '';
					$menu.parent().addClass( 'current-menu-item' );
					$menuAncestor.length ? $menuAncestor.addClass( 'current-menu-ancestor' ) : '';
				}
				setTimeout( function() { $menu.closest( '.sidemenu' ).find( '.close-button' ).trigger( 'click' ); }, 550 );
			}
		} )
		.on( 'mouseover', '#masthead .primary-menu .mega-menu .sub-cat-list li', function(  ) {
			if ( ! $( this ).hasClass( 'current' ) ) {
				var $posts = $( this ).parents( '.sub-cat-list' ).first().siblings( '.sub-cat-posts' ).first();
				$( this ).siblings( '.current' ).removeClass( 'current' ).end().addClass( 'current' );
				$posts.children( '.current' ).removeClass( 'current' );
				$posts.children( '.' + $( this ).attr( 'data-id' ) ).addClass( 'current' );
			}
		} )
		.on( 'click', '.main-navigation .dropdown-toggle, .secondary-navigation .dropdown-toggle, .cs-menu .dropdown-toggle', function( e ) {
			e.preventDefault();
			if ( $( this ).hasClass( 'toggled-on' ) ) {
				$( this ).parent().find( '.toggled-on' ).removeClass( 'toggled-on' );
			} else {
				$( this ).parent().siblings( 'li' ).find( '.toggled-on' ).removeClass( 'toggled-on' );
				$( this ).addClass( 'toggled-on' );
			}
		} )
		.on( 'click', '.site-header .site-header-search, .cs-search-toggle .toggle-button', function( e ) {
			e.preventDefault();
			if ( $searchScreen.length ) {
				if ( ! $searchScreen.hasClass( 'show' ) ) {
					if ( $( this ).data( 'post-types' ) ) {
						var $postTypes = $searchScreen.find( 'input[name="post_type"]' ).length
							? $searchScreen.find( 'input[name="post_type"]' ) : $( '<input>', { 'name': 'post_type', 'type': 'hidden' } ).appendTo( $searchScreen.find( '.search-form' ) );
						if ( $postTypes.val() && ! $postTypes.data( 'default-post-types' ) ) {
							$postTypes.data( 'default-post-types', $postTypes.val() );
						}
						$postTypes.addClass( 'custom-post-type' ).val( $( this ).data( 'post-types' ) );
					}
					$searchScreen.addClass( 'show' );
					setTimeout( function() {
						$searchScreen.find( 'form .search-field' ).focus();
					}, 250 );
					$body.css( 'overflow', 'hidden' );
				}
			}
		} )
		.on( 'click', '.search-screen.show .close-button', function( e ) {
			e.preventDefault();
			if ( $searchScreen.find( '.custom-post-type' ).length ) {
				var $postType = $searchScreen.find( '.custom-post-type' );
				$postType.data( 'default-post-types' )
					? $postType.val( $postType.data( 'default-post-types' ) ).removeAttr( 'class' ).removeAttr( 'data-default-post-types' )
						: $postType.remove();
			}
			$searchScreen.removeClass( 'show' );
			$body.css( 'overflow', '' );
		} )
		.on( 'click', '.single #primary .post-footer .comments-link a', function( e ) {
			e.preventDefault();
			var $comments = $( '#comments' );
			if ( $comments.length ) {
				var top = $comments.offset().top - topOffset;
				$( 'html, body' ).animate( { scrollTop: top }, 200 );
			}
		} )
		.on( 'click', function( e ) {
			var $focused = $( '#masthead .menu-item.focused' );
			if ( $focused.length ) {
				$focused.removeClass( 'focused' );
			}
		} )
		.on( 'keyup', function( e ) {
			var code = e.keyCode || e.which;
			if ( code === 9 ) {
				var $current = $( e.target ), $all = $( '#masthead .menu-item' ),
					isCurrentMenuItem = $current.hasClass( 'menu-item' ) || $current.parents( '.menu-item' ).length;
				if ( isCurrentMenuItem ) {
					var $item = $current.parents( '.menu-item' ).length ? $current.parents( '.menu-item' ).last() : $current;
					$all.removeClass( 'focused' );
					$item.addClass( 'focused' );
				} else {
					$all.removeClass( 'focused' );
				}
			}
		} )
		.on( 'click', '.quantity.cs-quantity .minus', function( e ) {
			e.preventDefault();
			var $qty = $ ( this ).siblings( '.qty' );
			if ( $qty.length ) {
				var currentValue = $qty.val();
				currentValue > 0 ? $qty.val( currentValue - 1 ).trigger( 'change' ) : '';
				cozystayCheckMinusBtn( $( this ), $qty );
			}
		} )
		.on( 'click', '.quantity.cs-quantity .plus', function( e ) {
			e.preventDefault();
			var $qty = $ ( this ).siblings( '.qty' );
			if ( $qty.length ) {
				var currentValue = $qty.val();
				$qty.val( ++currentValue ).trigger( 'change' );
				$( this ).siblings( '.minus' ).removeAttr( 'disabled' );
			}
		} );
		if ( cozystay.woocommerceProductFilterAjaxEnabled ) {
			var sortingRedirectURL = false;
			$doc.on( 'click', '.widget.woocommerce.widget_product_categories .product-categories a', function( e ) {
				e.preventDefault();
				var $link = $( this ), $items = $link.closest( '.product-categories' ).find( '.cat-item' );
				if ( $link.attr( 'href' ) && ! $link.parent().hasClass( 'current-cat' ) ) {
					$.get( $link.attr( 'href' ) ).done( function( data, status, jqXHR ) {
						if ( jqXHR && jqXHR.status && ( 200 == jqXHR.status ) ) {
							var $html = $( data ).find( '#primary' );
							if ( $html.length ) {
								var $currentPrimary = $( '#primary' ), $currentParents = $items.filter( $link.parent().parents( 'li.cat-item' ) );
								$html.hide();
								$currentPrimary.html( $html.html() );
								$html.show( 'fast' );
								$items.removeClass( 'current-cat-parent current-cat' );
								$link.parent().addClass( 'current-cat' );
								$currentParents.length ? $currentParents.addClass( 'current-cat-parent' ) : '';
								sortingRedirectURL = $link.attr( 'href' );
							}
						}
					} );
				}
				return false;
			} );
			$doc.on( 'change', '#primary .woocommerce-ordering .orderby', function( e ) {
				if ( false !== sortingRedirectURL ) {
					$( this ).closest( 'form.woocommerce-ordering' ).attr( 'action', sortingRedirectURL ).submit();
				}
			} );
		}
		if ( $( '.quantity.cs-quantity .minus' ).length ) {
			$( '.quantity.cs-quantity .minus' ).each( function() {
				cozystayCheckMinusBtn( $( this ), $( this ).siblings( '.qty' ) );
			} );
		}

		// Add extra class to image wrapper link
		if ( $contentImages.length ) {
			$contentImages.each( function() {
				if ( $( this ).parent( 'a' ).length ) {
					$( this ).parent( 'a' ).addClass( 'image-link' );
				}
			} );
		}
		// Gustified galleries
		$( '.post-content-gallery.gallery-justified' ).cozystayJustifiedGallery();

		$win.on( 'resize', function( e ) {
			if ( $( this ).data( 'refreshTimer' ) ) {
				clearTimeout( $( this ).data( 'refreshTimer' ) );
				$( this ).data( 'refreshTimer', false );
			}
			if ( $( '.slick-initialized.slick-slider' ).length ) {
				$( this ).data( 'refreshTimer', setTimeout( function() {
					$( '.slick-initialized.slick-slider' ).slick( 'refresh' );
				}, 300 ) );
			}
		} );
	} );
} ) ( jQuery );
