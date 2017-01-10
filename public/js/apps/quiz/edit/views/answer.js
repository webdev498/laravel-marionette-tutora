define([
    'base',
    'requirejs-text!apps/quiz/edit/templates/answer.html'
], function (
    Base,
    template
) {

    return Base.ItemView.extend({

        initialize: function(options) {
            this.model.set('parentKey', options.parentKey);
        },

        template : _.template(template),

        templateHelpers: function()
        {
            var checked = false;
            var question_key = this.options.parentKey;
            var answer_key = this.model.get('key');

            if(_.has(window, 'quiz_answers'))
                _.each(window.quiz_answers, function(question)
                {
                    if(question.name == question_key + '-answer' && question.value == answer_key)
                        checked = true;
                });

            return { 'checked' : checked };
        }
    });
});
