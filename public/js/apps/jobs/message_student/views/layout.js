define([
    'base',
    'entities/session_vars',
    'requirejs-text!apps/jobs/message_student/templates/layout.html'
], function (
    Base,
    SessionVars,
    layout
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(layout),

        ui : {
            'body'     : '.js-message',
            'next'     : '.js-next',
            'back'     : '.js-back',
            'submit'   : '.js-submit',
            'dialogue' : '.dialogue__window'
        },

        fields : [
            'body'
        ],

        events: {
            'click @ui.next'   : 'nextStep',
            'click @ui.submit' : 'onSubmitClick',
            'click @ui.back'   : 'prevStep'
        },

        initialize: function(options) {
            this.job = options.job;
        },

        templateHelpers : function () {
            return {
                'job'  : this.job.toJSON()
            };
        },

        onSubmitClick: function() {
            this.ui.form.submit();
        },

        nextStep : function(e) {
            e.preventDefault();

            this.hideErrors();

            var data = this.getFieldsData();
            this.trigger('nextStep', data);
        },

        prevStep : function(e) {
            e.preventDefault();

            this.switchLayout();
        },

        switchLayout: function() {
            this.ui.dialogue.not('.message--sent').toggleClass('hidden');
        },

        showConfirmation: function() {
            this.ui.dialogue.addClass('hidden');
            this.ui.dialogue.filter('.message--sent').removeClass('hidden');
        }

    }));

});
