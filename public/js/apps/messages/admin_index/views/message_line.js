define([
    'base'
], function (
    Base
) {

    return _.patch(Base.ItemView.extend({

        mixins : [],

        initialize: function() {
            var id     = this.model.get('id');
            var idAttr = '#item-'+id;

            this.setElement(idAttr);

            this.listenTo(this.model, 'change:flag', this.flagChanged);
        },

        template: false,

        ui: {
            flag : '.js-flag'
        },

        events: {
            'click @ui.flag' : 'clickedFlag'
        },

        clickedFlag: function() {
            this.triggerMethod('line:flag');
        },

        flagChanged: function(model, value) {
            this.updateFlag(value);
        },

        updateFlag: function(flag) {
            if(flag) {
                this.ui.flag.addClass('active');
            } else {
                this.ui.flag.removeClass('active');
            }
        }

    }));

});
