define([
    'base',
    'requirejs-text!apps/background_check/admin/dbs_update/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'issued_at'           : '.js-issued_at',
            'admin_status'        : '.js-admin_status',
            'certificate_number'  : '.js-certificate_number',
            'last_name'           : '.js-last_name',
            'dob'                 : '.js-dob',
            'layout_rejected_for' : '.layout__item--rejected_for',
            'rejected_for'        : '.js-rejected_for'
        },

        events: {
            "change @ui.admin_status": "statusChanged",
            "change @ui.rejected_for": "rejectReasonChanged"
        },

        fields : [
            'issued_at',
            'admin_status',
            'certificate_number',
            'last_name',
            'dob',
            'rejected_for'
        ],

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
            _.datepicker(this.ui.dob, {
                labelMonthNext: 'Go to the next month',
                labelMonthPrev: 'Go to the previous month',
                labelMonthSelect: 'Pick a month from the dropdown',
                labelYearSelect: 'Pick a year from the dropdown',
                selectMonths: true,
                selectYears: 100,
                max: true
            });

            this.updateReasonFieldVisibility(this.model.get('admin_status'));
        },

        statusChanged: function(event) {
            this.updateReasonFieldVisibility(this.ui.admin_status.val());
        },

        updateReasonFieldVisibility: function(value) {
            if(this.model.isRejectedStatus(value)) {
                this.ui.layout_rejected_for.removeClass('hidden');
            } else {
                this.ui.layout_rejected_for.addClass('hidden');
            }
        }

    }));

});
