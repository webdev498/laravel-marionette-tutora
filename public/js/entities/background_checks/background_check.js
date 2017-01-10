define([
    'base',
    'events',
    'entities/background_checks/dbs',
    'entities/background_checks/dbs_update'
], function (
    Base,
    Event,
    Dbs,
    DbsUpdate
) {

    var BackgroundCheck = Base.Model.extend({

        nested : {
            'models' : [
                'dbs',
                'dbs_update'
            ]
        },

        constructor: function(attributes, options) {

            this.listenTo(Event, 'background_checks:reset', function (response) {
                var data = response.data;
                this.set('background_status', data.background_status);
                this.dbs.set(data.dbs.data);
                this.dbs_update.set(data.dbs_update.data);
            });

            this.dbs = Dbs.model();
            this.dbs.url = _.bind(this.getDbsUrl, this);

            this.dbs_update = DbsUpdate.model();
            this.dbs_update.url = _.bind(this.getDbsUpdateUrl, this);

            Backbone.Model.apply(this, arguments);
        },

        getDbsUrl: function() {
            return this.getTypeUrl('dbs');
        },

        getDbsUpdateUrl: function() {
            return this.getTypeUrl('dbs_update');
        },

        getTypeUrl: function(type) {
            return this.url + '/' + type;
        },

        remove: function() {
            var _url = this.url;

            this.set('id', 'dummyValue');

            this.url = laroute.route('api.users.background_check.delete', {'uuid' : this.get('userUuid'), 'type': this.get('type')});
            this.destroy();

            this.url = _url;
        }

    });

    return {

        model : function (attributes, options) {
            return new BackgroundCheck(attributes, options);
        }

    };

});