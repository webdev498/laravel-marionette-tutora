define([
    'base',
    'requirejs-text!apps/background_check/dbs_update/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'certificate_number' : '.js-certificate_number',
            'last_name'          : '.js-last_name',
            'dob'                : '.js-dob'
        },

        fields : [
            'certificate_number',
            'last_name',
            'dob'
        ],

        onRender : function () {
            _.datepicker(this.ui.dob, {
                labelMonthNext: 'Go to the next month',
                labelMonthPrev: 'Go to the previous month',
                labelMonthSelect: 'Pick a month from the dropdown',
                labelYearSelect: 'Pick a year from the dropdown',
                selectMonths: true,
                selectYears: 100,
                max: true
            });
        }
    }));

});
