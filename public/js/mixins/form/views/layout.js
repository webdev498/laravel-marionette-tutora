define([
    'base'
], function (
    Base
) {

    return {

        submitDisabledClassName : 'btn--disabled',

        ui : {
            'form'   : '.js-form',
            'submit' : '.js-submit'
        },

        events : {
            'submit @ui.form' : 'onFormSubmit'
        },

        onFormSubmit : function (e) {
            if (e.preventDefault) {
                e.preventDefault();
            }

            var data = this.getData() || {};

            if (_.isFunction(this.getFieldsData)) {
                data = _.extend(this.getFieldsData(), data);
            }

            this.trigger('form:submit', data);

            return false;
        },

        getData : function () {
            //
        },

        disableSubmit : function () {
            this.ui.submit.prop('disabled', true).addClass(this.submitDisabledClassName);
        },

        enableSubmit : function () {
            this.ui.submit.prop('disabled', false).removeClass(this.submitDisabledClassName);
        },

        showErrors : function (errors) {
            //
        },

        hideErrors : function () {
            //
        }

    };

});