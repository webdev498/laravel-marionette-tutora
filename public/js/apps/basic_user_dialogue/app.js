define([
    'apps/basic_user_dialogue/controller',
    'apps/basic_user_dialogue/router'
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

