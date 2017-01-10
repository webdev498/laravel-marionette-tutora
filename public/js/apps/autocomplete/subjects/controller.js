define([
    'base',
    'underscore.string',
    'entities/subject',
    'apps/autocomplete/subjects/views/layout',
    'apps/autocomplete/subjects/views/collection'
], function (
    Base,
    _String,
    Subject,
    LayoutView,
    CollectionView
) {

    return _.patch(Base.Controller.extend({

        initialize : function (options) {
            this.$el       = options['$el'];
            this._subjects = Subject.collection();  // All
            this.subjects  = new Base.Collection(); // All, flattened

            this.$el.append('<div class="js-region-list" />');

            this.view = new LayoutView({
                'el'         : this.$el,
                'collection' : this.subjects
            });

            this.listenToOnce(this._subjects, 'sync', this.onSync);
            this.listenTo(this.view, 'search', this.search);
            this.listenTo(this.view, 'close', this.closeAutocomplete);

            this.$el.one('click keydown', _.bind(function () {
                if(this._subjects.length > 0) return;
                this._subjects.fetch();
            }, this));

            this._subjects.fetch();
        },

        onSync : function () {
            var flattenSubjects = _.bind(function (subject) {
                var children = subject.get('children');

                if (children.length > 0) {
                    _.each(children, flattenSubjects);
                }

                this.subjects.push(subject);
            }, this)

            this._subjects.each(flattenSubjects);
        },

        normalizeQuery : function (str) {
            return str ? str.toLowerCase().replace(/[^a-z0-9]+/g, '') : '';
        },

        extractTerms : function (query) {
            query = query.replace('-', '');
            query = query.split(' ');

            return _.map(query, this.normalizeQuery);
        },

        search : function (query) {
            this.closeAutocomplete();

            if (query.length < 3) {
                return;
            }

            query = this.extractTerms(query);

            var all = new Base.Collection(
                this.subjects.filter(function (subject) {
                    if (subject.get('depth') === 0) {
                        return false;
                    }

                    var search = this.normalizeQuery(subject.get('title'));
                    var score  = _.reduce(query, function(memo, term) {
                        return memo + (search.indexOf(term) > -1 ? 1 : 0);
                    }, 0);

                    if (score < query.length) {
                        return false;
                    }

                    subject.set('score', subject.get('depth'));

                    return subject;
                }, this)
            );

            var collection = new Base.Collection(all.sortBy('score').slice(0, 10));

            var view = new CollectionView({
                'collection' : collection
            });

            this.listenTo(view, 'fill', this.fill);

            this.view.listRegion.show(view);
        },

        fill : function (val) {
            this.closeAutocomplete();
            this.view.ui.input.val(val);
        },

        closeAutocomplete : function () {
            var Tipped = _.tipped();
            Tipped.hide('.tipped');

            if (this.view.listRegion && this.view.listRegion.currentView) {
                this.stopListening(this.view.listRegion.currentView);
                this.view.listRegion.currentView.destroy();
            }
        }

    }));

});
