define([
	'base',
	'entities/user',
	'entities/session_vars',
	'requirejs-text!apps/messages/create/templates/layout.html',
	'mailcheck',
	'tipped'
], function (Base,
			 User,
			 SessionVars,
			 template,
			 MailCheck,
			 Tipped) {

	return _.patch(Base.LayoutView.extend({

		mixins: ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

		template: _.template(template),

		ui: {
			'body': '.js-body',
			'subject_name': '.js-subject',
			'location_postcode': '.js-location',
			'action': '.js-action',
			'create_job': '.js-create-job',

			'register_button': '.js-register-button',
			'become_tutor_button': '.js-become-tutor-button',
			'register_form': '.js-register-form',
			'register.first_name': '.js-register-first-name',
			'register.last_name': '.js-register-last-name',
			'register.email': '.js-register-email',
			'register.telephone': '.js-register-telephone',
			'register.password': '.js-register-password',
			'register.create_job': '.js-register-create-job',

			'login_button': '.js-login-button',
			'login_form': '.js-login-form',
			'login.email': '.js-login-email',
			'login.password': '.js-login-password',
			'login.remember_me': '.js-login-remember-me',
			'login.create_job': '.js-login-create-job'
		},

		fields: [
			'create_job',
			'body',
			'subject_name',
			'location_postcode',
			'register.first_name',
			'register.last_name',
			'register.email',
			'register.telephone',
			'register.password',
			'register.create_job',
			'login.email',
			'login.password',
			'login.remember_me',
			'login.create_job'
		],

		events: {
			'click @ui.login_button': 'showLoginForm',
			'click @ui.register_button': 'showRegistrationForm',
			'blur .js-register-email': 'showSuggestion'
		},

		initialize: function () {
			var user = User.current();
			var sessionVars = SessionVars.current();
			var query = sessionVars.query;

			this.model.set('prevSearchSubject', query.get('subject'));
			this.model.set('prevSearchLocation', query.get('location'));

			if (user.isNew()) {
				this.model.set({'action': 'register'});
			} else {
				this.model.set({'action': 'message'});
			}
		},

		showLoginForm: function (e) {
			if (e && e.prevenDefault) e.prevenDefault();

			this.ui.login_form.css('display', 'block');
			this.ui.register_form.css('display', 'none');
			this.ui.create_job.css('display', 'none');

			this.ui.register_button.css('display', 'block');
			this.ui.become_tutor_button.css('display', 'block');
			this.ui.login_button.css('display', 'none');

			this.model.set({'action': 'login'});

			return false;
		},

		showRegistrationForm: function (e) {
			if (e && e.prevenDefault) e.prevenDefault();

			this.ui.register_form.css('display', 'block');
			this.ui.login_form.css('display', 'none');
			this.ui.create_job.css('display', 'none');

			this.ui.login_button.css('display', 'block');
			this.ui.register_button.css('display', 'none');
			this.ui.become_tutor_button.css('display', 'none');

			this.model.set({'action': 'register'});

			return false;
		},

		prepareAlreadyExistingStudent: function (studentEmail) {
			studentEmail = studentEmail || '';

			this.ui['login.email'].val(studentEmail);
			this.$el.find('.dialogue__nav').hide();
			this.$el.find('.heading').html('Welcome Back!');
			this.ui['login.remember_me']
				.parent().parent().hide()
				.parent().append('<a href="/lost-password" class="tiny">Lost Password?</a>');
			this.ui['register_form'].after('<p>We can see that you already have an account under this email address. Please enter your password to send your message and log in.</p>')

			this.enableSubmit();
			this.showLoginForm();
		},

		showSuggestion: function (e) {
			var el = e.currentTarget,
				vm = this;
			MailCheck.run({
				email: el.value,
				suggested: function (suggestion) {
					Tipped.create(vm.ui['register.email'].selector, 'Did you mean ' + suggestion.full + '?', {
						skin: 'lightyellow',
						position: 'left',
						showOn: false,
						hideOn: 'focus'
					})
						.show();
				},
				empty: function () {
					Tipped.hide(vm.ui['register.email'].selector);
				}
			});
		}
	}));

});
