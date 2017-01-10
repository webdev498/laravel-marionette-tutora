define([
    'base',
    'events',
    'entities/user',
    'entities/bank_account',
    'apps/tutor_account/payment/views/layout'
], function (
    Base,
    Event,
    User,
    BankAccount,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.user = User.current();
            this.view = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.view, 'render', this.onRender);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            Event.trigger('requirement:pending', {
                'section' : 'account',
                'name'    : 'payment_details'
            });

            attributes = object_undot(attributes);

            var filled = attributes.bank && (
                attributes.bank.account_number !== '' ||
                attributes.bank.routing_number !== ''
            );

            // Has the bank changed?
            if ( ! filled) {
                attributes.bank = null;
                this.saveWithTutora(attributes);
            } else {
                this.saveWithStripe(attributes);
            }
        },

        saveWithStripe : function (attributes) {
            var bank = BankAccount.model(attributes.bank);

            var errors = bank.validate();

            if ( ! _.isEmpty(errors)) {
                var _errors = {};

                _.each(errors, function (value, key) {
                    _errors['bank.' + key] = value;
                });

                this.onSyncErrorOrInvalid();
                this.onInvalid(this.user, _errors);
            } else {
                Stripe.bankAccount.createToken(bank.toJSON(), _.bind(this.onStripeResponse, this, attributes));
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

            attributes.bank = response.id;

            this.user.save(attributes, {
                'patch' : true
            });
        },

        onStripeError : function (error) {
            _.progress().done();

            this.view.enableSubmit();

            _.toast(error.message, 'error');
            
            Event.trigger('requirement:incompleted', {
                'section' : 'account',
                'name'    : 'payment_details'
            });
        },

        saveWithTutora : function (attributes) {
            attributes.bank = '';

            this.user.save(attributes, {
                'patch' : true
            });
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');

            this.view.enableSubmit();
        },

        onSyncError : function () {
            Event.trigger('requirement:incompleted', {
                'section' : 'account',
                'name'    : 'payment_details'
            });
        }

    }));

});
