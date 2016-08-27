peque.navigation = function ()
{
    'use strict';

    var $loader;

    var getLoader = function ()
    {
        if ($loader === undefined) {
            $loader = $("#global-loader");
        }

        return $loader;
    };

    var showLoader = function () {
        getLoader().show();
    };

    var hideLoader = function () {
        getLoader().hide();
    };

    /**
     * Redirect user to a path without ajax load
     * @param path
     */
    var redirect = function (path)
    {
        if (path == "home") {
            path = "";
        }
        if (path.substr(0, 1) != "/") {
            path = "/" + path
        }

        window.location = path;
    };

    /**
     * Ajax navigation
     * @param path
     */
    var browse = function (path)
    {
        if (path == "#") {
            return;
        }

        history.pushState({}, path, path);

        peque.navigation.get(path, function (html) {
            $("#main-container").html(html);

            afterLoad();
        });
    };

    var afterLoad = function ()
    {
        // init country selector widgets
        $('.country-selector:not(data-parsed)').each(function ()
        {
            var $that = $(this);

            $that.find('.arrow-container').on("click", function () {
                $that.find(".country-list").toggle();
            });

            $that.find('input').on("click", function () {
                var query = $(this).val().toLowerCase();

                if (query.length < 2) {
                    return;
                }

                $that.find("li").each(function () {
                    if ($(this).data('name').toLowerCase().indexOf(query) > -1 ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $(this).data("parsed", true);
        });
    };

    // must be declared after browser() method
    $(document).on("click", "a:not([data-noajax])", function (e)
    {
        e.preventDefault();

        browse($(this).attr("href"));
    });
    $(function() {
        afterLoad();
    });


    /**
     * Attempts to fix the given error code
     * @param data object
     * @param callback function
     */
    var parseError = function (data, callback)
    {
        switch (data.error)
        {
            case 11: // unauthorized request (probably session expired)
                peque.navigation.redirect("logout?redirect=" + encodeURIComponent(location.pathname + location.search));
                break;
            case 6: // no enough money
                showError("You can't afford that");
                break;
            case 7: // no enough resources
                showError("You don't have enough resources");
                break;
            case 4: // missing params
            case 5: // action denied
                showError(data.message);
                break;
            default:
                callback(data);
                break;
        }
    };

    var post = function (url, params, callback)
    {
        showLoader();
        $.post(url, params, function (data) {
            hideLoader();

            if (typeof data == "object") {
                parseError(data, callback);
            } else {
                callback(data);
            }
        });
    };

    var get = function (url, params, callback)
    {
        showLoader();

        // check if this request doesnt need params
        if (typeof params == "function") {
            callback = params;
            params = {};
        }

        $.get(url, params, function (data) {
            hideLoader();

            if (typeof data == "object") {
                parseError(data, callback);
            } else {
                callback(data);
            }
        });
    };

    var showError = function (message) {
        swal({title: "¡Error!", text: message, type: "error", confirmButtonText: "Cool", html: true});
    };

    var showConfirm = function (message, callback)
    {
        swal({title: "¿Are you sure?",
                text: message,
                type: "warning",
                html: true,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Confirm",
                closeOnConfirm: false
            },
            callback);
    };

    var showSuccess = function (message, ops)
    {
        if (typeof ops === "undefined") {
            ops = {};
        }

        if (typeof ops.callback === "undefined") {
            swal({title: "¡Success!",
                text: message,
                type: "success",
                html: true
            });
        } else {
            swal({title: "¡Success!",
                text: message,
                type: "success",
                html: true
            }, ops.callback);
        }
    };

    var reload = function () {
        location.reload();
    };

    return {
        reload: reload,
        showLoader: showLoader,
        hideLoader: hideLoader,
        redirect: redirect,
        browse: browse,
        parseError: parseError,
        showError: showError,
        showConfirm: showConfirm,
        showSuccess: showSuccess,
        post: post,
        get: get
    };
}();