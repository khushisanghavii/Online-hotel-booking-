( function( $ ) {
   "use strict";
   var $win = $( window ), $doc = $( document ), cozystayAnimations = {}, cozystayMasonry = {};

    cozystayAnimations = {
        'init': function() {
            this.processSlickSlider();
        },
        'processSlickSlider': function() {
            var galleryArgs = {
                dots: 			false,
                infinite: 		true,
                speed: 			500,
                fade: 			true,
                cssEase: 		'linear',
                autoplay: 		true,
                autoplaySpeed: 	5000,
                swipeToSlide: 	true,
                appendArrows:  	false
            }, relatedGalleryArgs = {
                dots: false,
                arrows: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                speed: 500,
                autoplay: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 800,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            };
            [ '.post-content-gallery.gallery-slider .image-gallery', '.loftocean-popup-sliders .popup-slider.gallery-slider .image-gallery' ].forEach( function( selector ) {
                if ( $( selector ).length ) {
                    $( selector ).each( function() {
                        var $gallery = $( this ), arrows = false, currentArgs = $.extend( {},  galleryArgs );
                        arrows = $( '<div>', { 'class': 'slider-arrows' } );
                        $gallery.after( arrows );
                        currentArgs['appendArrows'] = arrows;
                        if ( $gallery.closest( '.popup-slider' ).length ) {
                            currentArgs['autoplay'] = false;
                        }
                        $gallery.cozystaySlickSlider( currentArgs );
                    } );
                }
            } );
            $( '.related-posts .related-wrapper' ).each( function() {
                $( this ).cozystaySlickSlider( relatedGalleryArgs );
            } );

            var $postGalleries = $( '.post.format-gallery .thumbnail-gallery' );
            if ( $postGalleries.length ) {
                $postGalleries.cozystayPostsGallery();
            }
        }
    };

    cozystayMasonry = {
        'masonry': false,
        'loaded': false,
        'init': function( container, dynamicallyAdded ) {
            var $masonry = container && $( container ).length ? $( container ) : $( '.posts.layout-masonry' ), $removed = $();
            if ( $masonry.length ) {
                var site = this;
                site.masonry = site.masonry && site.masonry.length ? site.masonry.add( $masonry ) : $masonry;
                // site.masonry.each( function() {
                //     if ( ! $( this ).closest( '.elementor-widget-wrap' ).length ) {
                //         $removed = $removed.add( $( this ) );
                //     }
                // } );
                // if ( $removed.length ) {
                //     site.masonry = site.masonry.not( $removed );
                // }
                $masonry.data( 'mobile-mode', false ).each( function() {
                    var $layout = $( this );
                    if ( $( this ).find( '.posts-wrapper .post' ).length ) {
                        var list = [];
                        $layout.find( '.posts-wrapper .post' ).each( function() {
                            list.unshift( $( this )    );
                        } );
                        $layout.data( 'post-list', list );
                    } else {
                        $layout.data( 'post-list', false );
                    }
                } );
                if ( ! site.loaded ) {
                    site.loaded = true;
                    $doc.on( 'resize.cozystay.window', function() { 
                        var isMobile = site.isMobileDevice( site.masonry ), currentModeMobile = site.masonry.first().data( 'mobile-mode' );
                        if ( isMobile && ! currentModeMobile ) { 
                            site.runMasonryMobileMode( site.masonry );
                        } else if ( ! isMobile ) {
                            site.masonry.each( function() {
                                site.changeMasonryColumnSettings( $( this ) );
                                site.resetMasonryPosts( $( this ) );
                                site.runMasonryDesktopMode( $( this ) );
                            } );
                        }
                    } );
                }
                if ( site.isMobileDevice( $masonry ) ) {
                    site.runMasonryMobileMode( $masonry );
                } else {
                    dynamicallyAdded ? site.processDesktopMasonryLayout( $masonry )
                        : $win.on( 'load', function() { site.processDesktopMasonryLayout( $masonry ); } );
                }
            }
        },
        'processDesktopMasonryLayout': function( $masonry ) {
            var site = this;
            $masonry.each( function() {
                site.changeMasonryColumnSettings( $( this ) );
                site.runMasonryDesktopMode( $( this ), true );
            } );
        },
        'changeMasonryColumnSettings': function( $layout ) {
            if ( window.cozystayInnerWidth > 768 ) {
                if ( window.cozystayInnerWidth < 1024 ) {
                    $layout.data( 'masonry-column', 2 );
                } else {
                    $layout.data( 'masonry-column', $layout.find( '.masonry-column' ).length );
                }
            } else {
                $layout.data( 'masonry-column', false );
            }
        },
        'isMobileDevice': function( $layout ) {
            return $layout.length && $layout.find( '.posts-wrapper .post' ).length && ( window.cozystayInnerWidth < 768 );
        },
        'runMasonryMobileMode': function( $layout ) {
            var site = this;
            $layout.data( 'mobile-mode', true ).each( function() {
                site.resetMasonryPosts( $( this ) );
            } );
            $doc.trigger( 'animation.cozystay.masonry' );
        },
        'resetMasonryPosts': function( $wrap ) {
            if ( $wrap.data( 'post-list' ) ) {
                var list = $wrap.data( 'post-list' ), $container = $wrap.find( '.masonry-column' ).data( 'column-height', 0 ).first();
                list.forEach( function( $p ) {
                    $container.prepend( $p );
                } );
                $wrap.data( 'current', 0 );
                $container.siblings().addClass( 'empty' );
            }
        },
        'runMasonryDesktopMode': function( $container, resize ) {
            var option = resize ? { 'trigger-sidebar-resize': true } : {};
            $container.data( 'current', 0 ).find( '.masonry-column' ).data( 'column-height', 0 );
            $container.data( 'mobile-mode', false ).cozystayMasonry( option );
            $doc.trigger( 'animation.cozystay.masonry' );
        }
    };

    /**
    * Enable masonry for post list with masonry
    * 	Actually it's alreay splite into columns, just reorder it to fit the height
    */
    $.fn.cozystayMasonry = function( args ) {
        var options = $.extend({}, { 'post': '.post', 'append': false, 'trigger-sidebar-resize': false }, args || {} );
        $( this ).each( function() {
            var $masonry = $( this ), selector = options.post;
            if ( $masonry.hasClass( 'layout-masonry' ) && $masonry.find( selector ).length ) {
                var columns = [], length = $masonry.data( 'masonry-column' ) ? $masonry.data( 'masonry-column' ) : 2,
                    current = $masonry.data( 'current' ) || 0, $columns = $masonry.find( '.masonry-column' );
                for ( var i = 0; i < length; i ++ ) {
                    columns.push( $columns.eq( i ).data( 'column-height' ) || 0 );
                }

                $masonry.find( '.posts-wrapper ' + selector ).each( function( index, item ) {
                    var $item = $( item ), lowest = 0;
                    columns[ current ] += cozystayParseInt( $item.outerHeight( true ) );
                    $item.addClass( 'masonry-column-' + current );

                    lowest = columns[ current ];
                    for ( var i = ( length - 1 ); i >= 0; i -- ) {
                        if ( columns[ i ] <= lowest ) {
                            lowest 	= columns[ i ];
                            current = i;
                        }
                    }
                } );
                $columns.each( function( ci, co ) {
                    var column_class = 'masonry-column-' + ci;
                    if ( $masonry.find( '.post.' + column_class ).length ) {
                        $( this ).append( $masonry.find( '.post.' + column_class ).removeClass( column_class ).detach() );
                    }
                    $( this ).data( 'column-height', columns[ ci ] );
                    if ( columns[ ci ] ) {
                        $( this ).hasClass( 'empty' ) ? $( this ).removeClass( 'empty' ) : '';
                    } else {
                        $( this ).hasClass( 'empty' ) ? '' : $( this ).addClass( 'empty' );
                    }
                } );
                $masonry.data( 'current', current );
            }
        } );
        $doc.trigger( 'changed.cozystay.mainContent' );
        return this;
    }

    $.fn.cozystayPostsGallery = function() {
        return $( this ).each( function() {
            $( this ).cozystaySlickSlider( {
                dots: true,
                arrows: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                speed: 500,
                autoplay: false,
                autoplaySpeed: 5000,
                appendArrows: $( this ).parents( '.featured-img' ).first().find( '.slider-arrows' ),
                appendDots: $( this ).parents( '.featured-img' ).first().find( '.slider-dots' )
            } );
        } );
    }

    $doc.on( 'cozystay.init', function() {
        cozystayAnimations.init();
        // Process masonry posts layout
        cozystayMasonry.init();
    } ).on( 'cozystay.initMasonry', function( e, container ) {
        if ( container && $( container ).length ) {
            cozystayMasonry.init( container, true );
        }
    } );
} ) ( jQuery );
