define([], function () {

    // Create a dot notation object
    window.object_dot = function (object, prepend) {
        prepend = prepend || '';

        var results = {};

        _.each(object, function (value, key) {
            if (_.isObject(value) && ! _.isRegExp(value)) {
                results = _.extend(results, object_dot(value, prepend + key + '.'));
            } else {
                results[prepend + key] = value;
            }
        });

        return results;
    };

    // Undot an object
    window.object_undot = function (object) {
        var results = {};

        _.each(object, function(value, key) {
            object_set(results, key, value);
        });

        return results;
    }

    // Get from an object via dot notation
    window.object_get = function (object, key, _default) {
        _default = _default || null;

        if ( ! key) return object;

        if  ( _.has(object, key)) return object[key];

        var keys = key.split('.');

        for (var i = 0, j = keys.length; i < j; i++) {
            var segment = keys[i];

            if ( ! _.isObject(object) || ! _.has(object, segment)) {
                return _default;
            }

            object = object[segment];
        }

        return object;
    };
    
    // Set a value using the dot notation
    window.object_set = function (obj, keys, value) {
        if ( ! _.isArray(keys)) {
            var keys = keys.split('.');
        }

        for(var i = 0; i < keys.length - 1; i++) {
            obj = obj[keys[i]] = obj[keys[i]] || {};
        }

        obj = obj[keys[i]] = value;

        return obj;
    };

});
