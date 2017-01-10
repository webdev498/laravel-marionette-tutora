define([
    'base',
    'entities/session_vars',
    'requirejs-text!apps/jobs/create_job/templates/layout.html',
    'apps/autocomplete/subjects/controller'
], function (
    Base,
    SessionVars,
    layout,
    Subjects
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(layout),

        templateHelpers : function () {
            var sessionVars = SessionVars.current();
            var query       = sessionVars.query;

            return {
                'prevSearchSubject'  : query.get('subject'),
                'prevSearchLocation' : query.get('location')
            };
        },

        ui : {
            'first_name'        : '.js-first-name',
            'last_name'         : '.js-last-name',
            'email'             : '.js-email',
            'telephone'         : '.js-telephone',
            'subject_name'      : '.js-subject',
            'location_postcode' : '.js-location',
            'message'           : '.js-message',
            'password'          : '.js-password',
            'autocomplete'      : '.js-subjects-autocomplete'
        },

        fields : [
            'first_name',
            'last_name',
            'email',
            'telephone',
            'subject_name',
            'location_postcode',
            'message',
            'password'
        ],

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
