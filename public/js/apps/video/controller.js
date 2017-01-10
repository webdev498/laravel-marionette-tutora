define([
	'base',
	'apps/video/record_or_edit/controller',
], function (Base,
			 VideoController) {

	return Base.Controller.extend({

		video: function (options) {
			options = _.isObject(options) ? options : {};

			return new VideoController(_.extend({
				'app': this.app,
				'region': this.app.dialogueRegion
			}, options));
		}

	});

});