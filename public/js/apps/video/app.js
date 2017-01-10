define([
	'apps/video/controller',
	'apps/video/router'
], function (Controller,
			 Router) {

	return function () {
		var controller = new Controller({
			'app': this
		});

		return new Router({
			'controller': controller
		});
	}

});