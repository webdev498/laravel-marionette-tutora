define([
    'base'
], function (
    Base
) {

    return {

        onClose : function () {
            this.destroy();
        },

        onDestroy : function () {
            this.region.empty();
        }

   };

});