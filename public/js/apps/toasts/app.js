define([
    'apps/toasts/controller'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {
        new Controller({
            'app' : this
        });
    };
});
