define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'personal'       : laroute.route('tutor.account.personal.index'),
            'payment'        : laroute.route('tutor.account.payment.index'),
            'identification' : laroute.route('tutor.account.identification.index'),
            'admin_billing'  : laroute.route('admin.tutors.billing.index')
        },

        personal : function () {
            this.tab('personal');
            this.controller.personal();
        },

        payment : function () {
            this.tab('payment');
            this.controller.payment();
        },

        identification : function () {
            this.tab('identification');
            this.controller.identification();
        },

        admin_billing : function (uuid) {
            this.controller.adminBilling({
                uuid: uuid
            });
        },

        tab : function (name) {
            $('.js-tab').removeClass('tabs__link--active');
            $('[data-tab-name="' + name + '"]').addClass('tabs__link--active');
        }

    });

});
