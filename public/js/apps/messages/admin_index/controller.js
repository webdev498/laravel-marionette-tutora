define([
    'base',
    'entities/message_line',
    'apps/messages/admin_index/views/list'
], function (
    Base,
    MessageLine,
    CollectionView
) {

    return _.patch(Base.Controller.extend({

        mixins : [],

        initialize : function (options) {
            this.options = options;
            this.region  = options.region;
            this.lines   = MessageLine.currentCollection();

            this.renderLayout();
        },

        renderLayout: function() {
            this.view = new CollectionView({
                collection: this.lines
            });

            this.listenTo(this.view, 'line:flag', this.flagLine);

            this.view.render();
        },

        flagLine: function(model) {
            var flag = model.get('flag');
            model.set('flag', !flag);
            model.flagLine();
        }

    }));

});
