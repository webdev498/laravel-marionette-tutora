define([
    'base',
    'apps/qualifications/edit/views/child',
    'requirejs-text!apps/qualifications/edit/templates/university.html'
], function (
    Base,
    ChildView,
    template
) {

    return new ChildView({

        template : _.template(template),

        templateHelpers : function () {
            return {
                '_level'  : function (key) {
                    return _.lang('qualifications.university.levels.' + key)
                },
                '_levels' : _.lang('qualifications.university.levels')
            }
        },

        ui : {
            'university'     : '.js-university',
            'subject'        : '.js-subject',
            'level'          : '.js-level',
            'still_studying' : '.js-still-studying',
            'remove'         : '.js-remove'
        },

        fields : [
            'university',
            'subject',
            'level',
            'still_studying'
        ]

    });

});
