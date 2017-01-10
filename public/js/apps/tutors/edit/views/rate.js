define([
    'base'
], function (
    Base
) {

    return Base.ItemView.extend({

        template : false,

        initialize : function () {
            this.listenTo(this.model, 'sync', this.rateChanged);
        },

        rateChanged : function () {
            var rate = this.model.profile.get('rate');

            if (rate) {
                this.$el.html(rate);   
            }
        }

    });

});
