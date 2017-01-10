define([
	'base',
	'underscore',
	'requirejs-text!apps/video/record_or_edit/templates/edit.html',
	'ziggeo'
], function (Base,
			 _,
			 template,
			 Ziggeo) {
	var object = Base.LayoutView.extend({

		mixins: ['DialogueLayout'],

		ui: {
			'save': '.btn--save',
			'delete': '.btn--delete'
		},

		events: {
			'click @ui.save': 'onClickSave',
			'click @ui.delete': 'onClickDelete'
		},

		initialize: function (options) {
			this.user = options.user;
			this.attributes = {
				profile: {
					videoStatus: 'edited'
				}
			};
			this.rerecorded = false;
		},

		onShow: function () {
			var vm = this;

			setTimeout(function () {
				Ziggeo.embedResponsive('.js-edit-video', $('.js-edit-wrapper'), 1.33, {
					modes: 'rerecorder',
					limit: 180,
					video: '_' + vm.user.id
				});

				Ziggeo.Events.on("submitted", function () {
					vm.rerecorded = true;
				});
			}, 100);
		},

		onClickClose: function () {
			if (this.attributes.profile.videoStatus === 'edited' && this.rerecorded === true) {
				this.attributes.profile.videoStatus = 'canceled';
			}
			this.trigger('close');
		},

		onClickSave: function () {
			this.trigger('save');
		},

		onClickDelete: function () {
			this.attributes.profile.videoStatus = 'deleted';
			this.trigger('delete');
		},

		template: _.template(template)

	});

	return _(object).patch();
	// return _.patch(Base.LayoutView.extend({

	// 	mixins: ['DialogueLayout'],

	// 	ui: {
	// 		'save': '.btn--save',
	// 		'delete': '.btn--delete'
	// 	},

	// 	events: {
	// 		'click @ui.save': 'onClickSave',
	// 		'click @ui.delete': 'onClickDelete'
	// 	},

	// 	initialize: function (options) {
	// 		this.user = options.user;
	// 		this.attributes = {
	// 			profile: {
	// 				videoStatus: 'edited'
	// 			}
	// 		};
	// 		this.rerecorded = false;
	// 	},

	// 	onShow: function () {
	// 		var vm = this;

	// 		setTimeout(function () {
	// 			Ziggeo.embedResponsive('.js-edit-video', $('.js-edit-wrapper'), 1.33, {
	// 				modes: 'rerecorder',
	// 				limit: 180,
	// 				video: '_' + vm.user.id
	// 			});
	// 		}, 100);
	// 		Ziggeo.Events.on("submitted", function () {
	// 			vm.rerecorded = true;
	// 		});
	// 	},

	// 	onClickClose: function () {
	// 		if (this.attributes.profile.videoStatus === 'edited' && this.rerecorded === true) {
	// 			this.attributes.profile.videoStatus = 'canceled';
	// 		}
	// 		this.trigger('close');
	// 	},

	// 	onClickSave: function () {
	// 		this.trigger('save');
	// 	},

	// 	onClickDelete: function () {
	// 		this.attributes.profile.videoStatus = 'deleted';
	// 		this.trigger('delete');
	// 	},

	// 	template: _.template(template)

	// }));

});