peque.utils = function ()
{
    'use strict';

    var getTemplate = function (name) {
        return $("<div/>").html($("#tpl-" + name).html()).children();
    };

    return {
        getTemplate: getTemplate
    };
}();
