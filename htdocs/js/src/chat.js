peque.chat = function ()
{
    'use strict';

    var $chatContainer;

    var init = function (defaultChannel)
    {
        $chatContainer = $('#chat-container');

        $('#chat-channels li').on("click", changeChannel);

        $('#chat-channels li[data-channel=' + defaultChannel + ']').click();

        $chatContainer.find('[data-action=post]').on("click", post);
    };

    var initMessageListeners = function ()
    {
        $chatContainer.find('[data-action=vote]').on("click", vote);
        $chatContainer.find('[data-action=show-comments]').on("click", showComments);
    };

    var showComments = function () {

    };

    var changeChannel = function ()
    {
        var channel = $(this).data("channel");

        $('#chat-channels li').removeClass("selected");
        $('#chat-channels li[data-channel=' + channel + ']').addClass("selected");

        peque.api("chat/list", {channelType: channel}, function (data) {
            if (data.error > 0) {
                return false;
            }

            var k, html = '';

            for (k in data.result)
            {
                if (!data.result.hasOwnProperty(k)) {
                    continue;
                }

                html += renderMessage(data.result[k]);
            }

            $('#chat-messages-container').html(html);
            initMessageListeners();
        });
    };

    var renderMessage = function (message)
    {
        var $template = peque.utils.getTemplate("chat-message");

        $template.find('[data-id="message"]').text(message.message);
        $template.find('.message-body strong').text(message.sender.nick);
        $template.find('[data-id="likes-count"]').text(message.likes);

        return $template.parent().html();
    };

    var post = function (e) {
        e.preventDefault();

        var id = $(this).data("id"),
            type = parseInt($(this).data("type")),
            message = $chatContainer.find("textarea").val();

        if (type < 1 || isNaN(type)) {
            type = parseInt($('#chat-channels li.selected').data("channel"));
        }

        if (id < 1 || type < 1 || message.length < 10) {
            return false;
        }

        peque.api("chat/post", {channelId: id, channelType: type, message: message}, function (data)
        {
            if (data.error > 0) {
                return false;
            }

            $('#chat-messages-container').prepend(renderMessage(data.result));
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