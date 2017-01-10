<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Bookings
        'App\Console\Commands\CancelLessonBookingsCommand',
        'App\Console\Commands\CompleteLessonBookingsCommand',
        'App\Console\Commands\AuthoriseLessonBookingPaymentsCommand',
        'App\Console\Commands\CaptureLessonBookingPaymentsCommand',
        'App\Console\Commands\ChargeLessonBookingPaymentsCommand',
        'App\Console\Commands\RetryLessonBookingPaymentsCommand',

        // Reminders
        'App\Console\Commands\SendBackgroundCheckNotificationsCommand',
        'App\Console\Commands\EmailUpcomingLessonReminders',
        'App\Console\Commands\SendPendingLessonReminders',
        'App\Console\Commands\EmailReviewLessonReminders',
        'App\Console\Commands\SendMessageLineWrittenReminders',
        'App\Console\Commands\SendGoOnlineReminders',
        'App\Console\Commands\SendNotifications',

        // System Notifications
        'App\Console\Commands\FireJobHasBeenOpenSystemNotifications',
        
        // Jobs
        'App\Console\Commands\CloseExpiredTuitionJobsCommand',

        // Reports
        'App\Console\Commands\Reports\RunStudentStatusReport',
        'App\Console\Commands\Reports\RunAnalyticsReport',
        'App\Console\Commands\GeocodeAddressesCommand',

        //Algorithm
        'App\Console\Commands\Search\CalculateProfileScores',
        // Caching
        'App\Console\Commands\CacheSubjectsForAutocomplete'
 
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tutora:cancel_lesson_bookings')
            ->cron('* * * * * *');

        $schedule->command('tutora:send_background_check_notifications')
            ->dailyAt('11:00');

        $schedule->command('tutora:complete_lesson_bookings')
            ->cron('* * * * * *');

        $schedule->command('tutora:close_expired_tuition_jobs')
            ->cron('3-59/10 * * * * *');

        $schedule->command('tutora:authorise_lesson_booking_payments')
            ->cron('2-59/10 * * * * *');

        $schedule->command('tutora:capture_lesson_booking_payments')
            ->cron('7-59/10 * * * * *');

        $schedule->command('tutora:charge_lesson_booking_payments')
            ->cron('4-59/10 * * * * *');

        $schedule->command('tutora:retry_lesson_booking_payments')
            ->cron('1-59/10 * * * * *');

        $schedule->command('tutora:email_upcoming_lesson_reminders')
            ->cron('1-59/10 * * * * *');
        
        $schedule->command('tutora:send_message_line_reminders')
            ->cron('5-59/10 * * * * *');

        $schedule->command('tutora:send_go_online_reminders')
            ->cron('8-59/10 * * * * *');     

        $schedule->command('tutora:send_pending_lesson_reminders')
            ->cron('4-59/10 * * * * *');         

        $schedule->command('tutora:email_review_lesson_reminders')
            ->cron('7-59/10 * * * * *');

        $schedule->command('tutora:send_notifications')
            ->cron('7-59/10 * * * * *');

            $schedule->command('tutora:fire_job_has_been_open_system_notifications')
            ->cron('7-59/10 * * * * *');

        // REPORTS

        $schedule->command('tutora:run_student_status_report')
            ->cron('2-59/10 * * * * *');

        $schedule->command('tutora:run_analytics_report week 0')
            ->weekly()->mondays()->at('5:00');

        $schedule->command('tutora:run_analytics_report month 0')
            ->monthly()->at('5:00');

        // ALGORITHM
        $schedule->command('tutora:calculate_profile_scores')
            ->dailyAt('3:00');

        $schedule->command('tutora:geocode_addresses')
             ->hourly();
    }

}
