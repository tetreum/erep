peque.market = function () {
    'use strict';

    var initStorage = function ()
    {
        var $form = $('#sell-item-form');
        $form.on("submit", function (e) {
            e.preventDefault();

            var data = {
                item : parseInt($form.find('[name=item]').val()),
                quality : parseInt($form.find('[name=item] option:selected').data("quality")),
                quantity : parseInt($form.find('[name=quantity]').val()),
                price : $form.find('[name=price]').val()
            };

            if (data.item < 1 || data.quality < 0 || data.quantity < 1 || data.price < 0.01) {
                return false;
            }

            peque.api("marketplace/sell", data, function (data) {
                if (data.error > 0) {
                    return false;
                }

                peque.navigation.showSuccess("¡Item is on sale!", {
                    callback : function () {
                        peque.navigation.reload();
                    }
                });

            });
        });
    };

    return {
        initStorage: initStorage
    };
}();