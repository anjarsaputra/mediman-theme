(function($) {
    "use strict";
    $(document).ready(function() {
        var ticker = $('#news-ticker');
        if (ticker.length && ticker.children('li').length > 1) {
            setInterval(function() {
                ticker.find('li:first').slideUp(500, function() {
                    $(this).appendTo(ticker).slideDown();
                });
            }, 3000);
        }
    });
})(jQuery);