define([
    'base',
    'requirejs-text!apps/booking/refund/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--booking',

        template : _.template(template),

        ui : {
            'reverse_transfer'   : '.js-reverse_transfer',
            'amount'             : '.js-amount',
        },

        fields : [
            [
                'reverse_transfer',
                function () {
                    return this.ui.reverse_transfer.length > 0
                        ? _.val(this.ui.reverse_transfer)
                        : false;
                }
            ],
            'amount',
        ],

    }));

});
