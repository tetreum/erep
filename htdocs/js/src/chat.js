peque.chat = function ()
{
    'use strict';

    var init = function (defaultChannel)
    {
        $('#chat-channels li').on("click", changeChannel);

        $('#chat-channels li[data-channel=' + defaultChannel + ']').click();
    };

    var initMessageListeners = function ()
    {
        $('[data-action=post]').on("submit", post);
        $('[data-action=vote]').on("click", vote);
        $('[data-action=show-comments]').on("click", showComments);
    };

    var changeChannel = function ()
    {
        var channel = $(this).data("channel");

        $('#chat-channels li').removeClass("selected");
        $('#chat-channels li[data-channel=' + channel + ']').addClass("selected");

        peque.api("chat/list", {channel: channel}, function (data) {
            if (data.error > 0) {
                return false;
            }

            $('#chat-messages-container').html(data.result);
            initMessageListeners();
        });
    };

    var post = function (e) {
        e.preventDefault();

        var id = $(this).data("id"),
            type = $(this).data("type"),
            message = $(this).data("message");

        if (id < 1 || type < 1 || message.legth < 4) {
            return false;
        }

        peque.api("chat/post", {channelId: id, channelType: type, message: message}, function (data)
        {
            if (data.error > 0) {
                return false;
            }
            var $template = peque.utils.getTemplate("chat-message");

            $template.find("p").html(message);
        });
    };

    var vote = function ()
    {
        var id = $(this).data("id");

        if (id < 1) {
            return false;
        }

        peque.api("chat/vote", {id: id}, function (data)
        {
            if (data.error > 0) {
                return false;
            }

            var $counter = $('#message-' + id + " [data-id=vote-count]");

            $counter.html(parseInt($counter.html()) + 1);
        });
    };


    return {
        init: init
    };
}();