define([
	'base',
	'underscore',
	'maps',
	'ziggeo'
], function (Base,
			 _,
			 Maps,
			 Ziggeo) {

	var object = Base.ItemView.extend({

		template: false,

		initialize: function (options) {
			this.mapSettings = options.mapSettings;
			this.model = options.model;
			this.hasVideo = options.hasVideo;
		},

		onRender: function () {
			var settings = this.mapSettings;

			if (!_.isUndefined(settings)) {
				Maps.KEY = 'AIzaSyCAGGKh7kOhjd8rYYNg546zw_6tUonOQcw';
				Maps.LANGUAGE = 'en';
				Maps.region = 'GB';

				if (settings.enableButton === true) {
					$('.u-vam.u-ml--').parent().parent().append('<a href="#' + settings.mapId + '">(show on map)</a>');
				}

				$('.profile-body').append('<div id=' + settings.mapId + ' style="height: 392px; margin-top: 2rem"></div>');
				Maps.load(function (google) {
					var map = new google.maps.Map(document.getElementById(settings.mapId), {
						zoom: 15,
						center: settings.center,
						mapTypeId: 'terrain'
					});
					new google.maps.Circle({
						strokeColor: '#12D4D2',
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: '#12D4D2',
						fillOpacity: '0.35',
						map: map,
						center: settings.center,
						radius: settings.radius
					});
				});
			}

			if (this.hasVideo === true) {
				var userId = this.model.id;
				setTimeout(function () {
					Ziggeo.embedResponsive('.js-video-player', $('.js-video-wrapper'), 1.33, {
						modes: 'player',
						video: '_' + userId
					});
				},100);
			}
		}

	});
	
	return _(object).patch();
	// return _.patch(Base.ItemView.extend({

	// 	template: false,

	// 	initialize: function (options) {
	// 		this.mapSettings = options.mapSettings;
	// 		this.model = options.model;
	// 		this.hasVideo = options.hasVideo;
	// 	},

	// 	onRender: function () {
	// 		var settings = this.mapSettings;

	// 		if (!_.isUndefined(settings)) {
	// 			Maps.KEY = 'AIzaSyCAGGKh7kOhjd8rYYNg546zw_6tUonOQcw';
	// 			Maps.LANGUAGE = 'en';
	// 			Maps.region = 'GB';

	// 			if (settings.enableButton === true) {
	// 				$('.u-vam.u-ml--').parent().parent().append('<a href="#' + settings.mapId + '">(show on map)</a>');
	// 			}

	// 			$('.profile-body').append('<div id=' + settings.mapId + ' style="height: 392px; margin-top: 2rem"></div>');
	// 			Maps.load(function (google) {
	// 				var map = new google.maps.Map(document.getElementById(settings.mapId), {
	// 					zoom: 15,
	// 					center: settings.center,
	// 					mapTypeId: 'terrain'
	// 				});
	// 				new google.maps.Circle({
	// 					strokeColor: '#12D4D2',
	// 					strokeOpacity: 0.8,
	// 					strokeWeight: 2,
	// 					fillColor: '#12D4D2',
	// 					fillOpacity: '0.35',
	// 					map: map,
	// 					center: settings.center,
	// 					radius: settings.radius
	// 				});
	// 			});
	// 		}

	// 		if (this.hasVideo === true) {
	// 			var userId = this.model.id;

	// 			Ziggeo.embedResponsive('.js-video-player', $('.js-video-wrapper'), 1.33, {
	// 				modes: 'player',
	// 				video: '_' + userId
	// 			});
	// 		}
	// 	}

	// }));

});
