define([
    'base',
    'events',
    'entities/address',
    'entities/user_profile',
    'entities/user_qualification',
    'entities/user_qualification_teacher_status',
    'entities/background_checks/background_check',
    'entities/subject',
    'entities/quiz'
], function (
    Base,
    Event,
    Address,
    Profile,
    Qualification,
    QTS,
    BackgroundCheck,
    Subject,
    Quiz
) {

    var Requirement = Base.Model.extend({

        defaults : {
            'title'        : '',
            'is_pending'   : false,
            'is_completed' : false,
            'is_optional'  : false,
            'is_js'        : false
        }

    });

    var Requirements = Base.Collection.extend({

        model : Requirement,

        comparator : 'sort',

        initialize : function () {
            this.listenTo(Event, 'requirements:reset', function (response) {
                this.reset(response.data);
            });
        }

    });

    var User = Base.Model.extend({

        urlRoot : laroute.route('api.users.create'),

        idAttribute : 'uuid',

        nested : {
            'models'      : [
                'addresses',
                'profile',
                'qualifications',
                'background_checks',
                'qts',
                'identity_document'
             ],
            'collections' : [
                'subjects',
                'students',
                'tutors',
                'requirements'
            ]
        },

        defaults : {
            'first_name'    : null,
            'last_name'     : null,
            'dob'           : null,
            'email'         : null,
            'telephone'     : null,
            'password'      : null
        },

        validation : {
            'subject_name' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The subject field is required.'
                }
            ],
            'location_postcode' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The postcode field is required.'
                }
            ],
            'body' : [
                {
                    'required' : function () {
                        return this.get('action') === 'student-registration';
                    },
                    'msg'      : 'The message is required.'
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

            var uuid = attributes[this.idAttribute];

            this.addresses = Address.models();
            this.addresses.url = laroute.route('api.users.addresses.create', {
                'uuid' : uuid
            });

            this.profile = Profile.model();
            this.profile.url = laroute.route('api.users.profile.edit', {
                'uuid' : uuid
            });

            this.requirements = new Requirements();

            this.students = new Users();
            this.students.url = laroute.route('api.users.students.create', {
                'uuid' : uuid
            });

            this.tutors = new Users();
            this.tutors.url = laroute.route('api.users.tutors.get', {
                'uuid' : uuid
            });

            this.subjects = Subject.collection();
            this.subjects.url = laroute.route('api.users.subjects.create', {
                'uuid' : uuid
            });

            this.quiz = Quiz.model();
            this.quiz.url = laroute.route('api.users.quiz.submit', {
                'uuid' : uuid
            });

            this.qualifications = Qualification.model();
            this.qualifications.url = laroute.route('api.users.qualifications.create', {
                'uuid' : uuid
            });

            this.qts = QTS.model();
            this.qts.url = laroute.route('api.users.qualifications.qts.create', {
                'uuid' : uuid
            });

            this.identity_document = new Base.Model();

            this.background_checks = BackgroundCheck.model();
            this.background_checks.url = laroute.route('api.users.background_checks.create', {
                'uuid' : uuid
            });

            return Backbone.Model.apply(this, arguments);
        },

        allowedRateRange: function() {
            var rate = this.get('rate');

            return {
                'min': parseInt(rate * 0.5),
                'max': parseInt(rate * 2)
            }
        },

        toggleBlock: function () {
            var _url = this.url;

            this.url = this.urlRoot + '/' + this.id + '/toggleblock';
            this.save();

            this.url = _url;
        }

    });

    var Users = Base.Collection.extend({

        model : User,

        url : laroute.route('api.users.create')

    });

    var current;

    return {

        current : function () {
            if ( ! current) {
                var attributes = _.clone(_.config('user'));
                current = new User(attributes, {
                    'parse' : true
                });
            }

            return current;
        },

        set : function (attributes) {
            current = new User(attributes, {
                'parse' : true
            });

            return current;
        },

        model : function (attributes, options) {
            options = _.extend({
                'parse' : true
            }, options);

            return new User(attributes, options);
        }

    };

});
