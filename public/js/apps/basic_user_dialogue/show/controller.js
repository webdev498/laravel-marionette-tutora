define([
	'base',
	'events',
	'entities/user',
	'entities/basic_user_dialogue',
	'entities/user_dialogue_interaction',
	'apps/basic_user_dialogue/show/views/layout'
], function (Base,
			 Event,
			 User,
			 BasicUserDialogue,
			 UserDialogueInteraction,
			 LayoutView) {

	return _.patch(Base.Controller.extend({

		mixins: ['DialogueController'],

		initialize: function (options) {
			this.region = options.region;

			this.user = User.current();
			this.dialogue_route_name = options.dialogue_route_name;
			this.return_route = options.return_route;
			this.dialogue = new BasicUserDialogue({name: this.dialogue_route_name});

			this.view = new LayoutView({
				'model': this.dialogue,
				'name': this.dialogue_route_name,
				'user': this.user
			});

			this.listenToOnce(this.dialogue, 'sync', this.showRegionView);

			this.dialogue.fetch();

		},

		showRegionView: function () {

			this.listenTo(this.view, 'show', this.recordInteraction);
			this.listenTo(this.view, 'form:submit', this.onFormSubmit);
			this.listenTo(this.view, 'destroy', this.destroy);

			this.region.show(this.view);
		},

		recordInteraction: function () {
			this.timestamp = Date.now();
			this.interaction = new UserDialogueInteraction({"uuid": this.user.id, "name": this.dialogue_route_name});
			this.interaction.save();
		},

		onFormSubmit: function () {
			if (this.interaction && this.timestamp) this.interaction.save({duration: Date.now() - this.timestamp}, {patch: true});

			this.view.destroy();
		},

		onDestroy: function () {
			if (this.return_route) {
				this.app.history.navigate(this.return_route, {trigger: true});
			}
		}
	}));

});
