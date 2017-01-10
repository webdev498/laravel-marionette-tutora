define([
    'base',
    'apps/tutors/edit/controller',
    'apps/tutors/show/controller'
], function (
    Base,
    EditController,
    ShowController
) {

    return Base.Controller.extend({

        edit : function () {
            return new EditController({
                'app' : this.app
            });
        },

        show : function () {
            return new ShowController({
                'app' : this.app
            });
        }

    });

});
