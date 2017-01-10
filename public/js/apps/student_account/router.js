define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'personal'      : laroute.route('student.account.personal.index'),
            'payment'       : laroute.route('student.account.payment.index'),
            'admin_payment' : laroute.route('admin.students.payment.index')
        },

        personal : function () {
            this.tab('personal');
            this.controller.personal();
        },

        payment : function () {
            this.tab('payment');
            this.controller.payment();
        },

        admin_payment : function (uuid) {
            this.controller.adminPayment({
                'uuid' : uuid
            });
        },

        tab : function (name) {
            $('.js-tab').removeClass('tabs__link--active');
            $('[data-tab-name="' + name + '"]').addClass('tabs__link--active');

        }
    });

});
