peque.api = function (path, params, callback)
{
    'use strict';

    peque.navigation.showLoader();
    var method = "post";

    // check if this request doesnt need params
    if (typeof params == "function") {
        callback = params;
        params = {};
        method = "get";
    }

    $[method]("/api/" + path, params, function (data)
    {
        peque.navigation.hideLoader();

        if (data == "" || typeof data != "object") {
            peque.navigation.showError("internal error :(");
            return false;
        }
        else if (data.error > 0)
        {
            peque.navigation.parseError(data, callback);
        } else {
            callback(data);
        }
    });
};