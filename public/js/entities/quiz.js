define([
    'base'
], function (
    Base
) {

    var Quiz = Base.Model.extend({

        urlRoot : '/api/quiz'

    });

    var Question = Base.Model.extend({

        idAttribute: 'key',

        defaults : {
            'question' : '',
            'key'      : '',
            'answers'  : []
        }

    });

    var Questions = Base.Collection.extend({

        model : Question,

        url : '/api/quiz',

        parse : function (response) {
            if (_.has(response, 'data')) {
                data = response.data;
            } else {
                data = response;
            }

            if ( ! _.isArray(data)) {
                return data;
            }

            var questions = _.map(data, function (d) {
                if (_.has(d, 'answers') && _.isArray(d.answers)) {
                    d.answers = new Backbone.Collection(d.answers);
                }

                return new Question(d);
            }, this);
            
            return questions;
        }

    });

    return {

        model : function (attributes, options) {
            return new Quiz(attributes, options);
        },

        collection : function (models, options) {
            return new Questions(models, options);
        }

    };

});