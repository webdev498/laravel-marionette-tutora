define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'index' : '/search/*anything'
        },

        index : function () {
            this.controller.index();
        }

    });

});
