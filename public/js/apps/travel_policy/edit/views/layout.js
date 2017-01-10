define([
    'base',
    'requirejs-text!apps/travel_policy/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            return {
                'policies' : _.lang('user_profiles.travel_radius')
            };
        },

        ui : {
            'profile.travel_radius'      : '.js-travel-radius',
            'addresses.default.line_1'   : '.js-address-line-1',
            'addresses.default.line_2'   : '.js-address-line-2',
            'addresses.default.line_3'   : '.js-address-line-3',
            'addresses.default.postcode' : '.js-address-postcode'
        },

        fields : [
            'profile.travel_radius',
            'addresses.default.line_1',
            'addresses.default.line_2',
            'addresses.default.line_3',
            'addresses.default.postcode'
        ]

    }));

});
