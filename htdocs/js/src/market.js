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

    var initResultsList = function () {
        $('[data-action="buy-item"]').on("click", function () {
            var id = $(this).data("id"),
                amount = parseInt($('#offer-' + id + ' [name=quantity]').val());

            if (amount < 1 || id < 1) {
                return false;
            }

            peque.api("marketplace/buy", {id: id, amount: amount}, function (data) {
                if (data.error > 0) {
                    return false;
                }

                peque.navigation.showSuccess("");
            });
        });
    };

    var initHome = function (productList)
    {
        $('[data-action="show-product-catalogue"]').on("click", function ()
        {
            var id = $(this).data("id"),
                product = productList[id - 1];

            if (product.type == 1) { // raw material
                peque.navigation.redirect("/marketplace/" + id + "/1");
                return true;
            }

            var i = 1,
                $template,
                $catalogueContainer = $('#product-catalogue ul');

            $catalogueContainer.html("");

            while (i <= 4)
            {
                $template = peque.utils.getTemplate("item-quality");

                $template.find("a").attr("href", $template.find("a").attr("href") + id + "/" + i);
                $template.find("img").attr("src", "/img/products/"  + id + ".png");
                $template.find("span").text(product.name + " Q" + i);

                $catalogueContainer.append($template);
                i++;
            }
            $('#product-catalogue').show();
        });
    };

    return {
        initStorage: initStorage,
        initHome: initHome,
        initResultsList: initResultsList
    };
}();