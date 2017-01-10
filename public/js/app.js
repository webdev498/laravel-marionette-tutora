define([
    'backbone.marionette',
    'config',
    'mixins',
    'base',
    'entities/user',
    'components/requirements/component',
    'components/autocomplete/component',
    'components/scrolltabs/component',
    'apps/autocomplete/app',
    'apps/toasts/app',
    'apps/auth/app',
    'apps/register/app',
    'apps/search/app',
    'apps/tutors/app',
    'apps/tagline/app',
    'apps/rate/app',
    'apps/live/app',
    'apps/travel_policy/app',
    'apps/bio/app',
    'apps/subjects/app',
    'apps/qualifications/app',
    'apps/quiz/app',
    'apps/qts/app',
    'apps/background_check/app',
    'apps/booking/app',
    'apps/review/app',
    'apps/messages/app',
    'apps/tutor_account/app',
    'apps/jobs/app',
    'apps/student/app',
    'apps/student_account/app',
    'apps/user_delete/app',
    'apps/user_block/app',
    'apps/welcome/app',
    'apps/basic_user_dialogue/app',
    'apps/dialogues/app',
    'apps/video/app'
], function (
    Marionette,
    Config,
    Mixins,
    Base,
    User,
    RequirementsComponent,
    AutocompleteComponent,
    ScrolltabsComponent,
    AutocompleteApp,
    ToastsApp,
    AuthApp,
    RegisterApp,
    SearchApp,
    TutorsApp,
    TaglineApp,
    RateApp,
    LiveApp,
    TravelPolicyApp,
    BioApp,
    SubjectsApp,
    QualificationsApp,
    QuizApp,
    QTSApp,
    BackgroundCheckApp,
    BookingApp,
    ReviewApp,
    MessagesApp,
    TutorAccountApp,
    JobsApp,
    StudentApp,
    StudentAccountApp,
    UserDeleteApp,
    UserBlockApp,
    WelcomeApp,
    BasicDialogueApp,
    DialoguesApp,
    VideoApp
) {
    var user = User.current();

    var App = new (Marionette.Application.extend({
        // Store named callbacks
        // e.g. such as triggering a message after a sign up
        callbacks : {}

    }));

    App.addRegions({
        'dialogueRegion' : '.dialogue-region',
        'toastRegion'    : '.toast-region'
    });

    App.addInitializer(RequirementsComponent); 
    App.addInitializer(AutocompleteComponent);
    App.addInitializer(ScrolltabsComponent);

    App.addInitializer(AutocompleteApp);
    App.addInitializer(ToastsApp);
    App.addInitializer(AuthApp);
    App.addInitializer(RegisterApp);
    App.addInitializer(SearchApp);
    App.addInitializer(MessagesApp);
    App.addInitializer(BasicDialogueApp);
    App.addInitializer(DialoguesApp);
    App.addInitializer(TutorsApp);

    if ( ! user.isNew()) {
        App.addInitializer(ReviewApp);
        App.addInitializer(BookingApp);
        App.addInitializer(TaglineApp);
        App.addInitializer(RateApp);
        App.addInitializer(TravelPolicyApp);
        App.addInitializer(BioApp);
        App.addInitializer(LiveApp);
        App.addInitializer(SubjectsApp);
        App.addInitializer(QualificationsApp);
        App.addInitializer(QuizApp);
        App.addInitializer(QTSApp);
        App.addInitializer(BackgroundCheckApp);
        App.addInitializer(TutorAccountApp);
        App.addInitializer(JobsApp);
        App.addInitializer(StudentApp);
        App.addInitializer(StudentAccountApp);
        App.addInitializer(UserDeleteApp);
        App.addInitializer(UserBlockApp);
        App.addInitializer(WelcomeApp);
        App.addInitializer(VideoApp);
    }

    App.on('start', function () {
        Stripe.setPublishableKey(_.config('services.stripe.publishable'));

        $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
            options.xhrFields = {
                withCredentials: true
            };

            return jqXHR.setRequestHeader('X-CSRF-Token', _.config('csrf_token'));
        });

        /*
        $(document).ajaxComplete(function(event, response) {
            Config.set(
                'csrf_token',
                response.getResponseHeader('X-REISSUED-CSRF-TOKEN')
            );
        });
        */

        this.history = new Base.History({
            app : this
        });

        this.history.start({
            pushState : true
        });

        $(document).on('click', 'a[data-js]', _.bind(function (e) {
            var href = $(e.currentTarget).attr('href');

            if (href) {
                e.preventDefault();

                this.history.navigate(href, {
                    'trigger' : true
                });

                return false;
            }
        }, this));
    });

    return App;
});
