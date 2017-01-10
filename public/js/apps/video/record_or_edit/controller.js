define([
	'base',
	'entities/user',
	'apps/video/record_or_edit/views/record',
	'apps/video/record_or_edit/views/edit'
], function (Base,
			 User,
			 RecordView,
			 EditView) {

	return _.patch(Base.Controller.extend({

		mixins: ['DialogueController'],

		initialize: function (options) {
			this.region = options.region;
			this.user = User.current();
			this.view = _.indexOf(['new', 'edited'], this.user.profile.attributes.video_status) !== -1 ?
				new EditView({
					user: this.user
				}) : new RecordView({
				user: this.user
			});
			this.skipToast = false;

			this.listenTo(this.view, 'close delete', this.onCloseDelete);
			this.listenTo(this.view, 'save', this.onSave);

			this.listenTo(this.user, 'sync', this.onSyncSuccess);

			this.region.show(this.view);
		},

		onCloseDelete: function () {
			switch (this.view.attributes.profile.videoStatus) {
				case 'canceled':
					this.user.save(this.view.attributes, {
						patch: true
					});
					this.skipToast = true;
					break;
				case 'deleted':
					this.user.save(this.view.attributes, {
						patch: true
					});
					_.progress().start();
					break;
				default:
					this.destroy();
			}
		},

		onSave: function () {
			this.user.save(this.view.attributes, {
				patch: true
			});
			_.progress().start();
		},

		onSyncSuccess: function () {
			_.progress().done();
			if (this.skipToast === false) {
				_.toast('Saved!', 'success');
			}
			this.destroy();
		},

		onDestroy: function () {
			var url = laroute.route('tutor.profile.show', {
				'uuid': this.user.id
			});

			this.app.history.back(url);
		}

	}));

});