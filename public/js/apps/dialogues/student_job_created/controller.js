define([
    'base',
    'events',
    'entities/user',
    'entities/basic_user_dialogue',
    'entities/user_dialogue_interaction',
    'apps/dialogues/student_job_created/views/layout'
], function (
    Base,
    Event,
    User,
    UserDialogue,
    UserDialogueInteraction,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['DialogueController'],

        initialize : function (options) {
            
            this.region = options.region;
            this.user   = User.current();

            this.dialogueName = 'student_job_created';
            this.dialogue = new UserDialogue({
                name: this.dialogueName
            });

            this.view   = new LayoutView({
                'model' : this.dialogue
            });

            this.listenTo(this.view, 'show', this.recordInteraction);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.listenToOnce(this.dialogue, 'sync', this.showView);
            this.dialogue.fetch();
        },

        showView: function() {
            if(this.dialogue.get('isViewed')) {
                this.destroy();
                return;
            }
            this.region.show(this.view);
        },

        recordInteraction: function() {
            this.timestamp = Date.now();
            this.interaction = new UserDialogueInteraction({"uuid": this.user.id, "name": this.dialogueName});
            this.interaction.save();
        },

        onFormSubmit: function() {
            this.view.destroy();

            if(this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp}, {patch: true});

            var url = laroute.route('student.dashboard.index');

            this.app.history.navigate(url, {trigger: true});
        },

        onDestroy : function () {
            var url = laroute.route('student.dashboard.index');

            this.app.history.navigate(url, {trigger: true});
        }

    }));

});
