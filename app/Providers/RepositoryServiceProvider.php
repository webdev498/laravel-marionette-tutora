<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRole();
        $this->registerUser();
        $this->registerUserReview();
        $this->registerUserProfile();
        $this->registerUserProfileTask();
        $this->registerTutor();
        $this->registerStudent();
        $this->registerStudentStatus();
        $this->registerRelationship();
        $this->registerAddress();
        $this->registerIdentityDocument();
        $this->registerSubject();
        $this->registerQualification();
        $this->registerLesson();
        $this->registerLessonSchedule();
        $this->registerLessonBooking();
        $this->registerReminder();
        $this->registerMessage();
        $this->registerMessageLine();
        $this->registerSearch();
        $this->registerNotificationSchedule();
        $this->registerStudentSetting();
        $this->registerArticle();
        $this->registerTransgression();
        $this->registerUserSubscription();
        $this->registerImage();
        $this->registerBackgroundCheck();
        $this->registerLocation();
        $this->registerJob();
    }

    protected function registerRole()
    {
        $this->app->singleton(
            'App\Repositories\Contracts\RoleRepositoryInterface',
            function () {
                return $this->app->make('App\Repositories\CachingRoleRepository', [
                    $this->app['cache.store'],
                    $this->app->make('App\Repositories\EloquentRoleRepository')
                ]);
            }
        );
    }

    protected function registerUser()
    {
        $this->app->bind(
            'App\Repositories\Contracts\UserRepositoryInterface',
            'App\Repositories\EloquentUserRepository'
        );
    }

    protected function registerUserReview()
    {
        $this->app->bind(
            'App\Repositories\Contracts\UserReviewRepositoryInterface',
            'App\Repositories\EloquentUserReviewRepository'
        );
    }

    protected function registerUserProfile()
    {
        $this->app->bind(
            'App\Repositories\Contracts\UserProfileRepositoryInterface',
            'App\Repositories\EloquentUserProfileRepository'
        );
    }

    protected function registerUserProfileTask()
    {
        $this->app->bind(
            'App\Repositories\Contracts\UserProfileTaskRepositoryInterface',
            'App\Repositories\EloquentUserProfileTaskRepository'
        );
    }

    protected function registerTutor()
    {
        $this->app->bind(
            'App\Repositories\Contracts\TutorRepositoryInterface',
            'App\Repositories\EloquentTutorRepository'
        );
    }

    protected function registerStudent()
    {
        $this->app->bind(
            'App\Repositories\Contracts\StudentRepositoryInterface',
            'App\Repositories\EloquentStudentRepository'
        );
    }

    protected function registerStudentStatus()
    {
        $this->app->bind(
            'App\Repositories\Contracts\StudentStatusRepositoryInterface',
            'App\Repositories\EloquentStudentStatusRepository'
        );
    }
    protected function registerRelationship()
    {
        $this->app->bind(
            'App\Repositories\Contracts\RelationshipRepositoryInterface',
            'App\Repositories\EloquentRelationshipRepository'
        );
    }

    protected function registerAddress()
    {
        $this->app->bind(
            'App\Repositories\Contracts\AddressRepositoryInterface',
            'App\Repositories\EloquentAddressRepository'
        );
    }

    protected function registerIdentityDocument()
    {
        $this->app->bind(
            'App\Repositories\Contracts\IdentityDocumentRepositoryInterface',
            'App\Repositories\EloquentIdentityDocumentRepository'
        );
    }

    protected function registerSubject()
    {
        $this->app->singleton(
            'App\Repositories\Contracts\SubjectRepositoryInterface',
            function () {
                return $this->app->make('App\Repositories\CachingSubjectRepository', [
                    $this->app['cache.store'],
                    $this->app->make('App\Repositories\EloquentSubjectRepository')
                ]);
            }
        );
    }

    protected function registerQualification()
    {
        $this->app->bind(
            'App\Repositories\Contracts\QualificationRepositoryInterface',
            'App\Repositories\EloquentQualificationRepository'
        );
    }

    protected function registerLesson()
    {
        $this->app->bind(
            'App\Repositories\Contracts\LessonRepositoryInterface',
            'App\Repositories\EloquentLessonRepository'
        );
    }

    protected function registerLessonSchedule()
    {
        $this->app->bind(
            'App\Repositories\Contracts\LessonScheduleRepositoryInterface',
            'App\Repositories\EloquentLessonScheduleRepository'
        );
    }

    protected function registerLessonBooking()
    {
        $this->app->bind(
            'App\Repositories\Contracts\LessonBookingRepositoryInterface',
            'App\Repositories\EloquentLessonBookingRepository'
        );
    }

    protected function registerReminder()
    {
        $this->app->bind(
            'App\Repositories\Contracts\ReminderRepositoryInterface',
            'App\Repositories\EloquentReminderRepository'
        );
    }

    protected function registerMessage()
    {
        $this->app->bind(
            'App\Repositories\Contracts\MessageRepositoryInterface',
            'App\Repositories\EloquentMessageRepository'
        );
    }

    protected function registerMessageLine()
    {
        $this->app->bind(
            'App\Repositories\Contracts\MessageLineRepositoryInterface',
            'App\Repositories\EloquentMessageLineRepository'
        );
    }

    protected function registerSearch()
    {
        $this->app->bind(
            'App\Repositories\Contracts\SearchRepositoryInterface',
            'App\Repositories\EloquentSearchRepository'
        );
    }

    protected function registerNotificationSchedule()
    {
        $this->app->bind(
            'App\Repositories\Contracts\NotificationScheduleRepositoryInterface',
            'App\Repositories\EloquentNotificationScheduleRepository'
        );
    }

     protected function registerStudentSetting()
    {
        $this->app->bind(
            'App\Repositories\Contracts\StudentSettingRepositoryInterface',
            'App\Repositories\EloquentStudentSettingRepository'
        );
    }


    protected function registerArticle()
    {
        $this->app->bind(
            'App\Repositories\Contracts\ArticleRepositoryInterface',
            'App\Repositories\EloquentArticleRepository'
        );
    }

    protected function registerTransgression()
    {
        $this->app->bind(
            'App\Repositories\Contracts\TransgressionRepositoryInterface',
            'App\Repositories\EloquentTransgressionRepository'
        );
    }

    protected function registerUserSubscription()
    {
        $this->app->bind(
            'App\Repositories\Contracts\UserSubscriptionRepositoryInterface',
            'App\Repositories\EloquentUserSubscriptionRepository'
        );
    }

    protected function registerImage()
    {
        $this->app->bind(
            'App\Repositories\Contracts\ImageRepositoryInterface',
            'App\Repositories\EloquentImageRepository'
        );
    }

    protected function registerBackgroundCheck()
    {
        $this->app->bind(
            'App\Repositories\Contracts\BackgroundCheckRepositoryInterface',
            'App\Repositories\EloquentBackgroundCheckRepository'
        );
    }

    protected function registerLocation()
    {
        $this->app->bind(
            'App\Repositories\Contracts\LocationRepositoryInterface',
            'App\Repositories\EloquentLocationRepository'
        );
    }

    protected function registerJob()
    {
        $this->app->bind(
            'App\Repositories\Contracts\JobRepositoryInterface',
            'App\Repositories\EloquentJobRepository'
        );
    }

}
