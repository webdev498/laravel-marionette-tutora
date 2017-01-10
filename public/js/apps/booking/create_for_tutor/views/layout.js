define([
    'base',
    'requirejs-text!apps/booking/create_for_tutor/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--booking',

        initialize: function() {
            this.trial = _.queryString('trial') | 0;
            this.rate = this.trial ? this.options.user.get('rate')/2 : this.options.user.get('rate');
            this.duration = 0;
        },

        templateHelpers : function () {
            var students = this.options.user.students.map(function (student) {
                return {
                    'value'    : student.get('uuid'),
                    'title'    : student.get('first_name') + ' ' + student.get('last_name'),
                    'rate'     : student.get('rate'),
                    'selected' : _.queryString('student') == student.get('uuid')
                };
            });

            var student = _.findWhere(students, { 'selected' : true });

            var subjects = this.options.user.subjects.map(function (subject) {
                return {
                    'value'    : subject.get('id'),
                    'title'    : subject.get('title'),
                    'selected' : _.queryString('subject') == subject.get('id')
                };
            });

            var subject = _.findWhere(subjects, { 'selected' : true });

            var profile_rate = this.options.user.get('rate');

            return {
                'json'     : this.model.toJSON(),
                'subjects' : subjects,
                'student'  : student,
                'students' : students,
                'subject'  : subject,
                'profile_rate' : profile_rate,
                'trial' : this.trial
            };
        },

        ui : {
            'student'  : '.js-student',
            'subject'  : '.js-subject',
            'location' : '.js-location',
            'date'     : '.js-date',
            'time'     : '.js-time',
            'duration' : '.js-duration',
            'rate'     : '.js-rate',
            'repeat'   : '.js-repeat',
            'start'    : '.js-start'
        },

        events: {
            "change @ui.student": "onStudentChange",
            "change @ui.duration": "onDurationChange",
            "keyup @ui.rate": "onRateChange"
        },

        fields : [
            [
                'student',
                function () {
                    return this.options.user.students.get(_.val(this.ui.student));
                }
            ],
            [
                'subject',
                function () {
                    return this.options.user.subjects.get(_.val(this.ui.subject));
                }
            ],
            'location',
            'date',
            'time',
            'duration',
            'rate',
            [
                'repeat',
                function () {
                    return _.val(this.ui.repeat.filter(':checked'));
                }
            ]
        ],

        onRender : function () {
            _.datepicker(this.ui.date);
        },

        onStudentChange : function(e) {
            var rate = parseInt($(e.target).find(':selected').data('rate'));
            if (rate) {
                $('input[name="rate"]').val(rate);
                this.rate = rate;
            } else {
                $('input[name="rate"]').val('');
                this.rate = this.options.user.get('rate');
            }

            this.calculateLessonPrice();
        },

        onDurationChange : function(e) {
            var hms = e.target.value;
            var a = hms.split(':'); // split it at the colons
            var minutes = (+a[0]) * 60 + (+a[1]);
            this.duration = parseInt(minutes) / 60;

            this.calculateLessonPrice();
        },

        onRateChange : function(e) {
            if (e.target.value) {
                this.rate = parseInt(e.target.value);
            } else {
                this.rate = this.options.user.get('rate');
            }

            this.calculateLessonPrice();
        },

        calculateLessonPrice : function() {
            if (!this.trial) {
                var price = this.rate * this.duration;
                $('.total-price').html(price.toFixed(2));
            } else {
                $('.total-price').html(this.rate.toFixed(2));
            }
        }

    }));

});
