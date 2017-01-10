define([
    'base',
    'entities/user',
    'requirejs-text!apps/booking/cancel/templates/layout.html'
], function (
    Base,
    User,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--booking',

        ui : {
            'future' : '.js-future'
        },

        fields : [
            [
                'future',
                function () {
                    return this.ui.future.length > 0
                        ? _.val(this.ui.future)
                        : false;
                }
            ]
        ],

        templateHelpers : function () {
            var user = User.current();

            var shouldBePartiallyCharged = this.model.get('partial_charge');
            var isByStudent              = user.get('account') === 'student';

            return {
                'shouldBePartiallyCharged' : shouldBePartiallyCharged && isByStudent
            };
        }

    }));

});
