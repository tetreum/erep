peque.congress = function ()
{
    'use strict';

    var TYPE_NATURAL_ENEMY = 1;
    var TYPE_MUTUAL_PROTECTION_PACT = 2;
    var TYPE_WORK_TAX = 3;
    var TYPE_MANAGER_TAX = 4;
    var TYPE_IMPEACHMENT = 5;
    var TYPE_TRANSFER_FUNDS = 6;
    var TYPE_CEASE_FIRE = 7;

    var init = function ()
    {
        $('[data-action=resign]').on("click", function ()
        {
            peque.navigation.showConfirm("", function ()
            {
                peque.api("congress/resign", function (data) {
                    if (data.error > 0) {
                        return false;
                    }

                    peque.navigation.reload();
                });
            });
        });

        var $newLawForm = $('#propose-law');

        $newLawForm.on("submit", function (e)
        {
            e.preventDefault();

            var params = {
                    type: parseInt($("option:selected", $newLawForm).val()),
                    reason: $newLawForm.find("input[name=reason]").val()
                };

            if (params.type < 1 || params.reason.length < 3) {
                return false;
            }

            switch (params.type)
            {
                case TYPE_NATURAL_ENEMY:
                case TYPE_MUTUAL_PROTECTION_PACT:
                case TYPE_CEASE_FIRE:
                    params.country = $newLawForm.find("select[name=country] option:selected").val();
                    break;
                case TYPE_WORK_TAX:
                case TYPE_MANAGER_TAX:
                    params.amount = $newLawForm.find("input[name=amount]").val();
                    break;
                case TYPE_TRANSFER_FUNDS:
                    params.amount = $newLawForm.find("input[name=amount]").val();
                    params.amount = $newLawForm.find("select[name=currency] option:selected").val();
                    break;
                case TYPE_IMPEACHMENT:
                    break;
            }

            peque.navigation.showConfirm("", function ()
            {
                peque.api("congress/law/propose", params, function (data) {
                    if (data.error > 0) {
                        return false;
                    }
                });

                peque.navigation.redirect("law/" + data.result);
            });
        });

        $newLawForm.find("select[name=type]").on("change", function ()
        {
            var type = parseInt($("option:selected", this).val()),
                $country = $newLawForm.find("select[name=country]"),
                $amount = $newLawForm.find('[data-id="amount-label"]'),
                $currency = $newLawForm.find("select[name=currency]");

            // start hiding everything and the show the required fields of each case
            $country.hide();
            $amount.hide();
            $currency.hide();

            switch (type)
            {
                case TYPE_NATURAL_ENEMY:
                case TYPE_MUTUAL_PROTECTION_PACT:
                case TYPE_CEASE_FIRE:
                    $country.show();
                    break;
                case TYPE_WORK_TAX:
                case TYPE_MANAGER_TAX:
                    $amount.show();
                    break;
                case TYPE_TRANSFER_FUNDS:
                    $amount.show();
                    $currency.show();
                    break;
                case TYPE_IMPEACHMENT:
                    break;
            }
        });
    };

    return {
        init: init
    };
}();