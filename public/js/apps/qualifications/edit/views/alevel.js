define([
    'base',
    'apps/qualifications/edit/views/child',
    'requirejs-text!apps/qualifications/edit/templates/alevel.html'
], function (
    Base,
    ChildView,
    template
) {

    return new ChildView({

        template : _.template(template),

        templateHelpers : function () {
            return {
                '_grade'  : function (key) {
                    return _.lang('qualifications.alevel.grades.' + key)
                },
                '_grades' : _.lang('qualifications.alevel.grades')
            }
        },

        ui : {
            'college'        : '.js-college',
            'subject'        : '.js-subject',
            'grade'          : '.js-grade',
            'still_studying' : '.js-still-studying',
            'remove'         : '.js-remove'
        },

        fields : [
            'college',
            'subject',
            'grade',
            'still_studying'
        ]

    });

});
