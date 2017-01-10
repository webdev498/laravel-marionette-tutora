define([
    'base'
], function (
    Base
) {

    return Base.LayoutView.extend({

        regions : {
            'listRegion' : '.js-region-list'
        },

        ui : {
            'input' : '.js-autocomplete-input'
        },

        events : {
            'keyup @ui.input' : 'onInputKeyup'
            ,'blur @ui.input' : 'onInputBlur'
        },

        initialize : function () {
            this.bindUIElements();

            this.ui.input.attr('autocomplete', 'off');
        },

        onInputKeyup : function (e) {
            this.trigger('search', this.ui.input.val());
        },

        onInputBlur : function (e) {
            setTimeout(_.bind(function () {
                this.trigger('close');
            }, this), 100);
        }

    });

});