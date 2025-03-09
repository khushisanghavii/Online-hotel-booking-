( function( $ ) {
	"use strict";
	var $doc = $( document ), cozystaySinglePost = {};

	cozystaySinglePost = {
		'init': function() {
			this.processPostHeaderSlider();
			this.process_media();
			this.process_recipe();
		},
		'processPostHeaderSlider': function() {
			var $slider = $( '.single-format-gallery .featured-media-section .image-gallery' );
			if ( $slider.length ) {
				$slider.cozystaySlickSlider( {
					dots: false,
					infinite: true,
					speed: 500,
					fade: true,
					cssEase: 'linear',
					autoplay: true,
					autoplaySpeed: 5000,
					arrows: true,
					swipeToSlide: true
				} );
			}
		},
		'process_recipe': function() {
			var $recipes = $( '.wprm-recipe-container' );
			if ( $recipes.length ) {
				$recipes.each( function() { $( this ).fadeIn( 800 ); } );
			}
		},
		'process_media': function() {
			if ( cozystay.postFeaturedVideo ) {
				var $container = $( '.featured-media-section.has-video' );
				if ( $container.length ) {
					$doc.trigger( 'autoplay.loftocean.video', {
						'args': {},
						'video': cozystay.postFeaturedVideo,
						'container': $container
					} );
				}
			}
		}
	};

	$doc.on( 'cozystay.init', function() {
		cozystaySinglePost.init();
	} );
} ) ( jQuery );
