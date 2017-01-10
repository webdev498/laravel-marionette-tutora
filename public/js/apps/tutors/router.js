define([
    'base',
    'entities/user'
], function (
    Base,
    User
) {

    return Base.Router.extend({

        routes : {
            'pic' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'pic'
            }),
            'show' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid'
            })
        },

        initialize : function () {
            if (_.config('load') === 'tutors.edit') {
                this.controller.edit();
            }
        },

        pic : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : User.current().id
            });

            this.app.history.back(url);

            $('.js-pic .js-click').click();
        },

        show : function () {
            this.controller.show();
        }

    });

});
