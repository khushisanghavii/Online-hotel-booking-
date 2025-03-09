( function ( $ ) {
	"user strict";
	/**
	* Callback function to show navigation if needed
	*/
	function cozystayAjaxNav( data, $nav ) {
		if ( data && data.more ) {
			cozystayAjaxNavigation.data.query.paged ++;
		} else {
			if ( $nav.hasClass( 'infinite' ) ) {
				$nav.find( '.load-more-btn.ajax' ).remove();
				$nav.removeClass( 'infinite' ).append( $( '<span>', { 'class': 'no-more-posts-message', 'text': ( $nav.data( 'no-post-text' ) ? $nav.data( 'no-post-text' ) : cozystayAjaxNavigation.noMoreText ) } ) );
			} else {
				$nav.find( '.load-more-btn.ajax.manual' ).remove();
				$nav.append( $( '<span>', { 'class': 'no-more-posts-message', 'text': ( $nav.data( 'no-post-text' ) ? $nav.data( 'no-post-text' ) : cozystayAjaxNavigation.noMoreText ) } ) );
			}
		}
		$nav.data( 'loading', false ).removeClass( 'loading' );
	}
	if ( cozystayAjaxNavigation ) {
		var $doc = $( document );
		$doc.on( 'cozystayAjaxNavigationProcessData', function( e, args ) {
			var $nav = args['target'].closest( '.navigation.pagination' ), $list = $nav.siblings( '.posts-wrapper' ).parent();
			if ( $list.length && $list.hasClass( 'posts' ) && $( args['data']['items'] ).length ) {
				var $wrap = false, $posts = $( '<div>' ).append( args['data']['items'] ), 
					$gallery = $posts.find( '.image-gallery, .cs-room-item .thumbnail-gallery' ), 
					hasGallery = $gallery.length, hasPreloader = $.fn.loftoceanImageLoading;
				$posts.children().addClass( 'new-post-item post' ).css( 'opacity', 0 );

				if ( $list.hasClass( 'layout-masonry' ) ) {
					var list = $list.data( 'post-list' ) ? $list.data( 'post-list' ) : [],
						$columns = $list.find( '.masonry-column' );
					$posts.children().each( function() {
						list.unshift( $( this ) );
					} );
					$list.data( 'post-list', list );
					if ( $list.data( 'mobile-mode' ) ) { // If in mobile mode, just append the posts
						$columns.first().append( $posts.children() );
						$( document ).trigger( 'loftcean/moreContent/loaded' );
						$list.find( '.new-post-item' ).removeClass( 'new-post-item' ).fadeTo( 300, 1 );
						cozystayAjaxNav( args['data'], args['target'] );
						if ( hasPreloader ) {
							$list.loftoceanImageLoading();
						}
					} else { // Recalculate the height
						$columns.first().append( $posts.children() );
						$list.cozystayMasonry( { 'post': '.post.new-post-item', 'append': true } );
						hasGallery ? $gallery.cozystayPostsGallery() : '';
						$( document ).trigger( 'loftcean/moreContent/loaded' );
						$list.find( '.new-post-item' ).removeClass( 'new-post-item' ).fadeTo( 300, 1 );
						cozystayAjaxNav( args['data'], args['target'] );
						if ( hasPreloader ) {
			 				$list.loftoceanImageLoading();
			 			}
					}
				} else {
					$wrap = $list.find( '.posts-wrapper' );
					$wrap.append( $posts.children() );
	 				$( document ).trigger( 'loftcean/moreContent/loaded', { 'videos': ( args['data']['videos'] && Array.isArray( args['data']['videos'] ) ? args['data']['videos'] : false ) } );
					$list.find( '.new-post-item' ).removeClass( 'new-post-item' ).fadeTo( 300, 1 );
					hasGallery ? $gallery.cozystayPostsGallery() : '';
 					$( document ).trigger( 'changed.cozystay.mainContent' );
					cozystayAjaxNav( args['data'], args['target'] );
	 				if ( hasPreloader ) {
		 				$list.loftoceanImageLoading();
		 			}
	 			}
	 		}
		} );
		$( 'body' ).on( 'cozystayAjaxNavigationStart', '.pagination-container.load-more', function( e ) {
			e.preventDefault();
			var $nav = $( this ), $posts = $nav.parents( '.posts' );
			if ( $nav.data( 'loading' ) ) return false;

			$nav.data( 'loading', true ).addClass( 'loading' );
			var data = false;
			if ( $posts.length && $posts.first().attr( 'data-settings' ) ) {
				$posts = $posts.first();
				data = JSON.parse( $posts.attr( 'data-settings' ) );
				data[ 'query' ][ 'paged' ] += 1;
				$posts.attr( 'data-settings', JSON.stringify( data ) );
				data[ 'action' ] = cozystayAjaxNavigation.data.action;
			} else {
				data = cozystayAjaxNavigation.data;
			}
			$.post( cozystayAjaxNavigation.url, data ).done( function( response ) {
				if ( response.success ) {
					$( document ).trigger( 'cozystayAjaxNavigationProcessData', { 'data': response.data, 'target': $nav } );
				} else {
					$nav.data( 'loading', false ).removeClass( 'loading' );
				}
			} ).fail( function() {
				$nav.data( 'loading', false ).removeClass( 'loading' );
			} );
		} ).on( 'click', '.load-more-btn.ajax.manual', function( e ) {
			e.preventDefault();
			$( this ).closest( '.pagination-container.load-more' ).trigger( 'cozystayAjaxNavigationStart' );
		} );

		$doc.data( 'previousTop', cozystayParseInt( $( window ).scrollTop() ) );
		$doc.on( 'scrolling.cozystay.window', function( e ) {
			var $doc = $( this ), currentTop = cozystayParseInt( $( window ).scrollTop() ),
				$autLoadMore = $( '.pagination-container.load-more.infinite' );
			if ( $autLoadMore.length && $doc.data( 'previousTop' ) && ( $doc.data( 'previousTop' ) < currentTop ) ) {
				var currentBottom = cozystayParseInt( currentTop ) + cozystayParseInt( $( window ).height() );
				$autLoadMore.each( function() {
					var navTop = $( this ).closest( '.navigation.pagination' ).offset().top;
					if ( ( navTop > currentTop ) &&  ( navTop < currentBottom ) ) {
						$( this ).trigger( 'cozystayAjaxNavigationStart' );
						return false;
					}
				} );
			}
			$doc.data( 'previousTop', currentTop );
		} );
	}
} ) ( jQuery );
