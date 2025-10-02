( function( $ ) {
    wp.customize( 'custom_logo', function( value ) {
        value.bind( function( newval ) {
            $('.site-logo').html('<img src="' + wp.customize.settings.values.custom_logo + '" />');
        });
    });
} )( jQuery );
