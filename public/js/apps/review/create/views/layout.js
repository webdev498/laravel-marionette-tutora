define([
    'base',
    'requirejs-text!apps/review/create/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--review',

        ui : {
            'rating' : '.js-rating',
            'body'   : '.js-body'
        },

        fields : [
            'body',
            [
                'rating',
                function () {
                    return this.ui.rating.filter(':checked').val();
                }
            ]
        ]

    }));

});
