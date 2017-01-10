define([
    'base',
    'apps/auth/login/controller'
], function (
    Base,
    Login
) {

    return Base.Controller.extend({

        login : function (options) {
            options = _.isObject(options) ? options : {};

            return new Login(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
