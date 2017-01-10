define([
    'base',
    'requirejs-text!apps/review/select_tutor/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--review--choose-tutor',

        templateHelpers: function() {
            return { tutors: this.collection.toJSON() };
        }

    }));

});
