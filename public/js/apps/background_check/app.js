define([
    'apps/background_check/controller',
    'apps/background_check/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {
        var controller = new Controller({
            'app' : this
        });

        this.addRegions({
            'dbsRegion'       : '.js-dbs-region',
            'dbsUpdateRegion' : '.js-dbs-update-region'
        });

        return new Router({
            'controller' : controller
        });
    };
});
