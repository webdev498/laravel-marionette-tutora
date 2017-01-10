define([
	'base',
	'underscore',
	'requirejs-text!apps/video/record_or_edit/templates/record.html',
	'ziggeo'
], function (Base,
			 _,
			 template,
			 Ziggeo) {
	var object = Base.LayoutView.extend({

		mixins: ['DialogueLayout'],

		ui: {
			'save': '.btn--save'
		},

		events: {
			'click @ui.save': 'onClickSave'
		},

		initialize: function (options) {
			this.user = options.user;
			this.uploaded_video = {};
			this.attributes = {
				profile: {
					videoStatus: null
				}
			};
			this.uploading = false;
			this.uploaded = false;
			this.recording = false;
			this.recording_stream = {};
		},

		onShow: function () {

			var vm = this;

			setTimeout(function () {
				if($.isEmptyObject(Ziggeo)){
					return false;
				}

				vm.$el.find('.btn--save').addClass('btn--disabled').removeClass('btn--save');

				Ziggeo.embedResponsive('.js-recorder', $('.js-recorder-wrapper'), 1.33, {
					limit: 180,
					key: vm.user.id,
					id: vm.user.id
				});

				Ziggeo.Events.on("uploading", function () {
					vm.uploading = true;
					vm.recording = false;
					vm.recording_stream = {};

					vm.$el.find('.cancle_buttons').css('visibility','hidden');
				}).on("submitted", function (data) {
					vm.attributes.profile.videoStatus = 'new';
					vm.uploaded = true;
					vm.uploaded_video = data.video;
					vm.$el.find('.btn--disabled').addClass('btn--save').removeClass('btn--disabled');
					vm.$el.find('.cancle_buttons').css('visibility','visible');
				});

				Ziggeo.Events.on("recording", function ( data ) {
					console.log("Recording start");
					// vm.recording = true;
					// vm.recording_stream = data;
					vm.$el.find('.cancle_buttons').css('visibility','hidden');
				}).on("finished", function (data) {
					console.log("Recording Finished");
					// vm.$el.find('.cancle_buttons').css('visibility','visible');
				});

			},100);
		},

		onClickClose: function () {
			var vm = this;
			var embedding = Ziggeo.Embed.get(vm.user.id);
			embedding.reset();
			if(vm.uploaded == true){
				Ziggeo.Videos.destroy(vm.uploaded_video.token, {
					success: function (args, result) {
						console.log("Uploaded Video - Remove Successfully");
					},
					failure: function (args, error) {
						console.log("Uploaded Video Removing - Remove Failed");
					}
				});
			}
			if(vm.recording == true){
				Ziggeo.Videos.destroy(vm.recording_stream.video.token, {
					success: function (args, result) {
						console.log("Recording Video - Remove Successfully");
					},
					failure: function (args, error) {
						console.log("Recording Video - Remove Failed");
					}
				});
			}

			this.trigger('close');
			if (this.attributes.profile.videoStatus === 'new' || this.uploading === true) {
				this.attributes.profile.videoStatus = 'canceled';
			}
		},

		onClickSave: function () {
			this.trigger('save');
		},

		template: _.template(template)

	});

	return _(object).patch();
});