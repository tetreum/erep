peque.unloggedUser = function ()
{
    'use strict';

    var loginInit = function ()
    {
        $('#login-container').on("submit", function (e) {
            e.preventDefault();

            var $loginContainer = $('#login-container'),
                email = $loginContainer.find('[name="email"]').val(),
                password = $loginContainer.find('[name="password"]').val();

            if (email == "" || password == "") {
                return false;
            }
            $loginContainer.find(".error").hide();

            // Do not point this to peque.navigation.post|peque.api as they attempt to fix
            // errors like failed authentication (causing a loop to login)
            $.post("/login", {email: email, password: password}, function (data)
            {
                if (data == "" || data.error > 0) {
                    $loginContainer.find(".error").show();
                    return false;
                }

                if ($_GET("redirect") === undefined) {
                    peque.navigation.redirect("home");
                } else {
                    peque.navigation.redirect(decodeURIComponent($_GET("redirect")));
                }
            });
        });
    };

    var signupInit = function ()
    {
        var $container = $('#signup-wrapper');
        $container.find('form').on("submit", function (e) {
            e.preventDefault();

            var data = {
                username : $container.find('[name="username"]').val(),
                email : $container.find('[name="email"]').val(),
                password : $container.find('[name="password"]').val(),
                password2 : $container.find('[name="password2"]').val(),
                referrer : parseInt($container.find('[name="referrer"]').val()),
                country : parseInt($container.find('[name="country"]').val()),
                terms : $container.find('[name="terms"]').is(":checked")
            };

            if (data.username == "" || data.password == "" || data.password2 == "" || data.email == "" || !data.terms || data.country == 0) {
                return false;
            }

            $container.find(".invalid-field").removeClass("invalid-field");
            $container.find(".error").hide();

            if (data.password != data.password2) {
                console.log("block");
                $container.find('[name="password"]').addClass("invalid-field");
                $container.find('[name="password2"]').addClass("invalid-field");
                return false;
            }

            // Do not point this to peque.navigation.post|peque.api as they attempt to fix
            // errors like failed authentication (causing a loop to login)
            $.post("/signup", data, function (data)
            {
                if (data == "" || data.error > 0) {
                    $container.find(".error").show();
                    return false;
                }

                if ($_GET("redirect") === undefined) {
                    peque.navigation.redirect("home");
                } else {
                    peque.navigation.redirect(decodeURIComponent($_GET("redirect")));
                }
            });
        });
    };

    return {
        loginInit: loginInit,
        signupInit: signupInit
    };
}();