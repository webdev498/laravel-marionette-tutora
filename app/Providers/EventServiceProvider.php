<?php namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'App\Events\UserWasRegistered' => [
            'App\Handlers\Events\CreateUserAddresses',
            'App\Handlers\Events\CreateUserBillingAccount',
            'App\Handlers\Events\CreateUserProfile',
            'App\Handlers\Events\CreateUserRequirements',            
            'App\Handlers\Events\CreateStudentSettings',
            'App\Handlers\Events\CreateNotificationSchedules',
            'App\Handlers\Events\CreateStudentSettings',
            'App\Handlers\Events\Subscriptions\CreateUserSubscription',
            'App\Handlers\Events\SendConfirmRegistrationEmail',
            // 'App\Handlers\Events\AddUserToNewsletter',
        ],

        'App\Events\UserWasEdited' => [
            'App\Handlers\Events\UpdateUserBillingAccount',
            'App\Handlers\Events\CompleteUserRequirements',
        ],

        'App\Events\UserWasLegalEdited' => [
            'App\Handlers\Events\VerifyUserBillingAccount',
        ],

        'App\Events\UserWasBlocked' => [
            'App\Handlers\Events\TutorTasks\CreateTutorTaskOnTutorBlocked'
        ],
        
        'App\Events\IdentityDocumentWasSent' => [
            'App\Handlers\Events\PendUserRequirements',
        ],
        'App\Events\IdentityDocumentWasInspected' => [
            'App\Handlers\Events\CompleteUserRequirements',
            'App\Handlers\Events\IncompleteUserRequirements',
        ],
        'App\Events\UserRequirementWasCompleted' => [
            'App\Handlers\Events\CheckIfUserProfileIsComplete', // fires BroadcastUserRequirementWasCompleted
            'App\Handlers\Events\CheckIfUserProfileIsSubmittable', // fires BroadcastUserRequirementWasCompleted
        ],
        'App\Events\UserCardWasUpdated' => [
            'App\Handlers\Events\RebillLessonBookingPayments',
        ],
        'App\Events\UserProfileWasEdited' => [
            'App\Handlers\Events\CompleteUserRequirements',
            'App\Handlers\Events\CheckIfUserProfileNeedsReviewing',
        ],
        'App\Events\QuizWasSubmitted' => [
            'App\Handlers\Events\CompleteUserRequirements',
        ],
        'App\Events\UserProfileWasCompleted' => [
            'App\Handlers\Events\RequestNextUserProfileRequirements',
        ],
        'App\Events\UserProfileWasSubmitted' => [
            'App\Handlers\Events\RequestNextUserProfileRequirements',
            'App\Handlers\Events\RemoveTutorSignupSchedule',
        ],
        'App\Events\UserProfileWasAccepted' => [
            'App\Handlers\Events\CalculateProfileScore',
        ],
        'App\Events\UserProfileWasMadeLive' => [
            // 'App\Handlers\Events\AddUserToLiveTutorsNewsletter',
            'App\Handlers\Events\RemoveGoOnlineReminders'
        ],
        'App\Events\UserProfileWasMadeOffline' => [
            // 'App\Handlers\Events\AddUserToOfflineTutorsNewsletter',
            'App\Handlers\Events\CreateGoOnlineReminders'
        ],
        'App\Events\UserProfileWasRejected' => [
            // 'App\Handlers\Events\RemoveUserFromTutorsNewsletter',
        ],
        'App\Events\UserReviewWasLeft' => [
            'App\Handlers\Events\RemoveReviewReminder',
            'App\Handlers\Events\UpdateUserProfileRating',
            'App\Handlers\Events\EmailReviewReceived',
        ],
        'App\Events\RelationshipWasMade' => [
            'App\Handlers\Events\CreateMessage',
            'App\Handlers\Events\CreateMessageStatuses',
        ],

        'App\Events\RelationshipWasMismatched' => [
            'App\Handlers\Events\StudentTasks\CheckIfStudentIsMismatched'
        ],

        'App\Events\StudentWasMismatched' => [
            'App\Handlers\Events\Jobs\SetJobToPending',
            'App\Handlers\Events\StudentTasks\CreateMismatchedTaskForStudent'
        ],

        'App\Events\StudentWasMatched' => [
            'App\Handlers\Events\Jobs\SetJobToReserved',
        ],


        // LESSONS
        
        'App\Events\LessonWasBooked' => [
            'App\Handlers\Events\SendTheLessonWasBookedMessage',
            'App\Handlers\Events\SendTheLessonWasBookedEmail',
            'App\Handlers\Events\SendTheLessonWasBookedText',
        ],
        'App\Events\LessonWasConfirmed' => [
            'App\Handlers\Events\ConfirmRelationship',
            'App\Handlers\Events\RemovePendingLessonReminders',
            'App\Handlers\Events\SendTheLessonWasConfirmedMessage',
            'App\Handlers\Events\SendTheLessonWasConfirmedEmail',
            'App\Handlers\Events\SendTheLessonWasConfirmedText',
            'App\Handlers\Events\Jobs\CloseJobByConfirmedLesson',
             'App\Handlers\Events\StudentTasks\DeletePendingTaskForStudentOnConfirmedLesson',
        ],
        'App\Events\LessonWasEdited' => [
            'App\Handlers\Events\ReauthoriseLessonBookings',
            'App\Handlers\Events\SendTheLessonWasEditedEmail',
        ],
        'App\Events\LessonBookingWasMade' => [
            'App\Handlers\Events\CreateUpcomingLessonBookingReminder',
            'App\Handlers\Events\CreateStillPendingLessonBookingReminders',
            'App\Handlers\Events\StudentTasks\CreatePendingTaskForStudent',
            'App\Handlers\Events\TutorTasks\UpdateTutorTasksForRelationshipOnLessonBooked',
        ],
        'App\Events\LessonBookingWasEdited' => [
            'App\Handlers\Events\ReauthoriseLessonBooking',
            'App\Handlers\Events\SendTheLessonBookingWasEditedMessage',
            'App\Handlers\Events\SendTheLessonBookingWasEditedEmail',
        ],
        'App\Events\LessonBookingWasCancelled' => [
            'App\Handlers\Events\PartiallyCaptureOrCancelTheLessonBookingChargeAuthorisation',
            'App\Handlers\Events\RemoveLessonBookingReminders',
            'App\Handlers\Events\SendTheLessonBookingWasCancelledMessage',
            'App\Handlers\Events\SendTheLessonBookingWasCancelledEmailToTheStudent',
            'App\Handlers\Events\SendTheLessonBookingWasCancelledEmailToTheTutor',
            'App\Handlers\Events\TutorTasks\UpdateTutorTasksForRelationshipOnLessonCancelled',
            'App\Handlers\Events\StudentTasks\DeletePendingTaskForStudentOnCancelledLesson',
        ],
        'App\Events\LessonBookingWasCompleted' => [
            'App\Handlers\Events\CreateReviewReminder',
            'App\Handlers\Events\IncrementUsersLessonCount',
            'App\Handlers\Events\CreateLessonBookingsFromSchedule',
            'App\Handlers\Events\TutorTasks\CreateQueuedJobForTutorTasksOnLessonCompleted',
        ],
        
        'App\Events\LessonBookingHasExpired' => [
            'App\Handlers\Events\SendTheLessonBookingHasExpiredEmailToTheStudent',
            'App\Handlers\Events\SendTheLessonBookingHasExpiredTextToTheStudent',
            'App\Handlers\Events\SendTheLessonBookingHasExpiredTextToTheTutor',
            'App\Handlers\Events\RemoveLessonBookingReminders',
            'App\Handlers\Events\StudentTasks\CreateExpiredTaskForStudent',
            'App\Handlers\Events\StudentTasks\DeletePendingTaskForStudentOnExpiredLesson'
        ],
        'App\Events\LessonBookingChargeAuthorisationFailed' => [
            'App\Handlers\Events\SendTheLessonBookingChargeFailedNotifications',
            'App\Handlers\Events\StudentTasks\CreateFailedPaymentTaskForStudent',
        ],

        'App\Events\LessonBookingChargeAuthorisationSucceeded' => [
            'App\Handlers\Events\StudentTasks\DeleteFailedPaymentTaskForStudent',
        ],
        'App\Events\LessonBookingChargePaid' => [
            'App\Handlers\Events\SendTheLessonBookingChargePaidEmailToTheStudent',
        ],
        'App\Events\LessonBookingChargePaymentFailed' => [
            'App\Handlers\Events\SendTheLessonBookingChargeFailedNotifications',
        ],
        'App\Events\LessonBookingTransferFailed' => [
            'App\Handlers\Events\SendTheTransferFailedNotifications',
        ],

        // MESSAGE RESPONSES

        'App\Events\ApplicationMessageLineWasWritten' => [
            'App\Handlers\Events\MarkMessageLineAsUnread',
            'App\Handlers\Events\SendApplicationMessageLineWrittenEmail',
            'App\Handlers\Events\SendApplicationMessageLineWrittenText',
            'App\Handlers\Events\Reminders\CreateMessageLineWrittenReminders',
            'App\Handlers\Events\DecrementNoResponseCounter',
            'App\Handlers\Events\CalculateAverageResponseTime',
        ],

        'App\Events\MessageLineWasWritten' => [
            'App\Handlers\Events\MarkMessageLineAsUnread',
            'App\Handlers\Events\SendMessageLineWrittenEmail',
            'App\Handlers\Events\SendMessageLineWrittenText',
            'App\Handlers\Events\Reminders\CreateMessageLineWrittenReminders',
            'App\Handlers\Events\DecrementNoResponseCounter',
            'App\Handlers\Events\CalculateAverageResponseTime',
        ],

        'App\Events\StudentMessageLineWasWritten' => [
            'App\Handlers\Events\StudentTasks\DeleteNotRepliedTasksForStudent',
        ],

        'App\Events\TutorMessageLineWasWritten' => [
        ],

        'App\Events\MessageWasRead' => [
            'App\Handlers\Events\MarkMessageAsRead',
        ],
        'App\Events\TutorNotReplied' => [
            'App\Handlers\Events\IncrementNoResponseCounter',
            'App\Handlers\Events\SetRelationshipStatusToTutorNotReplied',
        ],
         'App\Events\StudentNotReplied' => [
            'App\Handlers\Events\SetRelationshipStatusToStudentNotReplied',
            'App\Handlers\Events\StudentTasks\CreateNotRepliedTaskForStudent',
        ],
        'App\Events\TutorNoResponseLimitReached' => [
            'App\Handlers\Events\TakeTutorOffline',
            'App\Handlers\Events\SendNoResponseLimitReachedEmail',
        ],
        'App\Events\BackgroundAdminStatusWasMadeApproved' => [
            'App\Handlers\Events\SendBackgroundCheckWasApprovedNotifications',
            'App\Handlers\Events\RequestUserBackgroundChecks',
        ],
        'App\Events\BackgroundAdminStatusWasMadePending' => [
            'App\Handlers\Events\RequestUserBackgroundChecks',
        ],
        'App\Events\BackgroundAdminStatusWasMadeRejected' => [
            'App\Handlers\Events\SendBackgroundCheckWasRejectedNotifications',
            'App\Handlers\Events\RequestUserBackgroundChecks',
        ],
        'App\Events\BackgroundCheckWasCreated' => [
            'App\Handlers\Events\RequestUserBackgroundChecks',
        ],

        // JOBS

        'App\Events\Jobs\JobWasCreated' => [
            'App\Handlers\Events\Jobs\CreateQueuedJobToSetJobToPending',
        ],

        'App\Events\Jobs\JobWasMadeLive' => [
            'App\Handlers\Events\Jobs\CreateCheckJobApplicantsReminder',
        ],

        'App\Events\Jobs\JobWasApplied' => [
            'App\Handlers\Events\Jobs\CloseJobByApplications',
        ],


        // TASKS
    ];
}