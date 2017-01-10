define([
    'base',
    'entities/user',
    'requirejs-text!apps/dialogues/student_job_created/templates/layout.html'
], function (
    Base,
    User,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template)

    }));

});
