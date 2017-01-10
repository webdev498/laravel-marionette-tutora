define([
    'base',
    'entities/user',
    'entities/quiz_prep',
    'requirejs-text!apps/quiz/prep/templates/layout.html'
], function (
    Base,
    User,
    QuizPrep,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {

            var user = User.current();

            var prep_item = window.quiz_prep_items.models[this.options.tab - 1];

            return {
                'user' : user.toJSON(),
                'title' : prep_item.get('title'),
                'text' : prep_item.get('text')
            };
        },

        events:
        {
            'click .btn--back':'back'
        },

        back: function() {
            this.trigger("back");
        }

    }));

});
