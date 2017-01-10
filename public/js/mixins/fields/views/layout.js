define([
    'base'
], function (
    Base
) {

    return {

        fields : [],

        fieldParentSelector : '.field',

        fieldParentHasErrorClassName : 'field--has-error',

        fieldErrorSelector : '.field__error',

        getFieldsData : function () {
            var data = {};

            _.each(this.fields, function (field) {
                if (_.isArray(field)) {
                    data[field[0]] = _.bind(field[1], this)();
                } else {
                    data[field] = _.val(this.ui[field]);
                }
            }, this);

            return data;
        },

        hideErrors : function () {
            _.each(this.fields, function (field) {
                var key    = _.isArray(field) ? field[0] : field;
                var $input = this.ui[key];
                if ($input) {
                    var $field = $input.parents(this.fieldParentSelector);
                    var $error = $field.find(this.fieldErrorSelector);

                    $field.removeClass(this.fieldParentHasErrorClassName);
                    $error.text('');
                }
            }, this);
        },

        showErrors : function (errors) {
            _.each(errors, function (msg, field) {
                var key    = _.isArray(field) ? field[0] : field;
                var $input = this.ui[key];
                if ($input) {
                    var $field = $input.closest(this.fieldParentSelector);
                    var $error = $field.find(this.fieldErrorSelector);

                    $error.text(msg);
                    $field.addClass(this.fieldParentHasErrorClassName);
                }
            }, this);
        }

    };

});
