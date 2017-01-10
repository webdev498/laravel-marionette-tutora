<?php namespace App\Handlers\Commands;

use App\User;
use App\Tutor;
use App\Lesson;
use App\Student;
use Carbon\Carbon;
use App\UserProfile;
use App\LessonBooking;
use App\LessonSchedule;
use InvalidArgumentException;
use App\Exceptions\AppException;
use Illuminate\Database\DatabaseManager;
use App\Commands\CreateLessonBookingCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Auth\Exceptions\UnauthorizedException;
use App\Validators\CreateLessonBookingValidator;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class CreateLessonBookingCommandHandler extends CommandHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var CreateLessonBookingValidator
     */
    protected $validator;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager                  $database
     * @param  Auth                             $auth
     * @param  CreateLessonBookingValidator     $validator
     * @param  LessonRepositoryInterface        $lessons
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  UserRepositoryInterface          $users
     * @param  SubjectRepositoryInterface       $subjects
     * @param  RelationshipRepositoryInterface  $relationship
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        Auth                             $auth,
        CreateLessonBookingValidator     $validator,
        LessonRepositoryInterface        $lessons,
        LessonBookingRepositoryInterface $bookings,
        UserRepositoryInterface          $users,
        SubjectRepositoryInterface       $subjects,
        RelationshipRepositoryInterface  $relationships
    ) {
        $this->database      = $database;
        $this->auth          = $auth;
        $this->validator     = $validator;
        $this->lessons       = $lessons;
        $this->bookings      = $bookings;
        $this->users         = $users;
        $this->subjects      = $subjects;
        $this->relationships = $relationships;
    }

    /**
     * Handle the command.
     *
     * @param CreateLessonBookingCommand $command
     * @return mixed
     */
    public function handle(CreateLessonBookingCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Lookups
            $tutor        = $this->auth->user();
            $student      = $this->findUser($command->student);
            $relationship = $this->findRelationship($tutor, $student);
            $subject      = $this->findSubject($command->subject);
            // Validate
            $command->relationship = $relationship;
            $this->guardAgainstInvalidData($command);
            // Data
            $start    = strtodate($command->date.' '.$command->time);
            $duration = (integer) strtoseconds($command->duration);
            $rate     = $command->rate ? (integer) $command->rate : (integer) $tutor->profile->rate;
            $location = (string)  $command->location;
            $repeat   = (string) $command->repeat;
            $trial    = (integer) $command->trial;
            // Guard
            $this->guardAgainstOfflineTutor($tutor);
            // Lesson
            $lesson = Lesson::make(
                $relationship,
                $subject,
                $duration,
                $rate,
                $location,
                $trial
            );

            if ($command->trial) $repeat = LessonSchedule::NEVER;

            // Schedule & booking dates
            list($schedule, $dates) = $this->makeSchedule($start, $repeat);

            // Bookings
            $bookings = $this->makeBookings($lesson, $dates);
            // Save
            $this->lessons->save($lesson, $bookings, $schedule);
            // Dispatch
            // n.b. schedule might be null, don't release events on it.
            $this->dispatchFor(array_filter([$lesson, $schedule, $bookings]));

            // Set relationship rate if applicable
            if ($command->rate && $command->rate != $relationship->rate) {
                $relationship->setHourlyRate((integer) $command->rate);
            }

            // Return
            return head($bookings);
        });
    }

    /**
     * Create an array of lesson bookings from the given lesson
     * and dates.
     *
     * @param  Lesson $lesson
     * @param  array  $dates
     * @return array
     */
    protected function makeBookings(Lesson $lesson, Array $dates)
    {
        return array_map(function ($date) use ($lesson) {
            $uuid = $this->generateUuid();
            return LessonBooking::make($uuid, $date, $lesson);
        }, $dates);
    }

    /**
     * Generate a uuid, ensuring it is, in fact, unique to the booking
     *
     * @return string
     */
    protected function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while ($this->bookings->countByUuid($uuid) > 0);
        return $uuid;
    }

    /**
     * Make a lesson schedule with the given start date and repeat type
     *
     * @param  Carbon   $start
     * @param  string   $repeat
     * @return array    ['schedule', 'dates']
     */
    protected function makeSchedule(Carbon $start, $repeat)
    {
        // Schedule, and grab dates to book
        if ($repeat === LessonSchedule::NEVER) {
            $schedule = null;
            $dates    = [$start];
        } else {
            switch ($repeat) {
                case LessonSchedule::WEEKLY:
                    $schedule = LessonSchedule::weekly($start);
                    break;
                case LessonSchedule::FORTNIGHTLY:
                    $schedule = LessonSchedule::fortnightly($start);
                    break;
                default:
                    throw new InvalidArgumentException(
                        "Invalid LessonBooking repeat value: '{$repeat}'."
                    );
            }

            $dates = $schedule->dates($start, config('booking.repeat.count', 10));

            LessonSchedule::updateLastScheduledAt($schedule, last($dates));
        }

        return [$schedule, $dates];
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
     * Find a subject by a given Subject, array or string.
     *
     * @param  mixed $subject App\Subject, array /w a id key or a string id
     * @return User
     */
    protected function findSubject($subject)
    {
        if ( ! ($subject instanceof Subject)) {
            $id = is_array($subject)
                ? array_get($subject, 'id')
                : $subject;

            $subject = $this->subjects->findById($id);

            if ( ! $subject) {
                throw new ResourceNotFoundException();
            }
        }

        return $subject;
    }

    /**
     * Find a relationship between a given tutor and studen
     *
     * @param  Tutor   $tutor
     * @param  Student $student
     * @return Relationhsip
     */
    protected function findRelationship(Tutor $tutor, Student $student)
    {
        $relationship = $this->relationships->findByTutorAndStudent($tutor, $student);

        if ( ! $relationship) {
            throw new ResourceNotFoundException();
        }

        return $relationship;
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  CreateLessonBookingCommand $command
     * @return mixed
     */
    protected function guardAgainstInvalidData(CreateLessonBookingCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against a tutor who hasn't yet been reviewed
     *
     * @param  User $tutor
     * @throws UnauthorizedException
     */
    protected function guardAgainstOfflineTutor(User $tutor)
    {
        if ( ! $tutor->profile->status === UserProfile::OK) {
            throw new UnauthorizedException('Your profile is still pending a review.');
        }
    }
}
