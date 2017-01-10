define([
    'apps/user_block/controller',
    'apps/user_block/router'
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
