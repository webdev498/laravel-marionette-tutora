define([
    'apps/rate/controller',
    'apps/rate/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {
        var controller = new Controller({
            'app' : this
        });

        return new Router({
            'controller' : controller
        });
    };
});
