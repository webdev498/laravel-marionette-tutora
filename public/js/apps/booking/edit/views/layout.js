define([
    'base',
    'requirejs-text!apps/booking/edit/templates/layout.html'
], function (
    Base,
    template
) {
    // @TODO: consider the trial booking
    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        className : 'dialogue dialogue--booking',

        initialize: function() {
            this.rate = this.options.model.get('rate');
            this.tutor_rate = this.options.model.get('tutor_rate');
            this.duration = this.calculateDuration(this.options.model.get('duration'));
        },

        ui : {
            'location' : '.js-location',
            'date'     : '.js-date',
            'time'     : '.js-time',
            'future'   : '.js-future',
            'start'    : '.js-start',
            'duration' : '.js-duration',
            'rate'     : '.js-rate'
        },

        events: {
            "change @ui.duration": "onDurationChange",
            "keyup @ui.rate": "onRateChange"
        },

        fields : [
            'location',
            'date',
            'time',
            'duration',
            'rate',
            'tutor_rate',
            [
                'future',
                function () {
                    return this.ui.future.length > 0
                        ? _.val(this.ui.future)
                        : false;
                }
            ]
        ],

        onRender : function () {
            _.datepicker(this.ui.date);
        },

        onShow : function () {
            this.calculateLessonPrice();
        },

        calculateDuration : function(hms)
        {
            var a = hms.split(':'); // split it at the colons
            var minutes = (+a[0]) * 60 + (+a[1]);

            return parseInt(minutes) / 60;
        },

        onDurationChange : function(e) {
            this.duration = this.calculateDuration(e.target.value);
            this.calculateLessonPrice();
        },

        onRateChange : function(e) {
            if (e.target.value) {
                this.rate = parseInt(e.target.value);
            } else {
                this.rate = this.tutor_rate;
            }

            this.calculateLessonPrice();
        },

        calculateLessonPrice : function() {
            var price = this.rate * this.duration;
            $('.total-price').html(price.toFixed(2));
        }

    }));

});
