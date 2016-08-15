peque.gym = function ()
{
    'use strict';

    var init = function ()
    {
        $('[data-action=train]').on("click", function ()
        {
            var quality = parseInt($(this).data("quality"));

            if (quality < 1) {
                return false;
            }

            peque.api("user/train", {quality: quality}, function (data) {
                if (data.error > 0) {
                    return false;
                }

                peque.navigation.showSuccess("");
            });
        });
    };

    return {
        init: init
    };
}();