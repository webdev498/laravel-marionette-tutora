define([
    'base',
    'entities/user_qualification_university',
    'entities/user_qualification_alevel',
    'entities/user_qualification_other'
], function (
    Base,
    University,
    Alevel,
    Other
) {

    var Qualification = Base.Model.extend({

        nested : {
            'collections' : [
                'universities',
                'alevels',
                'others'
            ]
        },

        constructor: function(attributes, options) {
            this.universities = University.collection();
            //this.universities.url = this.url + '/universities';

            this.alevels = Alevel.collection();
            //this.alevels.url = this.url + '/alevels';

            this.others = Other.collection();
            //this.others.url = this.url + '/others';

            Backbone.Model.apply(this, arguments);
        }

    });

    return {
        
        model : function (attributes, options) {
            return new Qualification(attributes, options);
        }

    };

});