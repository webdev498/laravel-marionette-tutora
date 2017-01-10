define([
    'base',
    'events',
    'entities/user',
    'components/requirements/views/layout',
    'components/requirements/views/list'
], function (
    Base,
    Event,
    User,
    LayoutView,
    ListView
) {

    return Base.Controller.extend({

        initialize : function (options) {
            this.region = options.region;
            this.user   = User.current();
            this.quiz_auto_loaded   = false;
            this.view   = new LayoutView({
                'collection' : this.user.requirements
            });

            this.listenTo(this.user.requirements, 'reset', this.showLists);

            this.listenTo(Event, 'requirement:completed', this.onRequirementCompleted);
            this.listenTo(Event, 'requirement:pending', this.onRequirementPending);
            this.listenTo(Event, 'requirement:incompleted', this.onRequirementIncompleted);

            this.listenTo(this.view, 'show', this.onShow);

            this.region.show(this.view);
        },

        onShow : function () {
            this.showLists();
        },

        showLists : function () {
            this.user.requirements.each(this.isRequirementJs, this);

            this.showList('profile', this.view.profileRequirementsRegion);
            this.showList('account', this.view.accountRequirementsRegion);
            this.showList('other',   this.view.otherRequirementsRegion);
        },

        showList : function (key, region) {
            var models = this.user.requirements.where({
                'section' : key
            });

            var view = new ListView({
                'collection' : new Backbone.Collection(models),
                'heading'    : _.humanize(key)
            });

            region.show(view);
        },

        onRequirementCompleted : function (data) {
            this.updateRequirement(_.extend({
                'is_pending'   : false,
                'is_completed' : true
            }, data));

            if(this.isQuizOnlyLeft()){
                this.redirectToTheQuiz();
            }
        },

        isQuizOnlyLeft: function() {
            var requirements = this.user.requirements;

            if( ! this.isProfileCompleted(requirements) || ! this.isAccountCompleted(requirements)) {
                return false;
            }

            if ( ! this.isQuizCompleted(requirements)) {
                return true;
            }

            return false;
        },

        isProfileCompleted: function(requirements) {
            return this.isSectionCompleted('profile', requirements);
        },

        isAccountCompleted: function(requirements) {
            return this.isSectionCompleted('account', requirements);
        },

        isQuizCompleted: function(requirements) {
            return this.isSectionCompleted('quiz', requirements);
        },

        isSectionCompleted: function(section, requirements) {
            var isSectionCompleted = true;

            _.each(requirements.models, function(requirement){
                var requirementSection = requirement.get('section');
                var isCompleted = requirement.get('is_completed');

                if(requirementSection === section && !isCompleted) {
                    isSectionCompleted = false;
                }

            }, this);

            return isSectionCompleted;
        },

        redirectToTheQuiz: function() {
            if(!this.quiz_auto_loaded)
            {
                this.quiz_auto_loaded = true;

                _.progress().done();

                var url = laroute.route('tutor.profile.show', {
                        'uuid' : this.user.get('uuid')
                    }) + '/quiz_intro';

                this.app.history.navigate(url, {trigger: true});
            }
        },

        // redirectToWelcome: function() {
        //     _.progress().done();
        //
        //     var url = laroute.route('tutor.profile.show', {
        //             'uuid' : this.user.get('uuid')
        //         }) + '/welcome';
        //
        //     this.app.history.navigate(url, {trigger: true});
        // },

        onRequirementPending : function (data) {
            this.updateRequirement(_.extend({
                'is_pending'   : true,
                'is_completed' : false
            }, data));
        },

        onRequirementIncompleted : function (data) {
            this.updateRequirement(_.extend({
                'is_pending'   : false,
                'is_completed' : false
            }, data));
        },

        updateRequirement : function (data) {
            var requirement = this.user.requirements.findWhere(
                _.pick(data, 'name', 'section')
            );

            if (requirement && ! requirement.get('is_completed')) {
                requirement.set(
                    _.pick(data, 'is_pending', 'is_completed')
                );
            }
        },

        isRequirementJs : function (requirement) {
            var method = _.camelize('is_' + requirement.get('name') + '_requirement_js');

            if (_.isFunction(this[method])) {
                requirement.set('is_js', this[method](_.config('route')));
            }
        },

        isTaglineRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isRateRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isTravelPolicyRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isBioRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isProfilePictureRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isSubjectsRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isQualificationsRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isQualifiedTeacherStatusRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isQuizQuestionsRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isBackgroundCheckRequirementJs : function (route) {
            return this.isProfileRequirementJs(route);
        },

        isIdentificationRequirementJs : function (route) {
            return this.isAccountRequirementJs(route);
        },

        isPaymentDetailsRequirementJs : function(route) {
            return this.isAccountRequirementJs(route);
        },

        isProfileRequirementJs : function (route) {
            return route === 'tutor.profile.show';
        },

        isAccountRequirementJs : function (route) {
            return _.startsWith(route, 'tutor.account');
        }

    });

});