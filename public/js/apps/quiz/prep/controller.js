define([
    'base',
    'events',
    'entities/user',
    'entities/quiz_prep',
    'entities/user_dialogue_interaction',
    'apps/quiz/prep/views/layout'
], function (
    Base,
    Event,
    User,
    QuizPrep,
    UserDialogueInteraction,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['DialogueController'],

        initialize : function (options) {

            this.region = options.region;
            this.user   = User.current();
            this.tab   = options.tab;

            this.view   = new LayoutView({
                'model' : this.user,
                'tab' : this.tab
            });

            this.requiredSyncedDepsCount = 2;
            this.syncedDepsCount = 0;

            this.recordInteraction();
            this.fetchQuizPrep();
        },

        showRegionView: function() {
            if(this.syncedDepsCount < this.requiredSyncedDepsCount) {return;}

            this.listenTo(this.view, 'back', this.onFormBack);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.region.show(this.view);
        },

        fetchQuizPrep: function() {
            if(!_.has(window, 'quiz_prep_items')) {
                window.quiz_prep_items = QuizPrep.collection();
                window.quiz_prep_items.fetch();

                this.listenToOnce(window.quiz_prep_items, 'sync', this.dependencySynced);
            } else {
                this.dependencySynced();
            }
        },

        recordInteraction: function() {
            this.timestamp = Date.now();
            this.interaction = new UserDialogueInteraction({"uuid": this.user.id, "name": "quiz_prep", "data": this.tab});
            this.interaction.save();

            this.listenToOnce(this.interaction, 'sync', this.dependencySynced);
        },

        dependencySynced: function() {
            this.syncedDepsCount++;

            this.showRegionView();
        },

        onFormBack: function() {

            if(this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp}, {patch: true});

            this.view.destroy();

            var url = null;
            var tab = parseInt(this.tab);
            if(isNaN(tab) || tab <= 1)
            {
                url = laroute.route('tutor.profile.show', {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'quiz_intro'
                });
            }
            else
            {
                url = laroute.route('tutor.profile.show', {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'quiz_prep',
                    'tab'     : (tab - 1)
                });
            }

            this.app.history.navigate(url, {trigger: true});
        },

        onFormSubmit: function() {
            
            if(this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp}, {patch: true});

            this.view.destroy();
            
            var tab_total = window.quiz_prep_items.length;
            var tab = parseInt(this.tab);
            var url = null;

            if(isNaN(tab) || tab >= tab_total)
            {
                url = laroute.route('tutor.profile.show', {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'quiz_questions'
                });
            }
            else
            {
                url = laroute.route('tutor.profile.show', {
                    'uuid'    : this.user.get('uuid'),
                    'section' : 'quiz_prep',
                    'tab'     : (tab + 1)
                });
            }

            this.app.history.navigate(url, {trigger: true});
        },

        onDestroy : function () {
            
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.app.history.back(url);
        }

    }));

});
