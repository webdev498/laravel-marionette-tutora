define([
    'apps/quiz/controller',
    'apps/quiz/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function ()
    {
        var controller = new Controller
        ({
            'app' : this
        });
        return new Router
        ({
            'controller' : controller
        });
    };
});
