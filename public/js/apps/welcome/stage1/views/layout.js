define([
    'base',
    'entities/user',
    'requirejs-text!apps/welcome/stage1/templates/layout.html'
], function (
    Base,
    User,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            var user = User.current();

            return {
                'user' : user.toJSON()
            };
        }

    }));

});
