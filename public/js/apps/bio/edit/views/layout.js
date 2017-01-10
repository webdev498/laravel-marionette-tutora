define([
    'base',
    'requirejs-text!apps/bio/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'profile.bio'       : '.js-bio',
            'profile.short_bio' : '.js-short-bio'
        },

        fields : [
            'profile.bio',
            [
                'profile.short_bio',
                function () {
                    var val = _.val(this.ui['profile.short_bio']);
                    return val ? val : null;
                }
            ]
        ]

    }));

});
