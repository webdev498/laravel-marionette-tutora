define([
    'base',
    'apps/autocomplete/subjects/controller'
], function (
    Base,
    Subjects
) {

    return Base.Controller.extend({

        initialize : function (options) {
            options = _.isObject(options) ? options : {};

            var $els = $('.js-autocomplete');

            $els.each(_.bind(function (i, el) {
                var $el          = $(el);
                var autocomplete = $el.data('autocomplete');

                if (autocomplete) {
                    var method = _.camelize(autocomplete);

                    if (this[method]) {
                        this[method]($el, options);
                    }
                }
            }, this));
        },

        subjects : function ($el, options) {
            return new Subjects(_.extend({
                '$el' : $el
            }, options));
        }

    });

});
