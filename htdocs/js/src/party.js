peque.party = function ()
{
    'use strict';
    var id;

    var init = function ()
    {
        id = $('#party-profile').data("id");

        $('[data-action="leave-party"]').on('click', leave);
        $('[data-action="join-party"]').on('click', join);
    };

    var leave = function ()
    {
        peque.api("party/leave", {id: id}, function (data) {
            if (data.error > 0) {
                return false;
            }

            peque.navigation.redirect("/");
        });
    };

    var join = function ()
    {
        peque.api("party/join", {id: id}, function (data) {
            if (data.error > 0) {
                return false;
            }

            peque.navigation.reload();
        });
    };

    var initCreationForm = function ()
    {
        $('#party-creation-form').on("submit", function (e)
        {
            e.preventDefault();

            var name = $('[name="name"]').val(),
                description = $('[name="description"]').val();

            if (name.length < 5 || description.length < 5) {
                return false;
            }

            peque.api("party/create", {name: name, description:description} , function (data) {
                if (data.error > 0 || !data.result) {
                    return false;
                }

                peque.navigation.redirect("/party/" + data.result + "/slug");
            });
        });
    };

    return {
        init: init,
        initCreationForm: initCreationForm
    };
}();