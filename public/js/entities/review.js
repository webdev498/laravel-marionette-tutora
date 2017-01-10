define([
    'base'
], function (
    Base
) {

    var Review = Base.Model.extend({

        urlRoot : function () {
            if (this.options.tutor) {
                return laroute.route('api.users.reviews.create', {
                    'uuid' : this.options.tutor
                });
            } else {
                return '/api/reviews';
            }

        },
        idAttribute: 'id',

        defaults : {
            'rating' : null,
            'body'   : null
        },

        validation : {
            'rating' : [
                {
                    'required' : true,
                    'msg'      : _.lang('validation.required', {
                        'attribute' : 'rating'
                    })
                }
            ],
            'body' : [
                {
                    'required' : true,
                    'msg'      : _.lang('validation.required', {
                        'attribute' : 'body'
                    })
                }
            ]
        },

        initialize : function (attributes, options) {
            this.options = options;
        }

    });

    var Reviews = Base.Collection.extend({

        model : Review,

        url : laroute.route('api.reviews')

    });

    return {

        model : function (attributes, options) {
            return new Review(attributes, options);
        },
        collection : function (models, options) {
            return new Reviews(models, options);
        }

    };

});
