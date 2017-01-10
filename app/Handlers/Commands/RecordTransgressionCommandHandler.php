<?php namespace App\Handlers\Commands;

use App\Commands\RecordTransgressionCommand;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\TransgressionRepositoryInterface;
use App\Student;
use App\Task;
use App\Transgression;
use App\Tutor;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager;


class RecordTransgressionCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;
    
    /**
     * @var TransgressionRepositoryInterface
     */
    protected $transgressions;

    /**
     * @param DatabaseManager $database
     * @param Auth $auth
     * @param StudentRepositoryInterface $students
     * @param Storage $storage
     */
    public function __construct(
        DatabaseManager     $database,
        Auth                $auth,
        MessageRepositoryInterface $messages,
        TransgressionRepositoryInterface $transgressions
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->messages = $messages;
        
        /**
         * @var TransgressionRepositoryInterface
         */
        $this->transgressions = $transgressions;
    }

    /**
     * Handle the command.
     *
     * @param  DeleteStudentCommand  $command
     * @return void
     */
    public function handle(RecordTransgressionCommand $command)
    {
        
        return $this->database->transaction(function () use ($command) {

            // Lookups
            $sender = $this->auth->user();
            $message = $this->messages->findByUuid($command->uuid);
            $relationship = $message->relationship;
            $student = $relationship->student;

            
            // Create
            $transgression = Transgression::make(
                $sender,
                $command->body
            );
            // Save
            $this->transgressions->saveForMessage($transgression, $message);

            // Task

            if ($sender instanceof Tutor) {
                $task = new Task();

                $task->body = 'Transgression: with ' . $student->first_name;
                $task->category = Task::TRANSGRESSION;
                $task->action_at = Carbon::now();

                $sender->tasks()->save($task);

            }

            // Dispatch
            $this->dispatchFor($transgression);
            // Return
            return $transgression;
        });
    }



}
