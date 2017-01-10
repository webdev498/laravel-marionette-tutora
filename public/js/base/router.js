define([
    'backbone.marionette'
], function (
    Marionette
) {
    return Marionette.AppRouter.extend({

        constructor: function(options) {
            if (options && options.controller) {
                this.controller = options.controller;

                if (this.controller.app) {
                    this.app = this.controller.app;
                    this.app.router = this;
                }
            }

            // Trim slashes off urls
            _.each(this.routes, function (url, method, list) {
                url = _.trim(url, '/');
                url = url.replace(/\{([^\}]+)\}/g, ':$1');

                list[method] = url;
            });

            // Invert the routes, since Marionette uses routes { url : method }        
            this.routes    = _.invert(this.routes);
            this.appRoutes = _.invert(this.appRoutes);

            Marionette.AppRouter.prototype.constructor.call(this, options);
        }

    });
});
