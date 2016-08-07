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
        history.pushState({}, path, path);

        peque.navigation.get(path, function (html) {
            $("#main-container").html(html);
        });
    };

    $(document).on("click", "a:not([data-noajax])", function (e)
    {
        e.preventDefault();

        browse($(this).attr("href"));
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
            case 4: // missing params
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
        alert(message);
    };

    var showConfirm = function (message, callback)
    {
        var success = confirm(message);

        if (success) {
            callback();
        }
    };

    var openCollection = function (db, collection) {
        peque.tree.getCollection(db, collection).find("a").click();
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
        openCollection: openCollection,
        post: post,
        get: get
    };
}();