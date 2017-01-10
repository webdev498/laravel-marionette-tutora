define([
    'base',
    'requirejs-text!apps/background_check/admin/dbs/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        mixins : ['ImageUploader', 'FormLayout', 'FieldsLayout'],

        ui: {
            'issued_at'             : '.js-issued_at',
            'admin_status'          : '.js-admin_status',
            'layout_rejected_for'   : '.layout__item--rejected_for',
            'rejected_for'          : '.js-rejected_for',
            'layout_reject_comment' : '.layout__item--reject_comment',
            'reject_comment'        : '.js-reject_comment'
        },

        events: {
            "change @ui.admin_status": "statusChanged",
            "change @ui.rejected_for": "rejectReasonChanged"
        },

        fields : [
            'issued_at',
            'admin_status',
            'rejected_for',
            'reject_comment'
        ],

        template : _.template(template),

        initialize: function(options) {
            this.user = options.user;
        },

        templateHelpers: function() {
            return {
                userUuid: this.user.get('uuid')
            };
        },

        onRender : function () {
            _.datepicker(this.ui.issued_at, {
                labelMonthNext: 'Go to the next month',
                labelMonthPrev: 'Go to the previous month',
                labelMonthSelect: 'Pick a month from the dropdown',
                labelYearSelect: 'Pick a year from the dropdown',
                selectMonths: true,
                selectYears: 100,
                max: true
            });

            this.updateReasonFieldVisibility(this.model.get('admin_status'));
            this.updateReasonCommentVisibility(this.model.get('rejected_for'));
        },

        statusChanged: function(event) {
            this.updateReasonFieldVisibility(this.ui.admin_status.val());
        },

        rejectReasonChanged: function(event) {
            this.updateReasonCommentVisibility(this.ui.rejected_for.val());
        },

        updateReasonFieldVisibility: function(value) {
            if(this.model.isRejectedStatus(value)) {
                this.ui.layout_rejected_for.removeClass('hidden');
                this.updateReasonCommentVisibility(this.ui.rejected_for.val());
            } else {
                this.ui.layout_rejected_for.addClass('hidden');
                this.ui.layout_reject_comment.addClass('hidden');
            }
        },

        updateReasonCommentVisibility: function(value) {
            if(this.model.isCustomRejectReason(value)) {
                this.ui.layout_reject_comment.removeClass('hidden');
            } else {
                this.ui.layout_reject_comment.addClass('hidden');
            }
        }

    }));

});
