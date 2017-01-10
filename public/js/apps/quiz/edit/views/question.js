define([
    'base',
    'apps/quiz/edit/views/answer',
    'requirejs-text!apps/quiz/edit/templates/question.html'
], function (
    Base,
    ChildView,
    template
) {

    return Base.CompositeView.extend({

        initialize: function() {
            this.collection = this.model.get('answers');
        },

        childViewContainer: '.radios',

        template : _.template(template),

        childView : ChildView,

        childViewOptions: function() {
            return {
                parentKey: this.model.get('key')
            };
        }

    });

});
