define([
    'base',
    'entities/user',
    'apps/messages/admin_index/views/message_line'
], function (
    Base,
    User,
    MessageLineView
) {

    return _.patch(Base.CollectionView.extend({

        mixins : [],

        el: '.js-messages-index-region',

        childView: MessageLineView,

        childEvents: function() {
            return {
                'line:flag' : this.flagLine
            }
        },

        flagLine: function(param) {
            this.trigger('line:flag', param.model);
        }
    }));

});
