define([
    'base',
    'entities/user',
    'entities/subject'
], function (
    Base,
    User,
    Subject
) {

    var BookingSchedule = Base.Model.extend({
        //
    });

    var Booking = Base.Model.extend({

        urlRoot : '/api/bookings',

        idAttribute : 'uuid',

        defaults : {
            'tutorId'  : null,
            'student'  : null,
            'subject'  : null,
            'location' : null,
            'date'     : null,
            'time'     : null,
            'duration' : null,
            'rate'     : null,
            'tutor_rate' : null,
            'trial'     : null
        },

        nested : {
            'models' : ['schedule', 'student', 'subject']
        },

        validation : {
            'date' : [
                {
                    'required' : true,
                    'msg'      : _.lang('validation.required', {
                        'attribute' : 'date'
                    })
                }
            ],
            'time' : [
                {
                    'required' : true,
                    'msg'      : _.lang('validation.required', {
                        'attribute' : 'time'
                    })
                }
            ],
            'duration' : [
                {
                    'required' : true,
                    'msg'      : _.lang('validation.required', {
                        'attribute' : 'duration'
                    })
                }
            ],
            rate: 'validateRate'
            /*)
            'rate' : [
                {
                    'required' : false,
                    'range': [this.profile_rate, this.profile_rate],
                    'msg'      : _.lang('validation.custom.booking.rate.between', {
                        'attribute' : 'rate',
                        'min' : this.profile_rate,
                        'max' : this.profile_rate
                    })
                }
            ]
            */
        },

        validateRate: function(value, attr, computedState) {
            var rate = parseInt(value);

            // the min and max rates depend on the form type
            // - £5-1x for trial forms
            // - 0.5x - 2x for regular booking forms
            var min = parseInt(this.trial ? 5 : this.tutor_rate * 0.5);
            var max = parseInt(this.trial ? this.tutor_rate : this.tutor_rate * 2);

            if (!this.trial && (rate < min || rate > max)) {
                return 'You can only set the hourly rate between £'+min+' - £'+max;
            } else if (this.trial) {
                if (rate < min) return 'The minimum price for a trial lesson is £' + min;
                if (rate > max) return 'The maximum price for your trial lesson is £' + max;
            }
        },

        constructor: function(attributes, options) {
            attributes = attributes || {};

            var uuid = attributes[this.idAttribute];

            this.trial = attributes['trial'];
            this.tutor_rate = attributes['tutor_rate'];

            this.schedule = new BookingSchedule();

            this.student = User.model();

            this.subject = Subject.model();

            return Backbone.Model.apply(this, arguments);
        },

        cancel : function (attributes) {
            var _url = this.url;

            this.url = this.urlRoot + '/' + this.id + '/cancel';
            this.save(attributes);

            this.url = _url;
        },

        confirm : function (attributes) {
            var _url = this.url;

            this.url = this.urlRoot + '/' + this.id + '/confirm';
            this.save(attributes);

            this.url = _url;
        },

        saveForTutor : function (tutorId, attributes) {
            var _url = this.url;

            this.url = laroute.route('api.tutors.lessons.book', {'uuid' : tutorId});
            this.save(attributes);

            this.url = _url;
        },

        refund : function (attributes) {

            var _url = this.url;

            this.url = this.urlRoot + '/' + this.id + '/refund';
            this.save(attributes);

            this.url = _url;
        }

    });

    return {

        model : function (attributes, options) {
            return new Booking(attributes, options);
        }

    };

});
