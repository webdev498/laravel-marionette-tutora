define([
    'base'
], function (
    Base
) {

    return Base.ItemView.extend({

        template : false,

        initialize : function () {
            this.listenTo(this.model, 'sync', this.taglineChanged);
        },

        taglineChanged : function () {
            var tagline = this.model.profile.get('tagline');

            if (tagline) {
                this.$el.html(tagline);   
            }
        }

    });

});
