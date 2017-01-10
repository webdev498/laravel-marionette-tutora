define([
    'base',
    'entities/user',
    'entities/bank_card',
    'apps/student_account/admin_payment/views/layout'
], function (
    Base,
    User,
    BankCard,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.user = User.model({'uuid' : options.uuid});

            _.progress().start();

            this.listenTo(this.user, 'sync', this.renderLayout);

            this.user.fetch();
        },

        renderLayout: function() {
            _.progress().done();
            this.stopListening(this.user);

            this.view = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            attributes = object_undot(attributes);

            var filled = attributes.card && (
                attributes.card.number    !== '' || 
                attributes.card.cvc       !== '' ||
                attributes.card.exp_month !== '' ||
                attributes.card.exp_year  !== ''
            );

            if ( ! filled && ! _.isNull(this.user.get('last_four'))) {
                attributes.card = '';
                this.saveWithTutora(attributes);
            } else {
                this.saveWithStripe(attributes);
            }
        },

        saveWithStripe : function (attributes) {
            var card = BankCard.model(attributes.card);

            var errors = card.validate();

            if ( ! _.isEmpty(errors)) {
                var _errors = {};
                
                _.each(errors, function (value, key) {
                    _errors['card.' + key] = value;
                });

                this.onSyncErrorOrInvalid();
                this.onInvalid(this.user, _errors);
            } else {
                Stripe.card.createToken(card.toJSON(), _.bind(this.onStripeResponse, this, attributes));
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

            this.user.save(attributes, {
                'patch' : true
            });
        },

        onStripeError : function (error) {
            _.progress().done();

            var errors = {};
            errors['card.' + error.param] = error.message;

            this.showErrors('ERR_INVALID', errors);

            this.view.enableSubmit();
        },

        saveWithTutora : function (attributes) {
            this.user.save(attributes, {
                'patch' : true
            });
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');

            this.view.enableSubmit();
        }

    }));

});
