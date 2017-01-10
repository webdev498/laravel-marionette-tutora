define([
    'base',
    'requirejs-text!apps/jobs/edit_job/templates/deleteConfirmation.html'
], function (
    Base,
    layout
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout'],

        template : _.template(layout),

        ui : {
            'confirm' : '.js-confirm'
        },

        events: {
            'click @ui.confirm' : 'onConfirmation'
        },

        onConfirmation: function(e) {
            e.preventDefault();
            this.trigger('job:delete:confirmed');
        }

    }));

});
