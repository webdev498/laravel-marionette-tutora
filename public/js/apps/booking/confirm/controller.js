define([
    'base',
    'entities/booking',
    'entities/bank_card',
    'apps/booking/confirm/views/layout'
], function (
    Base,
    Booking,
    BankCard,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.app.addRegions({
                'confirmRegion' : '.js-confirm'
            });

            this.booking = (function () {
                var attributes = _.clone(_.config('booking'));
                return Booking.model(attributes, {
                    'parse' : true
                });
            })();

            this.view = new LayoutView({
                'el'    : this.app.confirmRegion.$el,
                'model' : this.booking
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.booking, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.booking, 'sync', this.onSyncSuccess);
            this.listenTo(this.booking, 'error', this.onSyncError);
            this.listenTo(this.booking, 'invalid', this.onInvalid);

            this.app.confirmRegion.attachView(this.view);
        },

        save : function (attributes) {
            attributes = object_undot(attributes);

            var filled = attributes.card && (
                attributes.card.number       !== ''
                || attributes.card.cvc       !== ''
                || attributes.card.exp_month !== ''
                || attributes.card.exp_year  !== ''
            );

            if ( ! filled && attributes.card && attributes.card.exists) {
                attributes.card = null;
                this.saveWithTutora(attributes);
            } else {
                this.saveWithStripe(attributes);
            }
        },

        saveWithStripe : function (attributes) {
            var card   = BankCard.model(attributes.card);
            var errors = card.validate();

            if ( ! _.isEmpty(errors)) {
                var _errors = {};
                
                _.each(errors, function (value, key) {
                    _errors['card.' + key] = value;
                });

                this.onSyncErrorOrInvalid();
                this.onInvalid(this.user, _errors);
            } else {
                Stripe.card.createToken(
                    _.pick(card.toJSON(), ['number', 'cvc', 'exp_month', 'exp_year']),
                    _.bind(this.onStripeResponse, this, attributes)
                );
            }
        },

        onStripeResponse : function (attributes, status, response) {
            if (response.error) {
                this.onStripeError(response.error);
            } else {
                this.onStripeSync(attributes, status, response);
            }
        },

        onStripeSync : function (attributes, status, response) {
            _.progress().set(0.5);

            attributes.card = response.id;

            this.saveWithTutora(attributes);
        },

        onStripeError : function (error) {
            _.progress().done();

            this.view.enableSubmit();

            _.toast(error.message, 'error');
        },

        saveWithTutora : function (attributes) {
            this.booking.confirm(attributes);
        },

        onSyncSuccess : function (booking, json) {
            return window.location = json.meta.redirect;
        }

    }));

});
