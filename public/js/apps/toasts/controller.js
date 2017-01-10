define([
    'base',
    'apps/toasts/toast/controller'
], function (
    Base,
    Toast
) {

    return Base.Controller.extend({

        initialize : function (options) {
            options = _.isObject(options) ? options : {};

            return new Toast(_.extend({
                'app'    : this.app,
                'region' : this.app.toastRegion
            }, options));
        }

    });

});
