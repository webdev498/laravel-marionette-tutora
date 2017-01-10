define([
    'base'
], function (
    Base
) {

    return {

        className : 'dialogue',

        openClassName : 'dialogue--open',

        ui : {
            'close' : '.js-close'
        },

        events : {
            'click @ui.close' : 'onClickClose'
        },

        onShow : function () {
            this.$el.addClass(this.openClassName);
        },

        onClickClose : function (e) {
            if (e.preventDefault) {
                e.preventDefault();
            }

            this.destroy();

            return false;
        },

        onDestroy : function () {
            this.$el.removeClass(this.openClassName);
        }
    };

});