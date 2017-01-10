define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'introduction' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'quiz_intro'
            }),
            'quiz_prep' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'quiz_prep',
                'tab'     : ':tab'
            }),
            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'quiz_questions'
            })
        },

        introduction: function() {
            this.controller.introduction();
        },
        
        quiz_prep : function (uuid, tab) {
            this.controller.quiz_prep({}, uuid, tab);
        },

        edit : function () {
            this.controller.edit();
        }

    });

});
