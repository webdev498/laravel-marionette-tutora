define([
    'base',
    'requirejs-text!apps/quiz/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        initialize: function(options) {
            this.questions = options.questions;
            this.listenTo(this.questions, 'sync', this.onQuestionsChanged);
        },

        onQuestionsChanged: function() {
            this.fields = _.map(this.questions.models, this.getField, this);
        },

        getAnswers: function() {
            return $('form', this.$el);
        },

        getField: function(question) {
            var key = question.get('key');
            var answers = $('.js-answer', this.$el);

            return [
                key,
                function () {
                    return _.val(answers.filter('.js-'+key+':checked'));
                }
            ]
        },

        showErrors : function (errors) {
            _.each(object_undot(errors), function (errorsByViewIndex, key) {
                var region = this['questionsRegion'];

                _.each(errorsByViewIndex, function (value, index) {
                    var questionModel = this.questions.get(index);
                    var view = region.currentView.children.findByModel(questionModel);

                    if (view) {
                        var $error = view.$el.find(this.fieldErrorSelector);
                        var ans_key = view.$el.find(".radios__item--checked > input").val();

                        var error_text = null;

                        if(!_.isUndefined(ans_key))
                        {
                            var answers = questionModel.get('answers');
                            _.every(answers.models, function(answer)
                            {
                                if(answer.get('key') == ans_key)
                                {
                                    if(answer.get('if_wrong')) error_text = answer.get('if_wrong');
                                    else error_text = "Wrong answer, please try again.";
                                    return false;
                                }
                                return true;
                            });
                        }
                        else error_text = "You have not answered this question!";

                        $error.text(error_text);
                        view.$el.addClass(this.fieldParentHasErrorClassName);
                    }
                }, this);
            }, this);

            this.showSuccessFields();
            return true;
        },

        showSuccessFields: function() {
            var $emptyErrorsFields = this.$el.find(this.fieldErrorSelector).filter(':empty');

            $emptyErrorsFields.text(_.lang('validation.custom.quiz.correct_answer'));
            $emptyErrorsFields.addClass('correct');
        },

        hideErrors : function () {
            var $error = this.$el.find(this.fieldErrorSelector);

            $error.removeClass(this.fieldParentHasErrorClassName);
            $error.removeClass('correct');
            $error.text('');
        },

        template : _.template(template),

        regions : {
            'questionsRegion' : '.js-questions'
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
