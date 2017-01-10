define(['functions'], function () {
    var preloaded = (function () {
        var el = document.getElementById('preload');

        if ( ! el) return {};

        var innerHTML = el.innerHTML;

        if ( ! innerHTML) return {};

        return JSON.parse(innerHTML);
    }).call(this);

    var config = preloaded;

    return {
        get : function (key) {
            return object_get(config, key);
        },

        set: function (key, value) {
            return object_set(config, key, value);
        }
    };
});
