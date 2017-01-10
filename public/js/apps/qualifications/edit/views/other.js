define([
    'base',
    'apps/qualifications/edit/views/child',
    'requirejs-text!apps/qualifications/edit/templates/other.html'
], function (
    Base,
    ChildView,
    template
) {

    return new ChildView({

        template : _.template(template),

        ui : {
            'location'     : '.js-location',
            'subject'        : '.js-subject',
            'grade'          : '.js-grade',
            'still_studying' : '.js-still-studying',
            'remove'         : '.js-remove'
        },

        fields : [
            'location',
            'subject',
            'grade',
            'still_studying'
        ]

    });

});
