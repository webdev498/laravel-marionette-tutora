define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'login' : laroute.route('auth.login') + '(?target={target})'
        },

        login : function (target) {
            this.controller.login({
                target: target
            });
        }

    });

});
