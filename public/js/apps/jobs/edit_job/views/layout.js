define([
    'base',
    'entities/user',
    'entities/session_vars',
    'requirejs-text!apps/jobs/edit_job/templates/layout.html',
    'apps/autocomplete/subjects/controller'
], function (
    Base,
    User,
    SessionVars,
    template,
    Subjects
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'message'           : '.js-message',
            'subject_name'      : '.js-subject',
            'location_postcode' : '.js-location',
            'autocomplete'      : '.js-subjects-autocomplete',
            'closed_for'        : '.js-closed-for',
            'status'            : '.js-status',
            'delete'            : '.js-delete',
            'by_request'        : '.js-by_request'
        },

        events: {
            'click @ui.delete' : 'onDeleteClick'
        },

        fields : [
            'by_request',
            'message',
            'subject_name',
            'location_postcode',
            [
                'status',
                function () {
                    return _.val(this.ui.status.filter(':checked'));
                }
            ],
            [
                'closed_for',
                function () {
                    return _.val(this.ui.closed_for.filter(':checked'));
                }
            ]
        ],

        templateHelpers : function () {
            var sessionVars = SessionVars.current();
            var query       = sessionVars.query;

            return {
                'studentLink' : laroute.route('admin.students.show', {'uuid' : this.model.student.get('uuid')}),
                'prevSearchSubject'  : query.get('subject'),
                'prevSearchLocation' : query.get('location')
            };
        },

        onShow: function() {
            var el = this.ui.autocomplete;
            this.initSubjects(el);

            var closedFor = this.model.get('closed_for');
            if(closedFor) {
                this.ui.closed_for.filter('[value="'+closedFor+'"]')
                    .attr('checked', 'checked')
                    .parent().addClass('radios__item--checked');
            }

            var status = this.model.get('status');
            if(status) {
                this.ui.status.filter('[value="'+status+'"]').attr('checked', 'checked')
                    .parent().addClass('radios__item--checked');
            }
        },

        onDeleteClick: function(e) {
            e.preventDefault();
            this.trigger('job:delete');
        },

        initSubjects : function ($el, options) {
            return new Subjects(_.extend({
                '$el' : $el
            }, options));
        }

    }));

});
