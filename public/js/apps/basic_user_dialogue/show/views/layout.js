define([
	'base',
	'entities/basic_user_dialogue',
	'requirejs-text!apps/basic_user_dialogue/show/templates/layout.html'
], function (Base,
			 BasicUserDialogue,
			 template) {

	return _.patch(Base.LayoutView.extend({
		mixins: ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

		template: _.template(template),

		initialize: function (options) {
			this.routeName = options.name;
		},

		onRender: function () {
			if (this.routeName === 'student_first_message') {
				this.$el.find('.tar')
					.addClass('tac').removeClass('tar')
					.append('<p></p><small style="color: #232F49;">If you need any help at any point you can email us at <a href="mailto:support@tutora.co.uk">support@tutora.co.uk</a> or call us on <a href="tel:01142157026">0114 215 7026</a>.</small>');
			}
		}
	}));

});
