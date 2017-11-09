/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	"use strict";
	
	// Site Identity
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Logo
	wp.customize( 'evolution_logo_margin_top', function( value ) {
		value.bind( function( newval ) {
			$( '.site-logo' ).css( 'margin-top', newval + 'px' );
		} );
	} );
	wp.customize( 'evolution_logo_margin_bottom', function( value ) {
		value.bind( function( newval ) {
			$( '.site-logo' ).css( 'margin-bottom', newval + 'px' );
		} );
	} );
} )( jQuery );
