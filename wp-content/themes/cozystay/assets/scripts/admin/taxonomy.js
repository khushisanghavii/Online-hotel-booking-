( function( $ ) {
	"use strict";
	
	$( document ).ready( function() {
		function cozystayGetElementValue( element ) {
			if ( $( element ).length ) {
				var $element = $( element );
				return ( -1 !== [ 'radio', 'checkbox' ].indexOf( $element.attr( 'type' ) ) ) ? ( $element.filter( ':checked' ).length ? $element.filter( ':checked' ).val() : '' ) : $element.val();
			}

			return null;
		}
		/**
		* Helper function is Object
		*/
		function isObject( obj ) {
			var type = typeof obj;
			return type === 'function' || type === 'object' && !!obj;
		}
		/**
		* Dependency process determination
		*/
		function processDependency( deps ) {
			if ( isObject( deps ) ) {
				var relation = 'AND', isComplex = false;
				if ( deps.relation ) {
					relation = deps.relation ? deps.relation.toUpperCase() : 'AND';
				}

				isComplex = deps['is_complex'] || false;
				return ( 'AND' == relation ) ? checkDependencyAND( deps, isComplex ) : checkDependencyOR( deps, isComplex );
			}
			return true;
		}
		/**
		* Helper function to check dependency AND
		*/
		function checkDependencyAND( deps, isComplex ) {
			if ( ! isObject( deps ) ) return true;
			var passed = true;
			$.each( deps, function( id, dep ) {
				if ( isObject( dep ) ) {
					var result = isComplex ? processDependency( dep ) : dependencyItemCheck( id, dep );
					if ( ! result ) {
						passed = false;
						return false;
					}
				}
			} );
			return passed;
		}
		/**
		* Helper function to check dependency OR
		*/
		function checkDependencyOR( deps, isComplex ) {
			if ( ! isObject( deps ) ) return true;
			var passed = false;
			$.each( deps, function( id, dep ) {
				if ( isObject( dep ) ) {
					var result = isComplex ? processDependency( dep ) : dependencyItemCheck( id, dep );
					if ( result ) {
						passed = true;
						return false;
					}
				}
			} );
			return passed;
		}
		/**
		* Dependency item check
		*/
		function dependencyItemCheck( pid, vals ) {
			if ( ! pid || ! vals ) { // If not provide the test value list, return false
				return false;
			}

			var value = cozystayGetElementValue( '#' + pid );
			return vals.indexOf( value ) !== -1;
		}

        if ( $( '.cs-category-settings' ).length && cozystayCategory ) {
            $( '#cozystay_category_enable_individual_settings' ).on( 'change', function( e ) {
                if ( $( this ).is( ':checked' ) ) {
                    $( '.cs-category-settings .category-item-wrap' ).removeClass( 'hidden' );
                } else {
                    $( '.cs-category-settings .category-item-wrap' ).addClass( 'hidden' );
                }
            } ).trigger( 'change' );

            var prefix = '.category-item-wrap.item-';
            $.each( cozystayCategory, function( id, children ) {
                $( '#' + id ).on( 'change', function( e ) {
                    var val = cozystayGetElementValue( this );
                    $.each( children, function( cid, deps ) {
						if ( processDependency( deps ) ) {
                            $( prefix + cid ).css( 'display', '' );
                        } else {
                            $( prefix + cid ).css( 'display', 'none' );
                        }
                        $( '#' + cid ).trigger( 'change' );
                    } );
                } );
                $.each( children, function( cid, deps ) {
					if ( processDependency( deps ) ) {
                        $( prefix + cid ).css( 'display', '' );
                    } else {
                        $( prefix + cid ).css( 'display', 'none' );
                    }
                } );
            } );
        }
    } );
} ) ( jQuery );
