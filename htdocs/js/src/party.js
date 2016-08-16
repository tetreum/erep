peque.party = function ()
{
    'use strict';

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
        initCreationForm: initCreationForm
    };
}();