define([
    'base'
], function (
    Base
) {

    var UserDialogueInteraction = Backbone.Model.extend
    ({
        idAttribute: 'id',
        urlRoot: '/api/user_dialogue_interaction',
        defaults: {duration: null}
    });

    return function (attributes, options) {
        return new UserDialogueInteraction(attributes, options);
    };

});