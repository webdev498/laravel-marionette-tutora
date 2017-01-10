define([
    'base',
    'events',
    'entities/user',
    'entities/quiz',
    'entities/quiz_prep',
    'apps/quiz/edit/views/layout',
    'entities/user_dialogue_interaction',
    'apps/quiz/edit/views/questions'
], function (
    Base,
    Event,
    User,
    Quiz,
    QuizPrep,
    LayoutView,
    UserDialogueInteraction,
    QuestionsView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            
            this.region = options.region;
            this.user   = User.current();
            this.questions = Quiz.collection();
            this.quiz = Quiz.model();

            this.view     = new LayoutView({
                questions: this.questions
            });

            this.listenTo(this.view, 'back', this.onFormBack);

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user.quiz, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user.quiz, 'sync', this.onSyncSuccess);
            this.listenTo(this.user.quiz, 'error', this.onSyncError);
            this.listenTo(this.user.quiz, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        onShow: function() {
            _.progress().start();

            this.listenToOnce(this.questions, 'sync', function () {
                _.progress().done();
            });

            this.questions.fetch();

            this.renderQuestionList();
            this.recordInteraction();
        },

        recordInteraction: function()
        {
            this.timestamp = Date.now();
            this.interaction = new UserDialogueInteraction({"uuid": this.user.id, "name": "quiz_questions", "data": "incomplete"});
            this.interaction.save();
        },
        
        renderQuestionList: function() {
            var view = new QuestionsView({
                'collection' : this.questions
            });

            this.view.questionsRegion.show(view);
        },

        save : function (answers)
        {
            Event.trigger('requirement:pending', {
                'section' : 'quiz',
                'name'    : 'questions'
            });

            this.user.quiz.save({answers: answers});
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Thank you for the completing of our quiz!', 'success');

            if(this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp, data: "complete"}, {patch: true});

            delete window.quiz_answers;
            
            this.view.destroy();

            var url = laroute.route('tutor.profile.show',
                {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'review_notification'
                });

            this.app.history.navigate(url, {trigger: true});

        },

        onSyncError : function () {
            Event.trigger('requirement:incompleted', {
                'section' : 'quiz',
                'name'    : 'questions'
            });
        },

        onDestroy : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.app.history.navigate(url, {trigger: true});
        },

        onFormBack: function()
        {
            if(this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp, data: "back"}, {patch: true});

            window.quiz_answers = this.view.getAnswers().serializeArray();

            this.view.destroy();

            if(!_.has(window, 'quiz_prep_items')) {
                window.quiz_prep_items = QuizPrep.collection();
                window.quiz_prep_items.fetch();

                this.listenToOnce(window.quiz_prep_items, 'sync', this.navigateBack);
            } else {
                this.navigateBack();
            }
        },

        navigateBack: function() {
            var tab_total = window.quiz_prep_items.length;

            var url = laroute.route('tutor.profile.show',
                {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'quiz_prep',
                    'tab'     : tab_total
                });

            this.app.history.navigate(url, {trigger: true});
        }

    }));

});
