<?php namespace App\Handlers\Events;

use App\Events\UserWasRegistered;
use App\Handlers\Events\EventHandler;
use App\Student;
use App\StudentSetting;
use App\Repositories\Contracts\StudentSettingRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateStudentSettings extends EventHandler
{
    /**
     * @var StudentSettingsRepositoryInterface
     */
    protected $settings;    

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(StudentSettingRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {

        $user = $event->user;

        if ($user instanceof Student) {            
            $studentSettings = StudentSetting::make();

            $this->settings->saveForStudent($user, $studentSettings);
        }
    }
}
