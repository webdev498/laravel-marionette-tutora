define([
    'backbone.marionette'
], function (
    Marionette
) {
    return Marionette.Controller.extend({

        constructor : function (options) {

            if (options.app) {
                this.app = options.app
            }

            if (options.region) {
                this.region = options.region;
            } else if (this.app) {
                this.region = this.app.applicationRegion
            }

            Marionette.Controller.prototype.constructor.call(this, options);
        },

        getResponseJson : function (response) {
            if (_.has(response, 'responseJSON')) {
                return response.responseJSON;
            }

            return JSON.parse(response.responseText);
        }


    });
});
