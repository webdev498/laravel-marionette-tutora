define([
    'apps/student_account/controller',
    'apps/student_account/router'
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
            'accountRegion' : $regionEl
        });

        var controller = new Controller({
            'app' : this
        });

        return new Router({
            'controller' : controller
        });
    };
});
