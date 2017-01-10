define([
    'base'
], function (
    Base
) {

    return Base.ItemView.extend({

        template : false,

        initialize : function () {
            this.listenTo(this.collection, 'sync', this.subjectsChanged);
        },

        subjectsChanged : function () {
            var subjects = {};
            var html     = [];

            if (this.collection.isEmpty()) {
                html.push('<dd class="dl__dd">You haven&#39;t added any subjects yet.</dd>');
            } else {
                this.collection.each(function (subject) {
                    var path    = subject.get('path').split(' / ');
                    var key     = path.splice(0, 2).join('.');
                    var levels  = object_get(subjects, key, []);

                    if (path.length > 0) {
                        levels = levels.concat(path);
                    }

                    object_set(subjects, key, levels);
                });

                _.each(subjects, function (levels, subject) {
                    html = html.concat([
                        '<dt class="dl__dt">',
                        subject,
                        '</dt>'
                    ]);

                    var dd = [];

                    _.each(levels, function (values, key) {
                        key = '<strong>' + key + '</strong>';

                        if (values.length > 0) {
                            key = key + ' (' + values.join(', ') + ')';
                        }

                        dd.push(key);
                    });

                    html = html.concat([
                        '<dd class="dl__dd">',
                        dd.join(', '),
                        '</dd>'
                    ]);
                });
            }

            this.$el.html(html.join(''));
        }

    });

});
