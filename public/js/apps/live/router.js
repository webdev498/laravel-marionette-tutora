define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'create' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'review'
            }),

            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'go-live'
            }),

            'destroy' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'go-offline'
            }),
        },

        initialize : function () {
            var $el = $('.js-live-region');

            if ($el.length > 0) {
                this.app.addRegions({
                    'liveRegion' : '.js-live-region'
                });

                this.show();
            }
        },

        show : function () {
            this.controller.show();
        },

        create : function () {
            this.controller.create();
        },

        edit : function () {
            this.controller.edit();
        },

        destroy : function () {
            this.controller.destroy();
        }

    });

});
