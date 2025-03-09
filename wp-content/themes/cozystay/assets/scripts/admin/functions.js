( function( $ ) {
	"use strict";

	// Theme customized media lib
	window.cozystayMeida = {
		input: '',
		frame: '',
		frames: {},
		mediaFrame: function() {
			if ( ! this.frames[ this.frame ] ) {
				this.frames[this.frame] = wp.media( {
					id: 'cs-image-uploader',
					// frame: 'post',
					// state: 'insert',
					editing: true,
					library: {
						type : 'image' == this.frame ? 'image' : ['image', 'video']
					},
					multiple: false  // Set this to true to allow multiple files to be selected
				} )
				.on( 'select', function() {
					var media = cozystayMeida.frames[ cozystayMeida.frame ].state().get( 'selection' ).first().toJSON();
					cozystayMeida.input.trigger( 'changed.cozystay.media', media );
					cozystayMeida.input = ''; // reset input
				})
				.on( 'open', function() {
					var selection = cozystayMeida.frames[ cozystayMeida.frame ].state().get( 'selection' ),
						image_id  = cozystayMeida.input.val();
					selection.reset();
					if ( image_id && ( image_id !== '' ) ) {
						var attachment = wp.media.attachment( image_id );
						attachment.fetch();
						selection.add( attachment ? [attachment] : [] );
					}
				} );
			}
			return this.frames[ this.frame ];
		},
		open: function( $input, frame ) {
			this.input = $input.first();
			this.frame = frame || 'image';
			this.mediaFrame().open();
		}
	};

	window.cozystayInitEditor = function( id, settings, $container ) {
		window.tinymce.init( $.extend( settings.mce, {
			init_instance_callback: function( editor ) {
				editor.on( 'Dirty', function( e ) {
					var content = wp.editor.getContent( id );
					wp && wp.customize ? wp.customize( id )( content ) : $container.find( 'textarea' ).val( content ).trigger( 'change' );
				} );
				if( $container.find( '.wp-editor-wrap' ).length ) {
					$container.find( '.wp-editor-wrap' ).removeClass( 'html-active' ).addClass( 'tmce-active' );
				}
			}
		} ) );
		settings.qt ? quicktags( settings.qt ) : '';
	}
} ) ( jQuery );
