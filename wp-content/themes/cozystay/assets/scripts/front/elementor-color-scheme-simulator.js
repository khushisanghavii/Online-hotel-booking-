( function ( $ ) {
	"user strict";

    $( document).ready( function() {
        var $body = $( 'body' );
        $( '.cs-elementor-simulator-scheme-switcher > div' ).on( 'click', function() {
            var $self = $( this );
            if ( ! $self.hasClass( 'active' ) ) {
                $self.siblings().removeClass( 'active' );
                $self.addClass( 'active' );
                $body.removeClass( 'light-color dark-color' ).addClass( $self.data( 'color' ) );
            }
        } );
    } );
} ) ( jQuery );
