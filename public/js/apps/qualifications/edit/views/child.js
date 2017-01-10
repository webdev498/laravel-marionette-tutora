define([
    'base'
], function (
    Base
) {

    return _.patch(Base.ItemView.extend({

        mixins : ['FieldsLayout'],

        className : '[ layout ]',

        ui : {
            'remove' : '.js-remove'
        },

        events : {
            'click @ui.remove' : 'onClickRemove'
        },

        onClickRemove : function (e) {
            if (e.preventDefault) {
                e.preventDefault();
            }

            this.removeSelf();

            return false;
        },

        removeSelf : function () {
            this.trigger('remove', this.model);
        }

    }));

});
