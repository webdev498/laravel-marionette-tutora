define([
    'base',
    'entities/subject',
    'entities/location',
    'entities/user'
], function (
    Base,
    Subject,
    Location,
    Student
) {

    var Job = Base.Model.extend({

        urlRoot : laroute.route('api.jobs.index'),

        idAttribute : 'uuid',

        defaults : {
            'message'  : null,
            'subject'  : null,
            'location' : null
        },

        nested : {
            'models'      : [
                'subject',
                'location',
                'student',
                'tutor',
                'initialTutorMessage'
            ],
            'collections' : [
                'replies'
            ]
        },

        validation : {
            'message' : [
                {
                    'required' : true,
                    'msg'      : 'Please enter a message to send.'
                }
            ],
            'subject_name' : [
                {
                    'required' : true,
                    'msg'      : 'Please enter a subject.'
                }
            ],
            'location_postcode' : [
                {
                    'required' : true,
                    'msg'      : 'Please enter a location.'
                }
            ],
            'first_name' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The first name field is required.'
                }
            ],
            'last_name' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The last name field is required.'
                }
            ],
            'email' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The email field is required.'
                }
            ],
            'telephone' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The telephone field is required.'
                }
            ],
            'password' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The password field is required.'
                }
            ]
        },

        constructor: function(attributes, options) {
            attributes = attributes || {};

            if (_.has(attributes, 'data')) {
                attributes = attributes.data;
            }

            this.subject             = Subject.model();
            this.location            = Location.model();
            this.student             = new Base.Model();
            this.tutor               = new Base.Model();
            this.initialTutorMessage = new Base.Model();

            this.replies  = new Base.Collection();

            return Backbone.Model.apply(this, arguments);
        },

        saveForStudent : function (studentUuid, attributes) {
            var _url = this.url;

            this.url = laroute.route('api.students.jobs.create', {'uuid' : studentUuid});
            this.save(attributes);

            this.url = _url;
        },

        favouriteJob : function () {
            var _url = this.url;

            this.url = laroute.route('api.jobs.favourite', {'uuid' : this.get('uuid')});
            this.save({}, {validate: false});

            this.url = _url;
        }

    });

    var Jobs = Base.Collection.extend({

        model : Job,

        url : laroute.route('api.jobs.index')

    });

    var currentCollection;

    return {

        model : function (attributes, options) {
            return new Job(attributes, options);
        },

        collection : function (models, options) {
            return new Jobs(models, options);
        },

        currentCollection : function () {
            if ( ! currentCollection) {
                var models = _.clone(_.config('jobs'));
                currentCollection = new Jobs();

                _.each(models, function(attributes) {
                    delete attributes.student.private;
                    var job = new Job(attributes, {
                        'parse' : true
                    });
                    currentCollection.push(job);
                });
            }

            return currentCollection;
        }

    };

});
