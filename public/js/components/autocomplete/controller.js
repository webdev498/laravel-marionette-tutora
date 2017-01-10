define([
    'base',
    'autocomplete'
], function (
    Base,
    Autocomplete
) {

    return Base.Controller.extend({
        initialize : function (options) {
            var $el    = $(options.el);
            var method = _.camelize('autocomplete_' + $el.data('autocomplete'));

            if (_.isFunction(this[method])) {
                this[method]($el);
            }
        },

        autocompleteTutors : function ($el) {
            var $hidden = $('<input>', {
                'type' : 'hidden',
                'name' : $el.attr('name')
            });
            
            $el.after($hidden);

            $el.autocomplete({
                'serviceUrl' : '/api/autocomplete/tutors',
                'onSelect'   : function (suggestion) {
                    $hidden.attr('value', suggestion.data);
                },
                'transformResult' : function(response) {
                    response = JSON.parse(response);
                    return {
                        'suggestions' : $.map(response.data, function(item) {
                            return {
                                'value': item.value,
                                'data' : item.data
                            };
                        })
                    };
                }
            });
        },

        autocompleteStudents : function ($el) {
            var $hidden = $('<input>', {
                'type' : 'hidden',
                'name' : $el.attr('name')
            });
            
            $el.after($hidden);

            $el.autocomplete({
                'serviceUrl' : '/api/autocomplete/students',
                'onSelect'   : function (suggestion) {
                    $hidden.attr('value', suggestion.data);
                },
                'transformResult' : function(response) {
                    response = JSON.parse(response);
                    return {
                        'suggestions' : $.map(response.data, function(item) {
                            return {
                                'value': item.value,
                                'data' : item.data
                            };
                        })
                    };
                }
            });
        },

        autocompleteSearch : function ($el) {
            $el.autocomplete({
                'serviceUrl' : '/api/autocomplete/search',
                'onSelect'   : function (suggestion) {
                    window.location = suggestion.data;
                },
                'transformResult' : function(response) {
                    response = JSON.parse(response);
                    return {
                        'suggestions' : $.map(response.data, function(item) {
                            return {
                                'value': item.value,
                                'data' : item.data
                            };
                        })
                    };
                }
            });
        }
    });

});

