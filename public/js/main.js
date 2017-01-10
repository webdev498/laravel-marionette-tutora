require.config({
	priority: ["libraries", "plugins"],
	paths: {
		'autocomplete': '../vendor/devbridge-autocomplete/dist/jquery.autocomplete',
		'backbone': '../vendor/backbone/backbone',
		'backbone.babysitter': '../vendor/backbone.babysitter/lib/backbone.babysitter',
		'backbone.marionette': '../vendor/marionette/lib/core/backbone.marionette',
		'backbone.wreqr': '../vendor/backbone.wreqr/lib/backbone.wreqr',
		'backbone.validation': '../vendor/backbone-validation/dist/backbone-validation-amd',
		'cocktail': '../vendor/cocktail/Cocktail',
		'dropzone': '../vendor/dropzone/dist/dropzone-amd-module',
		'laroute': 'laroute',
		'jquery': '../vendor/jquery/dist/jquery',
		'nprogress': '../vendor/nprogress/nprogress',
		'requirejs-text': '../vendor/requirejs-text/text',
		'picker': '../vendor/pickadate/lib/picker',
		'picker.date': '../vendor/pickadate/lib/picker.date',
		'pusher': '../vendor/pusher/dist/pusher.min',
		'underscore': '../vendor/underscore/underscore',
		'underscore.string': 'underscore.string.min',
		'tipped': '../vendor/tipped/js/tipped',
		'mailcheck': '../vendor/mailcheck/src/mailcheck.min',
		'maps': '../vendor/google-maps/lib/Google.min',
		'ziggeo-stable': '../vendor/ziggeo/ziggeo-latest',
		// 'ziggeo-stable': '../vendor/ziggeo/ziggeo',
		'ziggeo': '../vendor/ziggeo/ziggeo-wrapper'
	},

	shim: {
		'underscore': {
			'deps': ['laroute']
		},
		'backbone': {
			'deps': ['jquery', 'underscore', 'underscore.string', 'picker', 'picker.date']
		},
		'backbone.validation': {
			'deps': ['backbone']
		},
		'backbone.marionette': {
			'deps': ['backbone.validation']
		},
		'autocomplete': {
			'deps': ['jquery']
		},
        'ziggeo': {
            // 'deps': ['ziggeo-stable']
        }
	}
});

require([
	'app',
	'ui'
], function (App) {
	window.App = App;

	window.App.start();
});
