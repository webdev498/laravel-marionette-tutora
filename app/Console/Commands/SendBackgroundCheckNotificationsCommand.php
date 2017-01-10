<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\UserBackgroundCheck;
use App\Mailers\BackgroundCheckMailer;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;

class SendBackgroundCheckNotificationsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:send_background_check_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for background checks.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var BackgroundCheckRepositoryInterface
     */
    protected $backgroundChecks;

    /**
     * @var BackgroundCheckMailer
     */
    protected $mailer;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager                    $database
     * @param  BackgroundCheckRepositoryInterface $backgroundChecks
     * @param  BackgroundCheckMailer              $mailer
     */
    public function __construct(
        DatabaseManager                    $database,
        BackgroundCheckRepositoryInterface $backgroundChecks,
        BackgroundCheckMailer              $mailer
    ) {
        parent::__construct();

        $this->database         = $database;
        $this->backgroundChecks = $backgroundChecks;
        $this->mailer           = $mailer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $this->sendBeforeExpiryNotifications();
        $this->sendOnExpiryNotifications();
        $this->sendAfterExpiryNotifications();
    }

    /**
     * One week prior to background check expiry
     */
    private function sendBeforeExpiryNotifications()
    {
        $date = $this->getTodayDate();
        $date->modify('+7 day');

        $backgrounds = $this->backgroundChecks->getExpiringOnDateBackgrounds($date);

        $this->sendNotifications(BackgroundCheckMailer::TEMPLATE_EXPIRING_WEEK_BEFORE, $backgrounds);
    }

    /**
     * On expiry
     */
    private function sendOnExpiryNotifications()
    {
        $date = $this->getTodayDate();

        $backgrounds = $this->backgroundChecks->getExpiringOnDateBackgrounds($date);

        $this->sendNotifications(BackgroundCheckMailer::TEMPLATE_EXPIRING_ON, $backgrounds);
    }

    /**
     * 14 days after expiry
     */
    private function sendAfterExpiryNotifications()
    {
        $date = $this->getTodayDate();
        $date->modify('-14 day');

        $backgrounds = $this->backgroundChecks->getExpiringOnDateBackgrounds($date);

        $this->sendNotifications(BackgroundCheckMailer::TEMPLATE_EXPIRING_TWO_WEEKS_AFTER, $backgrounds);
    }

    /**
     * @param string $template
     * @param $backgrounds
     */
    private function sendNotifications($template, $backgrounds)
    {
        foreach($backgrounds as $background) {
            $this->sendNotification($template, $background);
        }
    }

    /**
     * @param string $template
     * @param UserBackgroundCheck $background
     */
    private function sendNotification($template, UserBackgroundCheck $background)
    {
        $this->mailer->expiringNotification($template, $background);
    }

    /**
     * Get current date
     *
     * @return Carbon
     */
    protected function getDate()
    {
        return Carbon::now();
    }

    /**
     * Get current date
     *
     * @return Carbon
     */
    protected function getTodayDate()
    {
        return Carbon::today();
    }
}
