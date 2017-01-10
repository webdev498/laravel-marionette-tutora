<?php namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Exceptions\StudentNotFoundException;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Student;
use App\Tutor;
use App\User;
use Illuminate\Database\DatabaseManager;
use App\Commands\DeleteStudentCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Support\Facades\Storage;

class DeleteStudentCommandHandler extends CommandHandler
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
     * @param DatabaseManager $database
     * @param Auth $auth
     * @param StudentRepositoryInterface $students
     * @param Storage $storage
     */
    public function __construct(
        DatabaseManager                   $database,
        Auth                              $auth,
        StudentRepositoryInterface        $students,
        Storage                           $storage
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->students  = $students;
        $this->storage   = $storage;
    }

    /**
     * Handle the command.
     *
     * @param  DeleteStudentCommand  $command
     * @return $student
     */
    public function handle(DeleteStudentCommand $command)
    {
        $actioner = $this->auth->user();

        return $this->database->transaction(function () use ($command, $actioner) {
            $this->guardAgainstActionerNotPermitted($actioner);

            $student = $this->findStudent($command->uuid);

            // Set tutor to deleted
            $student = Student::trash($student);

            $this->removeSensitiveData($student);
            $this->removeStripeAccount($student);

            $student->save();

            return $student;
        });
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws StudentNotFoundException
     */
    protected function findStudent($uuid)
    {
        $student = $this->students->findByUuid($uuid);

        if ( ! $student) {
            throw new StudentNotFoundException();
        }

        return $student;
    }

    /**
     * @param User $user
     * @throws UnauthorizedException
     */
    protected function guardAgainstActionerNotPermitted(User $user)
    {
        if ( ! $user->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param Student $student
     * @throws \Exception
     */
    public function removeSensitiveData(Student $student)
    {
        $student->last_four = null;
        return $student;
    }

    /**
     * @param Student $student
     * @return bool
     */
    protected function removeStripeAccount(Student $student)
    {
        if ( ! $student->billing_id) {
            return false;
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::retrieve($student->billing_id);
        $customer->delete();

        $student->billing_id = null;

        return $student;
    }

}
