define([
    'base',
    'entities/user',
    'entities/session_vars',
    'requirejs-text!apps/jobs/create_student_job/templates/layout.html',
    'apps/autocomplete/subjects/controller'
], function (
    Base,
    User,
    SessionVars,
    template,
    Subjects
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'message'           : '.js-message',
            'subject_name'      : '.js-subject',
            'location_postcode' : '.js-location',
            'autocomplete'      : '.js-subjects-autocomplete'
        },

        fields : [
            'message',
            'subject_name',
            'location_postcode'
        ],

        templateHelpers : function () {
            var sessionVars = SessionVars.current();
            var query       = sessionVars.query;

            return {
                'prevSearchSubject'  : query.get('subject'),
                'prevSearchLocation' : query.get('location')
            };
        },

        onShow: function() {
            var el = this.ui.autocomplete;
            this.initSubjects(el);
        },

        initSubjects : function ($el, options) {
            return new Subjects(_.extend({
                '$el' : $el
            }, options));
        }

    }));

});
