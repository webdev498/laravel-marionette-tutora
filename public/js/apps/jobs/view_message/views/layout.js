define([
    'base',
    'entities/session_vars',
    'requirejs-text!apps/jobs/view_message/templates/layout.html'
], function (
    Base,
    SessionVars,
    layout
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout'],

        template : _.template(layout)

    }));

});
