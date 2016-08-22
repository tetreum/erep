peque.inbox = function ()
{
    'use strict';

    var init = function ()
    {
        $('#message-form').on("submit", function (e) {
            e.preventDefault();

            var to = $(this).data("to"),
                message = $('[name=message]').val();

            if (message.length < 4) {
                return false;
            }

            peque.api("pm/send", {to: to, message: message}, function (data)
            {
                if (data.error > 0){
                    return false;
                }

                peque.navigation.reload();
            });
        });
    };

    return {
        init: init
    };
}();