define([
    'apps/review/controller',
    'apps/review/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {
        var controller = new Controller({
            'app' : this
        });

        this.addRegions({
            'listRegion'       : '.list-region'
        });

        return new Router({
            'controller' : controller
        });
    };
});
