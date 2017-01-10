define([
    'apps/tutor_account/controller',
    'apps/tutor_account/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {

        var $regionEl = $('.js-account-region');

        if ($regionEl.length !== 1) {
            return;
        }

        this.addRegions({
            'accountRegion'        : $regionEl,
            'requirementsRegion'   : '.js-requirements',
            'identificationRegion' : '.js-identification-region',
            'paymentRegion'        : '.js-payment-region'
        });

        var controller = new Controller({
            'app' : this
        });

        return new Router({
            'controller' : controller
        });
    };
});
