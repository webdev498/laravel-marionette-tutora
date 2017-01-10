define([
    'base'
], function (
    Base
) {

    var QuizPrep = Base.Model.extend({

        urlRoot : '/api/quiz_prep'

    });

    var QuizPrepItem = Base.Model.extend({

        idAttribute: 'key',

        defaults : {
            'title' : '',
            'image' : null,
            'text'  : ''
        }

    });

    var QuizPrepItems = Base.Collection.extend({

        model : QuizPrepItem,

        url : '/api/quiz_prep',

        parse : function (response) {

            if (_.has(response, 'data')) {
                return data = response.data;   
            } else {
                return data = response;
            }
        }
 
    });

    return {

        model : function (attributes, options) {
            return new QuizPrep(attributes, options);
        },

        collection : function (models, options) {
            return new QuizPrepItems(models, options);
        }

    };

});
