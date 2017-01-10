define([
	'base',
	'entities/user',
	'apps/tutors/show/views/layout'
], function (Base,
			 User,
			 View) {

	return Base.Controller.extend({

		initialize: function () {
			var preloadedMapSettings = _.config('tutorMapSettings');

			if (!_.isNull(preloadedMapSettings)) {
				preloadedMapSettings.center.lat = parseFloat(preloadedMapSettings.center.lat);
				preloadedMapSettings.center.lng = parseFloat(preloadedMapSettings.center.lng);

				this.mapSettings = _.extend(preloadedMapSettings, {
					radius: 200,
					mapId: 'tutor-map',
					enableButton: true
				});
			}

			this.user = User.current();

			this.renderView();
		},

		renderView: function () {
			this.view = new View({
				mapSettings: this.mapSettings,
				model: this.user,
				hasVideo: this.user.profile.attributes.video_status === 'new' || this.user.profile.attributes.video_status === 'edited'
			});

			this.view.render();
		}

	});

});
