define([
	'base',
	'requirejs-text!apps/tutors/edit/templates/video.html',
	'ziggeo'
], function (Base,
			 template,
			 Ziggeo) {

	return Base.ItemView.extend({

		template: _.template(template),

		initialize: function () {
			this.listenTo(this.model, 'sync', this.render);
		},

		onRender: function () {
			if (_.indexOf(['new', 'edited'], this.model.profile.attributes.video_status) !== -1) {
				Ziggeo.embedResponsive('.js-video-player', $('.js-video-wrapper'), 1.33, {
					video: '_' + this.model.id,
					modes: 'player',
				});
			}
			else {
				$('.js-video-wrapper').remove();
				$('.js-video-button').html('Record a Video');
				$('.icon--video--large').parent().remove();
				$('.requirements__title:contains("Record")').removeClass('requirements__title--completed');
			}
		}

	})

})