define([
    'base',
    'requirejs-text!apps/background_check/dbs/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        mixins : ['ImageUploader', 'FormLayout', 'FieldsLayout'],

        template : _.template(template)

    }));

});
