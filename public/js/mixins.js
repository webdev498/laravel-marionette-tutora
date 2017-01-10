define([
    'backbone',
    'backbone.validation',
    'underscore',
    'underscore.string',
    'events',
    'cocktail',
    'mixins/fields/views/layout',
    'mixins/form/controller',
    'mixins/form/views/layout',
    'mixins/dialogue/controller',
    'mixins/dialogue/views/layout',
    'mixins/files/views/image_uploader',
    'nprogress',
    'tipped',
    'laroute',
    'config',
    'functions',
    'picker',
    'picker.date',
], function (
    Backbone,
    Validation,
    _,
    _string,
    Event,
    Cocktail,
    FieldsLayout,
    FormController,
    FormLayout,
    DialogueController,
    DialogueLayout,
    ImageUploader,
    Nprogress,
    Tipped,
    Laroute,
    config
) {
    window.laroute = Laroute;

    _.mixin(_string.exports());

    _.mixin({

        config : function (key) {
            return config.get(key);
        },

        lang : function (key, data, def) {
            data = _.isObject(data) ? data : {};
            def  = _.isUndefined(def) ? key : def;

            var value = config.get('lang.' + key);

            if (_.isString(value)) {
                return _.templ(value, data);
            } else if (_.isNull(value)) {
                return def;
            }

            return value;
        },

        templ : function (string, data) {
            return _.template(string.replace(/:([^\s]+)/g, '<%= $1 %>'), data);
        },

        pe : function (string) {
            string = '<p>' + string + '</p>';
            string = _.replaceAll(string, "\n\n", '</p><p>');
            string = _.replaceAll(string, "\n", '<br>');
            return string;
        },

        // http://stackoverflow.com/questions/10730362/get-cookie-by-name
        cookie : function (name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) {
                value = parts.pop().split(";").shift();
                // TODO -3 removes %3D from the cookie. weird?
                return value;
                // return value.substr(0, value.length - 3);
            }
        },

        queryString : function (key) {
            var parts = location.search.substr(1).split('&');

            for (var i = 0, j = parts.length; i < j; i++) {
                var item = parts[i].split('=');

                if (item[0] === key) {
                    return decodeURIComponent(item[1])
                }
            }
        },

        progress : function () {
            return Nprogress;
        },

        tipped : function () {
            return Tipped;
        },

        toast : function (message, severity, duration) {
            Event.trigger('toast', message, severity, duration);
        },

        val : function (input) {
            var $el = $(input);
            var val;

            if ($el.length > 1) {
                val = {};

                var regex = /\[(.*?)\]/g;

                _.each($el, function (el) {
                    var names = [];
                    var matches;

                    while (matches = regex.exec(el.name)) {
                        names.push(matches[1]);
                    }

                    object_set(val, names.join('.'), _.val(el));
                });
            } else {
                switch (true) {
                    case $el.is('[type="checkbox"]'):
                        val = $el.is(':checked');
                        break;

                    case $el.is('[type="radio"]'):
                        val = $el.filter(':checked').val();
                        break;

                    default:
                        val = $el.val();
                }
            }

            return val;
        },

        patch : function (object) {
            if (_.isObject(object && object.prototype && object.prototype.mixins)) {
                _.each(object.prototype.mixins, function (mixin) {
                    Cocktail.mixin(object, mixin); 
                });
            } else {
                _.each(object, _.patch);
            }

            return object;
        },

        callback : function (options) {
            var callback = options[0];
            var context  = options[1];
            var args     = options[2] || [];

            callback.apply(context, args);
        },

        datepicker : function ($el, options) {
            options = options || {};

            options = _.extend(options, {
                format: 'dd/mm/yyyy'
            });

            $el.pickadate(options);
        }

    });

    // _.extend(Backbone.Validation.validators, {
    _.extend(Validation.validators, {

        boolean: function(value, attr, customValue, model) {
            if ( ! _.isBoolean(value)) {
                return 'The ' + attr + ' field must be true or false.';
            }
        },

        numeric : function (value, attr, customValue, model) {
            if (value === NaN || ! _.isNumber(value) && isNaN(value)) {
                return 'The ' + attr + ' field must be a number.';
            }
        }
    });

    _.extend(Backbone.Model.prototype, Validation.mixin);

    Cocktail.mixins = {
        'FieldsLayout'       : FieldsLayout,
        'FormController'     : FormController,
        'FormLayout'         : FormLayout,
        'DialogueController' : DialogueController,
        'DialogueLayout'     : DialogueLayout,
        'ImageUploader'      : ImageUploader
    };
});
