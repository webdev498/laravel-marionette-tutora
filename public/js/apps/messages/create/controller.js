define([
	'base',
	'events',
	'entities/message',
	'entities/user',
	'apps/messages/create/views/layout',
	'apps/autocomplete/subjects/controller'
], function (Base,
			 Event,
			 Message,
			 User,
			 LayoutView,
			 Subjects) {

	return _.patch(Base.Controller.extend({

		mixins: ['FormController', 'DialogueController'],

		initialize: function (options) {
			this.region = options.region;
			this.message = new Message.model();
			this.view = new LayoutView({
				'model': this.message
			});

			this.listenToView(this.view);
			this.listenToMessage(this.message);

			this.region.show(this.view);
		},

		listenToView: function (view) {
			if (!view._listenId || !this._listeningTo[message._listenId]) {
				this.listenTo(this.view, 'form:submit', this.onFormSubmit);
				this.listenTo(this.view, 'destroy', this.destroy);
				this.listenTo(this.view, 'show', this.onShow);
			}
		},

		listenToMessage: function (message) {
			if (!message._listenId || !this._listeningTo[message._listenId]) {
				this.listenTo(message, 'sync error invalid', this.onSyncErrorOrInvalid);
				this.listenTo(message, 'sync', this.onSyncSuccess);
				this.listenTo(message, 'error', this.onSyncError);
				this.listenTo(message, 'invalid', this.onInvalid);
			}
		},

		onShow: function () {
			var el = $('.js-subjects-autocomplete');
			this.initSubjects(el);
		},

		initSubjects: function ($el, options) {
			return new Subjects(_.extend({
				'$el': $el
			}, options));
		},

		save: function (attributes) {
			this.listenToMessage(this.message);

			var user = User.current();

			attributes = object_undot(_.extend({
				'to': this.options.uuid,
			}, attributes));

			this.message.save(attributes);
		},

		onSyncSuccess: function (message, json) {
			if (_.has(json.meta, 'redirect') && _.has(json, 'email')
				&& json.meta.redirect === true) {
				this.view.prepareAlreadyExistingStudent(json.email);
				return;
			}

			if (_.config('live') === true && !_.isUndefined(window.ga)) {
				ga('send', 'event', 'message', 'send', 'new_enquiry', 10);

				try{
				  __adroll.record_user({"adroll_segments": "754c01f2"})
				} catch(err) {}


				window.setTimeout(function () {
					window.location = json.meta.redirect;
				}, 250);
			} else {
				window.location = json.meta.redirect;
			}
		},

		onDestroy: function () {
			var url = laroute.route('tutor.profile.show', {'uuid': this.options.uuid});
			this.app.history.back(url);
		}

	}));

});
