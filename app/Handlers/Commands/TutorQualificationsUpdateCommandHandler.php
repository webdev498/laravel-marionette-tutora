<?php namespace App\Handlers\Commands;

use App\Tutor;
use App\UserQualificationOther as Other;
use App\UserQualificationAlevel as Alevel;
use App\Validators\QualificationsValidator;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Auth\Exceptions\UnauthorizedException;
use App\UserQualificationUniversity as University;
use App\Commands\TutorQualificationsUpdateCommand;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\QualificationRepositoryInterface;

class TutorQualificationsUpdateCommandHandler extends CommandHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * @var QualificationsValidator
     */
    protected $validator;

    /**
     * @var QualificationRepositoryInterface
     */
    protected $qualifications;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                         $databaes
     * @param  Auth                             $auth
     * @param  QualificationsValidator          $validator
     * @param  QualificationRepositoryInterface $qualifications
     * @param  UserRepositoryInterface          $users
     * @return void
     */
    public function __construct(
        Database                           $database,
        Auth                               $auth,
        QualificationsValidator            $validator,
        QualificationRepositoryInterface   $qualifications,
        UserRepositoryInterface            $users
    ) {
        $this->database       = $database;
        $this->auth           = $auth;
        $this->validator      = $validator;
        $this->qualifications = $qualifications;
        $this->users          = $users;
    }

    /**
     * Execute the command.
     *
     * @param  TutorQualificationsUpdateCommand $command
     * @return Subjects
     */
    public function handle(TutorQualificationsUpdateCommand $command)
    {
        return $this->database->transaction(function() use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $tutor = $this->findUser($command->uuid);
            // Guard
            $this->guardAgainstUnauthorized($tutor);
            // Sync
            $this->qualifications->syncWithTutor(
                $tutor,
                $this->attendUniversity($command->universities),
                $this->attendCollege($command->alevels),
                $this->attendOther($command->others)
            );
            // Dispatch
            $this->dispatchFor($this->qualifications);
            // Return
            return $tutor;
        });
    }

    /**
     * Attend a given array of university classes
     *
     * @param  Array $qualifications
     * @return Array
     */
    protected function attendUniversity(Array $classes)
    {
        return array_filter(array_map(function ($attributes) {
            $attributes = array_to_object((array) $attributes);

            if ($attributes->university && $attributes->subject && $attributes->level) {
                return University::attend(
                    $attributes->subject,
                    $attributes->university,
                    $attributes->level,
                    $attributes->still_studying
                );
            }
        }, $classes));
    }

    /**
     * Attend a given array of college classes
     *
     * @param  Array $classes
     * @return Array
     */
    protected function attendCollege(Array $classes)
    {
        return array_filter(array_map(function ($attributes) {
            $attributes = array_to_object((array) $attributes);

            if ($attributes->college && $attributes->subject && $attributes->grade) {
                return Alevel::attend(
                    $attributes->subject,
                    $attributes->college,
                    $attributes->grade,
                    $attributes->still_studying
                );
            }
        }, $classes));
    }

    /**
     * Attend a given array of other classes
     *
     * @param  Array $classes
     * @return Array
     */
    protected function attendOther(Array $classes)
    {
        return array_filter(array_map(function ($attributes) {
            $attributes = array_to_object((array) $attributes);

            if ($attributes->location && $attributes->subject) {
                return Other::attend(
                    $attributes->subject,
                    $attributes->location,
                    $attributes->grade,
                    $attributes->still_studying
                );
            }
        }, $classes));
    }

    /**
     * Find a user by either a given User, array or string.
     *
     * @param  mixed $user App\User, array w/ a uuid key or a string uuid.
     * @return User
     */
    protected function findUser($user)
    {
        if ( ! ($user instanceof User)) {
            $uuid = is_array($user)
                ? array_get($user, 'uuid')
                : $user;

            $user = $this->users->findByUuid($uuid);

            if ( ! $user) {
                throw new ResourceNotFoundException();
            }
        }

        return $user;
    }

    /**
     * Guard against invalid data on the command.
     *
     * @throws ValidationException
     * @param  UpdateTutorQualificationsCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(TutorQualificationsUpdateCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against unauthorised editing of this user
     *
     * @throws UnauthorizedException
     * @param  Tutor $tutor
     * @return void
     */
    protected function guardAgainstUnauthorized(Tutor $user)
    {
        $authed = $this->auth->user();
        if ( ! $authed && $authed->id !== $user->id) {
            throw new UnauthorizedException();
        }
    }

}
