define([
    'base'
], function (
    Base
) {

    var BasicUserDialogue = Backbone.Model.extend
    ({
        idAttribute: 'name',
        urlRoot: '/api/basic_user_dialogue'
    });

    return function (attributes, options) {
        return new BasicUserDialogue(attributes, options);
    };

});