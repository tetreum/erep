peque.work = function () {
    'use strict';

    var init = function ()
    {
        $(document).on('click', '.dropdown-menu', function(e) {
            if ($(this).hasClass('dropdown-menu')) { e.stopPropagation(); }
        });
        $('.country-selector:not(data-parsed)').each(function () {
            var $lis = $(this).find("li");
            $(this).find('input').on("click", function () {
                var query = $(this).val().toLowerCase();

                if (query.length < 2) {
                    return;
                }

                $lis.each(function () {
                    if ($(this).data('name').toLowerCase().indexOf(query) > -1 ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    };


    return {
        init: init
    };
}();
