define([
    'apps/review_notification/controller',
    'apps/review_notification/router'
], function (
    Controller,
    Router
) {

    return /*App.addInitializer*/ function ()
    {
        var controller = new Controller({
            'app' : this
        });
        
        return new Router
        ({
            'controller' : controller
        });
    };
});

