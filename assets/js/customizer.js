(function($) {
    // Mengatur tampilan custom section berdasarkan opsi di Customizer
    wp.customize('display_custom_section', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('#custom-section').show();
            } else {
                $('#custom-section').hide();
            }
        });
    });

    // Mengubah warna tombol secara langsung di Customizer
    wp.customize('button_color', function(value) {
        value.bind(function(newColor) {
            $('.custom-button').css('background-color', newColor);
        });
    });
})(jQuery);
