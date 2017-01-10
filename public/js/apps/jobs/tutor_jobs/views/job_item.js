define([
    'base'
], function (
    Base
) {

    return _.patch(Base.ItemView.extend({

        mixins : [],

        initialize: function() {
            var id     = this.model.get('uuid');
            var idAttr = '#item-'+id;

            this.setElement(idAttr);

            this.listenTo(this.model.tutor, 'change:favourite', this.favouriteChanged);
            this.listenTo(this.model.tutor, 'change:applied', this.appliedChanged);
        },

        template: false,

        ui: {
            favourite      : '.favourite',
            applied        : '.application',
            messageStudent : '.message_student',
            viewMessage    : '.view_message'
        },

        events: {
            'click @ui.favourite'      : 'clickedFavourite',
            'click @ui.messageStudent' : 'clickedMessageStudent',
            'click @ui.viewMessage'    : 'clickedViewMessage'
        },

        clickedFavourite: function() {
            this.triggerMethod('job:favourite');
        },

        clickedMessageStudent: function(e) {
            e.preventDefault();

            this.triggerMethod('job:message:create');
        },

        clickedViewMessage: function(e) {
            e.preventDefault();

            this.triggerMethod('job:message:view');
        },

        favouriteChanged: function(model, value) {
            this.updateFavourite(value);
        },

        appliedChanged: function(model, value) {
            this.updateApplied(value);
        },

        updateFavourite: function(favourite) {
            if(favourite) {
                this.ui.favourite.addClass('active');
            } else {
                this.ui.favourite.removeClass('active');
            }
        },

        updateApplied: function(applied) {
            if(applied) {
                this.ui.applied.addClass('active');
                this.ui.messageStudent.addClass('hidden');
                this.ui.viewMessage.removeClass('hidden');
            } else {
                this.ui.applied.removeClass('active');
                this.ui.messageStudent.removeClass('hidden');
                this.ui.viewMessage.addClass('hidden');
            }
        }

    }));

});
