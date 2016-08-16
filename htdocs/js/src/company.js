peque.company = function ()
{
    'use strict';

    var init = function ()
    {
        $('[data-action=check-all]').on("click", function () {
            var isChecked = $(this).is(":checked");

            $('#my-companies input[data-company]').prop("checked", isChecked);
        });

        $('[data-action=work-as-manager]').on("click", function () {
            var list = [];

            $('#my-companies input[data-company]:checked').each(function () {
                if ($(this).is(":disabled")) {
                    return true;
                }
                list.push($(this).data("company"));
            });

            if (list.length < 1) {
                return false;
            }

            peque.api("user/work", {list: list}, function (data) {
                if (data.error > 0) {
                    return false;
                }
                var html = "",
                    name = "",
                    quantity,
                    $template;

                for (var item in data.result)
                {
                    if (!data.result.hasOwnProperty(item)) {
                        continue;
                    }

                    for (var quality in data.result[item])
                    {
                        if (!data.result[item].hasOwnProperty(quality)) {
                            continue;
                        }

                        $template = peque.utils.getTemplate("work-result");
                        quantity = parseFloat(data.result[item][quality]);

                        if (quality > 0) {
                            name = "Q" + quality;
                        } else {
                            name = "";
                        }

                        if (quantity > 0) {
                            $template.addClass("background-green");
                            name += " +";
                        } else {
                            $template.addClass("background-red");
                        }
                        $template.find("img").attr("src", "/img/products/" + item + ".png");
                        $template.find("[data-id=quantity]").html(name + quantity.toString());

                        html += $template[0].outerHTML;
                    }
                }

                peque.navigation.showSuccess(html);
            });
        });
    };

    var createInit = function ()
    {
        $('[data-action=show-company-options]').on("click", function () {
            var id = $(this).data("id");

            $(".company-qualities").hide();
            $('.company-qualities[data-id="' + id + '"]').show();
        });

        $('[data-action=buy-company]').on('click', function ()
        {
            var id = $(this).data("id"),
                quality = parseInt($(this).data("quality")),
                name = $(this).data("name");

            if (!id || !name || quality < 1) {
                return false;
            }

            peque.navigation.showConfirm("¿Are you sure to buy a " + name + "?", function ()
            {
                peque.api("company/create", {id: id, quality: quality}, function (data) {
                    if (data.error == 0) {
                        peque.navigation.redirect("/mycompanies");
                        return true;
                    } else if (data.error == 6) {
                        peque.navigation.showError("You dont have enough money");
                    } else {
                        peque.navigation.showError("internal error");
                    }
                });
            });
        });
    };

    return {
        init: init,
        createInit: createInit
    };
}();