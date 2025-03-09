( function( $ ) {
	"use strict";

	function updateLanguageForm( language, formID ) {
		if ( language ) {
			var $caller = $( this );
			formID = formID || 0;
			$caller.prop( 'disabled', true );
			return $.post(
				cozystayMC4WPTranslation.url,
				{ 'action': cozystayMC4WPTranslation.actionUpdate, 'language': language, 'formID': formID }
			).always( function() {
				$caller.prop( 'disabled', false );
			} );
		}
		return false;
	}
	function duplicateForm( language, defaultFormID ) {
		if ( language && defaultFormID ) {
			var $caller = $( this );
			$caller.data( 'processing', true );
			$.post(
				cozystayMC4WPTranslation.url,
				{ 'action': cozystayMC4WPTranslation.actionDuplicate, 'language': language, 'formID': defaultFormID, 'name': $caller.data( 'name' ) }
			).done( function( response ) {
				if ( response && response.data && response.data.status && ( 'done' == response.data.status ) ) {
					window.location = response.data.url;
				} else {
					$caller.after( $( '<div>', { 'class': 'response-message', 'html': 'Please try again later.' } ) );
				}
			} ).always( function() {
				$caller.data( 'processing', false );
			} );
		}
	}
	if ( $( '#tab-integrations' ).length && cozystayMC4WPTranslation ) {
		var defaultFormID = $( '#tab-integrations .default-language-form select' ).val();
		$( '#tab-integrations' ).on( 'change', '.default-language-form select', function( e ) {
			e.preventDefault();
			defaultFormID = $( this ).val();
			updateLanguageForm.call( this, $( this ).data( 'lang' ), defaultFormID );
		} ).on( 'change', '.other-language-forms select', function( e ) {
			e.preventDefault();
			var dfd = updateLanguageForm.call( this, $( this ).data( 'lang' ), $( this ).val() ), $tr = $( this ).closest( 'tr' ).first(),
				$actions = $tr.children().eq( 2 ), hasActions = $actions.length;
			hasActions ? $actions.hide() : '';
			if ( dfd ) {
				dfd.done( function( response ) {
					if ( response.data && $tr.find( 'a.edit-form' ).length ) {
						var $edit = $tr.find( 'a.edit-form' );
						response.data.editURL ? $edit.attr( 'href', response.data.editURL ).show().siblings().hide() : $edit.hide().siblings().show();
					}
					hasActions ? $actions.show() : '';
				} );
			} else if ( hasActions ) {
				$actions.show();
			}
		} ).on( 'click', '.duplicate-form', function( e ) {
			e.preventDefault();
			if ( ! $( this ).data( 'processing' ) ) {
				$( this ).parent().find( '.response-message' ).remove();
				duplicateForm.call( this, $( this ).data( 'lang' ), defaultFormID );
			}
		} );
	}
} ) ( jQuery );
