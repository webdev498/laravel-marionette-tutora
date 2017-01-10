define([
    'backbone.marionette'
], function (
    Marionette
) {

    return Marionette.Controller.extend({

        routers : [],

        addRouters : function(routers) {
            _.each(routers, function (router, name) {
                this.addRouter(router);
            }, this);
        },

        addRouter : function (router) {
            this.routers.push(router);
        }

    });
});