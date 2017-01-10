define([
	'base'
], function (Base) {

	return Base.Router.extend({

		routes: {
			'video': laroute.route('tutor.profile.show', {
				'uuid': ':id',
				'section': 'video'
			}),
			'record': laroute.route('tutor.profile.show', {
				'uuid': ':id',
				'section': 'video',
				'tab': 'record'
			})
		},

		video: function () {
			this.controller.video();
		}

	})

});